<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();

class PayplansadminControllerInvoice extends XiController
{
	protected	$_defaultOrderingDirection = 'DESC';
	public function newInvoice()
	{
		$this->setTemplate('newinvoice');
		return true;
	}
	
	public function sendInvoiceLink()
	{
		$this->setTemplate('sendinvoicelink');
		return true;
	}
	
	public function addTransaction($invoiceId = null)
	{
		$invoiceId = ($invoiceId === null)? JRequest::getVar('invoice_id') : $invoiceId;
		
		if(!$invoiceId){
			return true;
		}
		
		$invoice = PayplansInvoice::getInstance($invoiceId);
		$payment = $invoice->getPayment(PAYPLANS_INSTANCE_REQUIRE);
		
		if(!$payment){
			//create payment first then add transaction
			$adminApps = array_shift(XiFactory::getInstance('app', 'model')
											->loadRecords(array('type'=>'adminpay', 'published'=> 1)));

			$appId   = $adminApps->app_id;
			$payment = $invoice->createPayment($appId);
		}
		
		$this->setRedirect(XiRoute::_('index.php?option=com_payplans&view=transaction&task=edit&invoice_id='.$invoiceId));
		return false;
	}
	
	function mailInvoice()
	{
		$itemId       = $this->getModel()->getId();
		$invoice	  = PayplansInvoice::getInstance($itemId);
		$mailer  	  = XiFactory::getMailer();
        $body     	  = JRequest::getVar('email-body', '');
        $recipient    = JRequest::getVar('email-to', '');
        $ccRecipient  = JRequest::getVar('email-cc','');
        $bccRecipient = JRequest::getVar('email-bcc','');
        
        if($ccRecipient && !empty($ccRecipient))
        {
	        $ccEmails = explode(',', $ccRecipient);
	        $mailer->addCC($ccEmails);
        }
        
	   if($bccRecipient && !empty($bccRecipient))
        {
	        $bccEmails = explode(',', $bccRecipient);
	        $mailer->AddBCC($bccEmails);
        }
        
        $recipient = explode(',', $recipient);
    
        $mailBody 	= PayplansFactory::getRewriter()->rewrite($body, $invoice);

        $mailer->setSubject(JRequest::getVar('email-subject', ''));
        $mailer->setBody($mailBody);
        $mailer->addRecipient($recipient);
		$content = array('send_to'=> $recipient, 'CC'=>$ccRecipient, 'Bcc'=>$bccRecipient ,'body'=>$mailBody);

		if($mailer->Send() instanceof JException){
			$message=XiText::_('COM_PAYPLANS_EMAIL_SENDING_FAILED');
            PayplansHelperLogger::log(XiLogger::LEVEL_ERROR, $message, 'PayplansInvoice', $content);
			$this->setTemplate('error');
			return true;
		}

		$message=XiText::_('COM_PAYPLANS_EMAIL_SEND_SUCCESSFULLY');
        PayplansHelperLogger::log(XiLogger::LEVEL_INFO, $message,'PayplansInvoice', $content);
		$this->setTemplate('sent');
		return true;
	}
	
	function _save(array $data, $itemId=null, $type=null)
	{
		$invoice = PayplansInvoice::getInstance($itemId);
		$invoice->bind($data);
		
		$invoice->set('subtotal', $invoice->getPrice($invoice->getCounter()));
		$invoice->refresh();
		$invoice->save();
		
		return true;
		// set the subtotal of invoice according to 
		// parameters set in param field
		//$this->set('subtotal', $this->getPrice($this->counter));
		//$this->_loadModifiers($this->getId());
	}
	
	public function statusHelp()
	{
		$this->setTemplate('help');
		return true;
	}
	
	public function deleteModifier()
	{
		$modifier  = PayplansModifier::getInstance(JRequest::getVar("modifierId", false));
		$invoice   = $modifier->getInvoice(PAYPLANS_INSTANCE_REQUIRE);
		
		$modifier->delete();
		$invoice->refresh();
		
		$modifiers  = $invoice->getModifiers();
		$response	= XiFactory::getAjaxResponse();
		$currency   = $invoice->getCurrency();
		
		//call ajax to update the require details
		$amount = $invoice->getDiscount();
		$response->addScriptCall('payplans.jQuery(\'div[name="discount"]\').html',PayplansHelperTemplate::partial('default_partial_amount', compact('currency', 'amount')));
		
		$amount = $invoice->getTaxAmount();
		$response->addScriptCall('payplans.jQuery(\'div[name="taxamount"]\').html',PayplansHelperTemplate::partial('default_partial_amount', compact('currency', 'amount')));
		
		$amount = $invoice->getTotal();
		$response->addScriptCall('payplans.jQuery(\'div[name="total"]\').html',PayplansHelperTemplate::partial('default_partial_amount', compact('currency', 'amount')));
		
		$response->addScriptCall('payplans.jQuery(\'div[name="ppmodifiers"]\').html',PayplansHelperTemplate::partial('default_partial_modifier_table', compact('invoice', 'modifiers')));
		
		$response->sendResponse();
	}	
}


