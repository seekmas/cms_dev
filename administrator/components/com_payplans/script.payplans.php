<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class Com_payplansInstallerScript
{
	
	/**
	 * Runs on installation
	 * 
	 * @param JInstaller $parent 
	 */
	public function install($parent)
	{
		require_once dirname(__FILE__).DS.'admin'.DS.'installer'.DS.'installer.php';

		$installer	= new PayplansInstaller();

		return $installer->install();
	}
	
	/**
	 * Runs on uninstallation
	 * 
	 * @param JInstaller $parent 
	 */
	function uninstall($parent)
	{
		//it is called during installation, so we should not autoload
		require_once dirname(__FILE__).DS.'installer'.DS.'installer.php';
	
		$installer	= new PayplansInstaller();
	
		return $installer->uninstall();
 	}
 	
	public function preflight($type, $parent)
	{
		if($type == 'install' || $type == 'update'){
			self::_deleteAdminMenu();
		}	
	}

	/**
	 * Joomla! 1.6+ bugfix for "Can not build admin menus"
	 */
	function _deleteAdminMenu()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		//get all records
		$query->delete('#__menu')
			  ->where($db->quoteName('type').' = '.$db->quote('component'))
			  ->where($db->quoteName('menutype').' = '.$db->quote('main'))
			  ->where($db->quoteName('link').' LIKE '.$db->quote('index.php?option=com_payplans'));
	
		return $db->setQuery($query)->query();
	}
}
