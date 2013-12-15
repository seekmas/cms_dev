<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplanssiteControllerWallet extends XiController
{
	protected 	$_defaultTask = 'display';
	
	public function display()
	{
		$userId = XiFactory::getUser()->id;

		//if user is not logged in
		// currently sending to login page
		if(!$userId){
			$return	= JURI::getInstance()->toString();
			$url    = 'index.php?option='.PAYPLANS_COM_USER.'&view=login';
			$url   .= '&return='.base64_encode($return);
			$this->setRedirect($url, XiText::_('COM_PAYPLANS_ORDER_YOU_MUST_LOGIN_FIRST'));
			return false;
		}
		
		return true;
	} 
	
	public function recharge($amount = null)
	{
		$userid = JRequest::getVar('user_id', null);
		
		//check whether any user_id exists in post if not then check logged-in user
		$userid = ($userid === null) ? XiFactory::getUser()->id : $userid; 
		
		//if no user id exists then do nothing
		if(!$userid){
			return true;
		}
		
		$rechargeAmount  = JRequest::getVar('recharge_amount', 0);
		
		$amount = ($amount == null) ? $rechargeAmount : $amount;
		$appId  = JRequest::getVar('payment_method', null);
		
		XiError::assert($appId,XiText::_('COM_PAYPLANS_WALLET_INVALID_APP_ID'));
		XiError::assert($amount,XiText::_('COM_PAYPLANS_WALLET_INVALID_AMOUNT'));
		
		$invoice = PayplansHelperWallet::createInvoice($userid, $amount);
		
		$invoice->confirm($appId);
		// get payment created
        $payment = $invoice->getPayment();

        $response = XiFactory::getAjaxResponse();
        $response->addRawData('payment_key', $payment->getKey());
        $response->sendResponse();
	}
	
	public function rechargeRequest()
	{
		return true;
	}
}

