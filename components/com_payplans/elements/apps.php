<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class JFormFieldApps extends XiField
{
	public $type = 'apps'; 
	
	function getInput()
	{
		$purpose = parent::getAttrib($this, 'apptype', '');
		
		$attr    = array('queryfilter'	=>	array('published'=>1));

		return PayplansHtml::_('apps.edit', $this->name, $this->value, $purpose, $attr);
	}
}
