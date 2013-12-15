<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		team@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class JFormFieldPopup extends XiField
{
	public $type = 'popup'; 
	
	function getInput()
	{
		$options = array();
		$params  = array();
	
		$url = (string)$this->element['url'];
		foreach ($this->element->children() as $request)
		{
			$name 		= (string)$request['name'];
			$defaultVal = (string)$request['default'];
			
			$url .= "&".$name."=".JRequest::getVar($name, $defaultVal);
		}
		
		return '<a href="" onclick="payplans.url.modal(\''.XiRoute::_($url).'\');return false;" >'.XiText::_('COM_PAYPLANS_ELEMENT_POPUP_CLICK_HERE').'</a>';
	}
}
