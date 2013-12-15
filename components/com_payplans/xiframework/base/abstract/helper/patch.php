<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class XiAbstractHelperPatchBase
{
	static function changeModuleState($name,$position,$newState = 1)
	{
		$db		= JFactory::getDBO();
		
		//check whether the module exists or not
		$query  = 'SELECT `id` FROM '.$db->quoteName( '#__modules' )
			     .' WHERE '.$db->quoteName('module').'='.$db->Quote($name);

		if(!$db->setQuery($query)->loadResult()){
			return true;
		}
		
		$query	= ' UPDATE ' . $db->quoteName( '#__modules' )
				. ' SET '    . $db->quoteName('published').'='.$db->Quote($newState)
				. ',  '    . $db->quoteName('position').'='.$db->Quote($position)
		        . ' WHERE '  . $db->quoteName('module').'='.$db->Quote($name);
		$db->setQuery($query);
		if(!$db->query())
			return false;

		// also apply it to all menus J1.6 requirement
		
		$query	= ' SELECT `id` FROM ' . $db->quoteName( '#__modules' )
		        . ' WHERE '  . $db->quoteName('module').'='.$db->Quote($name);
		$db->setQuery($query);
		$moduleId = $db->loadResult();
		
		
		//during re-installation it will break, so added ignore
		$query	= ' INSERT IGNORE INTO ' . $db->quoteName( '#__modules_menu' )
				. ' ( `moduleid` , `menuid` ) ' 
				. " VALUES ({$moduleId}, '0') "    
				;
		$db->setQuery($query);
		if(!$db->query())
			return false;

		return true;
	}
	
	static function changePluginState($name, $newState = 1, $folder = 'system')
	{
		$db		= JFactory::getDBO();
	        
		$query	= 'UPDATE '. $db->quoteName( '#__extensions' )
				. ' SET   '. $db->quoteName('enabled').'='.$db->Quote($newState)
				. ' WHERE '. $db->quoteName('element').'='.$db->Quote($name)
				. ' AND ' . $db->quoteName('folder').'='.$db->Quote($folder) 
				. " AND `type`='plugin' ";
		
		$db->setQuery($query);
		if(!$db->query())
			return false;

		return true;
	}
	
	static function uninstallPlugin($name, $folder)
	{
		$db		=& JFactory::getDBO();
		
		$query	= ' SELECT  `extension_id` FROM  '. $db->quoteName( '#__extensions' )
				. ' WHERE '. $db->quoteName('element').'='.$db->Quote($name)
				. ' AND ' . $db->quoteName('folder').'='.$db->Quote($folder) 
				. " AND `type`='plugin' ";
				
		$db->setQuery($query);
		$identifier = $db->loadResult();
		
		if(!$identifier){
			return true;
		}		
		
		return self::uninstallExtension('plugin', $identifier, $cid=0);
	}
	
	static function uninstallModule($name)
	{
		$db		=& JFactory::getDBO();
		$query	= 'SELECT  `extension_id` FROM ' . $db->quoteName('#__extensions' )
		        . ' WHERE ' . $db->quoteName('element').'='.$db->Quote($name)
		        . ' AND ' . $db->quoteName('type').'='.$db->Quote('module')
		        ;

		$db->setQuery($query);
		$identifier = $db->loadResult();
		
		if(!$identifier){
			return true;
		}	
		
		return self::uninstallExtension('module', $identifier);
	}
	
	static function uninstallExtension($type, $identifier, $cid=0)
	{
		//type = component / plugin / module
		// $id = id of ext
		// cid = client id (admin : 1, site : 0) 
		$installer =  new JInstaller();
		return $installer->uninstall($type, $identifier, $cid);
	}
	
	//update the ordering of module
	static function changeModuleOrder($order, $moduleName)
	{
		$db		=& JFactory::getDBO();
		$query	= ' UPDATE ' . $db->quoteName( '#__modules' )
			. ' SET '    . $db->quoteName('ordering').'='.$db->Quote($order)
		        . ' WHERE '  . $db->quoteName('module').'='.$db->Quote($moduleName);
		$db->setQuery($query);
		$db->query();
	}
}

// Include the Joomla Version Specific class, which will ad XiAbstractHelperToolbar class automatically
XiError::assert(class_exists('XiAbstractJ'.PAYPLANS_JVERSION_FAMILY.'HelperPatch',true), XiError::ERROR);
