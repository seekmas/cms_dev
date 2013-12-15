<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


interface PayplansIfaceAppPayment
{
	function onPayplansPaymentForm(PayplansPayment $payment, $data=null);
	
	function onPayplansPaymentFormAdmin(PayplansPayment $payment, $data=null);
	
	function onPayplansPaymentRecord(PayplansPayment $payment =null);
}
