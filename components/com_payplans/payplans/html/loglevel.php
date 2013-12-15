<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHtmlLoglevel
{
	static function grid($name, $value, $attr = null)
	{
		$levels = XiLogger::getLevels();
		return $levels[$value];
	}
	
	static function edit($name, $value, $attr = null)
	{
		$options = array();
		
		if(isset($attr['none']))
			$options[] = JHTML::_('select.option', '', XiText::_('COM_PAYPLANS_LOGGER_SELECT_LOGLEVEL'));

		foreach(XiLogger::getLevels() as $key => $val)
    		$options[] = JHTML::_('select.option', $key, $val);

    	$style = (isset($attr['style'])) ? $attr['style'] : '';
    	return JHTML::_('select.genericlist', $options, $name, $style, 'value', 'text', $value);
	}
	
	static function filter($name, $view, Array $filters = array(), $prefix='filter_payplans', $attr = "")
	{
		$elementName  = $prefix.'_'.$view.'_'.$name;
		$elementValue = @array_shift($filters[$name]);
		
		$attr['none']  = true;
		$attr['style'] = (isset($attr['style']) ? $attr['style'] : '').' onchange="document.adminForm.submit();"';
		return PayplansHtml::_('loglevel.edit', $elementName.'[]', $elementValue, $attr);
	}
}