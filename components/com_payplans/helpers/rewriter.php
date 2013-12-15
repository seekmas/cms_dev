<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHelperRewriter
{
	public static function getRelativeObjects($refObject)
	{
		XiFactory::cleanStaticCache(true);
		$obj = array();

		if(!$refObject || !isset($refObject)){
			return array();
		}
		
		if($refObject instanceof PayplansOrder){
			$order = $refObject;
		}
		elseif($refObject instanceof PayplansPayment){
			$invoice = $refObject->getInvoice(PAYPLANS_INSTANCE_REQUIRE); 
			if($invoice->getObjectType() != 'PayplansOrder'){
				return array();
			}
			$order = $invoice->getReferenceObject(PAYPLANS_INSTANCE_REQUIRE);
		}
		elseif($refObject instanceof PayplansInvoice)
		{
			// get tokens in case of "Wallet-Recharge" & "Payplans-Donation"
			if($refObject->getObjectType() == 'PayplansWallet' || $refObject->getObjectType() == 'PayplansDonation'){
				$obj[] = $refObject->getPayment();
				$transaction = array_pop($refObject->getTransactions());  
				$obj[] = ($transaction instanceof PayplansTransaction) ? $transaction : PayplansTransaction::getInstance(0);
				$obj[] = $refObject->getBuyer(PAYPLANS_INSTANCE_REQUIRE);
				
				return $obj;
			}
			
			if($refObject->getObjectType() != 'PayplansOrder'){
				return array();
			}
			$order = $refObject->getReferenceObject(PAYPLANS_INSTANCE_REQUIRE);
		}	
		else{
			$order = $refObject->getOrder(PAYPLANS_INSTANCE_REQUIRE);
		}
		$obj[] = array_pop($order->getPlans(PAYPLANS_INSTANCE_REQUIRE));
		$obj[] = $order->getBuyer(PAYPLANS_INSTANCE_REQUIRE);
		$obj[] = $order->getSubscription();

		$latestInvoice = array_pop($order->getInvoices());

		//if invoice doesn't exist then do nothing
		if(empty($latestInvoice)){
			return $obj;
		}

		$latestInvoice->load($latestInvoice->getId());
		$obj[] = $latestInvoice;
		
		$payment = $latestInvoice->getPayment();
		if($payment instanceof PayplansPayment){
			$obj[] = $payment;
		}else{
			$obj[] = PayplansPayment::getInstance(0);
		}

        $transactions = $latestInvoice->getTransactions();
        
        if(!empty($transactions) && (array_pop($transactions) instanceof PayplansTransaction )){
        	$transactions = $latestInvoice->getTransactions();
			$transaction  = array_pop($transactions);
        	$obj[] 		  = $transaction;
        }
        else {
			$obj[]        = PayplansTransaction::getInstance(0);
		}
		 
		$obj[] = $order;
		XiFactory::cleanStaticCache(false);
		return $obj;
	} 
}