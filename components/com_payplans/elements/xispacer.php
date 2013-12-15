<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class JFormFieldXispacer extends XiField
{
	public $type ='XiSpacer';
	
	function getInput()
	{
		if ($this->value) {
			return XiText::_($this->value);
		} else {
			return '<div class="clr"></div><hr />';
		}
	}
}
