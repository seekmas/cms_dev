<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class JFormFieldXicountry extends XiField
{
	public $type = 'Xicountry'; 
	
	function getInput()
	{
		$reqNone = parent::hasAttrib($this, 'addnone');
		$attr    = (array)$this->element->attributes();
		return PayplansHtml::_('country.edit',$this->name, $this->value, $attr['@attributes']);
	}
}