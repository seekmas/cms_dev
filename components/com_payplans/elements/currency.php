<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class JFormFieldCurrency extends XiField
{
	public $type = 'currency'; 
	
	function getInput()
	{
		$attr  = array();
		$value = $this->value;
		
		if($this->hasAttrib($this, 'readonly')){
			$attr['readonly'] = true;
		}
		
		if(empty($value)){
			$value = XiFactory::getConfig()->currency;
		}
		return PayplansHtml::_('currency.edit',$this->name, $value, $attr);
		
	}
}