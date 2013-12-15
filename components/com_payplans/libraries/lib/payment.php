<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansPayment extends XiLib
	implements PayplansIfaceApptriggerable, PayplansIfaceApiPayment, PayplansIfaceMaskable
{
	// Table fields
	protected $payment_id;
	protected $user_id;
	protected $invoice_id;
	protected $app_id;
	protected $created_date;
	protected $modified_date;
	protected $params;
	protected $gateway_params;
	protected $_transactions	=	array();
	
	// skip these tokens in token rewriter
	public  $_blacklist_tokens = array('gateway_params'); 
	/**
	 * @return PayplansPayment
	 * @param string $dummy is added just for removing warning with development mode(XiLib::getInstance is having 4 parameters)
	 */
	static public function getInstance($id=0, $type=null, $bindData=null, $dummy=null)
	{
		return parent::getInstance('payment',$id, $type, $bindData);
	}
	
	// not for table fields
	public function reset(Array $option=array())
	{
		XiError::assert($this);
		$this->payment_id		= 	0;
		$this->user_id			=	0;
		$this->invoice_id		=	0;
		$this->app_id			=	0;
		$this->created_date		=	new XiDate();
		$this->modified_date	=	new XiDate();
		$this->params			= new XiParameter();
		$this->gateway_params   =   new XiParameter();
		$this->_transactions	=   array();
		
		return $this;
	}

	public function afterBind($id = 0)
	{
		if(!$id) return $this;

		//load dependent records
		return $this->_loadTransactions($id);
	}

	/**
	 * Gets the buyer of the payment
	 * @see PayplansIfaceApiPayment::getBuyer()
	 * @param boolean $requireinstance  If True return PayplansUser instance else Userid 
	 * 
	 * @return mixed Userid or instance of PayplansUser attached with the payment
	 */
	public function getBuyer($requireinstance=false)
	{
		if($requireinstance == PAYPLANS_INSTANCE_REQUIRE){
			return PayplansUser::getInstance($this->user_id);
		}
		
		return $this->user_id;
	}

	/**
	 * Implementing interface Apptriggerable
	 * @return array
	 */
	public function getPlans($requireInstance = false)
	{
		return $this->getInvoice(PAYPLANS_INSTANCE_REQUIRE)->getPlans($requireInstance);
	}
	
	public function setApp($app)
	{
		$this->app_id = is_a($app,'PayplansApp') ? $app->getId() : $app;  	
		return $this;
	}
	
	/**
	 * Gets the invoice linked with the current payment
	 * 
	 * @see PayplansIfaceApiPayment::getInvoice()
	 * 
	 * @param  boolean  $requireInstance  Optional parameter to get the object (PayplansInvoice)
	 * @return mixed  InvoiceId or object of PayplansInvoice for InvoiceId
	 */
	public function getInvoice($requireInstance = false)
	{
		if($requireInstance == PAYPLANS_INSTANCE_REQUIRE){
			return PayplansInvoice::getInstance($this->invoice_id);
		}
		
		return $this->invoice_id;
	}

	/**
	 * Gets the payment-gateway app name
	 * @return string  Title of the app
	 */
	public function getAppName()
	{
		return PayplansApp::getInstance( $this->app_id)->getTitle();
	}

	/**
	 * Gets the creation date of the payment
	 * @see PayplansIfaceApiPayment::getCreatedDate()
	 * @return object XiDate
	 */
	public function getCreatedDate()
	{
		return $this->created_date;
	}

	/**
	 * Gets the modified date of the payment
	 * @see PayplansIfaceApiPayment::getModifiedDate()
	 * @return object  XiDate
	 */
	public function getModifiedDate()
	{
		return $this->modified_date;
	}

	/**
	 * Gets the app attached with the payment
	 * @see PayplansIfaceApiPayment::getApp()
	 * @param   boolean  $requireinstance  Optional parameter to get the app instance rather than app id
	 * @return  mixed  AppId or object of PayplansApp for AppId
	 */
	public function getApp($requireinstance = false)
	{
		if($requireinstance == PAYPLANS_INSTANCE_REQUIRE){
			return PayplansApp::getInstance($this->app_id);
		}
		
		return $this->app_id;
	}

	/**
	 * Returns a key of the payment object or the default value if the key is not set.
	 *
	 * @param string  $key       The name of the property.
	 * @param mixed   $default   The default value.
	 * 
	 * @return  mixed   The value of the key.
	 */
	public function getParam($key,$default=null)
	{
		XiError::assert($this);
		return $this->getParams()->get($key,$default);
	}
	
	/**
	 * Gets all the parameters of the Order
	 * 
	 * @return object XiParameter
	 */
	public function getParams()
	{
		return $this->params;
	}
	
	/**
	 * Gets the gateway params of the payment
	 * Gateway params are payment gateway specific parameters 
	 * like pending recurrence cycle to process, subscribe id etc 
	 * 
	 * @see PayplansIfaceApiPayment::getGatewayParams()
	 * 
	 * @return  object XiParameter
	 */
	public function getGatewayParams()
	{
		return $this->gateway_params;
	}
	
	/**
	 * Gets the property of the gateway params of the payment
	 *  
 	 * @param   string  $key       The name of the property.
	 * @param   mixed   $default   The default value.
	 * 
	 * @return  mixed  The value of the key.
	 */
	public function getGatewayParam($key, $default=null)
	{
		return $this->getGatewayParams()->get($key,$default);
	}
	
	/**
	 * Gets the transaction attached with the payment
	 * 
	 * @see PayplansIfaceApiPayment::getTransactions()
	 * 
	 * @return array  Array of transaction object (PayplansTransaction)
	 */
	public function getTransactions()
	{
		return $this->_transactions;
	}
	
	/**
	 * Refer the payment record
	 * Load all the transactions attached to the payment
	 * @return object PayplansPayment
	 */
	public function refresh()
	{
		// get all transactions 
		$this->_loadTransactions($this->getId());
	
		// save update payment
		return $this;
	}
	
	protected function _loadTransactions($payment_id)
	{
		// get all transaction records of this payment
		$records = XiFactory::getInstance('transaction', 'model')
							->loadRecords(array('payment_id'=>$payment_id));

		foreach($records as $record){
			$this->_transactions[$record->transaction_id] = PayplansTransaction::getInstance($record->transaction_id, null, $record);
		}

		return $this;
	}
}

class PayplansPaymentFormatter extends PayplansFormatter
{

	function getIgnoredata()
	{
		$ignore = array('_trigger', '_component', '_errors', '_name','_blacklist_tokens','_transactions');
		return $ignore;
	}
	
	function getVarFormatter()
	{
		$rules = array('app_id'        => array('formatter'=> 'PayplansAppFormatter',
										       'function' => 'getAppName'));
		return $rules;
		
	}
}
