<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHtmlUsersubscription
{
	static function edit($name, $userid, $value, $attr = null)
	{
		if(!$userid){
			return '';
		}
		
		if(!is_array($value)){
			$value = explode(',', $value);
		}
		
		$subscriptions = XiFactory::getInstance('subscription', 'model')
									->loadRecords(array('user_id' => $userid));

		// unset those subscription id which have been deleted 
		foreach ($value as $key => $val){
			if(!isset($subscriptions[$val])){
				unset($value[$key]);
			}
		}

		return PayplansHtml::_('autocomplete.edit', $subscriptions, $name, array('multiple'=>true), 'subscription_id', 'subscription_id', $value);
	}
}