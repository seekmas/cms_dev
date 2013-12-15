<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class JFormFieldXistatus extends XiField
{
	public $type = 'Xistatus'; 
	
	function getInput()
	{
		$value = '';
		if(!empty($this->value) && !is_array($this->value)){
			$value  = explode("|", $this->value);
		}
		$entity   = parent::getAttrib($this, 'entity', '');
		$exclude  = parent::getAttrib($this, 'exclude','');
		$multiple = parent::getAttrib($this, 'multiple', false);
		$autocomplete = parent::getAttrib($this, 'useautocomplete','0');
		$attr = array();
		if($multiple){
			$attr['multiple'] = true;
		}
		
		// autocomplete is not working with joomla3.x because of jquery conflicts 
		// so set 'useautocomplete' to '0' in xml whenever its needed to use this field in
		// plugin/module xml 
		if(!$autocomplete){
			return PayplansHtml::_('status.edit', $this->name, $this->value, $entity,$exclude, $attr,'',false);
		}
		
		return PayplansHtml::_('status.edit', $this->name, $value, $entity,$exclude, $attr);
	}
}