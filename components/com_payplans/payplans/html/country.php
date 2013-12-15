<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHtmlCountry
{
	static function grid($name, $value, $attr=null )
	{
		if(in_array($value, array(PAYPLANS_CONST_NONE,PAYPLANS_CONST_ALL))){
			$html = '<lable>'.PayplansHelperFormat::country($value).'</label>';	
		}else{
			$items = XiFactory::getInstance('country', 'model')->loadRecords(array('id'=>$value));
			$html = '<lable>'.PayplansHelperFormat::country($items[$value]).'</label>';
		}
		$html.=	'<input type="hidden" name="'.$name.'" value="'.$value.'" />';
		return $html;
	}
	
	static function edit($name, $value, $attr=null, $type='country_id' )
	{
		$options = array();
		$items = XiFactory::getInstance('country', 'model')->loadRecords();

		if(isset($attr['option_none'])){
			$options[] = JHTML::_('select.option', PAYPLANS_CONST_NONE, PayplansHelperFormat::country(PAYPLANS_CONST_NONE));
		}
		
		if(isset($attr['option_all'])){
			$all 			 = new stdClass();
			$all->$type = PAYPLANS_CONST_ALL;
			$all->title 	 = PayplansHelperFormat::country(PAYPLANS_CONST_ALL); 
			$items[PAYPLANS_CONST_ALL] 		 = $all;
		}
		
		// check for read only
		if(isset($attr['readonly'])){
			$html = '<lable>'.PayplansHelperFormat::country($items[$value]).'</label>';
			$html.=	'<input type="hidden" id="'.$name.'" name="'.$name.'" value="'.$value.'" />';
			return $html;
		}
		
		if(isset($attr['multiple'])){
			return  PayplansHtml::_('autocomplete.edit',$items, $name, $attr,$type, 'title', $value);
		}

		foreach($items as $country){
			$options[] = JHTML::_('select.option', $country->$type, PayplansHelperFormat::country($country));
		}

		$style = isset($attr['style']) ? $attr['style'] : '';
		return JHTML::_('select.genericlist', $options, $name, $style, 'value', 'text', $value);
	}
}