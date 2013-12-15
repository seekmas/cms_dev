<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();

class PayplansadminControllerSubscription extends XiController
{
	protected	$_defaultOrderingDirection = 'DESC';

	/**
	 * Saves an item (new or old)
	 */
	public function _save(array $data, $itemId=null, $type=null)
	{
		$data['params'] = PayplansHelperParam::collectParams($data,'params');
		
		$subscription = PayplansSubscription::getInstance($itemId)
							->bind($data)
							->save();
		
		$order = $subscription->getOrder(true);
		
		if(!$itemId){
			//set plan as per posted data
			$subscription->setPlan($data['plan_id']);
			// new subscription, should create new order
			$order = PayplansOrder::getInstance();
			$order->setBuyer($subscription->getBuyer());
			$order->save();
			
			// attach order to subscription (saves automatically)
			$subscription->setOrder($order);
		}
		 
		// and update Subscription
		// load order and Refresh order
		$order->refresh()->save();

		return $subscription;
	}

	/*
	 * Attach plan with subscription
	 *
	 * If createNewOrder is true
	 *    1.a) user_id must be in post data
	 *    1.b) set this user id to user_id of subscription
	 * else
	 * 	  2.a) get order id  and load order
	 * 	  2.b) set buyer_id of order to user_id or subscription
	 *
	 * 3) create a plan for subscription
	 */

	public function edit($itemId = null)
	{
		$itemId = ($itemId === null) ? $this->getModel()->getId() : $itemId;
		$subscription = PayplansSubscription::getInstance( $itemId);
		$order 		  = PayplansOrder::getInstance();

		if(!$itemId){
			$planId = JRequest::getVar('plan_id',0);
			$subscription->setPlan(PayplansPlan::getInstance( $planId));

			$orderId = JRequest::getVar('order_id',0);
			$order = PayplansOrder::getInstance( $orderId);
			$plan = PayplansPlan::getInstance($planId);
			
			// if its a first subscription to the order
			// then change the currency of order 
			if($plan->getCurrency('isocode') != $order->getCurrency('isocode')) {
				$plans = $order->getPlans(PAYPLANS_INSTANCE_REQUIRE);
				if(!count($plans)){
					$order->set('currency', $plan->getCurrency('isocode'))->save();
				}
				else{
					$message = XiText::_('COM_PAYPLANS_ORDER_GRID_CURRENCY_MISMATCH_IN_ORDER_AND_PLAN');
					$url     = Xiroute::_('index.php?option=com_payplans&view=order');
					XiFactory::getApplication()->redirect($url, $message, 'warning');
				}
			}
			
			$subscription->set('order_id', $orderId);
			$subscription->set('user_id', $order->getBuyer());
		}

		$this->getView()->assign('subscription', $subscription);

		//set editing template
		$this->setTemplate('edit');
		return true;
	}
	
	public function extend($time = false, $subIds = array())
	{
		$time = JRequest::getVar('extend_time', $time);
		
		// if extend time is not set then show time
		if($time == false){
			$this->setTemplate('extend');
			return true;
		}
		
		$subIds = JRequest::getVar('cid', $subIds, 'request', 'array');
		 
		foreach($subIds as $id){
			$sub = PayplansSubscription::getInstance($id);
			// if subscription is expired 
			// then add expiration time from now
			// and activate the subscription
			if($sub->getStatus() == PayplansStatus::SUBSCRIPTION_EXPIRED){
				$sub->set('expiration_date', new XiDate());
				$sub->set('status', PayplansStatus::SUBSCRIPTION_ACTIVE);
			}
			
			$sub->set('expiration_date', $sub->getExpirationDate()->addExpiration($time));
			
			$sub->save();
		}
		
		$url = 'index.php?option=com_payplans&view=subscription';
		$this->setRedirect($url);
		
		return false;
	}
	
	function _remove($itemId=null, $userId=null)
	{
      if($itemId === null || $itemId === 0){
		$model = $this->getModel();
	    $itemId = $model->getId();
      }
	  $subscription = PayplansSubscription::getInstance($itemId);
	  PayplansOrder::getInstance($subscription->getOrder())->delete();

	  return true;
	}
}
