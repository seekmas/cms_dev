<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class JFormFieldRewriter extends XiField
{
	public $type = 'Rewriter'; 
	
	function getInput()
	{
		PayplansHelperTemplate::loadAssets();	
		return PayplansHtml::_('rewriter.edit', $this->name, $this->value, $this->group.$this->fieldname);
	}
}