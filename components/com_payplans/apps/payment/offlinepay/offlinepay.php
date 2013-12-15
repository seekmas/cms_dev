<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class  PayplansAppOfflinepay extends PayplansAppPayment
{
	protected $_location	= __FILE__;

	/**
	 * It will show the payment form
	 * @param $payment
	 * @param $post
	 */
	public function onPayplansPaymentForm(PayplansPayment $payment = null, $data=null)
	{
		return $this->_renderForm($payment);
	}
	
	public function _renderForm(PayplansPayment $payment = null, $form='form')
	{
		$invoice = $payment->getInvoice(PAYPLANS_INSTANCE_REQUIRE);
		XiError::assert($invoice, XiText::_('COM_PAYPLANS_ERROR_INVALID_INVOICE_ID'));
		$this->assign('payment_key',$payment->getKey());

		$formDetail= $payment->getModelform()->getForm($payment);
		
		$transPath = dirname($this->_location).DS.'transaction.xml';
		$formDetail->loadFile($transPath, false, '//config');
		$amountInfo = array('amount' => $invoice->getTotal(), 'currency' => $invoice->getCurrency('isocode'));
		$newData = array('gateway_params' => $amountInfo);
		$formDetail->bind($newData);
		
		$this->assign('transaction_html',$formDetail);

		$this->assign('posturl',XiRoute::_('index.php?option=com_payplans&view=payment&task=complete&payment_key='.$payment->getKey()));
		return $this->_render($form);
	}
	
	public function onPayplansPaymentAfter(PayplansPayment $payment, &$action, &$data, $controller)
	{
		XiError::assert(is_array($data) , XiText::_('COM_PAYPLANS_ERROR_INVALID_DATA_ARRAY'));

		if(isset($data['Payplans_form']['gateway_params']) == false)
			return false;

		// if required data is not set then return false
		if(isset($data['Payplans_form']['gateway_params']['amount']) == false)
			return false;

		//append offline app parameter as well in the gateway params
		$appParameter 				= 	$payment->getApp(PAYPLANS_INSTANCE_REQUIRE)->getAppParams()->toArray();
		$data['Payplans_form']['gateway_params']		= 	array_merge($appParameter, $data['Payplans_form']['gateway_params']);
		$appData['gateway_params']  = 	PayplansHelperParam::arrayToIni($data['Payplans_form']['gateway_params']);

		// initiate the payment only if action equals to success else status remains none
		if($action == 'success'){
			$payment->bind($appData)->save();
			
			$invoice = $payment->getInvoice(PAYPLANS_INSTANCE_REQUIRE);
			// get the transaction instace of lib
			$transaction = PayplansTransaction::getInstance();
			$transaction->set('user_id', $payment->getBuyer())
						->set('invoice_id', $invoice->getId())
						->set('payment_id', $payment->getId())
						->set('amount', 0)
						->set('gateway_txn_id', isset($data['Payplans_form']['gateway_params']['id']) ? $data['Payplans_form']['gateway_params']['id'] : 0)
						->set('gateway_subscr_id', 0)
						->set('gateway_parent_txn', 0)
						->set('params', PayplansHelperParam::arrayToIni($data))
						->set('message',XiText::_('COM_PAYPLANS_APP_OFFLINE_TRANSACTION_CREATED_FOR_INVOICE'))
						->save();
			return true;
		}		
	}
	
	public function onPayplansTransactionRecord(PayplansTransaction $transaction = null)
	{
		$payment = $transaction->getPayment(PAYPLANS_INSTANCE_REQUIRE);
		
		//if gateway parameter exists then display in the transaction record
		if($payment->getGatewayParams()){
			$this->assign('transaction_html', $payment->getGatewayParams()->toArray());
			
			return $this->_render('transaction');
		}	
	}
	
	public function onPayplansTransactionBeforeSave($prev, $new)
	{
		//perform the below task only once,
		//i.e. only when new transaction has been created
		if($prev != null){
			return true;
		}
		
		$param = PayplansHelperParam::iniToArray($new->getParams());
		
		//if gateway transaction id is not mentioned then 
		//fetch the txn id from payment params and set as gateway txn id
		$gatewayTxnId = $new->get('gateway_txn_id', '');
		
		if(empty($gatewayTxnId) && !empty($param) && $param['id']){
			$new->set('gateway_txn_id', $param['id']);
		}
		$message    = $new->get('message','');
		if(empty($message)){
			$new->set('message', 'COM_PAYPLANS_APP_OFFLINE_TRANSACTION_CREATED');
		}
		
		return true;
	}
	
	public function onPayplansPaymentTerminate(PayplansPayment $payment, $controller)
	{
		return $this->_render('cancel');
	}
}

