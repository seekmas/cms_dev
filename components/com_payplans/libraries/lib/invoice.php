<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

/**
 * Invoice Class
 * 
 * @author Gaurav Jain
 * @since 2.0
 *
 */
class PayplansInvoice extends XiLib
					implements PayplansIfaceApptriggerable, PayplansIfaceDiscountable, PayplansIfaceMaskable, PayplansIfaceApiInvoice
{
	/**
	 * Auto Increment Primary Key of Table
	 * @var integer
	 * @since 2.0
	 */
	protected $invoice_id = 0;
	
	/**
	 * order id for which current invoice has been generated
	 * @var integer
	 * @since 2.0
	 */
	protected $object_id = 0;
	protected $object_type = null;
	
	/**
	 * User id of user for which current invoice has been generated
	 * @var integer
	 * @since 2.0
	 */
	protected $user_id = 0;

	/**
	 * This field contians the subtotal, given by the order.
	 * @var float
	 * @since 2.0
	 */
	protected $subtotal = 0.00;
	
	/**
	 * Total amount after applying doscount/addtion/tax  
	 * @var float
	 * @since 2.0
	 */
	protected $total = 0.00;
	
	/**
	 * Currency in which user will pay.
	 * @var string
	 * @since 2.0
	 */
	protected $currency = null;
	
	/**
	 * This field contains the serial counter of invoice for a particular order.
	 * @var integer
	 * @since 2.0
	 */
	protected $counter = 0;
	
	/**
	 * Params for invoice
	 * @var XiParameter
	 */
	protected $params = null;
	
	/**
	 * Wallet transaction creation date
	 * @var XiDate
	 * @since 2.0
	 */
	protected $created_date = null;
	
	/**
	 * Wallet transaction modification date
	 * @var XiDate
	 * @since 2.0
	 */
	protected $modified_date = null;
	
	/**
	 * Is current record checked out?
	 * @var boolean
	 * @since 2.0
	 */
	protected $checked_out = true;
	
	/**
	 * Wallet transaction checked out time
	 * @var XiDate
	 * @since 2.0
	 */
	protected $checked_out_time = null;
	
	/**
	 * This contains the payment attached with Invoice
	 * This can be empty in case, when then is not direct transaction of payment from payment gatway
	 * and amount will be deducted from Wallet
	 *
	 * @var PayplansPayment
	 * @since 2.0
	 */
	private $_payment = null;
	
	/**
	 * This contains the array of modifiers which are applied on invoice
	 *
	 * @var Array PayplansPayment
	 * @since 2.0
	 */
	private $_modifiers = array();
	
	protected $_transactions = array();
	// skip these tokens in token rewriter
	public  $_blacklist_tokens = array('params'); 
	
	/**
	 * to get the lib instance of invoice
	 * @param integer $id
	 * @param string $type : should be null always 
	 * @param stdclass $bindData : no need to run a query, if bind data is there
	 * @param string $dummy is added just for removing warning with development mode(XiLib::getInstance is having 4 parameters)
	 * @return PayplansInvoice
	 */
	static public function getInstance($id=0, $type=null, $bindData=null, $dummy=null)
	{
		return parent::getInstance('invoice',$id, $type, $bindData);
	}
	
	/**
	 * Reset the variable of current invoice object
	 * @param Array $config
	 */
	public function reset(Array $config=array())
	{
		$this->invoice_id 		= 0;
		$this->object_id		= 0;
		$this->object_type		= null;
		$this->user_id			= 0;
		$this->subtotal			= 0.00;
		$this->total			= 0.00;
		$this->counter			= 0;
		// Load default currency from configuration
		$this->currency 		= XiFactory::getConfig()->currency;
		$this->status			= PayplansStatus::NONE;
		$this->created_date  	= new XiDate();
		$this->modified_date 	= new XiDate();
		$this->checked_out		= 0;
		$this->checked_out_time	= new XiDate();
		$this->params			= new XiParameter();
			
		$this->_payment 		= null;
		$this->_modifiers		= array();
		return $this;
	}

	/**
	 * This loads the transaction of invoice if exists
	 * @param integer $invoice_id
	 * @return PayplansTransaction
	 * @since 2.0
	 */
	protected function _loadTransactions($invoice_id)
	{
		// get all transaction records of this invoice
		$records = XiFactory::getInstance('transaction','model')
								->loadRecords(array('invoice_id'=>$invoice_id), array('limit'));

		$this->_transactions	= null;
		
		if(!empty($records)){
			foreach($records as $record){
				$this->_transactions[$record->transaction_id] = PayplansTransaction::getInstance( $record->transaction_id, null, $record);
			}
		}		

		return $this;
	}
	
	/**
	 * This loads the payment of invoice if exists
	 * @param integer $order_id
	 * @return PayplansInvoice
	 * @since 2.0
	 */
	protected function _loadPayment($invoice_id)
	{
		// get all subscription records of this order
		$records = XiFactory::getInstance('payment','model')
								->loadRecords(array('invoice_id'=>$invoice_id), array('limit'));

		$this->_payment	= null;
		
		if(!empty($records)){
			foreach($records as $record){
				$this->_payment = PayplansPayment::getInstance( $record->payment_id, null, $record);
			}
		}		

		return $this;
	}
	
	/**
	 * This loads the payment of invoice if exists
	 * @param integer $order_id
	 * @return PayplansInvoice
	 * @since 2.0
	 */
	protected function _loadModifiers($invoice_id)
	{
		$this->_modifiers = PayplansHelperModifier::get(array('invoice_id' => $invoice_id), PAYPLANS_INSTANCE_REQUIRE);
		$total = PayplansHelperModifier::getTotal($this->getSubtotal(), $this->_modifiers);
		$this->set('total', $total);
		return $this;
	}
	
	/**
	 * Load the respective payment of invoice after binding data with invoice 
	 * @see components/com_payplans/xiframework/base/XiLib::afterBind()
	 * @return PayplansInvoice
	 * @since 2.0
	 */
	public function afterBind($id = 0)
	{
		if(!$id) return $this;
		//load dependent records
		return $this->_loadModifiers($id)->_loadPayment($id)->_loadTransactions($id);
	}
	
	/**
	 * Create payment
	 * 
	 * @see PayplansIfaceApiInvoice::createPayment()
	 * 
	 * @param  integer  $appId  App identifier (payment gateway app) for creating payment
	 * @return PayplansPayment
	 * @since 2.0
	 */
	public function createPayment($appId)
	{
		$payment = PayplansPayment::getInstance()
						->set('user_id', $this->getBuyer())
						->set('invoice_id', $this->getId())
						->set('app_id',   $appId)
						->set('amount',   $this->getTotal())
						->set('currency', $this->getCurrency('isocode'));

		$payment->save();

		//also add into payment list of order
		$this->_payment = $payment;
		return $payment;
	}
	
	/**
	 * to get the instance of object/object_id attached with current invoice
	 * @param boolean optional $instanceRequire : true if instance required, false if only id required
	 * @return integer / PayplansOrder
	 * @since 2.0
	 */
	public function getReferenceObject($instanceRequire = false)
	{
		if($instanceRequire == PAYPLANS_INSTANCE_REQUIRE){
			$class = $this->object_type;
			return call_user_func(array($class, 'getInstance'), $this->object_id);
		}
		
		return $this->object_id;
	}
	
	/**
	 * return the total amount of invoice
	 * Invoice total is inclusive of discount and tax and other kind of amount modification
	 * 
	 * @see PayplansIfaceApiInvoice::getTotal()
	 * 
	 * @param  integer $number  Invoice number(counter) to get total of 
	 * @return float  Value of the total 
	 * @since 2.0
	 */
	public function getTotal($number = 0)
	{
		if($number == 0 || $number == $this->getCounter()){
			$total 	= $this->total;
		}
		else{
			//XITODO : work only on master invoice if future invoice is concerned 
			$subtotal 	= $this->getPrice($number);		
			$total 		= PayplansHelperModifier::getTotalByFrequencyOnInvoiceNumber($this->getModifiers(), $subtotal, $number);
		}
				
		return PayplansHelperFormat::price($total);
	}
	
	/**
	 * The subtotal amount of invoice
	 * This is exclusive of discount and tax
	 * 
	 * @see PayplansIfaceApiInvoice::getSubtotal()
	 * 
	 * @return float  Value of the subtotal
	 * @since 2.0
	 */
	public function getSubtotal()
	{
		return PayplansHelperFormat::price($this->subtotal);
	}
	
	/**
	 * return the currency attached with invoice
	 * @param string $format
	 * @return string
	 * @since 2.0
	 */
	public function getCurrency($format = null)
	{
		return PayplansHelperFormat::currency(XiFactory::getCurrency($this->currency), array(), $format);
	}
	
	/**
	 * returns the tax amount applied on invoice
	 * 
	 * @see PayplansIfaceApiInvoice::getTaxAmount()
	 * 
	 * @return integer
	 * @since 2.0
	 */
	public function getTaxAmount()
	{
		$tax = PayplansHelperModifier::getModificationAmount($this->getSubtotal(), $this->getModifiers(), array(PayplansModifier::FIXED_TAX, PayplansModifier::PERCENT_TAX));
		return PayplansHelperFormat::price($tax);
	}
	
	/**
	 * returns the discount amount applied on invoice
	 * 
	 * @see PayplansIfaceApiInvoice::getDiscount()
	 * 
	 * @return float  Value of the discount
	 * @since 2.0
	 */
	public function getDiscount()
	{
		$discount = PayplansHelperModifier::getModificationAmount($this->getSubtotal(), $this->getModifiers(), array(PayplansModifier::FIXED_DISCOUNT, PayplansModifier::PERCENT_DISCOUNT));
		return PayplansHelperFormat::price(-$discount);
	}
	
	/**
	 * The amount of invoice, on this amount discount will be applied.
	 * e.g. Plan amount + Any amount for addons (+/-)
	 * 
	 * @see PayplansIfaceApiInvoice::getDiscountable()
	 * 
	 * @return double
	 * @since 2.1
	 */
	public function getDiscountable()
	{
		$discount = PayplansHelperModifier::getModificationAmount($this->getSubtotal(), $this->getModifiers(), array(PayplansModifier::FIXED_DISCOUNTABLE, PayplansModifier::PERCENT_DISCOUNTABLE));
		return PayplansHelperFormat::price(-$discount);
	}
	
	/**
	 * returns the invoice amount after applying tax
	 * it will be always positive amount
	 * 
	 * @see PayplansIfaceApiInvoice::getNontaxableAmount()
	 * 
	 * @return double
	 * @since 2.1
	 */
	public function getNontaxableAmount()
	{
		$tax = PayplansHelperModifier::getModificationAmount($this->getSubtotal(), $this->getModifiers(), array(PayplansModifier::FIXED_NON_TAXABLE, PayplansModifier::PERCENT_NON_TAXABLE));
		return PayplansHelperFormat::price($tax);
	}
	
	/**
	 * returns the current status of invoice
	 * 
	 * @see PayplansIfaceApiInvoice::getStatus()
	 * 
	 * @return int Constant @type PayplansStatus
	 * @since 2.0
	 */
	public function getStatus()
	{
		return $this->status;
	}
	
	/**
	 * sets status of invoice
	 * @since 2.0
	 */
	public function setStatus($status)
	{
		$this->status = $status;
		return $this;
	}
	
	/**
	 * return the counter of current invoice
	 * 
	 * @see PayplansIfaceApiInvoice::getCounter()
	 * 
	 * @return integer
	 * @since 2.0
	 */
	public function getCounter()
	{
		return $this->counter;
	}
	
	/**
	 * Refresh the invoice object
	 * Reload modifiers, payment and transaction in current object
	 * so that modifiers can be applied
	 * @since 2.0
	 */
	public function refresh()
	{
		$id = $this->getId();
		$this->_loadModifiers($id)->_loadPayment($id)->_loadTransactions($id);
		return $this;
	}
	
	/**
	 * return all the plans attached with reference object for which invoice was created.
	 * (In case of PayPlans, reference object is PayPlansOrder)
	 * (non-PHPdoc)
	 * @see PayplansIfaceDiscountable::getPlans()
	 * @return Array PayplansPlan
	 */
	public function getPlans($instanceRequire = false)
	{
		$refereceObject = $this->getReferenceObject(PAYPLANS_INSTANCE_REQUIRE);
		if(method_exists($refereceObject, 'getPlans')){
			return $refereceObject->getPlans($instanceRequire);	
		}
		
		return array();
	}
	
	/**
	 * Gets the buyer of the order
	 * 
	 * @see PayplansIfaceApiInvoice::getBuyer()
	 * 
	 * @param boolean $requireinstance  If True return PayplansUser instance else Numeric Userid 
	 * 
	 * @return mixed Userid or PayplansUser attached with the order
	 */
	public function getBuyer($requireinstance=false)
	{
		if($requireinstance == PAYPLANS_INSTANCE_REQUIRE){
			return PayplansUser::getInstance($this->user_id);
		}

		return $this->user_id;
	}
	
 	/**
	 * Sets the buyer for the invoice
	 * 
	 * @param integer $userId  UserId to which the invoice will be attached
	 * 
	 * @return PayplansInvoice
	 */
	public function setBuyer($id)
	{
		$this->user_id = $id;
		return $this;
	}
	
	/**
	 * returns the array of PayplansModifier
	 * 
	 * @see PayplansIfaceApiInvoice::getModifiers()
	 * 
	 * @param  array $filters  Optional parameter to get the selected modifiers
	 * @return array PayplansModifier
	 * @since 2.0
	 */
	public function getModifiers($filters = array())
	{
		$modifiers = $this->_modifiers;
		if(!empty($filters)){
			foreach ( $modifiers as $key => $modifier){
                // Filters should be an array
				$filters = is_array($filters) ? $filters : array($filters);

				//XITODO : value can be array
				foreach ($filters as $filterKey => $value){
					if(!is_array($value)){
						 $value = array($value);
					}
					if(!in_array($modifier->get($filterKey),$value)){
						unset($modifiers[$key]);
					}
				}
			}
		}
		return $modifiers;
	}
	
	/**
	 * returns the PayplansPayment instance
	 * 
	 * @see PayplansIfaceApiInvoice::getPayment()
	 * 
	 * @return PayplansPayment
	 * @since 2.0
	 */
	public function getPayment()
	{
		return $this->_payment;
	}
	
	/**
	 * returns the PayplansTransaction instance
	 * 
	 * @see PayplansIfaceApiInvoice::getTransactions()
	 * 
	 * @return PayplansTransaction
	 * @since 2.0
	 */
	public function getTransactions(){
		if(count($this->_transactions) <= 0){			
			$records = XiFactory::getInstance('transaction','model')
								->loadRecords(array('invoice_id'=>$this->invoice_id));
			foreach($records as $record){
				$this->_transactions[ $record->transaction_id] = PayplansTransaction::getInstance( $record->transaction_id, null, $record);
			}
		}
		
		return $this->_transactions;
	}
	
	/**
	 * Gets the wallet records attached to the invoice
	 * 
	 * @see PayplansIfaceApiInvoice::getWallet()
	 * 
	 * @return array  Array of objects (PayplansWallet)
	 */
	public function getWallet()
	{
		$wallet = array();
		$records = XiFactory::getInstance('wallet','model')
							->loadRecords(array('invoice_id'=>$this->invoice_id));
		foreach($records as $record){		
            $wallet[ $record->invoice_id] = PayplansWallet::getInstance( $record->invoice_id, null, $record);
		}
	 
      return $wallet;					
	}
	
	/**
	 * Confirm the invoice and create payment
	 * 
	 * @see PayplansIfaceApiInvoice::confirm()
	 * 
	 * @param integer $appId  Payment gateway app id for payment creation
	 * 
	 * @return object  PayplansInvoice
	 */
	public function confirm($appId)
	{
		//create a new payment for this invoice
		$this->_payment = $this->createPayment($appId);
		
		//whatever the previous status,always save the invoice 
		//otherwise some extra details entered by users won't be updated  
		$this->set('status', PayplansStatus::INVOICE_CONFIRMED)
			 ->save();
		
		return $this;
	}
	
	/**
	 * Modifies a param of the invoice, creating it if it does not already exist.
	 *
	 * @see XiLib::setParam()
	 *
	 * @param   string  $key      The name of the key.
	 * @param   mixed   $value    The value of the key to set.
	 *
	 * @return  object  PayplansInvoice
	 */
	public function setParam($key, $value)
	{
		XiError::assert($this);
		$this->params->set($key,$value);
		return $this;
	}
	
	/**
	 * Returns a param of the invoice object or the default value if the key is not set.
	 *
	 * @param string  $key       The name of the property.
	 * @param mixed   $default   The default value.
	 * 
	 * @return  mixed   The value of the key.
	 */
	public function getParam($key,$default=null)
	{
		XiError::assert($this);
		return $this->params->Get($key,$default);
	}
	
	/**
	 * Gets the title of the invoice set in the parameters
	 * 
	 * @see PayplansIfaceApiInvoice::getTitle()
	 * 
	 * @return string  Title of the invoice
	 */
	public function getTitle()
	{
		return $this->params->get('title', XiText::_('COM_PAYPLANS_DEFAULT_TITLE'));
	}
	
	public function getPrice($number = 1)
	{
		$counter = $this->getCounter();
		if($number >= $counter){
			$number = $number - $counter + 1;
		}

		$type = $this->isRecurring();
		
		if(in_array($type, array(PAYPLANS_RECURRING_TRIAL_1, PAYPLANS_RECURRING_TRIAL_2)) && $number == 1){
			$priceParam = 'trial_price_1';
		}
		elseif($type == PAYPLANS_RECURRING_TRIAL_2 && $number == 2){
			$priceParam = 'trial_price_2';
		}
		else{
			$priceParam = 'price';
		}
		
		return PayplansHelperFormat::price($this->params->get($priceParam, 0.00));
	}
	
	/**
	 * Is invoice reccuring ?
	 * 
	 * @see PayplansIfaceApiInvoice::isRecurring()
	 * 
	 * @return mixed  Integer constant if invoice is of recurring type else False
	 */
	public function isRecurring()
	{
		$expirationType = $this->params->get('expirationtype', 'forever');
		
		if('recurring' == $expirationType){
			return PAYPLANS_RECURRING;
		}
		
		if('recurring_trial_1' == $expirationType){
			return PAYPLANS_RECURRING_TRIAL_1;
		}
		
		if('recurring_trial_2' == $expirationType){
			return PAYPLANS_RECURRING_TRIAL_2;
		}
		
		return false;
	}
	
	/**
	 * Gets the expiration time of the invoice
	 * 
	 * Invoice has expiration time of its own.
	 * Initially its copied from subscription parameter and can be changed later 
	 * 
	 * @param integer $for  An integer constant indicating expiration type 
	 * 
	 * @return array  An array containing expiration values as string for year|month|day|hours|minute|seconds, two digit each. 
	 * 				  equals to 12 digit
	 */
	public function getExpiration($for = PAYPLANS_SUBSCRIPTION_FIXED, $raw = false)
	{
		if($for === PAYPLANS_RECURRING_TRIAL_1){
			$rawTime = 'trial_time_1';
		}
		elseif($for === PAYPLANS_RECURRING_TRIAL_2){
			$rawTime = 'trial_time_2';
		}else{
			$rawTime = 'expiration';
		}
		
		if($raw == true){
			return $this->params->get($rawTime, '000000000000');
		}
		
		return PayplansHelperPlan::convertIntoTimeArray($this->params->get($rawTime, '000000000000'));
	}
	
	/**
	 * Gets the expiration type of the invoice
	 * @return string fixed / recurring / recurring_trial_1 / recurring_trial_2 / forever
	 */
	public function getExpirationType()
	{
		return $this->params->get('expirationtype', 'fixed');
	}
	
	/** 
	* return current invoice expiration time
	* @return Array with indexes as year/month/day/hour/minute/second
	*/
	public function getCurrentExpiration($raw = false)
	{
		$invoiceNumber = $this->getCounter();
		$type 		   =  $this->isRecurring();
		
		// in case of recurring trial to, still current expiration time should be trial time 1
		// trial time 2 is copied to next invoice's trial 1, so no need to return trial time 2
		if($type === PAYPLANS_RECURRING_TRIAL_1 || $type === PAYPLANS_RECURRING_TRIAL_2){
			$rawTime = 'trial_time_1';
		}
		else{
			$rawTime = 'expiration';
		}
		
		if($raw == true){
			return $this->params->get($rawTime, '000000000000');
		}
		
		return PayplansHelperPlan::convertIntoTimeArray($this->params->get($rawTime, '000000000000'));
	}
	
	/**
	 * Gets the recurrence count of the invoice
	 *    i.e. How many times payment need to be done.
    	 *    Special Case : 0 = Lifetime
	 *
	 * @return integer
	 */
	public function getRecurrenceCount()
	{
		return $this->params->get('recurrence_count');
	}
	
	/**
	 * Gets the last modification date of the invoice
	 * 
	 * @return object  XiDate
	 */
	public function getModifiedDate()
	{
		return $this->modified_date;
	}
	
	/**
	 * Gets the invoice status name
	 * @return  string  The invoice status name
	 */
	public function getStatusName()
	{
		return XiText::_('COM_PAYPLANS_STATUS_'.PayplansStatus::getName($this->status));
	}	
	
	/**
	 * Gets the type of the object which has created the invoice
	 * 
	 * @return string
	 */
	public function getObjectType()
	{
		return $this->object_type;
	}
	
	/**
	 * Gets the creation date of the invoice
	 * 
	 * @return object  XiDate
	 */
	public function getCreatedDate()
	{
		return $this->created_date;
	}
	
	/**
	 * Gets the object id of the invoice
	 * 
	 * Object id is the identifier which has created invoice
	 * 
	 * @return integer
	 */
	public function getObjectId()
	{
		return $this->object_id;
	}
	
	/**
	 * 
	 * Gets all the parameters of the invoice
	 * 
	 * @return object XiParameter
	 */
	public function getParams()
	{
		return $this->params;
	}
	
	/**
	 * Gets the regular amount including tax and discount
	 * 
	 * In terms of recurring, Regular amount is the one 
	 * which will be charged regularly after all the applicable trials
	 *  
	 * @return float
	 */
	public function getRegularAmount()
	{
		$recurring = $this->isRecurring();
		
		if($recurring){
			$counter = $this->getCounter();

			if($recurring == PAYPLANS_RECURRING_TRIAL_2){
				$regularAmount = $this->getTotal($counter+2);
			}
			
			elseif($recurring == PAYPLANS_RECURRING_TRIAL_1){
				$regularAmount = $this->getTotal($counter+1);
			}
			
			else {
				$regularAmount = $this->getTotal();
			}
		}
		
		else {
			$regularAmount = $this->getTotal();
		}
		
		return $regularAmount;
	}

	/**
	 * Terminate the invoice
	 * 
	 * Order is terminated by executing terminate on invoice object
	 *  Related Payment App is asked to terminate the recurring payments, if any.
	 * 
	 * @return array  boolean values indicating the output returned from event trigger
	 */
	public function terminate()
	{
		$payment = $this->getPayment();
		$args = array($payment, $this);
		return PayplansHelperEvent::trigger('onPayplansPaymentTerminate', $args, 'payment', $payment);
	}
	
	/**
	 * Request the payment either from payment gateway or 
	 * consume wallet balance for executing futher recurring cycle
	 *  
	 * @param   integer $invoiceCount  Invoice counter specifies the invoice number to be processed further
	 * @return  mixed  value returned from payment gateway app after processing recurring cycle
	 */
	public function requestPayment($invoiceCount = 1)
	{		
		//XITODO : Generate Error logs for proper debugging.
		$payment = $this->getPayment();
		if(!isset($payment) || empty($payment)){
			return true;
		}
		$instance = $payment->getApp(PAYPLANS_INSTANCE_REQUIRE);
		if(method_exists($instance, 'processPayment')){
			$instance->processPayment($this->getPayment(), $invoiceCount);
			
			//if payment is done through payment gateway then always returen true
			//to indicate that its not needed to deduct amount from wallet
			return true;
		}

		// error in processing
		return false;
	}
	
	/**
	 * Adds a transaction on the invoice
	 * 
	 * 1. Finds payment gateway for invoice (default to admin payment)
	 * 2. Adds a transaction on the payment record
	 * 
	 * @see PayplansIfaceApiInvoice::addTransaction()
	 * 
	 * @param   $parameters  object of stdClass
	 * 
	 * @return  object       PayplansTransaction
	 */
	public function addTransaction($parameters='')
	{
		$payment = $this->getPayment(PAYPLANS_INSTANCE_REQUIRE);
		
		if(!$payment){
			//create payment first then add transaction
			$adminApps = array_shift(XiFactory::getInstance('app', 'model')
											->loadRecords(array('type'=>'adminpay', 'published'=> 1)));

			$appId   = $adminApps->app_id;
			$payment = $this->createPayment($appId);
		}
		
		$txn_parameters = array('invoice_id' 	 => $this->getId(),
								'user_id'	 	 => $this->getBuyer(),
								'amount'		 => $this->getTotal(),
								'payment_id'	 => $payment->getId(),
								'message'		 => 'COM_PAYPLANS_TRANSACTION_ADDED_FOR_INVOICE',
								'params'		 => '');
		
		$transaction = PayplansTransaction::getInstance();

		foreach ($txn_parameters as $key => $value){
			$var_name = 'transaction_'.$key;
			$transaction->set( $key, isset($parameters->$var_name)? $parameters->$var_name : $value);
			//set params as ini
			if($key == 'params'){
				$data = isset($parameters->$var_name)? $parameters->$var_name : $value;
				$transaction->set($key, is_array($data)? PayplansHelperParam::arrayToIni($data) : $data);
			}
		}
								
		$transaction->save();
		$this->_transactions = $transaction;

		return $transaction;
	}

	/**
	 * add modifier by considering the given params
	 * 1. Create Modifier as per params
         * 2. Attach to current invoice.
	 *
	 * @see PayplansIfaceApiInvoice::addModifier()
	 * 
	 * @param $params : object of stdClass
	 */
	function addModifier($params = '')
	{
         $modifier         = PayplansModifier::getInstance();
         $modifier_columns = array('message'   => XiText::_('COM_PAYPLANS_INVOICE_FREE_COMPLETION'),
         						   'invoice_id'=> $this->getId(),
                                   'user_id'   => $this->getBuyer(),
         						   'type'      => 'admin-discount',
         						   'amount'    => 0.00,
         						   'reference' => 'admin-discount',
         						   'percentage'=> false,
                                   'frequency' => PayplansModifier::FREQUENCY_ONE_TIME,
          						   'serial'    => PayplansModifier::FIXED_DISCOUNT );
         
         foreach ($modifier_columns as $column => $value){
         	$modifier->set( $column, isset($params->$column)? $params->$column:$value );
         }

		 $modifier->save();
         $this->refresh();
         return $modifier;
	}
	
	public function emaillink()
	{
		$to 	    = $this->getBuyer(PAYPLANS_INSTANCE_REQUIRE)->getEmail();
		$subject	= XiText::_('COM_PAYPLANS_INVOICE_EMAIL_LINK_SUBJECT');
		$body	    = XiText::_('COM_PAYPLANS_INVOICE_EMAIL_LINK_BODY');	
		$mailBody   = PayplansFactory::getRewriter()->rewrite($body, $this);
		$mailer  	= XiFactory::getMailer();
		$mailer->setSubject($subject);
		$mailer->setBody($mailBody);
		$mailer->addRecipient($to);

		
		if($mailer->Send() instanceof JException){
			$message=XiText::_('COM_PAYPLANS_EMAIL_SENDING_FAILED');
            PayplansHelperLogger::log(XiLogger::LEVEL_INFO, $message, 'PayplansInvoice', $mailBody);
			return false;
		}
		$message=XiText::_('COM_PAYPLANS_EMAIL_SEND_SUCCESSFULLY');
        PayplansHelperLogger::log(XiLogger::LEVEL_INFO, $message,'PayplansInvoice', $mailBody);
		return true;
	}

	public function displayRefundButton(PayplansPayment $payment)
	{
		$refenceObj = $this->getReferenceObject(PAYPLANS_INSTANCE_REQUIRE);
		$order   	= ($refenceObj instanceof PayplansOrder)? $refenceObj : null;
		
		if(empty($order)){
			return false;
		}
		
		$app 	 	= $payment->getApp(PAYPLANS_INSTANCE_REQUIRE);
					
		if($app->supportForRefund() && $order->getStatus() == PayplansStatus::ORDER_COMPLETE){
			return true;
		}
		
		return false;
	}
}


class PayplansInvoiceFormatter extends PayplansFormatter
{
	function getIgnoredata()
	{
		$ignore = array('_trigger', '_component', '_errors', '_name', '_blacklist_tokens','_transactions');
		return $ignore;
	}
	
	function getVarFormatter()
	{
		$rules = array('params'        => array('formatter'=> 'PayplansFormatter',
										       'function' => 'getFormattedParams'));
		return $rules;
		
	}

}
