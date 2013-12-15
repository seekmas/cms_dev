<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansSetupUpgradeapp extends XiSetup
{
	public $_location = __FILE__;
	public $_message  = '';

	function isRequired()
	{
		$exist      = false;
		
		//get all the published plugins of payplans
		$plugins    = XiHelperJoomla::getPlugin('payplans', null, 1);
		$regPlugins = XiHelperJoomla::getPlugin('payplansregistration',null, 1);
	 
		//get payplans module 
		$query  = new XiQuery();
        $module = $query->select('extension_id as eid, folder AS type, element AS name, manifest_cache as xml_params')
              			->from('#__extensions')                  
                  		->where('type = "module"') 
                  		->where('element like "%mod_payplans%"')
                  		->where('enabled = 1')
                  		->dbLoadQuery()
                  		->loadObjectList('name');

		$extensions     = array_merge($plugins, $regPlugins);
		$extensions	    = array_merge($extensions, $module);
		
		//get current version of payplans
		$currentVersion = self::payplans_version();
		$ppversion      = explode('.', $currentVersion );
		
		foreach($extensions as $extension){
			$params  = json_decode($extension->xml_params);
			$version = (isset($params->version))? explode('.', $params->version) : null;
			if(empty($version) && $ppversion[0] > $version[0]){
				$exist = true;
				break;
			}
		}
		
 		if($exist){
 			$this->_message = 'COM_PAYPLANS_SETUP_NEED_TO_UPGRADE_APPS';
 			return $this->_required = true;
 		}
 		
	}
	
	static public function payplans_version($table_prefix = '#__') 
	{
		$table_name = $table_prefix.'payplans_support';
		
		$db 	= JFactory::getDbo();
		$query  = " SELECT GROUP_CONCAT(`value` ORDER BY `value` SEPARATOR '.') 
					FROM `$table_name`
					WHERE `key`IN ('global_version', 'build_version')
					";
		$db->setQuery($query);
		return $db->loadResult();
	}

	function doApply()
	{
		XiFactory::getApplication()->redirect("index.php?option=com_payplans&view=appmanager&checkUpgradeApps=1");
	}
}