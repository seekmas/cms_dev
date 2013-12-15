<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

require_once JPATH_ADMINISTRATOR.DS."includes".DS."toolbar.php";

class XiAbstractHelperToolbarBase extends JToolBarHelper
{
	public static function openPopup($task, $icon = '', $iconOver = '', $alt = 'COM_PAYPLANS_TOOLBAR_NEW')
	{
		$bar = JToolBar::getInstance('toolbar');
		$bar->appendButton('Standard', $icon, $alt, $task, false, false );
	}
	
	static function addSubMenu($menu, $selMenu,$comName='com_payplans')
	{
		$selected 	= ($menu==$selMenu);
		$link 		= "index.php?option=".$comName."&view=$menu";
		$title 		= XiText::_('COM_PAYPLANS_SM_'.JString::strtoupper($menu));
		JSubMenuHelper::addEntry($title,$link, $selected);
	}
	
    public static function save($task = 'save', $alt = 'COM_PAYPLANS_TOOLBAR_SAVE_CLOSE')
	{
		JToolBarHelper::save('save','COM_PAYPLANS_TOOLBAR_SAVE_CLOSE');
	}
	
	public static function apply($task = 'apply', $alt = 'COM_PAYPLANS_TOOLBAR_SAVE')
	{
		JToolBarHelper::apply('apply','COM_PAYPLANS_TOOLBAR_SAVE');
	}
	
	public static function savenew()
	{	
		JToolBarHelper::save2new('savenew','COM_PAYPLANS_TOOLBAR_SAVE_NEW');
	}
	
	public static function delete($alt = 'Delete')
	{
		$alt = XiText::_('COM_PAYPLANS_JS_ARE_YOU_SURE_TO_DELETE');
		JToolBarHelper::deleteList($alt);
	}
	
	public static function deleteRecord($alt = 'Delete')
	{
		$alt = XiText::_('COM_PAYPLANS_JS_ARE_YOU_SURE_TO_DELETE');
		JToolBarHelper::deleteList($alt);
	}
	
	public static function cancel($task = 'cancel', $alt = 'COM_PAYPLANS_TOOLBAR_CLOSE')
	{
		JToolBarHelper::cancel($task, $alt);
	}
}


// Include the Joomla Version Specific class, which will ad XiAbstractHelperToolbar class automatically
XiError::assert(class_exists('XiAbstractJ'.PAYPLANS_JVERSION_FAMILY.'HelperToolbar',true), XiError::ERROR);