<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Loggers
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansEventAccess
{
	public static function onPayplansAccessCheck(PayplansUser $user)
	{
		if(!$user->getId()){
			return true;
		}
		
		$option = JRequest::getVar('option', false);
		$task 	= JRequest::getVar('task', false);
		$view 	= JRequest::getVar('view', false);
		$planId = JRequest::getVar('plan_id', 0);
		
		//in case of sending pre-expiry mails
		if(isset($planId)== true && $option == 'com_payplans' && $view == 'plan'){			
			return self::_allowToRenew($planId);
		}
		
		// Block if user don't have active subscription
		if(!XiFactory::getConfig()->accessLoginBlock){
			return true;
		}

		// hack for jomsocail facebook connect
		// do not restric ajax 
		if($option == 'community' && $task == 'azrul_ajax'){
			return true;
		}
		
		if($option == 'com_payplans'){
			return true;
		}
		
		//XITODO :  Its a temporary fix for allowing logout, implement some proper solution
		// Do not block login and logout attempt, we will capture on next page
		if(($option == PAYPLANS_COM_USER) || ($task == 'logout')){ // && ($task== false || $task== 'login' || $task== 'logout')){
			return true;
		}
			
		$subs  = $user->getSubscriptions(PayplansStatus::SUBSCRIPTION_ACTIVE);
			
		//block user if no active subscription
		if(count($subs) <= 0){
			//XITODO : Handle ajax also
			XiFactory::getApplication()->redirect(XiRoute::_('index.php?option=com_payplans&view=dashboard&task=noaccess'));
		}
		
		return true;
	}
	
	public static function onPayplansViewBeforeRender(XiView $view, $task)
	{
		// do nothing when application is administrator
		if(XiFactory::getApplication()->isAdmin()){
			return true;
		}
		
		$user                = XiFactory::getUser();
		$payplansUser		 = PayplansUser::getInstance($user->id);
		$userPlans           = $payplansUser->getPlans();
	    $displaySubscribedPlans = XiFactory::_getConfig()->displayExistingSubscribedPlans;
		    
		if($displaySubscribedPlans == 1 || $user->id == false){
		   		return ;
		    }
		
	    // don't display the plans on subscribe page that user have already subscribed
		if(($view instanceof PayplanssiteViewPlan) && $task == 'subscribe')
		{  
		    if(count($userPlans) == 0){
		    	return ;
		    }

		    $plans = $view->get('plans');
		    //unset all plans subscribed by the user
		    foreach($userPlans as $plan_id){
		    	unset($plans[$plan_id]);
		    }
		    
		    // assign plans to lib
			$view->assign('plans',$plans);
		    return true;
		}
		
		// don't redirect user to order confirm page that user have already subscribed
		if(($view instanceof PayplanssiteViewOrder) && $task == 'confirm')
		{
			$plan      = $view->get('plan');
			$orderId   = $view->getModel()->getId();			
			$order     = PayplansOrder::getInstance($orderId);
			
			// when display subscribed plan set to no and
			// user renew their plan by renewal link then
			//check the status of order if complete then order is for renewal 
			if($order->getStatus() == PayplansStatus::ORDER_COMPLETE){
				return ;
			}

			if(in_array($plan->getId(),$userPlans)){
				$message = XiText::_('COM_PAYPLANS_DASHBOARD_NOT_ALLOWED_TO_SUBSCRIBED_THIS_PLAN_AS_ALREADY_SUBSCRIBED');
		        XiFactory::getApplication()->redirect(XiRoute::_('index.php?option=com_payplans&view=dashboard'),$message);
			}
			return true;
		}
		
		// don't redirect user to payment page of the plan that user have already subscribed
		if(($view instanceof PayplanssiteViewPayment) && $task == 'pay')
		{
			$payment_id    = $view->getModel()->getId();
		    $payment       = XiLib::getInstance('payment',$payment_id);
		    $planOnPayment = array_shift($payment->getPlans());

		    // in case of renewal check whether order 
		    //already contains completed payments or not
			//in case of renewal order must be marked as completed
		    $invoice  = $payment->getInvoice(PAYPLANS_INSTANCE_REQUIRE);
			$order    = $invoice->getReferenceObject(PAYPLANS_INSTANCE_REQUIRE);
	
			if(!is_a($order, 'PayplansOrder')){
				return ;
			}
			
			if($order->getStatus() == PayplansStatus::ORDER_COMPLETE){
				return ;
			}

		    if(in_array($planOnPayment , $userPlans)){
				$message = XiText::_('COM_PAYPLANS_DASHBOARD_NOT_ALLOWED_TO_SUBSCRIBED_THIS_PLAN_AS_ALREADY_SUBSCRIBED');
		        XiFactory::getApplication()->redirect(XiRoute::_('index.php?option=com_payplans&view=dashboard'),$message);
		
			}
			return true;
		}
	}
	
	protected static function _allowToRenew($planId)
	{   
		// do not work in admin
		if(XiFactory::getApplication()->isAdmin()){
			return true;
		}
		
		$user                = XiFactory::getUser();
		$payplansUser		 = PayplansUser::getInstance($user->id);
		$userPlans           = $payplansUser->getPlans();
	    $displaySubscribedPlans = XiFactory::_getConfig()->displayExistingSubscribedPlans;
		    
		if($displaySubscribedPlans == 1 || $user->id == false){
		   		return true;
		}
		    
		if(in_array($planId,$userPlans)){
				$message = XiText::_('COM_PAYPLANS_DASHBOARD_NOT_ALLOWED_TO_SUBSCRIBED_THIS_PLAN_AS_ALREADY_SUBSCRIBED');
		        XiFactory::getApplication()->redirect(XiRoute::_('index.php?option=com_payplans&view=dashboard'),$message);
		}

		return true;
	}
}
