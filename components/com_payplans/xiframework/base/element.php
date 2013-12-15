<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Elements
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class XiElement
{
	public static function hasAttrib($node, $attrib)
	{
		// for php 5.3 specific
		if(is_object($node->_attributes) && isset($node->_attributes->$attrib))
			return true;

		if(isset($node->_attributes[$attrib]))
			return true;

		return false;
	}
	
	public static function getAttrib($node, $attrib, $default = false)
	{
		$attributes = (array)$node->attributes();
		if(isset($attributes['@attributes']) && isset($attributes['@attributes'][$attrib])){
			return $attributes['@attributes'][$attrib];
		}
		
		// for php 5.3 specific
		if(is_object($node->_attributes) && isset($node->_attributes->$attrib))
			return $node->_attributes->$attrib;
		//for j2.5 
		if(isset($node->_attributes[$attrib]))
			return $node->_attributes[$attrib];
			
		// defaults
		return $default;
	}
	
	//Collect all attributes
	public static function getAttributes($node)
	{
		// for php 5.3 specific
		if(is_object($node->_attributes))
			return (array)$node->_attributes;

		return $node->_attributes;
	}
	
	public function render(&$xmlElement, $value, $control_name = 'params')
	{

		$name = $xmlElement->attributes('name');
		$label = $xmlElement->attributes('label');
		$descr = $xmlElement->attributes('description');

		//make sure we have a valid label
		$label = $label ? $label : $name;
		$result[0] = $this->fetchTooltip($label, $descr, $xmlElement, $control_name, $name);
		$result[1] = $this->fetchElement($name, $value, $xmlElement, $control_name);
		$result[2] = $descr;
		$result[3] = $label;
		$result[4] = $value;
		$result[5] = $name;

		return $result;
	}
	
	public function fetchTooltip($label, $description, &$xmlElement, $control_name = '', $name = '')
	{
		$output = '<label id="' . $control_name . $name . '-lbl" for="' . $control_name . $name . '"';
		if ($description)
		{
			$output .= ' class="hasTip" title="' . JText::_($label) . '::' . JText::_($description) . '">';
		}
		else
		{
			$output .= '>';
		}
		$output .= JText::_($label) . '</label>';

		return $output;
	}
}