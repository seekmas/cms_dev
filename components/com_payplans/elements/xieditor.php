<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();
JFormHelper::loadFieldClass('editor');


class JFormFieldxieditor extends JFormFieldEditor
{
	public $type = 'xieditor'; 
	
	function getInput()
	{
		$this->value  = base64_decode($this->value);
		return parent::getInput();
	}
}