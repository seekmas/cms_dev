<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHtmlOrders
{
	/**
	 * @return select box html of all available orders
	 * @param $name - name for the html element
	 * @param $value- selected value of order
	 * @param $attr - other attributes of select box html
	 */
	static function edit($name, $value, $attr=null )
	{
		$orders = XiFactory::getInstance('order', 'model')->loadRecords();

		$options = array();
		if(isset($attr['none']))
			$options[] = JHTML::_('select.option', '', XiText::_('COM_PAYPLANS_ORDER_SELECT'));
		
		foreach($orders as $order)
    		$options[] = JHTML::_('select.option', $order->order_id, $order->order_id);

    	$style = isset($attr['style']) ? $attr['style'] : '';
    	return JHTML::_('select.genericlist', $options, $name, $style, 'value', 'text', $value);
	}
	
	static function filter($name, $view, Array $filters = array(), $prefix='filter_payplans')
	{
		$elementName  = $prefix.'_'.$view.'_'.$name;
		$elementValue = @array_shift($filters[$name]);
		
		$attr['none']  = true;
		$attr['style'] = 'onchange="document.adminForm.submit();"';
		return PayplansHtml::_('orders.edit', $elementName.'[]', $elementValue, $attr);
	}
}