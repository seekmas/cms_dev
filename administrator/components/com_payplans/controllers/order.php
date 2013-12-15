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

class PayplansadminControllerOrder extends XiController
{
	protected	$_defaultOrderingDirection = 'DESC';
	public function terminate()
	{
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
	
	function createInvoice()
	{
		$orderId = $this->getModel()->getId();
		$order 		= PayplansOrder::getInstance($orderId);
		$invoice 	= $order->createInvoice();
		
		$this->setRedirect(XiRoute::_('index.php?option=com_payplans&view=invoice&task=edit&id='.$invoice->getId()));
		return false;
	}
}

