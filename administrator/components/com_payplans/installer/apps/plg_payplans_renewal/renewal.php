<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		Payplans
* @subpackage	renewal
* @contact		payplans@readybytes.in
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Payplans Renewal Plugin
 *
 * @author Puneet
 */
class plgPayplansRenewal extends XiPlugin
{
	public function onPayplansSystemStart()
	{
		$dir = dirname(__FILE__).DS.'renewal'.DS.'app';
		PayplansHelperApp::addAppsPath($dir);
		
		return true;
	}
		
	public function onPayplansOrderRenewalRequest()
	{
		// Step 1 :- select the subscription
		$subKey  		= JRequest::getVar('subscription_key', false);
		$subId   			= XiFactory::getEncryptor()->decrypt($subKey);
		$subscription  = PayplansSubscription::getInstance($subId);
		
		// Step 2 :- get price and type of old plan
		$oldPrice 		= $subscription->getPrice();
		$oldPlanType	= $subscription->getExpirationType();
		
		// Step 3 :- select the plan same as the plan attached with the subscription
		$newPlan 		= array_pop($subscription->getPlans(PAYPLANS_INSTANCE_REQUIRE));
		
		// check whether plan is valid or not
		if(!PayplansHelperPlan::isValidPlan($newPlan->getId())){
			XiFactory::getApplication()->enqueueMessage(XiText::_('COM_PAYPLANS_RENEWAL_PLAN_NOT_AVAILABLE_TO_RENEW'));
			XiFactory::getApplication()->redirect(XiRoute::_('index.php?option=com_payplans&view=subscription&task=display&subscription_key='.$subKey));
		}
		
		$newPrice 		= $newPlan->getPrice();
		$newDetails 	= $newPlan->getDetails();
		$newPlanType 	= $newPlan->getExpirationType();
		$newDetails->set('title', $newPlan->getTitle());
		
		// Step 4 :- get order from subscription
		$order 				= $subscription->getOrder(PAYPLANS_INSTANCE_REQUIRE);
		
		// Step 5 :- change plan details of subscription according to new plan
		$newSubscription = $this->_renewSubscription($subscription, $order, $newDetails);
		
		// Step 6 :- create new invoice from order
		$invoice 			= $order->createInvoice();

		// Step 7 :- set new price and expiration time at new invoice
		$invoice->set('subtotal', $newPrice);
		// set some params
		$subParams 	= $newSubscription->getParams()->toArray();
		$params = array('expirationtype', 'expiration', 'recurrence_count', 'price', 'title');
		foreach($params as $param){
			if(isset($subParams[$param])){
				$invoice->setParam($param, $subParams[$param]);
			}
		}
		
		$invoice->save();
		
		$expirationType = $invoice->getExpirationType();
		if(in_array($expirationType, array('recurring_trial_1', 'recurring_trial_2')))
		{
			$invoice->setParam('expirationtype', 'recurring')
						->save();
		}
		
		$invoice_key = $invoice->getKey();

		//trigger an event after invoice creation
		$args = array($subscription, $newSubscription, $order, $invoice);
		$results = PayplansHelperEvent::trigger('onPayplansSubscriptionAfterRenewalInvoiceCreation', $args, '', $newSubscription);
		
		// Step 7 :- Check whether plan price is changed or not?
		if(($oldPrice != $newPrice) || ($oldPlanType != $newPlanType)){
			XiFactory::getApplication()->enqueueMessage(XiText::_('COM_PAYPLANS_RENEWAL_CHANGE_PLAN_PRICE'));
		}
		
		//Step 8 : - redirect to invoice confirm page
		XiFactory::getApplication()->redirect(XiRoute::_('index.php?option=com_payplans&view=invoice&task=confirm&invoice_key='.$invoice_key));
	}
	
	protected function _renewSubscription(PayplansSubscription $subscription, PayplansOrder $order, $planDetails)
	{
		$subscription->set('params', $planDetails)
							->save();
							
		$order->refresh()->save();
		return $subscription;
	}
}
