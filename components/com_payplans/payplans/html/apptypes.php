<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHtmlApptypes
{
	static function edit($name, $value, $attr=null, $ignore=array())
	{
		$apps = PayplansHelperApp::getApps();
		
		$options = array();
		if(isset($attr['none']))
			$options[] = JHTML::_('select.option', '', XiText::_('COM_PAYPLANS_SELECT_APPTYPE'));
	
		$appTypes =	PayplansHelperApp::getXmlData('name');
			
		foreach($apps as $app){
			// now it works only for admin payment app
			if(in_array($app, $ignore))
				continue;
			$options[] = JHTML::_('select.option', $app, XiText::_($appTypes[$app]));	
		}

		$style = isset($attr['style']) ? $attr['style'] : '';
		return JHTML::_('select.genericlist', $options, $name, $style, 'value', 'text', $value);
	}
	
	static function filter($name, $view, Array $filters = array(), $prefix='filter_payplans', $attr = "")
	{
		$elementName  = $prefix.'_'.$view.'_'.$name;
		$elementValue = @array_shift($filters[$name]);
		
		$attr['none']  = true;
		$attr['style'] = (isset($attr['style']) ? $attr['style'] : '').' onchange="document.adminForm.submit();"';
		$app = array('adminpay');
		return PayplansHtml::_('apptypes.edit', $elementName.'[]', $elementValue, $attr, $app);
	}
}