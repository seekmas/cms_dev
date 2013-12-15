<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansSubscription extends XiLib
	  implements PayplansIfaceApptriggerable, 
	  			 PayplansIfaceApiSubscription,
	  			 PayplansIfaceMaskable
{
	// Table fields
	protected $subscription_id;
	protected $order_id;
	protected $user_id;
	protected $plan_id;
	protected $status;
	protected $total;
	/**
	 * @var XiDate
	 */
	protected $subscription_date;
	/**
	 * @var XiDate
	 */
	protected $expiration_date;
	/**
	 * @var XiDate
	 */
	protected $cancel_date;
	/**
	 * @var XiParameter
	 */
	protected $params;


	// Fields not related to tables
	/**
	 * @var PayplansPlan
	 */
	private $_plan ;	
	
	// skip these tokens in token rewriter
	public  $_blacklist_tokens = array('params'); 
	/**
	 * @return PayplansSubscription
	 * @param string $dummy is added just for removing warning with development mode(XiLib::getInstance is having 4 parameters)
	 */
	static public function getInstance($id=0, $type=null, $bindData=null, $dummy=null)
	{
		return parent::getInstance('subscription',$id, $type, $bindData);
	}
	
	// not for table fields
	public function reset(Array $option=array())
	{
		$this->subscription_id 	= 0;
		$this->order_id 		= 0;
		$this->user_id 			= 0;
		$this->plan_id 			= 0;
		$this->total 			= 0.0000;
		$this->status			= PayplansStatus::NONE;
		$this->subscription_date= new XiDate('0000:00:00 00:00:00');
		$this->expiration_date	= new XiDate('0000:00:00 00:00:00');
		$this->cancel_date		= new XiDate('0000:00:00 00:00:00');
		$this->params			= new XiParameter();

		return $this;
	}

	public function save()
	{
		// if subscription status is active and expiration time is not set/valid
		// it means activate subscription now
		if($this->getStatus() == PayplansStatus::SUBSCRIPTION_ACTIVE 
			&& ($this->getSubscriptionDate()->toMySql() == null
					&& $this->getExpirationDate()->toMySql() == null)){
			
			$this->subscription_date= new XiDate();
			
			//add expiration to current timestamp
			$dateExp	= 	new XiDate();
			$plan = array_pop($this->getPlans(PAYPLANS_INSTANCE_REQUIRE));
			$this->expiration_date	= $dateExp->addExpiration($plan->getRawExpiration());
			
		}
		
		
		//let it saves
		return parent::save();
	}

	public function afterBind($id = 0)
	{
		if(!$id) return $this;
		
		//load dependent Plan records
		$this->_plan = PayplansPlan::getInstance($this->plan_id);
		return $this;
	}

	/**
	 * Sets the plan to the subscription
	 * 
	 * Change the subscription parameter as per the plan parameters.
	 * 
	 * @see PayplansIfaceApiSubscription::setPlan()
	 * 
	 * @param object $plan  Payplansplan
	 * @return object PayplansSubscription
	 */
	public function setPlan($plan)
	{
		XiError::assert($plan);
		
		//support passing a plan-id.
		if(is_a($plan,'PayplansPlan') === false){
			$plan = PayplansPlan::getInstance($plan);
		}
		
		// no need to reset all time, for edit it will reset id, which is not desirable
		//$this->reset();

		// add basic data
		$this->plan_id 		= $plan->getId();
		$this->title		= $plan->getTitle();
		$this->status		= PayplansStatus::NONE;
		$this->cancel_date	= null;

		// current timestamp
		$this->subscription_date= new XiDate('0000:00:00 00:00:00');
		$this->expiration_date = new XiDate('0000:00:00 00:00:00');
		
		// set time params of Plan to params of subscription
		$this->params->loadArray($plan->getDetails()->toArray());
		$this->total  = $this->getTotal();

		$this->_plan	= $plan;
		return $this;
	}

	/**
	 * Gets the title of the subscription
	 * Subscription title is the title of the plan
	 *   
	 * @see PayplansIfaceApiSubscription::getTitle()
	 * @return string 
	 */
	public function getTitle()
	{
		//plan-modifier can change the title so if title is set then return that else plan title
		$title = $this->params->get('title', '');
		
		if(!empty($title)){
			return $title;
		}
		
		if(!isset($this->_plan))
			return '';

		if($this->_plan == false)
			return XiText::_("COM_PAYPLANS_SUBSCRIPTION_PLAN_DOES_NOT_EXIST");
			
		return $this->_plan->getTitle();
	}

	/**
	 * returns the price of subscription
	 * @see PayplansIfaceApiSubscription::getPrice()
	 * @param $type
	 * if type is not set then return the regular/normal price
	 * if it is set to RECURRING_TRIAL_1 then return first trial price 
	 * if it is set to RECURRING_TRIAL_2 then return second trial price 
	 */
	public function getPrice($type = PAYPLANS_SUBSCRIPTION_FIXED)
	{
		if($type === PAYPLANS_RECURRING_TRIAL_1){
			return PayplansHelperFormat::price($this->params->get('trial_price_1', 0.00));
		}
		
		if($type === PAYPLANS_RECURRING_TRIAL_2){
			return PayplansHelperFormat::price($this->params->get('trial_price_2', 0.00));
		}
		
		return PayplansHelperFormat::price($this->params->get('price', 0.00));
	}
	
	/**
	 * Sets the price of the subscription
	 * Change the price parameter of the subscription
	 * Subscription total will be update to the value passed
	 *  
	 * @param float  $price  Value of the price
	 * 
	 * @return object PayplansSubscription
	 */
	public function setPrice($price)
	{
		$this->params->set('price', $price);
		//also update total
		$this->getTotal();
		return $this;
	}
	
	/**
	 * Gets the total amount of the subscription
	 * Subscription total is exclusive of tax and discount.
	 * 
	 * @see PayplansIfaceApiSubscription::getTotal()
	 * 
	 * @return float  Value of the total
	 */
	public function getTotal()
	{
		//always ensure it to be calculated
		$this->total = $this->getPrice();
		return PayplansHelperFormat::price($this->total);
	}
	
	/**
	 * Gets the status of the subscription
	 * 
	 * @see PayplansIfaceApiSubscription::getStatus()
	 * 
	 * @return integer  Value of the status
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * Sets the status of the subscription.
	 * Available subscription status are active, expired, hold.
	 * 
	 * @see PayplansStatus
	 * 
	 * @param integer  $status  Value of the status
	 * @return object PayplansSubscription 
	 */
	public function setStatus($status)
	{
		$this->status = $status;
		return $this;
	}

	/**
	 * Sets the buyer for the subscription
	 * @see PayplansIfaceApiSubscription::setBuyer()
	 * 
	 * @param  integer $userId  UserId to which the subscription will be attached
	 * @return object  PayplansSubscription
	 */
	public function setBuyer($userId=0)
	{
		$this->user_id = $userId;
		return $this;
	}

	/**
	 * Gets the buyer of the subscription
	 * 
	 * @see PayplansIfaceApiSubscription::getBuyer()
	 * 
	 * @param boolean  $requireinstance  Optional parameter, If true return PayplansUser instance else userid
	 * 
	 * @return mixed Userid or instance of PayplansUser attached with the subscription 
	 */
	public function getBuyer($requireinstance=false)
	{
		if($requireinstance == PAYPLANS_INSTANCE_REQUIRE){
			return PayplansUser::getInstance($this->user_id);
		}

		return $this->user_id;
	}

	/**
	 * (non-PHPdoc)
	 * @see PayplansIfaceApiSubscription::setOrder()
	 * @return PayplansSubscription
	 */
	public function setOrder(PayplansOrder $order)
	{
		return $this->setBuyer($order->getBuyer())
			 		->set('order_id', $order->getId())
			 		->save();
	}

	/**
	 * Implementing interface Apptriggerable
	 * @return array
	 */
	public function getPlans($requireInstance = false)
	{
		if($requireInstance===PAYPLANS_INSTANCE_REQUIRE){
			return array(PayplansPlan::getInstance($this->plan_id));
		}
		//get all subscription's plans
		return array($this->plan_id);
	}

	/**
	 * @return PayplansOrder
	 * 
	 * (non-PHPdoc)
	 * @see components/com_payplans/libraries/iface/api/PayplansIfaceApiSubscription::getOrder()
	 */
	public function getOrder($requireinstance=false)
	{
		if($requireinstance == PAYPLANS_INSTANCE_REQUIRE){
			return PayplansOrder::getInstance($this->order_id);
		}
		
		return $this->order_id;
	}

	/**
	 * Gets the subscription date
	 * Subscription date is the activation date of the subscription
	 * 
	 * @return object  XiDate
	 */
	public function getSubscriptionDate()
	{
		return $this->subscription_date;
	}

	/**
	 * Gets the expiration date of the subscription
	 * 
	 * @return object XiDate
	 */
	public function getExpirationDate()
	{
		return $this->expiration_date;
	}

	/**
	 * Sets the expiration date of the subscription
	 * Expected parameter is an object of XiDate
	 * 12 digit rawexpiration time can also be passed.
	 * It internally converts the rawtime into its equivalant XiDate object 
	 * 
	 * @param object $date  Xidate object
	 * 
	 * @return object PayplansSubscription
	 */
	public function setExpirationDate($date)
	{
		if(!is_a($date, 'XiDate')){
			$date = new XiDate($date); 
		}
		
		$this->expiration_date= $date;
		return $this;
	}
	
	/**
	 * Gets the name of the buyer of the subscription
	 * @return string  Name 
	 */
	public function getBuyerName()
	{
		return PayplansHelperUser::getName($this->user_id);
	}

	/**
	 * Gets the username of the buyer of the subscription
	 * @return string  Username 
	 */
	public function getBuyerUsername()
	{
		return PayplansHelperUser::getUserName($this->user_id);
	}

	public function getStatusName()
	{
		return XiText::_('COM_PAYPLANS_STATUS_'.PayplansStatus::getName($this->status));
	}
	
	/*Implement API */
	/* 
	 * (non-PHPdoc)
	 * @see PayplansIfaceApiSubscription::isActive()
	 */
	public function isActive()
	{
		return ($this->status==PayplansStatus::SUBSCRIPTION_ACTIVE);	
	}
	
	/**
	 * Delete the subscription
	 * @see XiLib::delete()
	 */
	public function delete()
	{
		$order = $this->getOrder(PAYPLANS_INSTANCE_REQUIRE);
		parent::delete();
		
		if($order){
			return $order->refresh()->save();	
		}
		
		return null;
	}
	
	public function getParams()
	{
		return $this->params;
	}
	
	/**
	 * Renew the subscription
	 * 
	 * Activate the subscription and add the given 
	 * expiration time to the existing expiration time of the subscription.
	 * (Extend the sibscription if its already active)
	 * 
	 * 
	 * @param string $expiration  12 digits numeric string 
	 * each 2 digits denotes the value for year, month, day, hour, 
	 * minute and second in the same sequence, starting from year(starting 2 digits indicate year)
	 * 
	 * @return object PayplansSubscription
	 */
	public function renew($expiration)
	{
		if($this->getSubscriptionDate()->toMySql() == null){
			$this->subscription_date = new XiDate();
		}
	
		if($this->getExpirationDate()->toMySql() == null){
			$this->expiration_date = new XiDate();
		}
		
		$extend_from = $this->getExpirationDate();
		$current_date	= new XiDate('now');
		
		// extend the subscription time from the date which is greater not from the expiration date always
		// as in some cases user renew the subscription after few days of expiration 
		if($current_date->toMySQL() > $this->getExpirationDate()->toMySQL()){
			$extend_from = $current_date;
		}

		$this->expiration_date	= $extend_from->addExpiration($expiration);
		$this->set('status', PayplansStatus::SUBSCRIPTION_ACTIVE)->save();
		return $this;
	}
	
	/**
	 * Is subscription reccuring ?
	 * 
	 * @return mixed  if subscription is of recurring type 
	 *			 then Integer constant (PAYPLANS_RECURRING|PAYPLANS_RECURRING_TRIAL_1|PAYPLANS_RECURRING_TRIAL_2)
	 *		     else False
	 */
	public function isRecurring()
	{
		$expirationType = $this->getParams()->get('expirationtype', 'forever');
		
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
	 * This function will calculate the price for different invoices 
	 * Enter description here ...
	 * @param integer $invoiceNumber : this is the number of invoice for which price has been asked
	 * 								   default is 1
	 */
	public function getPriceForInvoice($invoiceNumber)
	{
		$recurringType = $this->isRecurring();
		
		// if subscription is recurring trial 1/2
		// and invoice number is 1 then return first trial price
		if((PAYPLANS_RECURRING_TRIAL_1 === $recurringType 
				|| PAYPLANS_RECURRING_TRIAL_2 === $recurringType) 
				&& $invoiceNumber === 1){
			return $this->getPrice(PAYPLANS_RECURRING_TRIAL_1);
		}
		
		// if subscription is recurring trial 2
		// and invoice number is 2 then return second trial price
		if(PAYPLANS_RECURRING_TRIAL_2 === $recurringType && $invoiceNumber === 2){
			return $this->getPrice(PAYPLANS_RECURRING_TRIAL_2);
		}
		
		// else return regular price
		return $this->getPrice();
	}
	
	/**
	 * Gets the expiration time of the subscription
	 * 
	 * @param integer $for  An integer constant indicating expiration type 
	 * 
	 * @return array  An array containing expiration values for year, month, day and so on
	 */
	public function getExpiration($for = PAYPLANS_SUBSCRIPTION_FIXED)
	{
		if($for === PAYPLANS_RECURRING_TRIAL_1){
			$rawTime = $this->getParams()->get('trial_time_1', '000000000000');
		}
		elseif($for === PAYPLANS_RECURRING_TRIAL_2){
			$rawTime = $this->getParams()->get('trial_time_2', '000000000000');
		}else{
			$rawTime = $this->getParams()->get('expiration', '000000000000');
		}
		
		return PayplansHelperPlan::convertIntoTimeArray($rawTime);
	}
	
	/**
	 * Sets the expiration time of the invoice
	 * 
	 * @param  string   $rawExpiration  12 digits numeric string each 2 digits denotes the value for year, month, day, hour, minute and second in the same sequence, starting from year(starting 2 digits indicate year)
	 * @param  integer  $for            Integer constant indicating the expiration type for which expiration time is to be set
	 * 
	 * @return object PayplansPlan
	 */
	public function setExpiration($rawExpiration, $for = PAYPLANS_SUBSCRIPTION_FIXED)
	{
		$varName = 'expiration';
		if($for === PAYPLANS_RECURRING_TRIAL_1){
			$varName = 'trial_time_1';
		}
		elseif($for === PAYPLANS_RECURRING_TRIAL_2){
			$varName = 'trial_time_2';
		}
		
		$this->getParams()->set($varName, $rawExpiration);
				
		return $this;
	}
	
	/**
	 * Gets the recurrence count of the subscription
	 * @return integer
	 */
	public function getRecurrenceCount()
	{
		return $this->getParams()->get('recurrence_count');
	}
	
	/** 
	 * Gets the currecny of the subscription
	 * 
	 * Subscription does not stores the currency in its own parameter
	 * It is saved in the attached order
	 * 
	 * @param string $format  An optional parameter to get the currency in different format.
	 * Available formats are isocode, symbol, fullname
	 * 
	 * @return  currency of the subscription
	 */
	public function getCurrency($format = null)
	{
		return $this->getOrder(PAYPLANS_INSTANCE_REQUIRE)->getCurrency($format);
	}
	
	/**
	 * Refund the subscription
	 * Mark the subscription status to refund and save
	 * 
	 * @return object PayplansSubscription
	 */
	public function refund()
	{
		return $this->set('status', PayplansStatus::SUBSCRIPTION_HOLD)
			 	    ->save();
	}
	
	/**
	 * Gets the expiration type of the subscription
	 * 
	 * @return  string 
	 */
	public function getExpirationType()
	{
		return  $this->getParams()->get('expirationtype', '');
	}
}

class PayplansSubscriptionFormatter extends PayplansFormatter
{

	function getIgnoredata()
	{
		$ignore = array('_trigger', '_component', '_errors', '_name', '_blacklist_tokens');
		return $ignore;
	}
	
	// get rules to apply on vars	
	function getVarFormatter()
	{
		$rules = array('params'      => array('formatter'=> 'PayplansFormatter',
										       'function' => 'getFormattedParams'),
						'plan_id'    =>  array('formatter'=> 'PayplansPlanFormatter',
										       'function' => 'getPlanName'));
		return $rules;
		
	}
	/**
	 * Get subscription link
	 * pass $key and $value through reference
	 * 
	 */
	function getSubscriptionDetails($key,$value,$data)
	{
		$key   = XiText::_('COM_PAYPLANS_LOG_KEY_SUBSCRIPTION');
		if(!empty($value)){
			$planName = PayplansHelperPlan::getName($value['plan_id']);
			$value = PayplansHtml::link(XiRoute::_("index.php?option=com_payplans&view=subscription&task=edit&id=".$value['subscription_id'], false), $value['subscription_id'].'('.$planName.')');
		}
	}
}
