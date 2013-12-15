<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHtmlGroups
{
	/**
	 * @return select box html of all available groups
	 * @param $name - name for the html element
	 * @param $value- selected value of group
	 * @param $attr - other attributes of select box html
	 */
	static function edit( $name, $value, $attr=null)
	{		
		// clean where clause so that we'll get all data
		$groups =  XiFactory::getInstance('group', 'model')->loadRecords(array(), array('where', 'limit'));

		if(isset($attr['unset'])){
			foreach($attr['unset'] as $unset){
				unset($groups[$unset]);
			}
		}
		
		if(isset($attr['multiple']))
			return  PayplansHtml::_('autocomplete.edit',$groups, $name, $attr, 'group_id', 'title', $value);

		$options = array();
		if(isset($attr['none']))
			$options[] = JHTML::_('select.option', '', XiText::_($attr['none']));

		foreach($groups as $group)
    		$options[] = JHTML::_('select.option', $group->group_id, $group->title);

    	$style = (isset($attr['style'])) ? $attr['style'] : '';
    	return JHTML::_('select.genericlist', $options, $name, $style, 'value', 'text', $value);
	}
	
	static function filter($name, $view, Array $filters = array(), $attr = array(), $prefix='filter_payplans')
	{
		$elementName  = $prefix.'_'.$view.'_'.$name;
		$elementValue = @array_shift($filters[$name]);
		
		$attr['style'] = (isset($attr['style']) ? $attr['style'] : '').' onchange="document.adminForm.submit();"';
		return PayplansHtml::_('groups.edit', $elementName.'[]', $elementValue, $attr);
	}
}