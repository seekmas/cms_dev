<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Order
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansEventOrder
{
	public static function onPayplansWalletUpdate($transaction, $amount)
	{
		// the transaction is not of any order's invoice then return 
		$invoice 	 = $transaction->getInvoice(PAYPLANS_INSTANCE_REQUIRE);

		//when the wallet amount is consumed then blank invoice object will be created
		// so no need to proceed further.
		if(!$invoice || !$invoice->getId()){
		   return true;
		}

		if(!(XiFactory::getUser($invoice->getBuyer())->id)){
		   return true;
		}
		//XITODO : remove the below line of code, fix caching issue
		$invoice->refresh();
		$order   	 = $invoice->getReferenceObject(PAYPLANS_INSTANCE_REQUIRE);

		if(!is_a($order, 'PayplansOrder')){
			return true;
		}
		
		//when refund is made externally then mark invoice as refunded
		if(floatval($amount) < floatval(0)){
			return self::_processRefund($transaction, $order);
		}
		
		//first check for whether its required to create new invoice or not
		if(self::_isRequiredInvoice($invoice, $order) === false){
			return true;
		}
		
		// when transaction with greater than zero 
		// create invoice if order is not expired
		return self::_processNewTransaction($invoice, $order, $transaction, $amount);
	}
	
	protected static function _processNewTransaction($invoice, $order, $transaction, $amount)
	{
		// when order is expired or hold then do nothing and return
		if(in_array($order->getStatus(), array(PayplansStatus::ORDER_EXPIRED, PayplansStatus::ORDER_HOLD, PayplansStatus::ORDER_CANCEL))){
			// XITODO : Add alert messae for admin
			return true;
		}
		
		$subscription = $order->getSubscription();

		// IMP : if order does not have any subscription, then do nothing
		if(!($subscription instanceof PayplansSubscription)){
			return false;
		}		

//XiTODO:Remove it, Was modified to synchronize wallet and consume amount instantly 
		
		//XiTODO:: don't do it if Migration is running
		// when subscription is already active and its expiration date is of future, means payment has been received
		// other wise process the invoice 
		// another/next recurring cycle so do not debit the amount immediately
		// rather maintain the balance into wallet and debit before subscription expiration
		//$dateExp	= $subscription->getExpirationDate()->getClone();
		//$now = new XiDate();
		//if( ($subscription->isRecurring()) && ($subscription->getStatus() == PayplansStatus::SUBSCRIPTION_ACTIVE) && $dateExp->toUnix() > $now->toUnix()){
		//	return true;
		//}

		
		$result = self::_processInvoice($order, $invoice);
		
		if(!($result instanceof PayplansInvoice)){
			return false;
		}				
		
		$newInvoice = $result->getClone();
		
		return self::_deductFromWalletAndRenew($order, $newInvoice);
	}

	/*
	 * Check the order and subscription status to work on
	 * process only when subscription is  going from active to expire 
	 * invoice is processed when all the checks are passed and after that
	 * transaction of 0 amount is created and amount is debited from wallet 
	 */
	protected function _renewUsingWallet($order, $subscription)
	{
		// 	when order is expired or hold then do nothing and return
		if(in_array($order->getStatus(), array(PayplansStatus::ORDER_EXPIRED, PayplansStatus::ORDER_HOLD, PayplansStatus::ORDER_CANCEL))){
			return false;
		}
		
		$invoice = $order->getLastMasterInvoice(PAYPLANS_INSTANCE_REQUIRE);
		
		$result = self::_processInvoice($order, $invoice);
		
		//if result is instanceOf Payplansinvoice only then process further
		//else it indicates that some check has been failed so no need to process further 
		if(!($result instanceof PayplansInvoice)){
			return false;
		}				
		
		$newInvoice = $result->getClone();
		
		//set the transaction parameters
		$params     = new stdClass();
		$params->transaction_amount     = 0;
		$params->transaction_invoice_id = $newInvoice->getId();
		$params->transaction_params     = array('amount'=>$newInvoice->getTotal());
		$params->transaction_message    = 'COM_PAYPLANS_WALLET_DEBIT_RECORD_CREATED_FOR_INVOICE_PAID_BY_WALLET';
		
		$transaction = $invoice->addTransaction($params);		
		
		return self::_deductFromWalletAndRenew($order, $newInvoice);
	}
	
	/*
	 * create new invoice if previous invoice is on paid or refunded status
	 * check the wallet amount with the desired amount
	 * mark invoice as paid if amount is available
	 */
	protected static function _processInvoice($order, $invoice)
	{	
		// if invoice status is paid/refunded then we need to get total of next invoice
		// otherwise use total of current invoice
 		$total = $invoice->getTotal();
		if(in_array($invoice->getStatus(), array(PayplansStatus::INVOICE_PAID, PayplansStatus::INVOICE_REFUNDED ))){
			$invoiceCount	 = $order->getRecurringInvoiceCount();
			$total = $invoice->getTotal($invoiceCount+1);
		}
		
		$user = $invoice->getBuyer(PAYPLANS_INSTANCE_REQUIRE);
		$walletBalance = $user->getWalletBalance();
		
		// if wallet does not have enough amount, then return false and request for new payment
		if(floatval($walletBalance) < floatval($total)){
			return false;
		}
				
		// 	if invoice is already paid, create new invoice 
		if(in_array($invoice->getStatus(), array(PayplansStatus::INVOICE_PAID, PayplansStatus::INVOICE_REFUNDED ))){	
			//check whether new invoice creation is allowed or not
			
			$newInvoice = $order->createInvoice();
			if(!($newInvoice instanceof PayplansInvoice))
			{
				return false;
			}
		}
		else{
			$newInvoice = $invoice->getClone();
		}

		
		$newInvoice->set('status', PayplansStatus::INVOICE_PAID)->save();
		return $newInvoice;
	}
	
	/*
	 * deduct amount from wallet 
	 * and renew the subscription
	 */
	protected static function _deductFromWalletAndRenew($order, $newInvoice)
	{
		$wallet = PayplansWallet::getInstance();
		$wallet->set('user_id', $newInvoice->getBuyer())
				->set('transaction_id', 0)    // this is internal transaction so set it to 0
				->set('amount', - $newInvoice->getTotal())  // this should be negative
				->set('message', 'COM_PAYPLANS_WALLET_MESSAGE_PAID_FOR_INVOICE')
				->set('invoice_id', $newInvoice->getId())
				->save();
		
		$expiration   =  $newInvoice->getCurrentExpiration(true);
		
		$order->renewSubscription($expiration)
				->set('status', PayplansStatus::ORDER_COMPLETE)
				->save();
				
		return true;
	}
	
	protected static function _isRequiredInvoice($invoice, $order)
	{
		//do nothing if first 
		if(!in_array($invoice->getStatus(), array(PayplansStatus::INVOICE_PAID, PayplansStatus::INVOICE_REFUNDED ))){
			return true;
		}

		self::_checkForInvoiceCounter($order,$invoice);
		
		$recurrenceCount = $invoice->getRecurrenceCount();
		$recurring  	 = $invoice->isRecurring();
		$invoiceCount	 = $order->getRecurringInvoiceCount();
		
		if($recurring === false){
			return false;
		}

		// for 0 reccurrence count, always create new invoice
		if($recurrenceCount == 0){
			return true;
		}
		
		if ($recurring == PAYPLANS_RECURRING && $invoiceCount >= $recurrenceCount){
			return false;
		}
		
		if ($recurring == PAYPLANS_RECURRING_TRIAL_1 && $invoiceCount >= ($recurrenceCount + 1)){
			return false;
		}
		
		if($recurring == PAYPLANS_RECURRING_TRIAL_2 && $invoiceCount >= ($recurrenceCount + 2)){
			return false;
		}
		
		return true;

	}

	public static function onPayplansInvoiceAfterSave($prev, $new)
	{
		//mark order confirm when invoice is marked as confirm
		if(($new->getStatus() == PayplansStatus::INVOICE_CONFIRMED) && ( $prev == null  || ($prev->getStatus()!= $new->getStatus()))){
			
			$order   = $new->getReferenceObject(PAYPLANS_INSTANCE_REQUIRE);

			if(!is_a($order, 'PayplansOrder')){
				return true;
			}
			
			if($order->getStatus() != PayplansStatus::ORDER_COMPLETE){
				return $order->set('status', PayplansStatus::ORDER_CONFIRMED)
				  		 	 ->save();
			}
		}
		
		$order   = $new->getReferenceObject(PAYPLANS_INSTANCE_REQUIRE);
		if(!is_a($order, 'PayplansOrder')){
			return true;
		}
		
		//when invoice is refunded then mark related order and subscription as hold
		if(($prev!= null) && ($prev->getStatus()==PayplansStatus::INVOICE_PAID) && ($new->getStatus()==PayplansStatus::INVOICE_REFUNDED) ){			
			return self::_processOrderForRefund($order, $new);
		}
		
		//when creating invoice for the first time
		// if it is not for first time then do nothing
		if($prev != null){
			return true;
		}
		
		// get the first invoice
		$invoice = $order->getInvoice(1);
		
		if(!$invoice){
			return true;
		}
		
		// if $invoice and $new are same then do nothing
		if($new->getId() == $invoice->getId()){
			return true;
		}
		
		// get all modifires
		$modifiers = $invoice->getModifiers();
		
		// if any modifier is for each time then apply it
		PayplansHelperModifier::applyConditionally($new, $invoice, $modifiers);
		
		$new->refresh()->save();
	}
	
	protected static function _processRefund($transaction, $order)
	{
		$invoiceToRefund = false;
			
		if($transaction instanceof PayplansTransaction){
			$txnAmount     = abs($transaction->getAmount());
			
			//when invoice amount is same as that of txn then mark the invoice refunded
			//otherwise mark the last invoice of the order as refunded 
			
			$invoices = array_reverse($order->getInvoices(PayplansStatus::INVOICE_PAID));
			
			if(empty($invoices)){
				return true;
			}
			
			foreach ($invoices as $invoice){
				$invoiceAmount = $invoice->getTotal();
				if(floatval($invoiceAmount) == floatval($txnAmount)){
					$invoiceToRefund = true;
					break;
				}
			}
			
			if($invoiceToRefund === false){
				$invoice = array_shift($invoices);
			}
			
			$invoice->set('status', PayplansStatus::INVOICE_REFUNDED)
					->save();

			$txn_message = $transaction->getMessage();

			if(empty($txn_message)){
				$transaction->set('message', XiText::_('COM_PAYPLANS_TRANSACTION_TRANSACTION_MADE_FOR_REFUND'));
			}
			//From version 2.1 current invoice id is difficult to maintain in every case
			$transaction->save();
		}
		
		return true;
	}
	
	protected function _processOrderForRefund($order, $invoice)
	{
		$order->refund();
			
		//create the wallet entry corresponding to the refund
		$wallet = PayplansWallet::getInstance();
		$wallet->set('user_id', $invoice->getBuyer())
				->set('transaction_id', 0)    // this is internal transaction so set it to 0
				->set('amount',  $invoice->getTotal())
				->set('message', 'COM_PAYPLANS_WALLET_MESSAGE_REFUNDED_FOR_INVOICE')
				->set('invoice_id', $invoice->getId())
				->save();
	}
	
	public static function onPayplansPaymentAfterSave($prev, $new)
	{
		// when creating invoice for the first time
		// if it is not for first time then do nothing
		if($prev != null){
			return true;
		}	
		
		$invoice 	 = $new->getInvoice(PAYPLANS_INSTANCE_REQUIRE);
		$order   	 = $invoice->getReferenceObject(PAYPLANS_INSTANCE_REQUIRE);

		if(!is_a($order, 'PayplansOrder')){
			return true;
		}
		
		return $order->setParam('last_master_invoice_id', $invoice->getId())
			  		 ->save();
	}

	public static function onPayplansNewPaymentRequest($subscription)
	{
		//set the inprocess variable on new subscription instance so that 
		//in between subscription is saved it does not create a loop
		if(!isset($subscription->inprocess)){
			$order = $subscription->getOrder(PAYPLANS_INSTANCE_REQUIRE);
			$invoice = $order->getLastMasterInvoice(PAYPLANS_INSTANCE_REQUIRE);
			$totalPaidInvoices = count(($order->getInvoices(PayplansStatus::INVOICE_PAID)));
			
			// do nothing when invoice is not recurring
			if(!($invoice instanceof PayplansInvoice)|| !($invoice->isRecurring())){
				return true;
			}
			
			//first check for whether its required to create new invoice or not.
			$invoice = $order->getLastMasterInvoice(PAYPLANS_INSTANCE_REQUIRE);
			if(self::_isRequiredInvoice($invoice, $order) === false){
				return true;
			}
			
			$subscription->inprocess = true;
			
			//when order is cancel or expired then do not request for  payment
			if(!in_array($order->getStatus(), array(PayplansStatus::ORDER_CANCEL, PayplansStatus::ORDER_EXPIRED))
			&& !$invoice->requestPayment($totalPaidInvoices)){
					self::_renewUsingWallet($order, $subscription);
			}

			//unset the variable after processing is done
			unset($subscription->inprocess);
		}
	}
	

	public static function onPayplansInvoiceBeforeSave($prev, $new)
	{
		if(!(isset($prev) && $prev->getStatus() == PayplansStatus::NONE && $new->getStatus() == PayplansStatus::INVOICE_CONFIRMED ) ){
			return true;
		}
		
		//create error log if plan is recurring and regular amount is zero after applying 100% discount
		if( $new->isRecurring() && floatval(0) == floatval($new->getRegularAmount() )){
			$message             = XiText::_('COM_PAYPLANS_LOG_100_PERCENT_DISCOUNT_ON_EACH_RECURRING');
			$error['Invoice ID'] = $new->getId();
			$error['Error']      = XiText::_('COM_PAYPLANS_LOG_ERROR_ON_100_PERCENT_EACH_RECURRING_DISCOUNT');
			PayplansHelperLogger::log(XiLogger::LEVEL_INFO, $message, 'PayplansInvoice',$error);
		}
	}
	
	protected function _checkForInvoiceCounter($order,$invoice)
	{
		//if total invoices(paid+refunded) are not equal to the last invoice counter
		//then create log and return
		$invoices      = $order->getInvoices(array(PayplansStatus::INVOICE_PAID, PayplansStatus::INVOICE_REFUNDED));
		$totalInvoices = count($invoices);
		sort($invoices);
		$lastInvoice   = array_pop($invoices);
		$invoiceCount  = $lastInvoice->getCounter();

		if($invoiceCount < $totalInvoices){
			$subscription = $order->getSubscription();
			$message = XiText::_("COM_PAYPLANS_INVOICE_LOG_COUNTER_MISMATCH");
			$error['Subscription Id'] = $subscription->getId();
			$error['Actual Counter'] = $totalInvoices+1;
			$error['Expected Counter'] = $invoiceCount+1;
			PayplansHelperLogger::log(XiLogger::LEVEL_ERROR, $message,'SYSTEM',$error);
		}
		//always create invoice
		return true;
	}
}
