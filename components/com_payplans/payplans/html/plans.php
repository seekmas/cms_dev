<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHtmlPlans
{
	/**
	 * @return select box html of all available plans
	 * @param $name - name for the html element
	 * @param $value- selected value of plan
	 * @param $attr - other attributes of select box html
	 */
	static function edit( $name, $value, $attr=null )
	{
		$plans = XiFactory::getInstance('plan', 'model')->loadRecords();

		if(isset($attr['multiple']))
			return  PayplansHtml::_('autocomplete.edit',$plans, $name, $attr, 'plan_id', 'title', $value);

		$options = array();
		if(isset($attr['none']))
			$options[] = JHTML::_('select.option', '', XiText::_('COM_PAYPLANS_PLAN_SELECT'));

		foreach($plans as $p)
    		$options[] = JHTML::_('select.option', $p->plan_id, $p->title);

    	$style = (isset($attr['style'])) ? $attr['style'] : '';
    	return JHTML::_('select.genericlist', $options, $name, $style, 'value', 'text', $value);
	}
	
	static function filter($name, $view, Array $filters = array(), $prefix='filter_payplans', $attr = "")
	{
		$elementName  = $prefix.'_'.$view.'_'.$name;
		$elementValue = @array_shift($filters[$name]);
		
		$attr['none']  = true;
		$attr['style'] = (isset($attr['style']) ? $attr['style'] : '').' onchange="document.adminForm.submit();"';
		return PayplansHtml::_('plans.edit', $elementName.'[]', $elementValue, $attr);
	}
}