<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplanssiteControllerPayment extends XiController
{
	protected 	$_defaultTask = 'notask';

	/*
	 * expects key instead of id
	 */
    protected   $_requireKey  = true;

	/**
	 * Collect payment for given payment key
	 */
	public function pay($paymentId = null, $post = null)
	{
		//session expired or not
   	    if($this->_checkSessionExpiry()==false){
	        return false;
   	    }
	        
		//load payment record
		$paymentId = ($paymentId === null) ? $this->getModel()->getId() : $paymentId;
		XiError::assert($paymentId,XiText::_('COM_PAYPLANS_ERROR_INVALID_PAYMENT_ID'));
		$payment = PayplansPayment::getInstance( $paymentId);

		//trigger all payment apps only
		$post = ($post === null) ? JRequest::get('POST') : $post;
		$args = array($payment,$post);
		$results = PayplansHelperEvent::trigger('onPayplansPaymentForm',$args,'payment',$payment);
		
		// set proper template
		$this->setTemplate(__FUNCTION__);
		$this->getView()->assign('result', $results);

		return true;
	}

	/**
	 * Custom action to be triggered on payment application
	 *
	 * @param $action
	 * @param $paymentId
	 */
	public function custom($action=null, $paymentId=null, $post=null)
	{
		//load payment record
		$paymentId = $this->getModel()->getId();
		XiError::assert($paymentId,XiText::_('COM_PAYPLANS_ERROR_INVALID_PAYMENT_ID'));
		$payment = PayplansPayment::getInstance( $paymentId);

		//collect action to be performed
		//XITODO : Security Check, MUST not start from _
		$action  = JRequest::getVar('action',$action);
		XiError::assert(!empty($action) , XiText::sprintf('COM_PAYPLANS_ERROR_INVALID_ACTION_%s_TO_TRIGGER',$action));

		// trigger apps,
		$post = $post ? $post : JRequest::get('POST');
		$args = array($payment,$post);
		PayplansHelperEvent::trigger($action,$args,'payment',$payment);

		// no need to generate payment view, its already done via app
		return false;
	}
	
	function invoice($userId = null)
	{
		$userId = XiFactory::getUser($userId)->id;

		//if user is not logged in
		// currently sending to login page
		if(!$userId){
			$return	= JURI::getInstance()->toString();
			$url    = 'index.php?option='.PAYPLANS_COM_USER.'&view=login';
			$url   .= '&return='.base64_encode($return);
			$this->setRedirect($url, XiText::_('COM_PAYPLANS_PAYMENT_INVOICE_YOU_MUST_LOGIN_FIRST_TO_VIEW'));
			return false;
		}
		
		$paymentId  = $this->getModel()->getId();
		$payment	= PayplansPayment::getInstance( $paymentId);
		$payment_status = $payment->getStatus();
		$order      = PayplansOrder::getInstance( $payment->getOrder());
		
		$this->setTemplate(__FUNCTION__);
	}
	
	public function trigger($event=null,$args=null)
	{
		parent::trigger($event,$args);
	}
	
	/**
	 * It is notification of payment recieved from Banks/Paypal
	 * that some one has made payment, so we should process it and
	 * update the status of payment
	 * 
	 * V V IMP : App must decide how to find payment key.
	 * App must work on onPayplansControllerCreation
	 * If payment key is not known.
	 *
	 * @param array $post
	 */
	public function notify($post=null)
	{
		$post = $post ? $post : JRequest::get('REQUEST');
		if(JDEBUG){
			// When debug mode is on dump in file
			file_put_contents(JPATH_SITE.DS.'tmp'.DS.time(), var_export($post,true), FILE_APPEND);
		}
		
		//load order record
		$paymentId = $this->getModel()->getId();
		XiError::assert($paymentId, XiText::_('COM_PAYPLANS_ERROR_INVALID_PAYMENT_ID'));
		
		$payment = PayplansPayment::getInstance($paymentId);

		$args = array($payment, $post, $this);
		$results = PayplansHelperEvent::trigger('onPayplansPaymentNotify',$args,'payment',$payment);

		foreach($results as $result){
			if($result === false){
				// some problem here
			}

			// echo the output
			if($result !== true){
				echo $result;
			}
		}

		// no need to generate payment view, its already done via app
		return false;
	}
	
	public function complete()
	{
        // get payment id
		$paymentId = $this->getModel()->getId();
		XiError::assert($paymentId, XiText::_('COM_PAYPLANS_ERROR_INVALID_PAYMENT_ID'));
		
		$payment = PayplansPayment::getInstance($paymentId);
			
		// set template success, so application can change it if required.
		$action = JRequest::getVar('action','success');

		// trigger apps, so they can do postpayment work
		$post = JRequest::get('REQUEST');
		$args = array($payment, &$action, &$post, $this);
		$appCompleteHtml = PayplansHelperEvent::trigger('onPayplansPaymentAfter',$args,'payment',$payment);

		$invoice = $payment->getInvoice(PAYPLANS_INSTANCE_REQUIRE);
		// if action is success then redirect to thanks page
		if($action === 'success'){

		//this is done beacuse it redirects to https from http but it will not redirect back to http from https after doing payment			
		//force ssl = 2 (ssl applied on entire site)		
		$uri  = JURI::getInstance();
		$host = $uri->toString(array('scheme', 'host', 'port'));
		
		//do not use full url to create route, sef routing won't work in that case
	    $url  = XiRoute::_('index.php?option=com_payplans&view=invoice&task=thanks&invoice_key='.$invoice->getKey());
	
	    if(XiFactory::getConfig()->https == true && JFactory::getConfig()->force_ssl !== 2){
	    	$host = JString::str_ireplace("https:", "http:", $host);
	    }

		$this->setRedirect($host.$url,false);
			return false;
		}
		
		$this->setTemplate('complete_'.$action);
		
		// assign appCompleteHtml to view // XITODO : clean it
		$this->getView()->assign('appCompleteHtml', $appCompleteHtml);

		return true;
	}
}