<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Elements
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

	jimport('joomla.form.formfield');
	
   abstract class XiField extends JFormField
	{		
		public function hasAttrib($node, $attrib)
		{
			return isset($node->elements[$attrib]);
		}
		
		public function getAttrib($node, $attrib, $default = false)
		{
			
			$attributes = (array)$node->element;
			if(isset($attributes['@attributes']) && isset($attributes['@attributes'][$attrib])){
				return $attributes['@attributes'][$attrib];
			}
			
			// defaults
			return $default;
		}
	}