<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansOrder extends XiLib
	implements PayplansIfaceApptriggerable, PayplansIfaceApiOrder, PayplansIfaceMaskable, PayplansIfaceDiscountable
{
	// Table fields
	protected $order_id;
	protected $buyer_id;
	protected $total;
	protected $currency;
	protected $status;
	protected $created_date;

	//secondary information
	protected $_subscription	= null;
	protected $_invoices		= array();
	
	protected $params;

	/**
	 * @return PayplansOrder
	 * @param string $dummy is added just for removing warning with development mode(XiLib::getInstance is having 4 parameters)
	 */
	static public function getInstance($id=0, $type=null, $bindData=null, $dummy=null)
	{
		return parent::getInstance('order',$id, $type, $bindData);
	}

	// 	not for table fields
	public function reset(Array $config=array())
	{
		$this->order_id	= 0;
		$this->buyer_id	= 0;
		$this->total	= 0.0000;

		// Load default currency from configuration
		$this->currency 	= XiFactory::getConfig()->currency;
		$this->status		= PayplansStatus::NONE;

		//XITODO : Is it ok to store current timestamp ?
		$this->created_date = new XiDate();

		//clean all subscriptions stored
		$this->_subscription	= null;
		$this->_invoices		= array();
		$this->params			= new XiParameter();
		
		return $this;
	}

	protected function _loadSubscription($order_id)
	{
		// get all subscription records of this order
		$subRecords = XiFactory::getInstance('subscription','model')
								->loadRecords(array('order_id'=>$order_id));

		$this->_subscription   = null;
		if(!empty($subRecords)){
			$record = array_shift($subRecords);
			$this->_subscription = PayplansSubscription::getInstance( $record->subscription_id, null, $record);
			$this->total = $this->_subscription->getPrice();
		}

		return $this;
	}

	protected function _loadInvoices($order_id)
	{
		// get all subscription records of this order
		$records = XiFactory::getInstance('invoice', 'model')
							->loadRecords(array('object_id'=>$order_id, 'object_type' => get_class($this)));

		$this->_invoices	= array();

		foreach($records as $record){
			$this->addInvoice(PayplansInvoice::getInstance( $record->invoice_id, null, $record));
		}

		return $this;
	}
	
	public function afterBind($id = 0)
	{
		if(!$id) return $this;

		//load dependent records
		return $this->_loadSubscription($id)->_loadInvoices($id);
	}

	//Invoice records of orders
	public function addInvoice(PayplansInvoice $item)
	{
		// save it on payment list
		$this->_invoices[$item->getId()]=$item;
		return $this;
	}

	/**
	 * Gets the invoices attached on the order
	 * If status is null then return all the attached invoices
	 * 
	 * @param integer|array $status  Status of the invoice to be get
	 * 
	 * @return array  Array of PayplansInvoice
	 */
	public function getInvoices($status = null)
	{
		$invoices = array();
		if($status === null){
			return $this->_invoices;
		}

		$status = is_array($status) ? $status : array($status); 
		foreach ($this->_invoices as $invoice){
			if(in_array($invoice->getStatus(), $status)){
				$invoices[$invoice->getId()] = $invoice ;
			}
		}

		return $invoices;
	}
	
	/**
	 * Gets the invoice attached on the order with specified counter
	 * 
	 * @param integer $counter  Counter of the invoice to be get
	 * 
	 * @return  mixed  Object if invoice with the specified counter exists else retuen false
	 */
	public function getInvoice($counter = null)
	{
		$invoices = $this->getInvoices();
		
		if($counter == null){
			return array_shift($invoices);
		}
		
		foreach($invoices as $invoice){
			if($invoice->getCounter() == $counter){
				return $invoice;
			}
		}
		
		// no invoice exists with this number
		return false;
	}
	
	private function _createFirstInvoice()
	{
		$invoice = PayplansInvoice::getInstance();
		$invoice->set('object_id', $this->getId())
				->set('object_type', get_class($this))
				->set('user_id', $this->getBuyer())
				->set('counter', 1)
				->set('currency', $this->getCurrency('isocode'));

		$amount = $this->_subscription->getPriceForInvoice(1);
		$invoice->set('subtotal', $amount);
		// set some params
		$subParams 	= $this->getSubscription()->getParams()->toArray();
		$params = array('expirationtype', 'expiration', 'recurrence_count', 'price',
					'trial_price_1', 'trial_time_1', 'trial_price_2', 'trial_time_2');
		foreach($params as $param){
			if(isset($subParams[$param])){
				$invoice->setParam($param, $subParams[$param]);
			}
		}
		
		$invoice->setParam('title', $this->getTitle());
		return $invoice;
	}
	
	private function _createChildInvoice($invoiceCount)
	{
		$invoice = PayplansInvoice::getInstance();
		$invoice->set('object_id', $this->getId())
				->set('object_type', get_class($this))
				->set('user_id', $this->getBuyer())
				->set('counter', $invoiceCount + 1)
				->set('currency', $this->getCurrency('isocode'));
				
		// get the last invoice
		$masterInvoice = $this->getInvoice($invoiceCount);
		if(!($masterInvoice instanceof PayplansInvoice))
		{ 
			return false;
		}	
		$amount = $masterInvoice->getPrice($invoiceCount+1);
		$invoice->set('subtotal', $amount);

		$params = $masterInvoice->getParams();
		// set some params
		$invoice->set('params', $params);
		
		$recurring = $masterInvoice->isRecurring();
		if($recurring){
			// XITODO : use Data structure instead of of if else
			// like load the expiration type and then remve the frist element, and use next element
			// like trial_time_11, 10,9,8,7,6....and so on
			$expirationType = 'recurring';
			if($recurring == PAYPLANS_RECURRING_TRIAL_2){
				$expirationType = 'recurring_trial_1';
				$invoice->setParam('trial_price_1', $masterInvoice->getParam('trial_price_2', '0.00'));
				$invoice->setParam('trial_time_1', $masterInvoice->getParam('trial_time_2', '000000000000'));
			}
			
			$invoice->setParam('expirationtype', $expirationType);		
		}
		$invoice->setParam('title', $this->getTitle());
		return $invoice;
	}
	
	/**
	 * Create a new invoice on the order
	 * 
	 * @return object PayplansInvoice
	 */
	public function createInvoice()
	{
		$invoices     = $this->getInvoices(array(PayplansStatus::INVOICE_PAID, PayplansStatus::INVOICE_REFUNDED));
		$invoiceCount = count($invoices);
				
		if(empty($this->_subscription)){
			$invoice = $this->createEmptyInvoice();
		}
		else{
			// if order is creating the first invoice
			if($invoiceCount === 0){
				$invoice = $this->_createFirstInvoice();
			}
			else{
				sort($invoices);
				$lastInvoice       = array_pop($invoices);
				$invoiceCount      = $lastInvoice->getCounter(); 
				
				$invoice = $this->_createChildInvoice($invoiceCount);
				if(!($invoice instanceof PayplansInvoice))
				{
					return false;
				}
			}
		}		
		
		// Total need to be updated
		$invoice->refresh()->save();
				
		//also add into payment list of order
		$this->addInvoice($invoice);
		
		// invoice is created
		// check order have the first invioce id or not
		// if not then set it and save
		if($this->getFirstInvoice() == false){
			$this->setParam('first_invoice_id', $invoice->getId());
			$this->setParam('last_master_invoice_id', $invoice->getId());
		}

		$this->save();
		
		// explicitly refreshed the already cached object.
		PayplansOrder::getInstance($this->getId())->refresh();
		
		
		return $invoice;
	}
	
	/**
	 * Create an invoice with default properties
	 * @deprecated 2.2
	 */
	protected function createEmptyInvoice()
	{
		$invoiceCount = count($this->getInvoices(array(PayplansStatus::INVOICE_PAID, PayplansStatus::INVOICE_REFUNDED)));

		// $invoice count should be incremented by one
		$invoiceCount++;
		$invoice = PayplansInvoice::getInstance();
		$invoice->set('object_id', $this->getId())
			->set('object_type', get_class($this))
			->set('user_id', $this->getBuyer())
			->set('counter', $invoiceCount)
			->set('currency', $this->getCurrency('isocode'));
				
		$params 	= array('expirationtype' => 'None',
									 'expiration' => '000000000000', 
									 'recurrence_count' => 0, 
									 'price' => 0.00,
									 'trial_price_1' => 0.00, 
									 'trial_time_1' => '000000000000', 
									 'trial_price_2' => 0.00, 
									 'trial_time_2' => '000000000000',
									 'title' => XiText::_('COM_PAYPLANS_DEFAULT_TITLE'));
			
		foreach($params as $param=>$value){
				$invoice->setParam($param, $value);
		}
		
		return $invoice;
	}
	
	/**
	 * Load all the subscription and invoices attached on the order
	 * @return object PayplansOrder
	 */
	public function refresh()
	{
		// get all subscription records of this order
		$this->_loadSubscription($this->getId());
		$this->_loadInvoices($this->getId());
	
		// save update order
		return $this;
	}

	/**
	 * Is recurring Order?
	 * 
	 * @return integer when subscription attached with the order is of recurring/recurring+trial type else return False
	 */
	public function isRecurring()
	{
		return $this->getSubscription()->isRecurring();
	}
	
	/**
	 * Renew the subscription 
	 * Extend the subscription time to the specified time
	 * 
	 * @param string $expiration  12 digits numeric string each 2 digits denotes the value for year,
	 *  month, day, minute, hour and second in the same sequence, starting from year(starting 2 digits indicate year)
	 *  
	 *  @return object PayplansOrder
	 */
	function renewSubscription($expiration)
	{
		$subscription = $this->_subscription;
		
		//when there is no subscription exists
		if(empty($subscription)){
			return $this;
		}

		$this->_subscription->renew($expiration);
		
		return $this;
	}
	
	/**
	 * Gets the buyer of the order
	 * 
	 * @see PayplansIfaceApiOrder::getBuyer()
	 * 
	 * @param boolean $requireinstance  If True return PayplansUser instance else Userid 
	 * 
	 *  @return mixed Userid or PayplansUser attached with the order
	 */
	public function getBuyer($requireinstance=false)
	{
		if($requireinstance == PAYPLANS_INSTANCE_REQUIRE){
			return PayplansUser::getInstance($this->buyer_id);
		}

		return $this->buyer_id;
	}
	
	/**
	 * Sets the buyer for the order
	 * 
	 * @param integer $userId  UserId to which the order will be attached
	 * 
	 * @return PayplansOrder
	 */
	public function setBuyer($userId=0)
	{
		$this->buyer_id = $userId;

		// update subscription also
		if($this->_subscription !== null){
			$this->_subscription->setBuyer($userId);
		}

		return $this;
	}
	
	/**
	 * Gets the status of the Order
	 * 
	 * @see PayplansIfaceApiOrder::getStatus()
	 * 
	 * @return integer  The order status
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * Sets the status of the order
	 * 
	 * @param integer  $status   The value of the status 
	 * 
	 *  @return object PayplansOrder
	 */
	public function setStatus($status)
	{
		$this->status = $status;
		return $this;
	}
	
	/**
	 * Gets the status name
	 * @return string  The order status name
	 */
	public function getStatusName()
	{
		return XiText::_('COM_PAYPLANS_STATUS_'.PayplansStatus::getName($this->status));
	}

	/**
	 * Gets the total of the order
	 *
	 * Order total is exclusive of discount or 
	 * tax or any other kind of amount modification.
	 * 
	 * @see PayplansIfaceApiOrder::getTotal()
	 * 
	 * @return float  Total of the order
	 */
	public function getTotal()
	{
		return PayplansHelperFormat::price($this->total);
	}

 	/**
 	 * Implementing interface Apptriggerable
 	 * @return array
 	 */
 	public function getPlans($requireInstance = false)
 	{
 		if($this->_subscription !== null){
 			return $this->getSubscription()->getPlans($requireInstance);
 		}
 		
 		return array();
 	}

 	/**
 	 * Gets the subscription of the order
 	 * @return object PayplansSubscription
 	 */
 	public function getSubscription()
 	{
 		return $this->_subscription;
 	}
	
 	/**
 	 * Gets the currency of the order
 	 * 
 	 * @param string $format  An optional parameter to get the currency in different format.
	 * Available formats are isocode, symbol, fullname
	 * 
	 * @return  currency of the order
 	 */
	public function getCurrency($format = null)
	{
		return PayplansHelperFormat::currency(XiFactory::getCurrency($this->currency), array(), $format);
	}
	
	
	/**
	 * Gets the created date of the order
	 * 
	 * @see PayplansIfaceApiOrder::getCreatedDate()
	 * 
	 * @return object XiDate
	 */
	public function getCreatedDate()
	{
		return $this->created_date;
	}
	
	/**
	 * Modifies a key of the order, creating it if it does not already exist.
	 *
	 * @param   string  $key      The name of the key.
	 * @param   mixed   $value    The value of the key to set.
	 *
	 * @return  object  PayplansOrder
	 */
	public function setParam($key, $value)
	{
		XiError::assert($this);
		$this->getParams()->set($key,$value);
		return $this;
	}
	
	/**
	 * Returns a key of the order object or the default value if the key is not set.
	 *
	 * @param string  $key       The name of the property.
	 * @param mixed   $default   The default value.
	 * 
	 * @return  mixed   The value of the key.
	 */
	public function getParam($key,$default=null)
	{
		XiError::assert($this);
		return $this->getParams()->Get($key,$default);
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
	 * Gets the title of the order (title of the subscription attached with the order)
	 * 
	 * @return string  Title
	 */
	public function getTitle()
	{
		return $this->getSubscription()->getTitle();
	}
	
	/**
	 * Gets the expiration time from the attached subscription record
	 * 
	 * Order does not have any expiration time of its owm. 
	 * Its attached subscription has expiration time and type related data.
	 * 
	 * @param   integer $for An integer constant indicating expiration type 
	 * 
	 * @return  array   An array containing expiration values for year, month, day and so on 
	 */
	public function getExpiration($for = PAYPLANS_SUBSCRIPTION_FIXED )
	{
		return $this->getSubscription()->getExpiration($for);
	}
	
	/**
	 * Gets the recurrence count of the order
	 * 
	 * @return integer  Recurrence count value of the subscription attached with the order
	 */
	public function getRecurrenceCount()
	{
		// XITODO: handle when no subscription exists for all the functions
		return $this->getSubscription()->getRecurrenceCount();
	}
	
	/**
	 * Gets the price of the order
	 * @see components/com_payplans/libraries/iface/PayplansIfaceOrderable::getPrice()
	 * 
	 * If type is not set then return the regular/normal price
	 * if it is set to RECURRING_TRIAL_1 then return first trial price 
	 * if it is set to RECURRING_TRIAL_2 then return second trial price
	 * 
	 * @param integer $for  A constant indicating expiration type
	 * 
	 * @return float  Value of the price
	 */
	public function getPrice($for = PAYPLANS_SUBSCRIPTION_FIXED)
	{
		$subscription = $this->getSubscription();
		//when there is no subscription exists
		if(!$subscription){
			return false;
		} 
		
		return $subscription->getPrice($for);
	}
	
	/**
	 * Refund the subscription amount and mark order on Hold
	 * 
	 * @return object PayplansOrder
	 */
	public function refund()
	{
		$this->_subscription->refund();
		$this->set('status', PayplansStatus::ORDER_HOLD)
			 ->save();
			 
		return $this;
	}
	
	/**
	 * Terminate/cancel the order
	 * 
	 * Termination is applicable on recurring orders only.
	 *
	 * @return array  boolean values indicating the output returned from event trigger
	 */
	public function terminate()
	{
		$invoice = $this->getLastMasterInvoice(PAYPLANS_INSTANCE_REQUIRE);
		
		//if last_master_invoice id is not set
		if(!$invoice){
			$invoice = $this->getFirstInvoice(PAYPLANS_INSTANCE_REQUIRE);
		}
		
		if(!$invoice){
			$invoices = $this->getInvoices();
			$invoice  = array_shift($invoices);
		}
		
		if (!$invoice){
			return false;
		}
		
		return $invoice->terminate();
	}
	
	/**
	 * Gets the first invoice id on which payment has been received for the order
	 * 
	 * @param boolean $instanceRequire  If true return the PayplansInvoice instance else invoice id
	 * 
	 * @return mixed  Invoice id of the order or PayplansInvoice instance for the invoice id
	 */
	public function getFirstInvoice($instanceRequire = false)
	{
		$id = $this->getParam('first_invoice_id', 0);
		if(!$id){
			return false;
		}
		
		if($instanceRequire == PAYPLANS_INSTANCE_REQUIRE){
			return PayplansInvoice::getInstance($id);
		}
		
		return $id;
	}
	
	/**
	 * Gets the last invoice id on which payment has been received for the order
	 * 
	 * @param boolean $instanceRequire  If true return the PayplansInvoice instance else invoice id
	 * 
	 * @return mixed  Invoice id of the order or PayplansInvoice instance for the invoice id
	 */
	public function getLastMasterInvoice($instanceRequire = false)
	{
		$id = $this->getParam('last_master_invoice_id', 0);
		
		$invoices = $this->getInvoices();
		$invoice  = array_shift($invoices);

		if($id){
			$invoice = PayplansInvoice::getInstance($id);
		}
		
		if(!$invoice){
			return false;
		}
		
		if($instanceRequire == PAYPLANS_INSTANCE_REQUIRE){
			return $invoice;
		}
		
		return $invoice->getId();
	}
	
	/**
	 * Gets the object link to be displayed on Invoice edit screen
	 * 
	 * @return string  Url link of the attached subscription record
	 */
	public function getObjectLink()
	{
		$subscription = $this->getSubscription();
		if(empty($subscription)){
			return XiText::_('COM_PAYPLANS_INVOICE_EDIT_OBJECT_DELETED');
		} 
		
		return PayplansHtml::link(XiRoute::_("index.php?option=com_payplans&view=subscription&task=edit&id=".$subscription->getId(), false),$subscription->getId().'('.XiHelperUtils::getKeyFromId($subscription->getId()).')');
	}
	
	/**
	 * Gets the count of the completed recurrence cycle
	 * @return integer  Number indicating completed recurring cycle
	 */
	public function getRecurringInvoiceCount()
	{
		$status = array(PayplansStatus::INVOICE_PAID, PayplansStatus::INVOICE_REFUNDED);
		
		// get counter of last master invoice
		$last_master_invoice = $this->getLastMasterInvoice(PAYPLANS_INSTANCE_REQUIRE);
		$counter = 0;
		if($last_master_invoice){
			$counter = $last_master_invoice->getCounter();
		}
		
		$totalInvoices    = $this->getInvoices($status); 
		sort($totalInvoices);
		$lastInvoice      = array_pop($totalInvoices);
		$lastCounter      = $lastInvoice->getCounter(); 

		return $lastCounter - ($counter - 1);
	}
}

class PayplansOrderFormatter extends PayplansFormatter
{
	function getIgnoredata()
	{
		$ignore = array('_trigger', '_component', '_errors', '_name', '_invoices');
		return $ignore;
	}
	
	function getVarFormatter()
	{
		$rules = array('buyer_id'       => array('formatter'=> 'PayplansUserFormatter',
										         'function' => 'getBuyerName'),
						'_subscription' => array('formatter'=> 'PayplansSubscriptionFormatter',
										         'function' => 'getSubscriptionDetails'),
						'params'      => array('formatter'=> 'PayplansFormatter',
										       'function' => 'getFormattedParams')
						);
		return $rules;
	}
	
	// get name of order status
	function getOrderStatusName($key,$value,$data)
	{
		$status = array();
		foreach ($value as $v)
		{
			$status[] = PayplansStatus::getName($v);
		}	
		$value = $status;
	}
}

