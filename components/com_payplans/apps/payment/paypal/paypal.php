<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansAppPaypal extends PayplansAppPayment
{
	protected $_location	= __FILE__;
	
	function isApplicable($refObject = null, $eventName='')
	{
		// return true for event onPayplansControllerCreation
		if($eventName == 'onPayplansControllerCreation'){
			return true;
		}
		
		return parent::isApplicable($refObject, $eventName);
	}
	
	function onPayplansControllerCreation(&$view, &$controller, &$task, &$format)
	{
		if($view != 'payment' || ($task != 'notify') ){
			return true;
		}
		
		$paymentKey = JRequest::getVar('invoice', null);
		if(!empty($paymentKey)){
			JRequest::setVar('payment_key', $paymentKey, 'POST');
			return true;
		}
		
		return true;
	}

	public function getAppParam($key, $default=null)
	{
		static $isSandBox = null;

		// initialize sandbox testing variable
		if($isSandBox === null){
			$isSandBox = parent::getAppParam('sandbox',false);
		}

		//check if such a variable exist, then return it
		if($isSandBox){
			$return = parent::getAppParam('sandbox_'.$key,null);
			if($return !== null)
				return $return;
		}

		// else send the normal variable
		return parent::getAppParam($key,$default);
	}

 	/**
     * Gets the Paypal gateway URL
     *
     * @param boolean $full
     * @return string
     * @access protected
     */
    function _getPaypalUrl()
    {
        $url = $this->getAppParam('sandbox') ? 'www.sandbox.paypal.com' : 'www.paypal.com';
        return 'https://' . $url . '/cgi-bin/webscr';
    }

	/**
	 * Checks the validity of given IPN
	 * @param $data
	 */
	function _validateIPN(array $data )
    {
    	// this is for test cases only
    	// if sandbox value is 2, validation must not be there
    	if($this->getAppParam('sandbox', false) == 2){
    		return true;
    	}
    	
    	$paypal_url	=  $this->_getPaypalUrl();

        $req = 'cmd=_notify-validate';

	   foreach ($data as $key => $value) {
	      //ignore joomla url variables
	      if (in_array($key, array('option','task','view','layout'))) {
				continue;
	      }
	      $req .= "&" . $key . "=" . urlencode(stripslashes($value));
	   }

	     // Set up request to PayPal
	     $curl_result = '';
	     $ch = curl_init();
	     curl_setopt($ch, CURLOPT_URL,$paypal_url);
	     curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	     curl_setopt($ch, CURLOPT_POST, 1);
	     curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
	     curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Content-Length: " . strlen($req)));
	     curl_setopt($ch, CURLOPT_HEADER , 0);
	     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	     curl_setopt($ch, CURLOPT_TIMEOUT, 30);

	     $curl_result = curl_exec($ch);
	     curl_close($ch);
	     
	     if(strcmp ($curl_result, 'VERIFIED') === 0){
		 	return true;
	     }
		 	return false;
     }


    /**
     * Payment received; source is a Buy Now, Donation, or Auction Smart Logos button
     * Process in same way
     */
    function _validateNotification(PayplansPayment $payment, array $data)
    {
    	$errors = array();

    	// find the required data from post-data, and match with payment
    	// check reciever email must be same.
    	if($this->getAppParam('merchant_email') != $data['business']) {
            $errors[] = XiText::_('COM_PAYPLANS_INVALID_PAYPAL_RECEIVER_EMAIL');
        }
        
        return $errors;
    }

	public function onPayplansPaymentForm(PayplansPayment $payment, $data = null)
	{
		if(is_object($data)){
			$data = (array)$data;
		}

		$invoice = $payment->getInvoice(PAYPLANS_INSTANCE_REQUIRE);
		
		$this->assign('merchant_email',		$this->getAppParam('merchant_email'));
		$this->assign('merchant_id',		$this->getAppParam('merchant_id',false));
		$this->assign('currency', 			$invoice->getCurrency('isocode'));

		// build urls
    	$sandbox = $this->getAppParams()->get('sandbox',false) ? 'sandbox.' : '';
    	$this->assign('post_url', $this->_getPaypalUrl());
    	
    	$root = JURI::root();
    	if(XiFactory::getConfig()->https == true){
    		$root = JString::str_ireplace("http:", "https:", $root);
    	}
    	
    	$this->assign('return_url', 	$root.'index.php?option=com_payplans&gateway=paypal&view=payment&task=complete&action=success&payment_key='.$payment->getKey());
    	$this->assign('cancel_url', 	$root.'index.php?option=com_payplans&gateway=paypal&view=payment&task=complete&action=cancel&payment_key='.$payment->getKey());
    	$this->assign('notify_url', 	$root.'index.php?option=com_payplans&gateway=paypal&view=payment&task=notify');

    	$this->assign('order_id', 		$invoice->getKey());
    	$this->assign('invoice',		$payment->getKey());
    	$this->assign('item_name',		$invoice->getTitle());
    	$this->assign('item_number',	$invoice->getKey());
    	$this->assign('cmd',			'_xclick');
    	$this->assign('amount',			number_format($invoice->getTotal(),2));
    	
       	$recurring = $invoice->isRecurring();
	   	if($recurring){
	   		$counter = $invoice->getCounter();
	   		
			// Regular expiration parameters
       		$regularExpTime = $invoice->getExpiration(PAYPLANS_RECURRING);
       		$regularExpTime = $this->getRecurrenceTime($regularExpTime);
       		
       		$this->assign('p3', $regularExpTime['period']);
	       	$this->assign('t3', $regularExpTime['unit']);
       		
	       	// Regular subscription parameters
       		if($recurring == PAYPLANS_RECURRING){
	       		$this->assign('a3', number_format($invoice->getTotal(),2));
       		}
       		
       		// first trial subscription parameters
       		if(in_array($recurring, array(PAYPLANS_RECURRING_TRIAL_1, PAYPLANS_RECURRING_TRIAL_2))){
       			$expTime = $invoice->getExpiration(PAYPLANS_RECURRING_TRIAL_1);
	       		$expTime = $this->getRecurrenceTime($expTime);
	       		$this->assign('a1', number_format($invoice->getTotal(),2));
	       		$this->assign('p1', $expTime['period']);
	       		$this->assign('t1', $expTime['unit']);  

	       		//Regular subscription
	       		$this->assign('a3', number_format($invoice->getTotal($counter + 1),2));
       		}
       		
       		// second trial subscription parameters
       		if($recurring == PAYPLANS_RECURRING_TRIAL_2){
       			$expTime = $invoice->getExpiration(PAYPLANS_RECURRING_TRIAL_2);
	       		$expTime = $this->getRecurrenceTime($expTime);
	       		$this->assign('a2', number_format($invoice->getTotal($counter + 1),2));
	       		$this->assign('p2', $expTime['period']);
	       		$this->assign('t2', $expTime['unit']);  

	       		$this->assign('a3', number_format($invoice->getTotal($counter + 2),2));
       		}
       		
       		$this->assign('srt', 		$this->_getRecurrenceCount($invoice));
       		$this->assign('recurring', 	$recurring);
       		$this->assign('cmd',		'_xclick-subscriptions');
        	return $this->_render('form_subscription');
        }
        
        return $this->_render('form_buynow');
	}
	
	public function getRecurrenceTime($expTime)
	{
		$expTime['year'] = isset($expTime['year']) ? intval($expTime['year']) : 0;
		$expTime['month'] = isset($expTime['month']) ? intval($expTime['month']) : 0;
		$expTime['day'] = isset($expTime['day']) ? intval($expTime['day']) : 0;;
		
		// years
		if(!empty($expTime['year'])){
			if($expTime['year'] >= 5){
				return array('period' => 5, 'unit' => 'Y', 'frequency' => XiText::_('COM_PAYPLANS_RECURRENCE_FREQUENCY_GREATER_THAN_ONE'),
										'message' => XiText::_('COM_PAYPLANS_PAYMENT_APP_PAYPAL_RECURRING_MESSAGE'));
			}
			
			if($expTime['year'] >= 2){
				return array('period' => $expTime['year'], 'unit' => 'Y', 'frequency' => XiText::_('COM_PAYPLANS_RECURRENCE_FREQUENCY_GREATER_THAN_ONE'),
										'message' => XiText::_('COM_PAYPLANS_PAYMENT_APP_PAYPAL_RECURRING_MESSAGE'));
			}
			
			// if months is set then return years * 12 + months
			if(isset($expTime['month']) && $expTime['month']){
				return array('period' => $expTime['year'] * 12 + $expTime['month'], 'unit' => 'M', 'frequency' => XiText::_('COM_PAYPLANS_RECURRENCE_FREQUENCY_GREATER_THAN_ONE'),
										'message' => XiText::_('COM_PAYPLANS_PAYMENT_APP_PAYPAL_RECURRING_MESSAGE'));
			}				
			
			return array('period' => $expTime['year'], 'unit' => 'Y', 'frequency' => XiText::_('COM_PAYPLANS_RECURRENCE_FREQUENCY_GREATER_THAN_ONE'),
										'message' => XiText::_('COM_PAYPLANS_PAYMENT_APP_PAYPAL_RECURRING_MESSAGE'));
		}
		
		// if months are set
		if(!empty($expTime['month'])){
			// if days are empty
			if(empty($expTime['day'])){
				return array('period' => $expTime['month'], 'unit' => 'M', 'frequency' => XiText::_('COM_PAYPLANS_RECURRENCE_FREQUENCY_GREATER_THAN_ONE'),
										'message' => XiText::_('COM_PAYPLANS_PAYMENT_APP_PAYPAL_RECURRING_MESSAGE'));
			}
			
			// if total days are less or equlas to 90, then return days
			//  IMP : ASSUMPTION : 1 month = 30 days
			$days = $expTime['month'] * 30;
			if(($days + $expTime['day']) <= 90){
				return array('period' => $days + $expTime['day'], 'unit' => 'D', 'frequency' => XiText::_('COM_PAYPLANS_RECURRENCE_FREQUENCY_GREATER_THAN_ONE'),
										'message' => XiText::_('COM_PAYPLANS_PAYMENT_APP_PAYPAL_RECURRING_MESSAGE'));
			}
			
			// other wise convert it into weeks
			return array('period' => intval(($days + $expTime['day'])/7, 10), 'unit' => 'W', 'frequency' => XiText::_('COM_PAYPLANS_RECURRENCE_FREQUENCY_GREATER_THAN_ONE'),
										'message' => XiText::_('COM_PAYPLANS_PAYMENT_APP_PAYPAL_RECURRING_MESSAGE'));
		}
		
		// if only days are set then return days as it is
		if(!empty($expTime['day'])){
			return array('period' => intval($expTime['day'], 10), 'unit' => 'D', 'frequency' => XiText::_('COM_PAYPLANS_RECURRENCE_FREQUENCY_GREATER_THAN_ONE'),
										'message' => XiText::_('COM_PAYPLANS_PAYMENT_APP_PAYPAL_RECURRING_MESSAGE'));
		}
		
		// XITODO : what to do if not able to convert it
		return false;
	}

	public function onPayplansPaymentAfter(PayplansPayment $payment, &$action, &$data, $controller)
	{
		$record = array_pop(PayplansHelperLogger::getLog($payment, XiLogger::LEVEL_ERROR));			
		if($record && !empty($record)){
			$action = 'error';
		}
		
		return parent::onPayplansPaymentAfter($payment, $action, $data, $controller);
	}

	public function onPayplansPaymentNotify(PayplansPayment $payment, $data, $controller)
	{
		$invoice = $payment->getInvoice(PAYPLANS_INSTANCE_REQUIRE);
		
		$errors = array();		
		// is it a valid records, ask to paypal
    	if($this->_validateIPN($data) == false){
    		$errors[] = XiText::_('COM_PAYPLANS_INVALID_IPN');
    		$message = XiText::_('COM_PAYPLANS_LOGGER_PAYMENT_INVALID_IPN');
    		PayplansHelperLogger::log(XiLogger::LEVEL_ERROR, $message, $payment, $errors);
    		return "INVALID IPN";
    	}
    	
    	// if same notification came more than one time
    	// check if transaction already exists
    	// if yes then do nothing and return
    	$txn_id 	= isset($data['txn_id']) ? $data['txn_id'] : 0;
    	$subscr_id  = isset($data['subscr_id']) ? $data['subscr_id'] : 0;
    	$parent_txn = isset($data['parent_txn_id']) ? $data['parent_txn_id'] : 0;
    	
    	$transactions = $this->_getExistingTransaction($invoice->getId(), $txn_id, $subscr_id, $parent_txn);
    	if($transactions !== false){
    		foreach($transactions as $transaction){
    			$transaction = PayplansTransaction::getInstance($transaction->transaction_id, null, $transaction);
    			$par = $transaction->getParam('txn_type', '');
    			if($transaction->getParam('payment_status','') == $data['payment_status']){
    				return true;
    			}
    		}
    	}
    	
    	// get the transaction instace of lib
		$transaction = PayplansTransaction::getInstance();
		$transaction->set('user_id', $payment->getBuyer())
					->set('invoice_id', $invoice->getId())
					->set('payment_id', $payment->getId())
					->set('gateway_txn_id', $txn_id)
					->set('gateway_subscr_id', $subscr_id)
					->set('gateway_parent_txn', $parent_txn)
					->set('params', PayplansHelperParam::arrayToIni($data));
					
					
		$func_name = '_process_web_accept';

		$func_name_rec 		= isset($data['txn_type']) ? '_process_'.JString::strtolower($data['txn_type']) : 'EMPTY';
		$func_name_nonrec 	= isset($data['payment_status']) ? '_on_payment_'.JString::strtolower($data['payment_status']) : 'EMPTY';
		
		if(method_exists($this, $func_name_rec)){
			$errors = $this->$func_name_rec($payment, $data, $transaction);
		}
		elseif(method_exists($this, $func_name_nonrec)){
			$errors = $this->$func_name_nonrec($payment, $data, $transaction);
		}
		else{
			$errors[] = XiText::_('COM_PAYPLANS_APP_PAYPAL_INVALID_TRANSACTION_TYPE_OR_PAYMENT_STATUS');
		}
		
    	//if error present in the transaction then redirect to error page
		if(!empty($errors)){
			$message = XiText::_('COM_PAYPLANS_LOGGER_ERROR_IN_PAYPAL_PAYMENT_PROCESS');
			$log_id = PayplansHelperLogger::log(XiLogger::LEVEL_ERROR, $message, $payment, $errors);
		}
	
		//store the response in the payment AND save the payment
		if(!$transaction->save()){
			$message = XiText::_('COM_PAYPLANS_LOGGER_ERROR_TRANSACTION_SAVE_FAILD');
			$log_id = PayplansHelperLogger::log(XiLogger::LEVEL_ERROR, $message, $transaction, $errors);
		}
		
		$payment->save();
		
		return count($errors) ? implode("\n", $errors) : ' No Errors';
	}
	
	protected function _getRecurrenceCount($invoice)
	{
		return $invoice->getRecurrenceCount();
	} 
	
	protected function _on_payment_canceled_reversal($payment, $data, $transaction)
	{
		//		Canceled_Reversal: A reversal has been canceled. For example, you
		//		won a dispute with the customer, and the funds for the transaction that was
		//		reversed have been returned to you.
		
		$transaction->set('message', 'COM_PAYPLANS_APP_PAYPAL_TRANSACTION_CANCELED_REVERSAL');
		
		return array();
	}

	protected function _on_payment_completed($payment, $data, $transaction)
	{
		//		Completed: The payment has been completed, and the funds have been
		//		added successfully to your account balance.
		
		$errors = $this->_validateNotification($payment, $data);
        
		if(empty($errors)){
			$transaction->set('amount', $data['mc_gross'])
						->set('message', 'COM_PAYPLANS_APP_PAYPAL_TRANSACTION_COMPLETED');
		}
		return $errors;
	}
	
	protected function _on_payment_created($payment, $data, $transaction)
	{
		//  A German ELV payment is made using Express Checkout.
		// Probably we don't need it
		$transaction->set('message', 'COM_PAYPLANS_APP_PAYPAL_TRANSACTION_CREATED');
					
		return array();
	}
	
	protected function _on_payment_denied($payment, $data, $transaction)
	{
		//		Denied: You denied the payment. This happens only if the payment was
		//		previously pending because of possible reasons described for the
		//		pending_reason variable or the Fraud_Management_Filters_x
		//		variable.
		
		$transaction->set('message', 'COM_PAYPLANS_APP_PAYPAL_TRANSACTION_DENIED');
		return array();
	}
	
	protected function _on_payment_expired($payment, $data, $transaction)
	{
		//		This authorization has expired and cannot be captured.
		$transaction->set('message', 'COM_PAYPLANS_APP_PAYPAL_TRANSACTION_EXPIRED');

		return array();
	}
	
	protected function _on_payment_failed($payment, $data, $transaction)
	{
		//		The payment has failed. This happens only if the payment was
		//		made from your customer’s bank account.
		
		$transaction->set('message', 'COM_PAYPLANS_APP_PAYPAL_TRANSACTION_FAILED');
		
		return array();		
	}
	
	protected function _on_payment_pending($payment, $data, $transaction)
	{
		//		The payment is pending. See pending_reason for more
		//		information.
		
		$transaction->set('message', 'COM_PAYPLANS_APP_PAYPAL_TRANSACTION_PENDING');

		return array();
	}
	
	protected function _on_payment_refunded($payment, $data, $transaction)
	{
		//		Refunded: You refunded the payment.
		
		// 		XITODO : Configurtion is there to ask from admin
		//		What to do on partial refund
        
       $transaction->set('amount', $data['mc_gross'])
					->set('message', 'COM_PAYPLANS_APP_PAYPAL_TRANSACTION_REFUNDED');
		
		return array();
	}
	
	protected function _on_payment_reversed($payment, $data, $transaction)
	{
		//		Reversed: A payment was reversed due to a chargeback or other type of
		//		reversal. The funds have been removed from your account balance and
		//		returned to the buyer. The reason for the reversal is specified in the
		//		ReasonCode element.
		
		$transaction->set('message', 'COM_PAYPLANS_APP_PAYPAL_TRANSACTION_REVERSED');
		
		return array();	
	}
	
	protected function _on_payment_processed($payment, $data, $transaction)
	{
		//		Processed: A payment has been accepted.
		
		$transaction->set('message', 'COM_PAYPLANS_APP_PAYPAL_TRANSACTION_PROCESSED');

		return array();	
	}
	
	protected function _on_payment_voided($payment, $data, $transaction)
	{
		//		Voided: This authorization has been voided.
		$transaction->set('message', 'COM_PAYPLANS_APP_PAYPAL_TRANSACTION_VOIDED');
		
		return array();	
	}
	
	//XITODO : cros check subscr_id
	protected function _process_subscr_payment($payment, $data, $transaction)
	{		
		$errors = $this->_validateNotification($payment, $data);
		$func_name = '_on_payment_'.JString::strtolower($data['payment_status']);
		
		$temp = $this->$func_name($payment, $data, $transaction);
		$errors = array_merge($errors, $temp);	

		return $errors;
	}
	
	protected function _process_subscr_signup($payment, $data, $transaction)
	{
		$transaction->set('message', 'COM_PAYPLANS_APP_PAYPAL_TRANSACTION_SUBSCR_SIGNUP');
	
		//if free trail then change the invoice status to paid
		$invoice = $payment->getInvoice(PAYPLANS_INSTANCE_REQUIRE);
		$amount = $invoice->getTotal();
		if(floatval($amount) == floatval(0)){
			// trigger the event 
			$args = array($transaction, $amount);
			PayplansHelperEvent::trigger('onPayplansWalletUpdate', $args);
		}
		return array();
	}
	
	protected function _process_subscr_cancel($payment, $data, $transaction)
	{
		$transaction->set('message', 'COM_PAYPLANS_APP_PAYPAL_TRANSACTION_SUBSCR_CANCEL');
		//terminate the order
		$invoice = $payment->getInvoice(PAYPLANS_INSTANCE_REQUIRE);
		$invoice->terminate();
		return array();
	}
	
	protected function _process_subscr_modify($payment, $data, $transaction)
	{
		$transaction->set('message', 'COM_PAYPLANS_APP_PAYPAL_TRANSACTION_SUBSCR_MODIFY');
		// XITODO : what to do here
	}
	
	protected function _process_subscr_failed($payment, $data, $transaction)
	{
		$transaction->set('message', 'COM_PAYPLANS_APP_PAYPAL_TRANSACTION_SUBSCR_FAILED');
		
		return array();
	}
	
	protected function _process_subscr_eot($payment, $data, $transaction)
	{
		$transaction->set('message', 'COM_PAYPLANS_APP_PAYPAL_TRANSACTION_SUBSCR_EOT');
		
		return array();
	}
	
	protected function _process_new_case($payment, $data, $transaction)
	{
		//		dispute : 
		// 		user has filed a dispute respect to this payment
		
		$transaction->set('message', 'COM_PAYPLANS_APP_PAYPAL_TRANSACTION_NEW_CASE');
		
		return array();
	}
	
}

