<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class JFormFieldParammanipulator extends XiField
{
	public $type = 'parammanipulator'; 
	
	function getInput()
	{
		$class = ( (string)$this->element['class'] ? (string)$this->element['class'] : 'inputbox' );
		$class = 'class="'.$class.' pp-parammanipulator"';
		$options = array ();
		$params  = array();
		
		foreach ($this->element->children() as $option)
		{
			// Only add <option /> elements.
			if ($option->getName() != 'option')
			{
				continue;
			}
			$val	   = (string) $option['value'];
			$options[] = PayplansHtml::_('select.option', $val, XiText::_((string) $option));
			
			// get attribute params from each option
			$paramsVal	= (string)$option['params'];
			$params[$val] = explode(',', $paramsVal);
		}
		
		return PayplansHtml::_('parammanipulator.edit',$options, $this->value, $params, ''.$this->name, $class, $this->group.$this->fieldname ,$this->group);
		
	}
}
