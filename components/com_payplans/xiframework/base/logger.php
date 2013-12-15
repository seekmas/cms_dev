<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class XiLogger 
{
	const LEVEL_DEBUG   = 0;
	const LEVEL_INFO    = 1;
	const LEVEL_NOTICE  = 2;
	const LEVEL_WARNING = 3;
	const LEVEL_ERROR   = 4;

	/* log level */
	protected $_level = array();
	
	static $_levels  = null;
		
	public function __construct($level=XiLogger::LEVEL_INFO) {
		// init vars
		$this->_level = $level;
		
	}

	public function log($level, $message, $object_id, $class, $content = null, $type, $token) 
	{		
		if ($this->_level <= $level ) {
			$log_id = $this->_log($level, $message, $object_id, $class, $content, $type, $token);
			if($level == self::LEVEL_ERROR )
			{
				$mailer  = XiFactory::getMailer();
				$subject = XiText::_('COM_PAYPLANS_ERROR_LOG_SUBJECT');
				$mailer->setSubject($subject);
				$args   = array('message'=>$message,'object_id'=>$object_id,'class'=>$class,'content'=>$content);
				$body   = XiHelperTemplate::partial('default_partial_email_errorlog',$args);
				$mailer->setBody($body);
				$mailer->IsHTML(1);
				$admins = XiHelperJoomla::getUsersToSendSystemEmail();		

				// IMP: when there are no users who can receive system emails then return log_id
				if(empty($admins)){
					return $log_id;
				}
				$first  = array_shift($admins);
				$mailer->addRecipient($first->email);
				// get other super admin users email
				foreach ( $admins as $admin )
				{
					$mailer->addCC($admin->email); ;
				}
				
				$mailer->Send();
			
			}
			return $log_id;
		}

		return false;
	}


	public function getLogLevel() {
		return $this->_level;
	}


	public function setLogLevel($level) {
		$this->_level = (array) $level;
	}

	static public function getLevels() {
		
		if(self::$_levels === null){
			self::$_levels[self::LEVEL_INFO] 		= XiText::_('COM_PAYPLANS_LOGGER_LEVEL_INFO');
			self::$_levels[self::LEVEL_NOTICE] 	= XiText::_('COM_PAYPLANS_LOGGER_LEVEL_NOTICE');
			self::$_levels[self::LEVEL_WARNING] 	= XiText::_('COM_PAYPLANS_LOGGER_LEVEL_WARNING');
			self::$_levels[self::LEVEL_ERROR] 		= XiText::_('COM_PAYPLANS_LOGGER_LEVEL_ERROR');
			self::$_levels[self::LEVEL_DEBUG] 		= XiText::_('COM_PAYPLANS_LOGGER_LEVEL_DEBUG');
		}
		
		return self::$_levels;
	}
	
	public function getLevelText($level) {
		$levels[self::LEVEL_INFO] 		= XiText::_('COM_PAYPLANS_LOGGER_LEVEL_INFO');
		$levels[self::LEVEL_NOTICE] 	= XiText::_('COM_PAYPLANS_LOGGER_LEVEL_NOTICE');
		$levels[self::LEVEL_WARNING] 	= XiText::_('COM_PAYPLANS_LOGGER_LEVEL_WARNING');
		$levels[self::LEVEL_ERROR] 		= XiText::_('COM_PAYPLANS_LOGGER_LEVEL_ERROR');
		$levels[self::LEVEL_DEBUG] 		= XiText::_('COM_PAYPLANS_LOGGER_LEVEL_DEBUG');

		return isset($levels[$level]) ? $levels[$level] : XIText::_('COM_PAYPLANS_LOGGER_UNKNOWN_LEVEL');
	}


	protected function _log($level, $message, $object_id, $class, $content = null, $type, $token='')
	{	
		$data['log_id'] 	= 0 ;
		
		//get userId from session in case of autoRegistration
		$data['user_id'] 	= XiFactory::getUser()->get('id') != null ? XiFactory::getUser()->get('id') : XiFactory::getSession()->get('REGISTRATION_USER_ID');
		$data['object_id'] 	= $object_id; 
		$data['class'] 		= $class ;
		$data['user_ip'] 	= isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] 
								: ( isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : XiText::_('COM_PAYPLANS_LOGGER_REMOTE_IP_NOT_DEFINED')) ;
		$data['message']    = $message ;
		$data['content'] 	= '' ;
		$data['level'] 			= $level ;
	    $data['current_token']  = $token;
		$data['position']       = '';
		$data['owner_id']		= '';
		$data['previous_token'] = '';
		$model = XiFactory::getInstance('log','model');
		
		if(isset($object_id) && $object_id != 0){
		    $owner_id = PayplansHelperLogger::getOwnerId($content);	
		    $data['owner_id']		= $owner_id;
		}
		
			//In order to not save the data of cron again and again, this work is done.
			// in this calculate the previous position of cron(if already exist) and update the row
			//else follow the normal process. 
			$tokenExist = PayplansHelperLogger::calculatePreviousposition($token, $class);				
			if(!empty($tokenExist)){
				$data['previous_token'] = "";
				$data['position'] = $tokenExist;
				$log_id 	 			= $model->save($data, 0);
				return $log_id;
			}
		
			 if (isset($content['current']) ||  isset($content['previous'])){
		  	  	$data['previous_token'] = PayplansHelperLogger::calculatePreviousToken($object_id,$class, $content);
			 }

		$log['type']			=	$type;
		
		// When log for sample data migration is created then $content is in form of string. So it is required 
		// to convert in a array.
		if(!is_array($content)){
			$content = array($content);
		}
		$log['content']			=   $content;
		
		// if previous token is there, then store only the current token.
		if(!empty($data['previous_token']) || (isset($content['previous']) && empty($content['previous']))){
			$log['content'] = $content['current'];
		}
		
		// create a new record in <token>content</token> format
	  	$content 		= 	json_encode(serialize($log));
		$finalContent   = '<'.$token.'>'.$content.'</'.$token.'>'."\n";
		$log_id 		= $model->save($data, 0);

		if(!isset($log_id)){
			return false;
		}
		
		// save the data in file if new record is there.
		$position = PayplansHelperLogger::dumpDataInFile($log_id, $finalContent);
	    $model->save(array('position' => $position), $log_id);		
	
      	return $log_id;
	}
}