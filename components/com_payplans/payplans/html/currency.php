<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHtmlCurrency
{
	/**
	 * @return select box html of all available currency
	 * @param $name - name for the html element
	 * @param $value- selected value of currency
	 * @param $attr - other attributes of select box html
	 */
	static function edit($name, $value, $attr=null )
	{
		$items = XiFactory::getCurrency();

		// check for read only
		// XITODO : use disable property
		$readonly = isset($attr['readonly']);
		if($readonly){
			$html = '<label>'.PayplansHelperFormat::currency($items[$value]).'</label>';
			$html.=	'<input type="hidden" name="'.$name.'" value="'.$value.'" />';
			return $html;
		}

		if(isset($attr['hidden'])){
			return '<input type="hidden" name="'.$name.'" value="'.$value.'" />';
		}
		
		$options = array();
		foreach($items as $currency){
			$options[] = JHTML::_('select.option', $currency->currency_id, PayplansHelperFormat::currency($currency, array(), 'fullname'));
		}

		$style = isset($attr['style']) ? $attr['style'] : '';
		return JHTML::_('select.genericlist', $options, $name, $style, 'value', 'text', $value);
	}
}