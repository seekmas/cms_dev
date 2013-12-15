<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHtmlPrice
{
	/**
	 * @return text box formated price
	 * @param $name - name for the html element
	 * @param $value- selected value of price
	 * @param $attr - other attributes of price text box
	 */
	static function edit($name, $id, $value, $class='', $size='', $attr=null )
	{
		$value = PayplansHelperFormat::price($value);
		
		if(isset($attr['readonly'])){
			return $value.'<input type="hidden" name="'.$name.'" id="'.$id.'" value="'.$value.'" />';
		}
		
		$style = isset($attr['style']) ? $attr['style'] : '';
		
		return '<input type="text" name="'.$name.'" id="'.$id.'" value="'.$value.'" '.$class.' '.$size.' '.$style.'/>';
	}
}