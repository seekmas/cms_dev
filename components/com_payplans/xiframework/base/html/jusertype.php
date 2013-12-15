<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class XiHtmlJusertype extends XiHtml
{
	static function edit($name, $value, $attr=null, $ignore=array())
	{
		$options = array();
		
		$groups 	= XiHelperJoomla::getUsertype();
		
		$textField = 'value';
		$valueField = 'name';
		
		if(isset($attr) && isset($attr['userAutocomplete']) && $attr['userAutocomplete'] == false){
			if(isset($attr['none']))
                       $options[] = JHTML::_('select.option', '', XiText::_('COM_PAYPLANS_SELECT_USERTYPE'));
                       
            foreach($groups as $group=>$val){
            	$options[] = JHTML::_('select.option', $val, $val);        
            }

            $style = isset($attr['style']) ? $attr['style'] : '';
            return JHTML::_('select.genericlist', $options, $name, $style, 'value', 'text', $value);
		}
		
	    return PayplansHtml::_('autocomplete.edit', $groups, $name, $attr, $textField, $valueField, $value);		
	}
	
	static function filter($name, $view, Array $filters = array(), $prefix='filter_payplans', $attr = "")
	{
		$elementName  = $prefix.'_'.$view.'_'.$name;
		$elementValue = @array_shift($filters[$name]);
		
		$attr['none'] = true;
		$attr['userAutocomplete'] = false;
		$attr['style'] = (isset($attr['style']) ? $attr['style'] : '').' onchange="document.adminForm.submit();"';
		return XiHtml::_('jusertype.edit', $elementName.'[]', $elementValue, $attr);
	}
}