<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class JFormFieldJusertype extends XiField
{
	public $type = 'Jusertype'; 
	
	function getInput()
	{
		$groups 	= XiHelperJoomla::getJoomlaGroups();
		return PayplansHtml::_('autocomplete.edit', $groups, $this->name, array('multiple'=>true), 'value', 'name', $this->value);
	}	
}