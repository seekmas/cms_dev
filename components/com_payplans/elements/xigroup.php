<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class JFormFieldXigroup extends XiField
{
	public $type = 'Xigroup'; 
	
	function getInput()
	{
		$reqNone = parent::hasAttrib($this, 'addnone');

		$attr    = array();
		$attr    = array('none'	=>	'COM_PAYPLANS_NONE');
	
		if(isset($node->_attributes['multiple']) ){
			$attr['multiple'] = $node->attributes('multiple');
		}


		return PayplansHtml::_('groups.edit',$this->name, $this->value, $attr);
	}
}