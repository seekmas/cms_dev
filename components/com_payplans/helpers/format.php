<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansHelperFormat
{
	static function country($item, $config=array())
	{
		if(is_object($item)==false){
			
			if(is_array($item) && is_object(array_shift($item))){
				return XiText::_('COM_PAYPLANS_COUNTRY_NONE_SELECTED');
			}

			switch($item)
			{
				case PAYPLANS_CONST_NONE : 
					return XiText::_('COM_PAYPLANS_COUNTRY_NONE');
				case PAYPLANS_CONST_ALL : 
					return XiText::_('COM_PAYPLANS_COUNTRY_ALL');
				default :
					return XiError::assert(false, 'INVALID_COUNTRY_CODE');
			}
		}
		
		return $item->title;
	}

	static function currency($item, $config=array(), $format = null)
	{
		$config = XiFactory::getConfig();
		$format = ($format === null) ? $config->show_currency_as : $format;
		
		if(!isset($format) || $format == 'fullname'){
			return $item->title.' ('. $item->currency_id .')';
		}
		
		if($format == 'isocode'){
			return $item->currency_id;
		}
		
		if($format == 'symbol'){
			return $item->symbol;
		}
		
		return false;
	}
	
	static function date(XiDate $date ,$format=null)
	{
		$config = XiFactory::getConfig();
		$format = ($format === null) ? $config->date_format : $format;
		return $date->toFormat($format);
	}

	static function amount($amount, $currency, $config=array())
	{
		// Standard way of formatting amount display
		$amount = self::price($amount);
		return "$currency $amount";
	}
	
	static function displayAmount($amount, $config=array())
	{
		// use this formatter only in case of amount display
		// do not use it when some calculation is required to be done on the returned amount
		$config = XiFactory::getconfig();
		$fractionDigitCount = $config->fractionDigitCount;
		$separator = $config->price_decimal_separator;
		return  number_format(round($amount, $fractionDigitCount), $fractionDigitCount, $separator, '');
	}
	
	static function price($amount)
	{
		$fractionDigitCount = XiFactory::getconfig()->fractionDigitCount;
		
		// XITODO : configuration for rounding the value or not
		return number_format(round($amount, $fractionDigitCount), $fractionDigitCount, '.', '');
	}

	static function user($item, $config=array())
	{
		if(!empty($config)){
			return self::_ops('user', $item, $config);
		}
		
		return $item->realname.' ( '.$item->username.' ) ';
	}

	static function app($item, $config=array())
	{
		return $item->title;
	}


	static function planTime($time, $config=array())
	{
		return PayplansHelperTemplate::partial('default_partial_format_timer', array('timer' => $time));
	}
	
	static function order($item, $config=array('prefix'=>false, 'link'=>false, 'admin'=>false, 'attr'=>''))
	{
		return self::_ops('order', $item, $config);
	}
	
	static function subscription($item, $config=array('prefix'=>false, 'link'=>false, 'admin'=>false, 'attr'=>''))
	{
		return self::_ops('subscription', $item, $config);
	}
	
	static function payment($item, $config=array('prefix'=>false, 'link'=>false, 'admin'=>false, 'attr'=>''))
	{
		return self::_ops('payment', $item, $config);
	}
	
	static function invoice($item, $config=array('prefix'=>false, 'link'=>false, 'admin'=>false, 'attr'=>''))
	{
		return self::_ops('invoice', $item, $config);
	}
	
	private static function _ops($entity, $item, $config)
	{
		$str = $config['prefix'] ? XiText::_('COM_PAYPLANS_SEARCH_'.JString::strtoupper($entity)).' # ' : '' ;
		
		// show ID in admin
		$id = array('var'=>'key', 'value'=>$item->getKey());
		if($config['admin']){
			$id = array('var'=>'id', 'value'=>$item->getId());	
		}
		
		// add ID in string
		$str .= $id['value'];
		
		// do we need to create link
		if($config['link']){
			$link = XiRoute::_('index.php?option=com_payplans&'."view={$entity}&task=edit&{$id['var']}={$id['value']}");
			$str = PayplansHtml::link($link, $str, $config['attr']);
		}
		
		return $str;
	}
	
	static function plan($item, $config=array())
	{
		if(!empty($config)){
			return self::_ops('plan', $item, $config);
		}
		
		return $item->title;
	}
	
	static function group($item, $config=array())
	{
		if(!empty($config)){
			return self::_ops('group', $item, $config);
		}
		
		return $item->title;
	}
}