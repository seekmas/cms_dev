<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class JFormFieldXijsection extends XiField
{
	public $type = 'Xijsection';
	
	function getInput()
	{
		$sections = array();

		if(empty($sections)){
			return XiText::_('COM_PAYPLANS_CONTENTACL_NO_SECTION_AVAILABLE');	
		}
		
		return PayplansHtml::_('autocomplete.edit', $sections, $this->name, array('multiple'=>true), 'section_id', 'title', $this->value);
	}
}