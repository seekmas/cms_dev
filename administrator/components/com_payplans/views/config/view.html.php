<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansadminViewConfig extends XiView
{
	//xitodo : remove this function and convert it to class variable
	public function getJSValidActions()
    {
    	return array();
    }
	
    
	protected function _adminEditToolbar()
	{
		XiHelperToolbar::apply();
		XiHelperToolbar::divider();
		XiHelperToolbar::openPopup('searchRecords', 'search', 'search.png', 'COM_PAYPLANS_TOOLBAR_SEARCH', true );
	}

	function edit($tpl=null)
	{
		//now record is always an array, pick all records
		$modelform  = PayplansFactory::getInstance('config', 'Modelform');
		$form		= $modelform->getForm();
		$form->loadFile(PAYPLANS_PATH_XML.DS.'config.xml',false, '//config');
		$data 		= PayplansFactory::getConfig();
		$logRecords= XiFactory::getInstance('log', 'model')
                                                              ->loadRecords(array('class'=>'PayplansConfig'));
		$form->bind($data);
		
		$this->assign('form',$form);
		
		$this->assign('log_records', $logRecords);
		return true;
	}
		
	public function migration()
	{
		$action = JRequest::getVar('action','start').'Migration';
		//Calls Apropriate Action+Task(RebuildStats)
		return $this->$action();
	}
	
	
	public function startMigration()
	{
		$ajax  = XiFactory::getAjaxResponse();
		$query 	= new XiQuery();
		$totalRecordToProcess = $query->select('count(*)')
							->from('`#__payplans_log`')
							->where("`content` != ''")
							->dbLoadQuery()->loadResult();
		
		if ($totalRecordToProcess == 0)
		{
			$domObject   = 'xiWindowBody';
			$domProperty = 'innerHTML';
			JRequest::setVar('action','complete');
			$html = $this->loadTemplate('migration');
			$object2 		= new stdClass();
			$object2->id    = "";
			$object2->click = 'xi.ui.dialog.close()';
			$object2->text 	= XiText::_('COM_PAYPLANS_LOG_MIGRATION_CANCEL');
			$object2->classes = "btn-large btn";
			$ajax->addScriptCall('xi.ui.dialog.button',array($object2));
			$ajax->addAssign( $domObject , $domProperty , $html );
			$ajax->sendResponse();
			return true;
		}
	
			//Assigned for use in template
 			$this->assign('progress', 0);
 			$this->assign('exeCount', 0);
			$this->assign('migrate_total',$totalRecordToProcess);
			$ajax->addScriptCall('payplans.admin.config.migrateLogs.update',$totalRecordToProcess,$exeCount);

		return true;

	}
	
	
	public function inProcessMigration()
	{
		$ajax  = XiFactory::getAjaxResponse();
		$start = JRequest::getVar('start', 0);
		$totalRecordToProcess = JRequest::getVar('totalRecordToProcess', 0);
		
		$query 	= new XiQuery();
		$log_data = $query->select('`log_id`,`class`, `content`,`object_id`')
				  ->from('`#__payplans_log`')
				  ->where("`content` != ''")
				  ->order('`log_id` ASC')
				  ->limit(50, 0)
				  ->dbLoadQuery()->loadObjectList('log_id');
				  
			foreach ($log_data as $log)
			{
				try{
					list($type, $content) = PayplansHelperLogger::readBaseEncodeLog($log);
				}
				catch (Exception $e)
				{
					file_put_contents(JPATH_SITE.DS.'tmp'.DS.'payplans_migration_log', var_export($e->getMessage()." \n ".$log->log_id.",",true), FILE_APPEND);
					continue;
				}
				$token = md5(serialize($content));
				if(!is_array($content)){
					$content = array($content);
				}
				$data['owner_id'] = PayplansHelperLogger::getOwnerId($content);
				$tokenExist = PayplansHelperLogger::calculatePreviousposition($token, $log->class);
				$model = XiFactory::getInstance('log','model');
				$data['current_token'] = $token;
				
				if(!empty($tokenExist)){
					$data['position'] = $tokenExist;
					$data['content'] = '';
					$model->save($data, $log->log_id);
					continue;
				}
				
				 $data['previous_token'] = PayplansHelperLogger::calculatePreviousToken($log->object_id,$log->class, $content);
				 
				// if previous token is there, then store only the current token.
				if(!empty($data['previous_token']) || (isset($content['previous']) && empty($content['previous']))){
					$content = $content['current'];
				}
				
				// create a new record in <token>content</token> format
			  	$content 		= 	json_encode(serialize(array('type' => $type, 'content' => $content)));
				$contentToDump   = '<'.$token.'>'.$content.'</'.$token.'>'."\n";
				
				// save the data in file if new record is there.
				$data['position'] = PayplansHelperLogger::dumpDataInFile($log->log_id, $contentToDump);
				$data['content']  = '';
				$model->save($data, $log->log_id);
				
			}
			// 50 records will be processed in single shot
			$exeCount = $start + 50;
				
				//When migration Completes
				if($exeCount >= $totalRecordToProcess) {
				    $db  = JFactory::getDBO();
				    $sql = "OPTIMIZE TABLE ".  $db->quoteName(`#__payplans_log`);
				    $db->setQuery($sql)->query();
					$domObject   = 'xiWindowBody';
					$domProperty = 'innerHTML';
					JRequest::setVar('action','complete');
					$html = $this->loadTemplate('migration');
					$object2 		= new stdClass();
					$object2->id    = "";
					$object2->click = 'xi.ui.dialog.close()';
					$object2->text 	= XiText::_('COM_PAYPLANS_LOG_MIGRATION_CANCEL');
					$object2->classes = "btn-large btn";
					$ajax->addScriptCall('xi.ui.dialog.button',array($object2));
					$ajax->addAssign( $domObject , $domProperty , $html );
					$ajax->sendResponse();
					return true;
				}
			
			//For increasing width of progress bar.
			$progress = ($exeCount/$totalRecordToProcess)*100;
		
			//Assigned for use in template
 			$this->assign('progress', $progress);
 			$this->assign('exeCount', $exeCount);
			$this->assign('migrate_total',$totalRecordToProcess);

			//For Calculating Next records
			$ajax->addScriptCall('payplans.admin.config.migrateLogs.update',$totalRecordToProcess,$exeCount);
	}
	
}

