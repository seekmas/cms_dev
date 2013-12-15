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

class PayplansadminControllerTransaction extends XiController
{
	protected	$_defaultOrderingDirection = 'DESC';
	public function _save(array $data, $itemId = null, $type=null)
	{
		if(!$itemId){
			XiError::assert(isset($data['payment_id']) && $data['payment_id'], XiText::_('COM_PAYPLANS_ERROR_INVALID_PAYMENT_ID'));
		}
		
		if(!empty($data['params'])){
			$data['params'] = PayplansHelperParam::arrayToIni($data['params']);
		}
		
		$invoice_id = $data['invoice_id'];
		$invoice = PayplansInvoice::getInstance($invoice_id);
		$amount = $invoice->getTotal();
		
		//create new lib instance
		$transaction  = PayplansTransaction::getInstance($itemId)
								->bind($data);
		
		// trigger the event for free invoice only
		if(floatval($amount) == floatval(0)){
			// trigger the event 
			$args = array($transaction, $amount);
			PayplansHelperEvent::trigger('onPayplansWalletUpdate', $args);
		}

		$transaction->save();

		return true;
	}
	
	public function newTransaction()
	{
		$this->setTemplate('newtransaction');
		return true;
	}
	
	public function refund()
	{
		$this->setTemplate('refund');

		// if not confirm then set confirm = false on its view
		if(JRequest::getVar('confirm', false) == false){
			$this->getview()->set('confirm', false);
			return true;
		}

		// set confirm = true on its view
		$this->getview()->set('confirm', true);
		
		$refund_amount = JRequest::getVar('refund_amount');
		if(isset($refund_amount) && !empty($refund_amount)){
			$this->getview()->set('refund_amount',$refund_amount);
		}
	
		return true;
	}
}

