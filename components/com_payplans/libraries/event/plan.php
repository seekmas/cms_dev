<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Order
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansEventPlan
{
	public static function onPayplansViewBeforeRender(XiView $view, $task)
	{
		 if(($view instanceof PayplanssiteViewInvoice) && $task == 'thanks'){
		 	$invoice_key	= JRequest::getVar('invoice_key');
		 	$invoiceId 		= XiHelperUtils::getIdFromKey($invoice_key);
		 	$invoice 		= PayplansInvoice::getInstance($invoiceId);
		 	$user			= $invoice->getBuyer(PAYPLANS_INSTANCE_REQUIRE);
			$order   	    = $invoice->getReferenceObject(PAYPLANS_INSTANCE_REQUIRE);
			
			if( !is_a($order, 'PayplansOrder') ){
				return true;
			}
			
			$subscription = $order->getSubscription();
			if(empty($subscription)){
				return true;
			}
			
		 	// load authentication plugins before trigerring them
		 	XiHelperPlugin::loadPlugins('authentication');
		 	if(($subscription->getStatus() == PayplansStatus::SUBSCRIPTION_ACTIVE) && class_exists('PayplansUserAutologinHelper')){
		 		PayplansUserAutologinHelper::autoLogin($user);
		 	}
			
			//Don't use array_pop($invoice->getPlans(PAYPLANS_INSTANCE_REQUIRE));
			// It generates warning with develpoment mode
		 	$plans 			= $invoice->getPlans(PAYPLANS_INSTANCE_REQUIRE);
		 	$plan 			= array_pop($plans);
		 	$redirecturl 	= $plan->getRedirecturl();
		 	$view->assign('redirecturl', $redirecturl);
		 }
	}
}