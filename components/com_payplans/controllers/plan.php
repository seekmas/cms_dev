<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplanssiteControllerPlan extends XiController
{
	protected 	$_defaultTask = 'subscribe';

	/**
	 * User have selected one plan and want to subscribe it
	 * Lets process it
	 */
	public function subscribe($planId=null, $userId=null)
	{
		//data posted (might be via URL), find selected plan
		if(!$planId){
			$planId = $this->getModel()->getId();
		}

		if(!$planId)
			return true;

		//if not a valid plan, send to plan selection
		if(PayplansHelperPlan::isValidPlan($planId)===false){
			$this->setMessage(XiText::_('COM_PAYPLANS_PLAN_PLEASE_SELECT_A_VALID_PLAN'));
			return true;
		}

		$plan = PayplansPlan::getInstance( $planId);
		$args = array(&$planId, $this);
		// trigger event after plan selection
		PayplansHelperEvent::trigger('onPayplansPlanAfterSelection', $args, '', $plan);
	
		// if user id is not available then send to login page
		$userId = XiFactory::getUser($userId)->id;
		if(!$userId){
			$this->setRedirect(XiRoute::_('index.php?option=com_payplans&view=plan&task=login&plan_id='.$planId));
			return false;
		}

		$plan 	 = PayplansPlan::getInstance( $planId);
		$order 	 = $plan->subscribe($userId);
		$invoice = $order->createInvoice();

		// now redirect to confirm action
		$invoiceKey = $invoice->getKey();
		$this->setRedirect(XiRoute::_("index.php?option=com_payplans&view=invoice&task=confirm&invoice_key=".$invoiceKey, false));
		return false;
	}
	
	
	function login($planId = null, $userId = null)
	{
		// if plan id is null then return back to plan subscription page
		$planId =  ($planId != null) ? $planId : $this->getModel()->getId();
		if($planId === null){
			$this->setMessage(XiText::_('COM_PAYPLANS_PLAN_PLEASE_SELECT_A_VALID_PLAN'));
			$this->setRedirect(XiRoute::_("index.php?option=com_payplans&view=plan&task=subscribe", false));
			return false;
		}
		
		// if login button is clicked
		if(JRequest::getVar('payplansLoginSubmit', false, 'POST') != false){
			// check for user name and password
			// XITODO : use it in JAVASCRIPT validation
			$username = JRequest::getVar('payplansLoginUsername', false);
			$password = JRequest::getVar('payplansLoginPassword', false);
			$user 	  = PayplansUser::getInstance();

			if(!$user->login($username, $password)){
				$this->setRedirect(XiRoute::_("index.php?option=com_payplans&view=plan&task=login&plan_id={$planId}", false));
				return false;
			}
		}
		
		// if user id is available then send to subscribe page
		$userId = XiFactory::getUser($userId)->id;
		if($userId){
			$this->setRedirect(XiRoute::_("index.php?option=com_payplans&view=plan&task=subscribe&plan_id=".$planId, false));
			return false;
		}	
		
		// if use is not logged in, then show the login page
		$this->setTemplate('login');
		return true;
	}
	
	public function trigger($event=null,$args=null)
	{
		parent::trigger($event,$args);
	}

}