<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

define('MENU_TABLE_COMPONENT_ID_STR','component_id') ;
class PayplansSetupMenus extends XiSetup
{
	public $_location = __FILE__;
	public $_message  = 'COM_PAYPLANS_SETUP_MENUS_DO_ENABLE';
	public $_type = 'INFORMATION';

        public $_menus = array(
            'index.php?option=com_payplans' => array('title'=> 'Memberships', 'alias'=>'membership'),
            '%index.php?option=com_payplans&view=dashboard%'  => array('title'=> 'My Dashboard', 'alias'=>'mydashboard'),
            '%index.php?option=com_payplans&view=plan&task=subscribe%'  => array('title'=> 'Subscribe', 'alias'=>'subscribe')
        );
	public $_componentId = null;

	public function __construct()
	{
		parent::__construct();

		// find component id
		$cmp = JComponentHelper::getComponent('com_payplans');
		$this->_componentId	 = $cmp->id;
	}

	function isRequired()
	{
		// migrate old menus if exists
		$this->_migrateOldMenus();

		$this->_status = true;
        foreach ($this->_menus as $url => $data){
            $this->_status = $this->_status && $this->_hasMenu($url);
        } 

		if($this->_status){
			$this->_message = 'COM_PAYPLANS_SETUP_MENUS_DONE';
			return $this->_required=false;
		}

		return $this->_required=true;
	}

	function doApply()
	{
		$unpublished = 0;
		$trashed     = -2;

		foreach ($this->_menus as $url => $data){
           	$url = strstr($url,'%')?str_replace('%','',$url):$url;
			//if menu is either unpublished or in trash then mark the status of menu-item as published            
			if($this->_checkMenuStatus( $data['alias'], $url, $data['title'], $unpublished) || $this->_checkMenuStatus( $data['alias'], $url, $data['title'], $trashed)){
            	continue;
            }

           	$url = strstr($url,'%')?str_replace('%','',$url):$url;
       		$this->_addMenu( $data['title'], $data['alias'], $url);
         }

         return true;
     }

	function _checkMenuStatus($alias, $link, $title, $status){
		if($this->_hasMenu($link, $status)){	
			$name = 'title';
			
			$db = XiFactory::getDBO();
			$db->setQuery(' UPDATE `#__menu`
					SET `published` = 1
					WHERE `published` = "'.$status.'"
					AND '.$name.' = "'.$title.'"					
					AND `alias` = "'.$alias.'" ');
			$db->query();
			return true;
		} 
		return false;
	}

	function _hasMenu($link, $status=1)
	{
		return XiHelperJoomla::isMenuExist($link, $this->_componentId, $status);
	}

	function _addMenu($title, $alias, $link, $menu='mainmenu')
	{
		// if link already exist
		if($this->_hasMenu($link)){
			return true;
		}

		return XiHelperJoomla::addMenu($title, $alias, $link, $menu, $this->_componentId);
	}
	
	function _migrateOldMenus()
	{
		$query = "UPDATE `#__menu`
				  SET `".MENU_TABLE_COMPONENT_ID_STR."` = ".$this->_componentId." 
				  WHERE `".MENU_TABLE_COMPONENT_ID_STR."` <> ".$this->_componentId." 
				  	AND `link` LIKE '%option=com_payplans%' ";
		
		$db = XiFactory::getDBO();
		$db->setQuery($query);
		$result = $db->query();
		
		$query = "UPDATE `#__menu`
				  SET `link` = concat_ws('&',link, 'task=subscribe')
				  WHERE `link` LIKE 'index.php?option=com_payplans&view=plan' 
				  		AND `link` NOT LIKE 'task=subscribe' ";
		
		$db = XiFactory::getDBO();
		$db->setQuery($query);
		return $db->query();
	}
}
