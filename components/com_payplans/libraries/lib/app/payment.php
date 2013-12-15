<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

/**
 * All PAYMENT Plugin should implement this interface
 * Event names should start always as
 * onPayplansPayment
 * @author shyam
 */

abstract class PayplansAppPayment extends PayplansApp
{

	const CREDIT_TRANSACTION = 'CREDIT';
	const DEBIT_TRANSACTION  = 'DEBIT';
	
	public function isApplicable($refObject = null, $eventName='')
	{
		// if not with reference to payment then return
		if($refObject === null || !($refObject instanceof PayplansIfaceApptriggerable)){
			return false;
		}
		
		$object = false;

		// if ref object is instance of plan 
		if($refObject instanceof PayplansPlan)
		{	
			// if apply all then return true
			if($this->getParam('applyAll',false) == true){
					return true;
				}
			 // else check in app plans
			if(in_array($refObject->getId(),$this->getPlans())){
				return true;
			}
			return false;
		}
			
		if($refObject instanceof PayplansTransaction){
			$object = $refObject->getPayment(PAYPLANS_INSTANCE_REQUIRE);
		}
		
		if($refObject instanceof PayplansInvoice){
			$object = $refObject->getPayment();
		}
		
		if($refObject instanceof PayplansPayment){
			$object = $refObject->getClone();
		}
		
		// if reference object is payment then check then app id only
		if($object instanceof PayplansPayment){
			if($object->getApp() == $this->getId()){
				return true;
			}

			return false;
		}
		
		if($refObject instanceof PayplansInvoice){
			if($this->getParam('applyAll',false) == true){
				return true;
			}
			
			// if reference object has the getPlans function 
			if(method_exists($refObject, 'getPlans')){
				$plans = $refObject->getPlans();
			
			 	// if object is of interest as per plans selected
				$ret = array_intersect($this->getPlans(), $plans);
				if(count($ret) > 0 ){
					return true;
				}
			}	
		}	

		return false;
	}

	/**
	 * If app can accept recurring payments
	 */
	public function _isRecurring(PayplansPayment $payment)
	{
		// XITODO : copied to order, remove from here
		$plans = $payment->getPlans(PAYPLANS_INSTANCE_REQUIRE);
		//XITODO : need to change in concept when multiple subscription support will be available
		// if any one plans if recurring then return true
		foreach($plans as $plan){
			if($plan->getRecurring()){
				return true;
			}	
		}
		
		return false;
	}
	
	/**
	 * if app support payment cancel
	 * @since 2.0
	 */
	public function isSupportPaymentCancellation($invoice)
	{
		return false;
	}	
	
	/**
	 * Just before going to display payments form
	 */
	public function onPayplansPaymentBefore(PayplansPayment $payment, $data=null)
	{
		return true;
	}


	public function onPayplansPaymentDisplay(PayplansPayment $payment, $data=null)
	{
		return true;
	}
	/**
	 * Render Payment Forms
	 * @param data : to bind it with any Params or anything else
	 */
	public function onPayplansPaymentForm(PayplansPayment $payment, $data=null)
	{
		return true;
	}

	/**
	 * Render Payment Forms at Admin Panel
	 * @param data : to bind it with any Params or anything else
	 */
	public function onPayplansPaymentFormAdmin(PayplansPayment $payment, $data=null)
	{
		return true;
	}

	/**
	 * Render Payment Records
	 * @param data : to bind it with any Params or anything else
	 */
	public function onPayplansTransactionRecord(PayplansTransaction $transaction =null)
	{
		if($transaction->getParams()){
			//XITODO : probably we need INI there, so it should not be lost becuase of save
			$this->assign('transaction_html',$transaction->getParams()->toArray());
			
			return $this->_render('transaction');
		}
	}

	/**
	 * Payment collection is complete
	 * Show a thank you message.
	 */
	public function onPayplansPaymentAfter(PayplansPayment $payment, &$action, &$data, $controller)
	{
		if($action=='error'){

				$errors = array();
				$log_id = JRequest::getVar('log_id');
				if($log_id && !empty($log_id)){
					$record = XiFactory::getInstance('log', 'model')->loadRecords(array('id'=>$log_id));
					$errors = unserialize(base64_decode($record[$log_id]->content));
					$errors = unserialize(base64_decode($errors['content']));
				}
				else 
				{
					$errorLog = PayplansHelperLogger::getLog($payment, XiLogger::LEVEL_ERROR);
					$record = array_pop($errorLog);
					$errors = unserialize(base64_decode($record->content));
					$errors = unserialize(base64_decode($errors['content']));
				}
				
				$this->assign('errors', $errors);
				
				// set error template
				$controller->setTemplate('complete_'.$action);
				return $this->_render('error');
		}
		return true;
	}

	/**
	 * A trigger comes from payment service.
	 * Verify Payment Details, all sanity checks
	 */
	public function onPayplansPaymentNotify(PayplansPayment $payment, $data=null, $controller)
	{
		return true;
	}

	public function onPayplansPaymentTerminate(PayplansPayment $payment, $controller)
	{
		$order   = $controller->getReferenceObject(PAYPLANS_INSTANCE_REQUIRE);
		if(!is_a($order, 'PayplansOrder')){
			return true;
		}
		
		$order->set('status', PayplansStatus::ORDER_CANCEL)
					->save();
					
		return true;	
	}
	
	/**
	 * If plugin need some special event
	 */
	public function onPayplansPaymentCustom(PayplansPayment $payment, $data=null)
	{
		return true;
	}

	/**
	 * onSave actions
	 */
	public function onPayplansPaymentBeforeSave(PayplansPayment $prev=null, PayplansPayment $new=null)
	{
		return true;
	}

	public function onPayplansPaymentAfterSave(PayplansPayment $prev=null, PayplansPayment $new=null)
	{
		return true;
	}
	
	protected function _getExistingTransaction($invoiceid, $txn_id, $subscr_id, $parent_txn)
	{
		// if all arguments are empty or then return exists
		if(empty($txn_id) && empty($subscr_id) && empty($parent_txn)){
			return true;
		}
		
		$model = XiFactory::getInstance('transaction', 'model');
		$filter = array();
		$filter['invoice_id']			= $invoiceid;
		$filter['gateway_txn_id'] 		= $txn_id; 
		$filter['gateway_subscr_id'] 	= $subscr_id;
		$filter['gateway_parent_txn'] 	= $parent_txn;
		
		$result = $model->loadRecords($filter);
		if(count($result)){
			return $result;
		}
		
		return false;
	}	
	
	//this function should be override by app if it supports the refund from backend
	public function supportForRefund()
	{
		return false;
	}
	
	//this function should be overide by and app if it want to do some refund action after admin confirm for refund
	public function refundRequest(PayplansTransaction $transaction,$refundAmouont)
	{
		return false;
	}
}

