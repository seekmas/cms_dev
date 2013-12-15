<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplanssiteControllerOrder extends XiController
{
	protected 	$_defaultTask = 'display';
	
	/*
	 * expects key instead of id
	 */
    protected   $_requireKey  = true;
    
	//XiTODO: Depricated  it
	function display($userId = null)
	{
		$userId = XiFactory::getUser($userId)->id;

		//if user is not logged in
		// currently sending to login page
		if(!$userId){
			$return	= JURI::getInstance()->toString();
			$url    = 'index.php?option='.PAYPLANS_COM_USER.'&view=login';
			$url   .= '&return='.base64_encode($return);
			$this->setRedirect($url, XiText::_('COM_PAYPLANS_SUBSCRIPTION_YOU_MUST_LOGIN_FIRST'));
			return false;
		}
		
		// if user is logged-in and comes across order's screen then redirect him to subscription page
		$this->setRedirect(XiRoute::_('index.php?option=com_payplans&view=subscription&task=display'));
		return false;
	}
	
	public function trigger($event=null,$args=null)
	{
		parent::trigger($event,$args);
		return false;
	}
	
	public function terminate()
	{
		//session expired or not
        if($this->_checkSessionExpiry() == false){
        	return false;
        }
             
        //load order record
		$orderId = $this->getModel()->getId();
		
		XiError::assert($orderId, XiText::_('COM_PAYPLANS_ERROR_INVALID_ORDER_ID'));
		$order = PayplansOrder::getInstance($orderId);	
		
		if(!$order->isRecurring()){
			return true;
		}
		
		$invoice = $order->getInvoice();
		$payment = $invoice->getPayment();

		XiError::assert($payment, XiText::_('COM_PAYPLANS_ERROR_INVALID_PAYMENT'));

		// if not confirm then set confirm = false on its view
		if(JRequest::getVar('confirm', false) == false){
			$this->getview()->set('confirm', false);
			return true;
		}
		// set confirm = true on its view
		$this->getview()->set('confirm', true);
		
		$appCancelHtml = $order->terminate();

		// assign appCompleteHtml to view // XITODO : clean it
		$this->getView()->assign('appCancelHtml', $appCancelHtml);

		return true;
	}	
}

