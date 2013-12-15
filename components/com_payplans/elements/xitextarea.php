<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();
JFormHelper::loadFieldClass('textarea');


class JFormFieldXitextarea extends JFormFieldTextarea
{
	public $type = 'Xitextarea'; 
	protected function getInput()
	{
		if($this->element['decode'] == true){
			  $this->value  = base64_decode($this->value);
			  return parent::getInput();
		}
	}
}