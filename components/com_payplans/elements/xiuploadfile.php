<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
// no direct access
if(!defined('_JEXEC')) die('Restricted access');

class JFormFieldXiUploadfile extends XiField
{
	public $type = 'Xiupload'; 
	
	function getInput()
	{
		$value = htmlspecialchars(html_entity_decode($this->value, ENT_QUOTES), ENT_QUOTES);
        return '<input type="file" name="'.$this->name.'" id="'.$this->id.'" value="'.$value.'" />';	
	}
}