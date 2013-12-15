<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHelperLogger
{
	static public function log($level, $message, $object=null, $content=null, $type= 'PayplansFormatter', $class= 'SYSTEM')
	{
        $token = md5(serialize($content));
		$block = false;
		// block logging for selected objects
		$blockLogging = XiFactory::getConfig()->blockLogging;

		if(!empty($blockLogging)){
			foreach($blockLogging as $logObject){
				$className = 'Payplans'.$logObject;
				// IMP: for proper handling when config log needs to be blocked 
				//since in config log we dont have any object to get class from
				if($object == null && $class != 'PayplansConfig'){
					$className = 'Payplans_'.$logObject;
				}
	
				if((($object != null) && ($object instanceof $className)) || !strcasecmp($className, $class)){
					$block = true;
					continue;
				}
			}
		}

		if($block === true){
			return true;
		}

		$logger = XiFactory::getLogger();
		
		$object_id 	= 0;
		if($object && is_object($object)){
			$object_id 	 = method_exists($object, 'getId') ? $object->getId() : 0 ;
			$class 		 = get_class($object);
		}
		
		// always encode the content
		return $logger->log($level, $message, $object_id, $class, $content, $type, $token);
	}
	
	//return log entries for the given object and mentioned log-level
	static public function getLog($object=null, $level)
	{
		if($object && is_object($object)){
			$object_id 	 = method_exists($object, 'getId') ? $object->getId() : 0 ;
			$record = XiFactory::getInstance('log', 'model')->loadRecords(array('object_id'=>$object_id, 'level'=>$level));
			return $record;
		}

		return false;
	}
	
	public static function getOwnerId($content)
	{
			$owner_id = '';
			$id 	 	 	  = array('user_id' => '','buyer_id' => '');
			$compare 	 	  = isset($content['current']) ? $content['current'] : $content;
			$owner_id 	 	  = array_intersect_key($id, $compare);
			if (!empty($owner_id)){
				$owner_id    	  = key($owner_id);
				$owner_id =  $compare[$owner_id];
			}
			return $owner_id;
	}
	
	public static function calculatePreviousToken($object_id, $class,$content)
	{
		// when log for any app is created then $object_id == app_id
		// Always create an log when it is related to an app.
		
		preg_match('/^PayplansApp/', $class, $matches);
		if(count($matches) == 1  && !isset($content['current']))
		{
			return '';
		}
		$previousToken  = '';
		$query    		= new XiQuery();
		$previousToken  = $query->select('`current_token`')
								  ->from('`#__payplans_log`')
								  ->where('`object_id` ='.$object_id)
								  ->where('`class`= "'.$class.'"')
								   ->where('`class` NOT IN ( "SYSTEM", "Payplans_Cron")')
								  ->order('log_id DESC')
								  ->dbLoadQuery()
								  ->loadResult();
		return $previousToken;
	}
	
	public static function calculatePreviousposition($previousToken, $class = "")
	{
		$query    = new XiQuery();
		if(!empty($class))
		{
			$query->where('`class`= "'.$class.'"');
		}
		$previousTokenPosition = $query->select('`position`')
								  ->from('`#__payplans_log`')
								  ->where('`current_token`= "'.$previousToken.'"')
								  ->order('`log_id` DESC')
								  ->dbLoadQuery()
								  ->loadResult();
		return $previousTokenPosition;
	}
	
	
	public function dumpDataInFile($log_id,$finalContent,$random = '')
	{
		   $file =  PayplansHelperLogger::calculateFileName($log_id,$random = '');
			//XiTODO: Use some other solution, if xilock is used here then it will create conflict with cron
		    //$lock =  XiLock::getInstance($file, 1);
		    
		    // do not allow to write another data in the file if currently writing the data
		    // if($lock->getLockResult()){
			    $fh 	  = fopen($file, 'a+') or die("can't open file");
	      		fseek($fh, 0, SEEK_END);
	      		$pos = ftell($fh);
	     		fwrite($fh, $finalContent);
				fclose($fh);
				// release the lock after data is written in the file
				//$lock->releaseLock();
			    $position = json_encode(array('location'=>$pos, 'filePath' => urlencode($file)));
				return $position;
		    //}
		    
		    // if the current file is lock, generate a random number and calculate another file to write.
		    //$random = rand();
		    //$this->dumpDataInFile($log_id, $finalContent, $random);
		    
	}
	
	
	
	public function calculateFileName($log_id,$random = '')
	{
			//32 MB in bytes.
			$maxFolderSize = 32768;
			$folder_id = 1;
	
			$folder_id  = isset(XiFactory::getConfig()->logBucket)? XiFactory::getConfig()->logBucket: 1;
			$foldername = 'log_bucket_'.$folder_id;
			$folderPath = JPATH_ROOT . DS. 'media'. DS. 'payplans'. DS. 'log'. DS .$foldername;
			if(!JFolder::exists($folderPath))
			{
				JFolder::create(JPATH_ROOT . DS. 'media'. DS. 'payplans'. DS. 'log'. DS .$foldername);
			}
			
			// If the maximum size of the folder exceeds 32 MB then change the folder
			if($maxFolderSize < filesize($folderPath))
			{
				$folder_id = $folder_id++;
				$foldername = 'log_bucket_'.$folder_id;
				$config = PayplansFactory::getInstance('config', 'model');
				$config->save(array('logBucket'=>$folder_id));
				JFolder::create(JPATH_ROOT . DS. 'media'. DS. 'payplans'. DS. 'log'. DS .$foldername);
			}
			
			// this is done, to save the data in other file, when lock is there on the current file
			$id 		 = !empty($random)? $random:$log_id;
			$filename_id = $id % 16;
			$filename 	 = 'log_'.$filename_id.'.txt';
			$file  	     = JPATH_ROOT . DS. 'media'. DS. 'payplans'. DS. 'log'. DS .$foldername.DS. $filename;
	
			return $file;
		
	}
	
	/*
	 * Search for the given token from the file and return it
	 */
	public function calculateLogData($log_position, $token)
	{
			$file_path = urldecode($log_position->filePath);
			$fh 		  = fopen($file_path, 'r');
			fseek($fh, $log_position->location);
			$content 	  = fgets($fh);
			$searchFor 	  = '#\<'.$token.'>(.*?)\</'.$token.'>#m'; 
			//#\<ref\>(.*?)\</ref\>#m
			preg_match($searchFor, $content, $contentData);
			$logData = unserialize(json_decode($contentData[1]));
			return $logData;
	}
	
	public function readBaseEncodeLog($log)
	{
			$content['content'] = '';
			$logData 		    = unserialize(base64_decode($log->content));
			$content['type']    = array_shift($logData);
			if(!empty($logData)){
					$content['content'] = unserialize(base64_decode(array_shift($logData)));
			}
			return array($content['type'], $content['content']);
	}
	
	public function readJsonEncodeLog($log)
	{
			$previousToken = $log->previous_token;
			$prevLogData = array();
	
			// If previous token is set, then calculate previous data
			if(isset($previousToken) && !empty($previousToken))
			{
				$previousData 	 = PayplansHelperLogger::calculatePreviousposition($previousToken);
				$prevLog_position= json_decode($previousData);
				$prevLogData = PayplansHelperLogger::calculateLogData($prevLog_position, $previousToken);
			}
	
			// get the current data
			$log_position = json_decode($log->position);
	 		$currentLogData = PayplansHelperLogger::calculateLogData($log_position, $log->current_token);
	 		
			if(isset($prevLogData['content'])){
				$content['previous'] = $prevLogData['content'];
			}
			$classname  = array_shift($currentLogData);
			if(!isset($currentLogData['content']['current']) && isset($currentLogData['content']['previous']) )
            {
                   $content['previous'] = $currentLogData['content']['previous'];
            }
            else
					$content['current'] = array_shift($currentLogData);
			
			// this is done in order to read base64_encode data.
			if(count($content['current']) == 2)
			{
				$content = array_shift($content);
			}
			return array($classname, $content);
	}
	
}
