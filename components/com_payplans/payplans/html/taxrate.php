<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHtmlTaxrate
{
	static function edit( $name, $value, $attr=null )
	{
		$taxrates = XiFactory::getInstance('taxrate', 'model')->loadRecords();

		if(isset($attr['multiple'])){
			return  PayplansHtml::_('autocomplete.edit',$taxrates, $name, $attr, 'taxrate_id', 'title', $value);
		}

		$options = array();
		if(isset($attr['none'])){
			$options[] = JHTML::_('select.option', '', XiText::_('COM_PAYPLANS_TAXRATE_SELECT'));
		}

		foreach($taxzones as $p){
    		$options[] = JHTML::_('select.option', $p->taxrate_id, $p->title);
		}

    	$style = (isset($attr['style'])) ? $attr['style'] : '';
    	return JHTML::_('select.genericlist', $options, $name, $style, 'value', 'text', $value);
	}
}