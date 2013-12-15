<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class XiHtmlText
{
	static function filter($name, $view, Array $filters = array(), $prefix='filter_payplans', $attr = "")
	{
		$elementName  = $prefix.'_'.$view.'_'.$name;
		$elementValue = @array_shift($filters[$name]);
		
		$html  = '<input id="'.$elementName.'" ' 
						.'name="'.$elementName.'[]" '
						.'value="'.$elementValue.'" ';
		$html = $html.(isset($attr['style']) ? $attr['style'] : 'size="25"');
		$html = $html. '/>';
						
		return $html;
	}
}
