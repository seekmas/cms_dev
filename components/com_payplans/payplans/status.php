<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansStatus
{
	const NONE = 0;

	// Subscriptions
	const SUBSCRIPTION_ACTIVE	= 1601;
	const SUBSCRIPTION_HOLD		= 1602;
	const SUBSCRIPTION_EXPIRED	= 1603;
	
	// Orders
	const ORDER_CONFIRMED		= 301;
	const ORDER_PAID 			= 302;    // Un-used later 1.4 
	const ORDER_COMPLETE 		= 303;
	const ORDER_HOLD 			= 304;
	const ORDER_EXPIRED			= 305;
	const ORDER_CANCEL			= 306;

	// Invoice
	const INVOICE_CONFIRMED		   = 401;
	const INVOICE_PAID 			   = 402;
	const INVOICE_REFUNDED		   = 403;
	const INVOICE_WALLET_RECHARGE  = 404;
	
	static public function getName($value)
	{
		if("" == JString::trim($value)){
			return '';
		}
		static $constants = null;
		
		//clean cache if required
		if(XiFactory::cleanStaticCache()){
			$constants = null;
		}
		
		//load constants
		if($constants===null){
			XiError::assert(is_numeric($value), XiText::_('COM_PAYPLANS_VALUE_MUST_BE_NUMERIC'));
			$class 		= new ReflectionClass(__CLASS__);
			$tmp	 	= $class->getConstants();
			//flip the array to ease searching
			$constants	= array_flip($tmp);
		}
		
		//default is NONE
		XiError::assert(isset($constants[$value]), XiText::_('COM_PAYPLANS_ILLEGAL_STATUS_CODE'));		
		return $constants[$value];
	}

	static public function getStatusOf($entity='')
	{
		static $allStatus = null;

		//Cache the results
		if($allStatus === null) {
			$class = new ReflectionClass(__CLASS__);
			$allStatus = $class->getConstants();
		}

		// if we need all status then return it, without none
		if(empty($entity)){
			$result = array_flip($allStatus);
			unset($result[self::NONE]);
			return $result;
		}

		// no need of all
		$entity = JString::strtoupper($entity);

		$status = array(self::NONE => 'NONE');
		foreach($allStatus as $key => $val){
			if(preg_match("/^{$entity}_/i", $key))
				$status[$val] = $key;
		}

		return $status;
	}
}
