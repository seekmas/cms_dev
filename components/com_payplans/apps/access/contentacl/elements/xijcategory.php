<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class JFormFieldXijcategory extends XiField
{
	public $type = 'Xijcategory'; 
	function getInput()
	{
		$categories = XiHelperJoomla::getJoomlaCategories('com_content');

		if(empty($categories)){
			return XiText::_('COM_PAYPLANS_CONTENTACL_NO_CATEGORY_AVAILABLE');	
		}
		
		return PayplansHtml::_('autocomplete.edit', $categories, $this->name, array('multiple'=>true), 'category_id', 'title', $this->value);
	}
}
