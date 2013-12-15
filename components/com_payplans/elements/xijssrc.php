<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @contact 		payplans@readybytes.in
*/	


// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

class JFormFieldXiJssrc extends XiField
{
	public $type = 'XiJssrc';
	
	function getInput()
	{
		$filename = ( (string)$this->element['filename'] ? (string)$this->element['filename'] : '' );
		$path 	  = ( (string)$this->element['path']     ? (string)$this->element['path']  : '' );
		
		PayplansHtml::script(JPATH_ROOT.DS.$path.DS.$filename);
    	return '';
		
	}
}
