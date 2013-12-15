<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();

// Get installer libraries
jimport( 'joomla.installer.installer' );
jimport('joomla.installer.helper');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');


//load helper
require_once dirname(__FILE__).DS.'..'.DS.'includes'.DS.'includes.php';

class PayplansInstaller extends JObject
{
	function uninstall()
	{	
		//also check task otherwise process will always remain in loop	
		if(JRequest::getVar('option') == 'com_ppinstaller' && JRequest::getVar('task') != 'revert'){
	 	    //first check, if database is required to be reverted
	        //before installing the current version
	        $oldVersion = ppinstallerHelperMigrate::oldVersion('major');
	        $newVersion = PpinstallerHelperMigrate::requiredVersion(null,'major');
	        if(version_compare($oldVersion, $newVersion,'>') ){
	            $msg = XiText::_("COM_PAYPLANS_REVERT_BACKUP_BEFORE_DOWNGRADING");
	            XiFactory::getApplication()->redirect(XiRoute::_('index.php?option=com_ppinstaller'), $msg,'error');
	        }
		}
	
		$this->updateExtensions(0);
		//In order to remove _filetree chaching, _filetree.php is removed
 		// It will be created again when cron runs
		$tmpPath = JFactory::getConfig()->get('tmp_path');
	    $filename = $tmpPath.DS.'_filetree.php';
	    if(file_exists($filename))
	    {
	    	unlink($filename);
	    }	
	}

	function install()
	{
		//#1 : file have already copied

		//#3 : Install additional extensions
		//These are in upgrade mode.
		$this->installExtensions();

		//#2 : Now apply version to verion db updates
		$this->applyPatches();

		// Enable System Plugin
		$this->updateExtensions(1);
		
		//check if error occured
		$errors = XiHelperPatch::addError(null,true);

		//if no errors simply return
		if(empty($errors)){
			// log installation of payplans			
//			if(class_exists('PayplansHelperLogger')){
//				$message = "PayPlans Installed Successfully";			
//				PayplansHelperLogger::log(XiLogger::LEVEL_INFO, $message, null, $message);
//			}
			return true;
		}

		//some errors are there display them and write into errlog
		$buffer	= "Installation logs on ". date('D, d M Y H:i:s',time()) . '\n';
		foreach($errors as $err)
			$buffer = $buffer.$err.'\n';

		$logFileName = "install.".time().".log";
		$res = JFile::write(dirname(__FILE__).DS.$logFileName, $buffer);

		//put error message
		$app = XiFactory::getApplication();
		$app->enqueueMessage(JText::sprintf('INSTALLATION ERROR. PLEASE SEE LOG FILE FOR DETAILS %s', $logFileName));

		//if we were not able to write file show permission error
		if(!$res)
			$app->enqueueMessage(JText::sprintf('NOT ABLE TO WRITE LOG FILE - %s', $logFileName));

//		if(class_exists('PayplansHelperLogger')){
//			$message = "Error Occurred in PayPlans Installation";
//			PayplansHelperLogger::log(XiLogger::LEVEL_ERROR, $message, null, $errors);
//		}
		return true;
	}

	/**
	 * @param string  $zipfile : file to install
	 * @return boolean : success or failed
	 */
	function installExtensions($appsPath=null,$delFolder=true)
	{
		//if no path defined, use default path
		if($appsPath==null)
			$appsPath = dirname(__FILE__).DS.'apps';

		//get instance of installer
		$installer =  new JInstaller();
		//$installer->setOverwrite(true);

		$apps	= JFolder::folders($appsPath);
		$ignore = array('');
		//no apps there to install
		if(empty($apps))
			return true;

		//install all apps
		foreach ($apps as $app)
		{
			$msg = " ". $app . ' : Installed Successfully ';

			// Install the packages
			if($installer->install($appsPath.DS.$app)==false){
				$msg = " ". $app . ' : Installation Failed. Please try to reinstall. [Supportive plugin/module for PayPlans]';
				
				//Log if not able to install
				if(class_exists('PayplansHelperLogger')){				
					PayplansHelperLogger::log(XiLogger::LEVEL_ERROR, $msg, null, $msg);
				}
			}

			//enque the message
			JFactory::getApplication()->enqueueMessage($msg);

			//if(class_exists('PayplansHelperLogger')){
			//	// Imp : We do not need this logging
			//	PayplansHelperLogger::log(XiLogger::LEVEL_INFO, $msg, null, $msg);
			//}
		}

		if($delFolder){
			JFolder::delete($appsPath);
		}
		return true;
	}

	/*
	 * We might want to do some code/db modification
	 * whenever we install the component
	 *
	 * Here we will maintain, what have been patched,
	 * and what needs to be patched
	 * We will apply patches in order,
	 * so solving lots of complex problems
	 */
	function applyPatches($lastPatch=null)
	{	
		// Primary data (included install.sql) 
		PayplansHelperPatch::firstPatch();
		
		// System data if required
		// IMP : Now we must not install data everytime, just one time for fresh installation
		//PayplansHelperPatch::secondPatch();

		// Required plugin should be enabled
		PayplansHelperPatch::patch_enable_plugins();
			
		// always enable admin modules at least
		PayplansHelperPatch::patch_enable_modules();
		
		return true;
	}

	// Always enable extension
	function updateExtensions($enable)
	{
		$plugins	= array( 
						array('system',		'payplans')
					  );
						
		foreach($plugins as $plugin){
			$folder = $plugin[0];
			$pluginName = $plugin[1];
			XiHelperPatch::changePluginState($pluginName, $enable, $folder);
		}
	}
}

