<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	PayPlans-Installer
* @contact 		payplans@readybytes.in
*/

// No direct access.
defined('_JEXEC') or die;
jimport('joomla.application.component.controller');

/**
 * PayPlans Installer Controller
 *
 * @package		Joomla.Administrator
 */

if(!class_exists('PpinstallerControllerAdapt')) {
	if(interface_exists('JController')) {
		abstract class PpinstallerControllerAdapt extends JControllerLegacy {}
	} else {
		class PpinstallerControllerAdapt extends JController {}
	}
}

class PpinstallerController extends PpinstallerControllerAdapt
{
	public function display($cachable = false, $urlparams = false)
	{ 
		// Clear previous data if exist in session
		PpinstallerHelperPatch::clear_session();
		PpinstallerHelperMigrate::clear_session();

		$this->_display();
	}
	
	public function disable()
	{
		// disable PP-installer from Joomla table
		PpinstallerHelperInstall::set_extension();
		//redirect to payplans
		 JFactory::getApplication()->redirect('index.php?option=com_payplans&view=appmanager&checkUpgradeApps=1');
	}

	public function backup() 
	{
		PpinstallerHelperBackup::create();
		$this->_display('backup');	
	}
	
	public function revert() 
	{
		if(JRequest::getVar('remove', false)){
			//before revert, remove extension
			PpinstallerHelperInstall::remove_plugins();
			PpinstallerHelperInstall::remove_component();
			JFactory::getApplication()->redirect('index.php?option=com_ppinstaller&task=revert');
		}
		PpinstallerHelperBackup::revertDbBackup();
		$this->_display('revert');	
	}
	
	public function migrate() 
	{
		//Model wil be included
		$model = $this->getModel('migrate');
		$this->_display('migrate', 'migrate');
	}
	
	function patch()
	{	
		$this->_display('patch','patch');
	}
	
	public function install()
	{
		// Check for request forgeries
		//JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		

		// sub_task => uninstall, extract and install
		$sub_task = JRequest::getVar('subtask',false);
		$next_sub_task = '';
		switch ($sub_task){
			// extract Zip and goes to installation
			case 'extract':
					$extractdir		 = constant('PPINSTALLER_TMP_PAYPLANS'.PPINSTALLER_PAYPLANS_KIT_SUFFIX);
					$archivename	 = constant('PPINSTALLER_PAYPLANS'.PPINSTALLER_PAYPLANS_KIT_SUFFIX).'.'.PPINSTALLER_COMPRESSION_TYPE;
					PpinstallerHelperInstall::extract($archivename, $extractdir);
					$next_sub_task = 'install';
					break;
			case 'install':
					$model = $this->getModel('install');
					if ($model->install()){
						$cache = JFactory::getCache('mod_menu');
						$cache->clean();
					}
					
					//delete folder from tmp after installation
					$extract_dir = constant('PPINSTALLER_TMP_PAYPLANS'.PPINSTALLER_PAYPLANS_KIT_SUFFIX);
					if(is_dir($extract_dir)){
						JFolder::delete($extract_dir);
					}
					break;
			// By default uninstall old version
			default:
				PpinstallerHelperInstall::remove_component();
				// After PayPlans uninstallation. 
				// Continue current process by redirecting otherwise issue will be occured by loaded PayPlans Plugins  
				if(!JRequest::getVar('is_redirect',false)){
					JFactory::getApplication()->redirect('index.php?option=com_ppinstaller&task=install&is_redirect=1');
				}
				$next_sub_task = 'extract';	
		}

		JRequest::setVar('subtask',$next_sub_task);
		$this->_display('install','install');		
	}
	
	public function complete() 
	{
		// Remove PayPlans folder which was created for installation
		PpinstallerHelperUtils::remove_dir();
		// Clear all patch from session
		PpinstallerHelperPatch::clear_session();
		// Clear payplans version and back up table prefix
		PpinstallerHelperMigrate::clear_session();

		require_once JPATH_ROOT.DS.'components'.DS.'com_payplans'.DS.'includes'.DS.'includes.php';
		// log the uninstallation of payplans	
		if(class_exists('PayplansHelperLogger')){
			$message = "PayPlans Installed Successfully";
			PayplansHelperLogger::log(XiLogger::LEVEL_INFO, $message, null, $message);
		}
		
		//enable old payplans menus
		if(class_exists('PayplansSetupMenus')){
			$object = new PayplansSetupMenus();
			$object->_migrateOldMenus();
		}

		$this->_display('complete');
	}
	
	private function _display($layout = 'default', $fun ='display')
	{
		$vName = JRequest::getCmd('view', 'default');
		$view  = $this->getView($vName);
		$view->setLayout($layout);
		$view->$fun();
	}
	
	static function nextTask($task, &$needToContinue)
	{
		switch ($task){
			case 'prerequirements' : 
			case 'display' : 
				// if payplans doesn't exist then install it without any work.
				$nextTask = 'install';
				//make sure ... you have follow all minimum requirment
				if(empty($needToContinue)){
					JFactory::getApplication()
							->enqueueMessage(JText::_('COM_PPINSTALLER_NEED_TO_COMPLETE_PREREQUIRMENTS'),'error');
					$nextTask ='display';
					break;	
				}
				
				
				$isPayPlansExist = PpinstallerHelperBackup::getTables();
			
				// if tables exist then create backup
				if(!empty($isPayPlansExist))
				{
					//don't let the users install payplans 3 series before installing 2.4
			    		$installer = new PpinstallerHelperInstall();
                			if(!$installer->isUpgradeAllowed()){
						$nextTask ='display';
						break;
					}

					if(PpinstallerHelperBackup::is_required()){$nextTask = 'backup'; break;}
					
					$migration_status = PpinstallerHelperMigrate::getKeyValue();
					// Migration staus is "in progress"
					// we already have backup and need to  "migration start" 
					if( $migration_status >= PPINSTALLER_BACKUP_CREATED && 
						$migration_status < PPINSTALLER_MIGRATION_START )
					  {	$nextTask = 'migrate';	}

					  // If Migration hault or for any reason migration didn't complete then Installer will give warning msg
					 if( $nextTask == 'install' &&
					 	 $migration_status >= PPINSTALLER_MIGRATION_START && 
						 $migration_status < PPINSTALLER_MIGRATION_SUCCESS )
						 {
						 	JFactory::getApplication()
								->enqueueMessage(JText::_('COM_PPINSTALLER_MIGRATION_WARNING'),'error');
						 }
					
					if(!PpinstallerHelperMigrate::isRequired()){$nextTask = 'install'; break;} 
				}
				break;
			case 'backup':
			case 'revert':
				$nextTask='install';
				//migrate data if required
				if(PpinstallerHelperMigrate::isRequired()){	$nextTask = 'migrate';	}
				break; 
			case 'migrate':
				$nextTask = 'install';
				if(!$needToContinue) { $nextTask = 'migrate'; }
				break;
			case 'install':
				$nextTask = 'patch';
				$sub_task = JRequest::getVar('subtask',false);
				if($sub_task ){
					$nextTask='install';
				}
				break;
			case 'patch':
				$nextTask = 'complete';
				if(!$needToContinue) { $nextTask = 'patch'; }
				break;
			default://default task
				$nextTask = 'display';
		}
		return $nextTask;
	}
	
	function _migrate(&$needToContinue)
	{
		$migrateAction = JRequest::getVar('migrateAction',false);
		// You are just entering in migration process and 
		//it will start with plan migration
		if(false == $migrateAction){
			$migrateAction = 'before';
		}
			
		$version 	= PpinstallerHelperMigrate::oldVersion();
		$file       = PpinstallerHelperMigrate::fileName($version);
		$class_name = PpinstallerHelperMigrate::className($version);
		
		$file_path =PPINSTALLER_MIGRATER_PATH.DS.$file;
		if(!JFile::exists($file_path)){
			JFactory::getApplication()->redirect('index.php?option=com_ppinstaller&task=display',Jtext::sprintf('COM_PPINSTALLER_MISSING_MIGRATION_FILE',$file_path),'error');	
		}
		
		include_once PPINSTALLER_MIGRATER_PATH.DS.$file;
		$obj	= new $class_name;
		$migrate_info = $obj->$migrateAction();
		
		// set migration will continue or not
		$needToContinue = false;
		if(!isset($migrate_info['migrateAction'])){
			$migrate_info['migrateAction'] = $obj->nextMigrateAction($migrateAction);
		}
		if(empty($migrate_info['migrateAction'])){
			$needToContinue = true;
		}

		// set next task. {migration or install}
		$migrate_info['nextTask'] 	  	= self::nextTask('migrate', $needToContinue);
		$migrate_info['currentAction']  = $migrateAction;
		$migrate_info['action'] 	  	= $obj->get('action');
		$migrate_info['msg']  			= $obj->get('msg');
		return $migrate_info;
	}
	
	// XiTODO:: Required payplans log
	static function _patch(&$view)
	{
		require_once  JPATH_ROOT.DS.'components'.DS.'com_payplans'.DS.'includes'.DS.'includes.php';		
		
		//No need to extra code ...just calling Payplans function
		//PayplansHelperPatch::applyPatches();

		$class		   		='PayplansHelperPatch';
		$view->patches = PpinstallerHelperUtils::get_session_value(PPINSTALLER_REQUIRED_PATCHES, Array());
		if(empty($view->patches)){
			$last_patch = XiHelperPatch::queryPatch();
			if(!empty($last_patch)){
				PpinstallerHelperPatch::update_previous_patches($last_patch);
			}
			$view->patches = PpinstallerHelperPatch::set_into_session();
		}
		
		$view->offset = JRequest::getVar('offset',0);
		$view->needToContinue = false;
		
		foreach ($view->patches as $key => $patch){	
			// if patch already applied then skip it
			if(PpinstallerHelperPatch::is_applicable($patch)){
				$view->key  = $key;
				$next_patch = $patch;
				break;
			}
		}
		
		if(isset($next_patch)){
			if(method_exists($class, $next_patch)===false){
				// XITODO : handle false. Patch does not exist
				exit;
			}
			 
			$result = call_user_func(Array($class, $next_patch), PPINSTALLER_PATCH_LIMIT,$view->offset);
			// log update
			$msg = JText::sprintf('COM_PPINSTALLER_APPLIED_PATCH',$next_patch,$result);
			PpinstallerHelperLogger::log($msg);
			//if some error return false
			if($result === false){
				//XITODO : Patch fail 
				exit;
			}
			// Imp for exe single patch on multiple req
			$view->offset = JRequest::getVar('offset',0);

			// Current patch will be set as applied patch if offset limit is empty
			if(!$view->offset){
				// Manage: Patch has applied
				if(!PpinstallerHelperPatch::insert($next_patch)){
					JFactory::getApplication()
								->enqueueMessage(
									JText::sprintf('COM_PPINSTALLER_PATCH_UPDATION_FAIL',$next_patch));
				}
			}
		}
		else{ 			// Last step of patch or we can say Its a last patch 
			$view->needToContinue = true;
			XiHelperPatch::updateVersion();
			PpinstallerHelperLogger::log('Payplans Version Updated into support table');
			// XiTODO:: use constant instead of -1
			$view->key = -1;
		}
		// Push last step of the patch
		array_push($view->patches,'update_payplans_version');
	}
}
