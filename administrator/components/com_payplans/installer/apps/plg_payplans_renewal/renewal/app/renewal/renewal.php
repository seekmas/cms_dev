<?php
/**
* @copyright			Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license				GNU/GPL, see LICENSE.php
* @package				Payplans
* @subpackage			Renewal
* @contact 				payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

/**
 * Renewal System
 * @author Puneet
 */
class PayplansAppRenewal extends PayplansApp
{
	//inherited properties
	protected $_location	= __FILE__;
	
	public function isApplicable($refObject = null, $eventName=''){
		// return true for event onPayplansViewBeforeRender
		if($refObject instanceof PayplanssiteViewSubscription && $eventName == 'onPayplansViewBeforeRender'){
			$subscription_key = JRequest::getVar('subscription_key', '');
			$subscription = PayplansSubscription::getInstance(XiHelperUtils::getIdFromKey($subscription_key));
			
			return parent::isApplicable($subscription, $eventName);
		}
		
		// trigger the event onPayplansInvoiceAfterSave
		// without referring any specified refObject
		if($eventName == 'onPayplansInvoiceAfterSave'){
			return true;
		}
		
		return false;
	}
	
	//render renew link
	public function onPayplansViewBeforeRender(XiView $view, $task)
	{
		if(($view instanceof PayplanssiteViewSubscription) && $task == 'display')
		{
			$subscription_key 		= JRequest::getVar('subscription_key');
			$subscription_id 		= XiFactory::getEncryptor()->decrypt($subscription_key);
			$subscription 			= PayplansSubscription::getInstance($subscription_id);
			if(isset($subscription) && (!empty($subscription))){
				// If order is expired then do not allow to renew
				$order = $subscription->getOrder(PAYPLANS_INSTANCE_REQUIRE);
				if(in_array($order->getStatus(), array(PayplansStatus::ORDER_EXPIRED))){
					return true;
				}
				$this->assign('subscription', $subscription);
			
				$position = 'pp-subscription-display-action';
				$html = $this->_render('widgethtml');
				return  array($position => $html);
			}
		}
	}
	
	function onPayplansInvoiceAfterSave($prev, $new)
	{
		// if there is change in status of order
		if($new->getStatus() != PayplansStatus::INVOICE_PAID){
			return true;
		}
		
		if(PayplansHelperInvoice::isRenewalInvoice($new)){
			$order = $new->getReferenceObject(PAYPLANS_INSTANCE_REQUIRE);
			if(($order instanceof PayplansOrder) == false){
				return true;
			}
			$subscription 	= $order->getSubscription();
			$args 			= array($subscription, $new);
			PayplansHelperEvent::trigger('onPayplansSubscriptionRenewalComplete', $args, '', $subscription);
		}
		
		return true;
	}
}
