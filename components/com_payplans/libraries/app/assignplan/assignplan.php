<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class  PayplansAppAssignplan extends PayplansApp
{
	protected $_location	= __FILE__;
	
	public function isApplicable($refObject = PayplansSubscription, $eventName='')
	{
		if($eventName === 'onPayplansSubscriptionAfterSave'){
			return true;
		}
		
		return parent::isApplicable($refObject, $eventName);
	}
	
	public function onPayplansSubscriptionAfterSave($prev, $new)
	{
		// no need to trigger if previous and current state is same
		if($prev != null && $prev->getStatus() == $new->getStatus()){
			return true;
		}

		// If record is being deleted then don't do anything
		if(isset($new->deleting) && $new->deleting){
			return true;
		}		

		$newStatus  = $new->getStatus();
		$userid     = $new->getBuyer();
		$planId		= array_shift($new->getPlans());

		if($this->getParam('applyAll',false) == false){
			$appplans = $this->getPlans();
			if(!in_array($planId, $appplans)) return true;
		}
		
		$active = $this->getAppParam('assignPlan');
		$active	= (is_array($active)) ? $active : array($active);
		
		$hold 	= $this->getAppParam('setPlanOnHold');
		$hold	= (is_array($hold)) ? $hold : array($hold);
		
		$expire = $this->getAppParam('setPlanOnExpire');
		$expire	= (is_array($expire)) ? $expire : array($expire);

		//if subscription is active
		if($newStatus == PayplansStatus::SUBSCRIPTION_ACTIVE){
			return $this->_setPlan($userid, $active, $planId);
		}
		
		//if subscription is hold
		if($newStatus == PayplansStatus::SUBSCRIPTION_HOLD){
			return $this->_setPlan($userid, $hold, $planId);
		}
		
		// if subscription is expire
		if($newStatus == PayplansStatus::SUBSCRIPTION_EXPIRED){
			return $this->_setPlan($userid, $expire, $planId);
		}

		return true;
	}

	protected function _setPlan($userId, $assignPlan, $subscribedPlan)
	{
		if(!$userId){
			return true;
		}

		//check if there is any plan in assignplan to assign/set
		if(empty($assignPlan) && !(is_array($assignPlan))){
			return true;
		}
		
		foreach ($assignPlan as $planid){
			// if plan to be assinged is same as the plan 
			//on which this event triggered then do not assign 
			//plan as it will create infinite loop
			if(!empty($planid) && ($planid != $subscribedPlan)){
				$plan = PayplansPlan::getInstance($planid);
				$order = $plan->subscribe($userId)
			 				  ->save();
			 				  
				$invoice = $order->createInvoice();
	
				//apply 100% discount
				$modifier = PayplansModifier::getInstance();
				$modifier->set('message', XiText::_('COM_PAYPLANS_ASSIGN_PLAN_TO_USER_MESSAGE' ))
					 ->set('invoice_id', $invoice->getId())
					 ->set('user_id', $invoice->getBuyer())
					 ->set('type', 'assign_plan')
					 ->set('amount', -100) // 100 percent Discount, discount must be negative
					 ->set('percentage', true) 
					 ->set('frequency', PayplansModifier::FREQUENCY_ONE_TIME)
					 ->set('serial', PayplansModifier::FIXED_DISCOUNT)
					 ->save();
					  
				$invoice->refresh()->save();
				
				// create a transaction with 0 amount 
				$transaction = PayplansTransaction::getInstance();
				$transaction->set('user_id', $invoice->getBuyer())
							->set('invoice_id', $invoice->getId())
							->set('message', 'COM_PAYPLANS_TRANSACTION_CREATED_FOR_ASSIGN_PLAN_TO_USER')
							->save();
	
				//trigger the event
				$args = array($transaction, 0);
				PayplansHelperEvent::trigger('onPayplansWalletUpdate', $args);
			}
		}
		return true;
	}
}

class  PayplansAppAssignplanFormatter extends PayplansAppFormatter
{
	// get rules
	function getVarFormatter()
	{
		$rules = array('_appplans'       => array('formatter'=> 'PayplansAppFormatter',
										       'function' => 'getAppPlans'),
					   'app_params'      => array('formatter'=> 'PayplansAppAssignplanFormatter',
										       'function' => 'getFormattedContent'));
		return $rules;
	}
	
	function getFormattedContent($key,$value,$data)
	{
		$params                    = PayplansHelperParam::iniToArray($value);
		$params['assignPlan'] 	   = PayplansHelperPlan::getName($params['assignPlan']);
		$params['setPlanOnHold']   = PayplansHelperPlan::getName($params['setPlanOnHold']);
		$params['setPlanOnExpire'] = PayplansHelperPlan::getName($params['setPlanOnExpire']);
		$value                     = PayplansHelperParam::arrayToIni($params);
	}
}