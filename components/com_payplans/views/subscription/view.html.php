<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplanssiteViewSubscription extends XiView
{
	public function display($userId=null)
	{
		$userId = ($userId === null) ? XiFactory::getUser($userId)->id : $userId;

		// show all subscriptions
		if(intval($id = $this->getModel()->getId()) <= 0) {
			$subscriptionRecords = $this->getModel()->loadRecords(array('user_id' => $userId));
			$this->assign('subscription_records', $subscriptionRecords);
			return true;
		}
		
		//get subscription
		$subscription = PayplansSubscription::getInstance($id);
		
		// show specific order
		$order = $subscription->getOrder(PAYPLANS_INSTANCE_REQUIRE);
		
		if($order->getBuyer() != $userId && XiHelperJoomla::isAdmin($userId) == false) {
			$message = XiText::_('COM_PAYPLANS_SUBSCRIPTION_CAN_NOT_VIEW_SUBSCRIPTION_OF_OTHERS_USER');
			$url = XiRoute::_('index.php?option=com_payplans&view=subscription');
			XiFactory::getApplication()->redirect($url, $message, 'message');
		}
		$noneInvoices = $order->getInvoices(PayplansStatus::NONE);
		$confirmedInvoices = $order->getInvoices(PayplansStatus::INVOICE_CONFIRMED);
		$pendingInvoices = array_merge($noneInvoices, $confirmedInvoices);
		
		$paidInvoices = $order->getInvoices(PayplansStatus::INVOICE_PAID);
		$refundInvoices = $order->getInvoices(PayplansStatus::INVOICE_REFUNDED);
		$completeInvoices = array_merge($paidInvoices, $refundInvoices);
		
		// display pending and completed invoices separetly, 
		// else look of dashborad will not be uniform 
		$allInvoices = array_merge($completeInvoices, $pendingInvoices);
		
		// get all transactions
		$allTransactions = array();
		foreach ($allInvoices as $invoice){
			$transactions = $invoice->getTransactions();
			if(!(isset($transactions)) && empty($transactions)){
				continue;
			}
			foreach ($transactions as $transaction){
				$allTransactions[] = $transaction;
			}
		}
		
		$this->assign('subscription', $subscription);
		$this->assign('all_invoices', $allInvoices);
		$this->assign('user', $order->getBuyer(PAYPLANS_INSTANCE_REQUIRE));
		$this->assign('transactions', $allTransactions);
		$this->assign('order', $order);
		$this->setTpl('view');
		
		// show or hide the recurring order cancellation button
		if($order->isRecurring()){
			$invoice = $order->getLastMasterInvoice(PAYPLANS_INSTANCE_REQUIRE);
			if(!$invoice){
				return true;
			}
			$payment = $invoice->getPayment();
			if(!$payment){
				return true;
			}
			$app = $payment->getApp(PAYPLANS_INSTANCE_REQUIRE);

			$subscription_status = $subscription->getStatus();
			$order_status 		 = $order->getStatus();
			
			if($order_status != PayplansStatus::ORDER_CONFIRMED && $order_status != PayplansStatus::ORDER_CANCEL && $order_status != PayplansStatus::ORDER_EXPIRED &&  $subscription_status != PayplansStatus::SUBSCRIPTION_EXPIRED){
			$this->assign('show_cancel_option', $app->getAppParam('allow_recurring_cancel', false));
			}
			$position = 'pp-subscription-display-action';
			$html 		= $this->loadTemplate('order_cancel');
			$this->assign('plugin_result', array($position=>$html));
		}
		
		return true;
	}
	
}	
