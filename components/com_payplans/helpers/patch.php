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

jimport('joomla.filesystem.file');

class PayplansHelperPatch
{
	static function getMapper()
	{
		$_mapper = array();
		// when add new patch update the definition
		
		$_mapper  = Array('secondPatch',
						  'patch_enable_plugins',
						  'patch_enable_modules',
						  'patch_20_001_create_email_attachment_dir'
						);
		return $_mapper;
	}
	
	static function applyPatches($class='PayplansHelperPatch', $mapper=null)
	{
		if($mapper === null){
			$mapper = self::getMapper();
		}
		
		$lastPatch = XiHelperPatch::queryPatch();
		$key 	   = array_search($lastPatch, $mapper);
		
		$key++;
		
		while (isset($mapper[$key])){
			$nextPatch = $mapper[$key];
			if(method_exists($class, $nextPatch)===false){
				return false;
			}
			// XITODO : handle false return 
			$result = call_user_func(array($class, $nextPatch));
			//if some error return false
			if($result === false){
				return false;
			}
			//update current patch to database
			XiHelperPatch::updatePatch($nextPatch);			
			$key++;
		}
		
		return true;
	}

	//do install install.sql
	static function firstPatch()
	{
		return XiHelperPatch::applySqlFile(dirname(__FILE__).DS.'patch'.DS.'sql'.DS.'install.sql');
	}


	//install system data
	static function secondPatch()
	{
		// check if admin payment app already exists 
		// if exists then do nothing other wise insert it
		$query 	= new XiQuery();
		$result = $query->select('app_id')
						->from('#__payplans_app')
						->where(' `type` = "adminpay" ')
						->dbLoadQuery()->loadObjectList();
						
		if(empty($result)){
			$db = XiFactory::getDBO();
			$sql = "INSERT IGNORE INTO `#__payplans_app` 
					(`title`, `type`, `description`, `core_params`, `app_params`, `ordering`, `published`) 
					VALUES
					('Admin Payment', 'adminpay', 'Through this application Admin can create payment from back-end. There is no either way to create payment from back-end. This application can not be created, changed and deleted. And can not be used for fron-end payment.', 'applyAll=1\n\n', '\n', 1, 1)";
			$db->setQuery($sql);
			$db->query();
		}
		return XiHelperPatch::applySqlFile(dirname(__FILE__).DS.'patch'.DS.'sql'.DS.'system-data.sql');
	}

	static function patch_enable_plugins()
	{
		$enable=1;
		$plugins = array( 
						array('system',		'payplans'),
					 	array('payplans',	'discount'),
					 	array('payplansmigration',	'sample'),
					    array('payplansregistration', 'auto'),
					    array('payplans', 'appmanager')
					  );
						
		foreach($plugins as $plugin){
			$folder = $plugin[0];
			$pluginName = $plugin[1];
			XiHelperPatch::changePluginState($pluginName, $enable, $folder);
		}

		return true;
	}
	
	static function patch_enable_modules()
	{
		$enable=1;
		$modules	= array(
					'mod_payplans_quickicon'	=> 'icon'
				);
		foreach($modules as $module=>$position){
			XiHelperPatch::changeModuleState($module, $position, $enable);
		}

		// special case order mod_payplans_setup at least order
		XiHelperPatch::changeModuleOrder(-100, 'mod_payplans_setup');
		return true;
	}
		
    //enbale only system plugin during upgradation
	static function patch_enable_system_plugin($enable=1)
	{
		XiHelperPatch::changePluginState('payplans', $enable, 'system');
        return true;
	}

	static function patch_20_001_create_email_attachment_dir()
	{
		$path = JPATH_SITE.DS.'media'.DS.'payplans'.DS.'app'.DS.'email';
		//check if folder exist or not. If not exists then create it.
		if(JFolder::exists($path)==false)
		{	
			return JFolder::create($path);
		}
		return true;
	}
}
