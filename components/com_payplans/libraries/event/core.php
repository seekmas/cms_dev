<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Loggers
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansEventCore
{
	/* trigger of On After Save */

	/**
	 * this event is triggered After saving order
	 */
	static function onPayplansOrderAfterSave($previous, $current)
	{
		// Consider Previous State also
		if(isset($previous) && $previous->getStatus() == $current->getstatus())
			return true;
			
		// if there is change in status of order
		switch($current->getStatus()){
			case PayplansStatus::NONE 			:
								$subsStatus = PayplansStatus::NONE;
								break;

			case PayplansStatus::ORDER_CONFIRMED	:
								$subsStatus = PayplansStatus::NONE;
								break;

			case PayplansStatus::ORDER_COMPLETE	:
								$subsStatus = PayplansStatus::SUBSCRIPTION_ACTIVE;
								break;

			case PayplansStatus::ORDER_HOLD		:
								$subsStatus = PayplansStatus::SUBSCRIPTION_HOLD;
								break;
								
			case PayplansStatus::ORDER_EXPIRED		:
								$subsStatus = PayplansStatus::SUBSCRIPTION_EXPIRED;
								break;

			case PayplansStatus::ORDER_PAID		:
			default 						:
								$subsStatus = PayplansStatus::NONE;
		}

		$subs = $current->getSubscription();
		if(is_a($subs, 'PayplansSubscription')){
			$subs->load($subs->getId());

			// no change in status then need not to update
			if($subs->getStatus() == $subsStatus || !$subsStatus){
				return true;
			}
				
			$subs->setStatus($subsStatus)->save();
		}
		return true;
	}
	
	public static function onPayplansOrderBeforeDelete($order)
	{	
		$subscription = $order->getSubscription();
		// delete all the subscriptions linked with this order
	    if(!empty($subscription)){
		  	$subscription->delete();
	    }
	    
	    $invoices      = $order->getInvoices();
		if(!empty($invoices)){
			foreach ($invoices as $invoice){
				$invoice->delete();
            }
        }
		return true;
	}

	
	static function onPayplansInvoiceBeforedelete($invoice)
	{
		
	  $payments = $invoice->getPayment();
	  
	  // delete all the payment linked with this order
	  if(!empty($payments)){  
	         $payments->delete();
	  }
	  
	  //get all the transaction records,get and delete wallet entry 
	  //related to tranaction and then delete transaction
	  $transactions = $invoice->getTransactions();
	  if(empty($transactions)){
			return true;
	  }

	  self::deleteTransaction($transactions);
			
	  //delete wallet entry related to the invoice
	  $walletDebits = $invoice->getWallet();
	  foreach ($walletDebits as $walletDebit)
	  {
         $walletDebit->delete();
	  }
             
       //delete all modifier related to invoice
       $modifiers = $invoice->getModifiers();
       self::deleteModifiers($modifiers);
       
		return true;
	}

	protected static function deleteTransaction($transactions = array())
	{
		foreach ($transactions as $transaction)
		{
			
			$wallet = $transaction->getWallet();
			
			if($wallet instanceof PayplansWallet)
				$wallet->delete();
			
			$transaction->delete();
		}
	}
	
	protected static function deleteModifiers($modifiers = array())
	{
	    foreach ($modifiers as $modifier)
        {
            $modifier->delete();
        }
	}
	
	
	// Mark subscription expire
	public static function onPayplansCron()
	{
		$message = XiText::_('COM_PAYPLANS_LOGGER_CRON_START');
		$content = array('Message'=>$message);
		PayplansHelperLogger::log(XiLogger::LEVEL_INFO, $message, null, $content, 'PayplansFormatter', 'Payplans_Cron');
		
		PayplansHelperCron::doSubscriptionExpiry();
		
		PayplansHelperCron::doAutoDeleteDummyOrders();
		
		// generate cache
		PayplansHelperCron::genereateFileTreeCache();
		return true;
	}
	
	// Add Module at header n footer of payplan screens.
    public static function onPayplansViewAfterRender(XiView $view, $task, $output)
	{
		$position = 'payplans-';

		if(XiFactory::getApplication()->isAdmin()==true){
			$position .= 'admin-';
		}
				
		$name = $view->getName();
		if(isset($name)){
			$position .=  $name. '-';
		}
		
		if(isset($task)){
			$position.= $task. '-';
		}
		
		$modulehtmlTop 		= implode(PayplansHelperTemplate::_renderModules($position."top"));
		$modulehtmlBottom 	= implode(PayplansHelperTemplate::_renderModules($position."bottom"));

		// update output variable
		$output = $modulehtmlTop . $output . $modulehtmlBottom;
		
		return true;
	}
	
	//before deleting subscription changed its status to expired
	//so as to trigger all the app which are set on status "Subscription-expired" 
	//and do what thay are expected to on subscription expired status before the subscription gets deleted
	public static function onPayplansSubscriptionBeforeDelete($object)
	{
		// set deleteing  to true so that it won't ask for payment on order deletion
		$object->deleting = true;

		// Expire only when it is already active
		if($object->getStatus() == PayplansStatus::SUBSCRIPTION_ACTIVE || $object->getStatus() == PayplansStatus::SUBSCRIPTION_HOLD){
			$object->setStatus(PayplansStatus::SUBSCRIPTION_EXPIRED)
					->save();
		}
		return true;
	}

}
