<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHelperTransaction
{
	//this function is used for request the refund from backend transaction screen
	public static function refundRequest(PayplansTransaction $transaction, $refundAmouont)
	{
		$payment 		= $transaction->getPayment(PAYPLANS_INSTANCE_REQUIRE);
		$app 			= $payment->getApp(PAYPLANS_INSTANCE_REQUIRE);
		return $app->refundRequest($transaction,$refundAmouont);
	}
}