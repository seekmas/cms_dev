<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansPlan extends XiLib
	implements PayplansIfaceApptriggerable, PayplansIfaceApiPlan
{
	// Table fields
	protected $plan_id;
	protected $title;
	protected $published;
	protected $visible;
	protected $description;
	protected $details;
	protected $params;
	protected $_planapps;
	protected $_groups;
	
	// skip these tokens in token rewriter
	public  $_blacklist_tokens = array('published','visible','params');
	
	// not for table fields
	/**
	 * reset all the Plans properties to their default values
	 */
	public function reset(Array $option=array())
	{
		$this->plan_id 			= 0;
		$this->title 			= '';
		$this->published		= 1;
		$this->visible			= 1;
		$this->description		= '';
		$this->details    		= new XiParameter();
		$this->params  			= new XiParameter();
		$this->_planapps		= array();
		$this->_groups			= array();
		
		return $this;
	}

	/**
	 * @return PayplansPlan
	 * @param string $dummy is added just for removing warning with development mode(XiLib::getInstance is having 4 parameters)
	 */
	static public function getInstance($id=0, $type=null, $bindData=null, $dummy=null)
	{
		return parent::getInstance('plan',$id, $type, $bindData);
	}
	
	public function afterBind($id = 0)
	{
		if(!$id) return $this;

		$this->_planapps = XiFactory::getInstance('planapp', 'model')
									->getPlanApps($id);
									
		// get groups
		$this->_groups = XiFactory::getInstance('plangroup', 'model')
									->getPlanGroups($id);
		return $this;
	}
	
	public function bind($data, $ignore=array())
	{
		if(is_object($data)){
			$data = (array) ($data);
		}

		parent::bind($data, $ignore=array());

		if(isset($data['planapps'])){
			$this->_planapps = $data['planapps'];
		}
		
		// bind groups
		if(isset($data['groups'])){
			$this->_groups = $data['groups'];
		}
		return $this;
	}

	/**
	 * Save the plan
	 * @see XiLib::save()
	 * 
	 * @return object PayplansPlan
	 */
	public function save()
	{
		parent::save();
		$this->_savePlanApps();
		return $this->_savePlanGroups();
	}

	/**
	 * Create an order and subscription for the given userid
	 * 
	 * @see PayplansIfaceApiPlan::subscribe()
	 * 
	 * @param integer $userId UserId for which order and subscription is to be created
	 * 
	 * @return object  PayplansOrder  Instance of PayplansOrder
	 */
	public function subscribe($userId)
	{
		//Create a NEW Order
		$order = PayplansOrder::getInstance()
					->setBuyer($userId)
					->set('currency', $this->getCurrency('isocode'))
					->save();

		// Create a Subscription
		// attach order with subscription
		$subscription = PayplansSubscription::getInstance();
		$subscription->setPlan($this)
					 ->setOrder($order)
					 ->save();

		// refresh order after saving subscription
		$order->refresh()->save();
		return $order;
	}
	
	function hasApp($appId)
	{
		XiError::assert($appId, XiText::_('COM_PAYPLANS_INVALID_APP_ID_TO_CHECK'));

		return in_array($appId,$this->_planapps);
	}
	
	public function delete()
	{
		//delete plan only when no subscription exists for the corresponding plan
		$subscription = XiFactory::getInstance('subscription','model')
									->loadRecords(array('plan_id'=>$this->getId()));
		if(empty($subscription)){
			return parent::delete();
		}
		
		$this->setError(XiText::_('COM_PAYPLANS_PLAN_GRID_CAN_NOT_DELETE_PLAN_SUBSCRIPTION_EXISTS'));
		return false;
	}
	
	/**
	 * Gets the title of the plan
	 * 
	 * @see PayplansIfaceApiPlan::getTitle()
	 * 
	 * @return plan title
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Returns the price of plan with different expiration types
	 * 
	 * @see PayplansIfaceApiPlan::getPrice()
	 * 
	 * @param integer $type  A constant indicating expiration type
	 *  
	 * if type is not set then return the regular/normal price
	 * if type is set to RECURRING_TRIAL_1 then return first trial price 
	 * if type is set to RECURRING_TRIAL_2 then return second trial price
	 * 
	 * @return float  Price of the plan
	 */
	public function getPrice($type = null)
	{
		if($type === PAYPLANS_RECURRING_TRIAL_1){
			return PayplansHelperFormat::price($this->details->get('trial_price_1', 0.00));
		}
		
		if($type === PAYPLANS_RECURRING_TRIAL_2){
			return PayplansHelperFormat::price($this->details->get('trial_price_2', 0.00));
		}
		
		return PayplansHelperFormat::price($this->details->get('price', 0.00));
	}
	
	/**
	 * Sets the price of the plan
	 * 
	 * @param  float $price  Price to set on the current plan 
	 * @param  integer               $type   Expiration type for which price is to be set
	 * 
	 * @return mixed  The value of the that has been set.
	 */
	public function setPrice($price, $type = null)
	{
		$var = 'price';
		if($type === PAYPLANS_RECURRING_TRIAL_1){
			$var = 'trial_price_1';
		}elseif($type === PAYPLANS_RECURRING_TRIAL_2){
			$var = 'trial_price_2';
		}
		
		return $this->details->set($var, $price);
	}
	
	/**
	 * Gets plan expiration time
	 * 
	 * @see PayplansIfaceApiPlan::getExpiration()
	 * 
	 * @return Array  An array containing expiration values for year, month, day and so on 
	 */
	public function getExpiration()
	{
		$rawTime = $this->getDetails()->get('expiration');

		return PayplansHelperPlan::convertIntoTimeArray($rawTime);
	}
	
	/**
	 * Sets the expiration time(regular expiration time) of the plan
	 * 
	 * @see PayplansIfaceApiPlan::setExpiration()
	 * 
	 * @param  string $time  12 digits numeric string each 2 digits denotes the value for year, month, day, minute, hour and second in the same sequence, starting from year(starting 2 digits indicate year) 
	 * 
	 * @return object PayplansPlan
	 */
	public function setExpiration($time)
	{
		$this->getDetails()->set('expiration', $time);
		return $this;
	}

	/**
	 * Gets the currency of the plan
	 * 
	 * @see PayplansIfaceApiPlan::getCurrency()
	 * @param  string $format  An optional parameter to get the currency in different format.
	 * Available formats are isocode, symbol, fullname
	 * 
	 * @return currency of the plan in desired format
	 * 
	 */
	public function getCurrency($format = null)
	{
		$currency = $this->getDetails()->get('currency', XiFactory::getConfig()->currency);
		return PayplansHelperFormat::currency(XiFactory::getCurrency($currency), array(), $format);
	}

	/**
	 * 
	 * Sets the currency of the plan
	 * if currency is not mentioned then currency set in the configuration will be set in the plan
	 * 
	 * @param string  $currency currency isocode to set for the current plan 
	 * 
	 * @return object PayplansPlan
	 */
	public function setCurrency($currency = null)
	{
		if($currency === null){
			$currency = XiFactory::getConfig()->currency;
		}
		
		$this->getDetails()->set('currency', $currency);
		return $this;
	}
	
	/**
	 * Implementing interface Apptriggerable
	 * @return array
	 */
	public function getPlans()
	{
		return array($this->getId());
	}
	
	/**
	 * Gets group the plan is attached with 
	 * 
	 * @see PayplansIfaceApiPlan::getGroups()
	 * 
	 * @return Array of group id
	 */
	public function getGroups()
	{
		return $this->_groups;
	}
	
	/**
	 * Gets published status of the plan
	 * 
	 * @see PayplansIfaceApiPlan::getPublished()
	 * 
	 * @return boolean True if plan is published
	 */
	public function getPublished()
	{
		return $this->published;
	}
	
	/**
	 * Gets plan visibility
	 * 
	 * @see PayplansIfaceApiPlan::getVisible()
	 * 
	 * @return boolean True if plan is visible
	 */
	public function getVisible()
	{
		return $this->visible;
	}
	
	/**
	 * Gets the description of the plan
	 * 
	 * @see PayplansIfaceApiPlan::getDescription()
	 * 
	 * @param boolean $descriptionFormat  Trigger Joomla events if true 
	 * 
	 * @return string The description of the plan
	 */
	public function getDescription($descriptionFormat = false)
	{
		if($descriptionFormat == true)
		{
			$planDescription = new stdClass();
			$planDescription->text = $this->description;
			JPluginHelper::importPlugin('content');

            $param = null;
            $args  = array('com_payplans.planDescription', &$planDescription, &$param, 0);
            XiHelperPlugin::trigger('onContentPrepare', $args);
           
            return $planDescription->text;
		}
		return $this->description;
	}
	
	
	/**
	 * Gets the app the plan is attached with
	 * 
	 * @see PayplansIfaceApiPlan::getPlanapps()
	 * 
	 * @return array of app id
	 */
	public function getPlanapps()
	{
		return $this->_planapps;
	}
	
	/**
	 * Gets the raw expiration time of the plan
	 * @return string  expiration time of the plan
	 */
	public function getRawExpiration()
	{
		return $this->getDetails()->get('expiration');
	}
	
	/**
	 * Gets the details of the plan
	 * 
	 * Plan Details include expiration type, expiration time, 
	 * price, currecny, trial time, trial price, recurrence count etc
	 * 
	 * @see PayplansIfaceApiPlan::getDetails()
	 * 
	 * @return object XiParameter
	 */
	public function getDetails()
	{
		return $this->details;
	}
	
	/**
	 * Gets the Teaser Text of the plan
	 * 
	 * @see PayplansIfaceApiPlan::getTeasertext()
	 * 
	 * @return string
	 */
	public function getTeasertext()
	{
		return $this->getParams()->get('teasertext','');
	}
	
	public function getCssClasses()
	{
		return $this->getParams()->get('css_class','');
	}
	
	/**
	 * Gets the plan params
	 * Plan Params include teaser text, css-classes applied on the plan and redirect url
	 * 
	 * @return object XiParameter
	 */
	public function getParams()
	{
		return $this->params;
	}
	
	/**
	 * Is recurring Plan?
	 * 
	 * @see PayplansIfaceApiPlan::isRecurring()
	 * 
	 * @return integer when plan is recurring/recurring+trial else return False
	 */
	public function isRecurring()
	{
		$expirationType = $this->getExpirationType();
		
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
	 * Gets the recurrence count of the plan
	 * 
	 * @see PayplansIfaceApiPlan::getRecurrenceCount()
	 * 
	 * @return integer recurrence count value of the plan
	 */
	public function getRecurrenceCount()
	{
		return $this->getDetails()->get('recurrence_count', 1);
	}
	
	/**
	 * Gets the expiration type of the plan
	 * 
	 * @see PayplansIfaceApiPlan::getExpirationType()
	 * 
	 * @return string expiration type of the plan
	 */
	public function getExpirationType()
	{
		return $this->getDetails()->get('expirationtype', 'forever');
	}
	
	/**
	 * Gets the redirect url of the plan
	 * 
	 * @see PayplansIfaceApiPlan::getRedirecturl()
	 */
	public function getRedirecturl()
	{
		return $this->getParams()->get('redirecturl','');
	}
	
	
	private function _savePlanApps()
	{
		// delete all existing values of current plan id
		$model = XiFactory::getInstance('planapp', 'model');
		$model->deleteMany(array('plan_id' => $this->getId()));

		// insert new values into planapp for current plan id
		$data['plan_id'] = $this->getId();
		if(is_array($this->_planapps)){
			foreach($this->_planapps as $app){
				$data['app_id'] = $app;
				$model->save($data);
			}
		}

		return $this;
	}
	
	private function _savePlanGroups()
	{
		// delete all existing values of current plan id
		$model = XiFactory::getInstance('plangroup', 'model');
		$model->deleteMany(array('plan_id' => $this->getId()));

		// insert new values into planapp for current plan id
		$data['plan_id'] = $this->getId();
		if(is_array($this->_groups)){
			foreach($this->_groups as $group){
				$data['group_id'] = $group;
				$model->save($data);
			}
		}

		return $this;
	}
}

class PayplansPlanFormatter extends PayPlansFormatter
{
	function getIgnoredata()
	{
		$ignore = array('_trigger', '_component', '_name', '_errors','_blacklist_tokens');
		return $ignore;
	}
	
	// get formatter to apply on vars
	function getVarFormatter()
	{
		$rules = array('_planapps'    => array('formatter'=> 'PayplansAppFormatter',
										       'function' => 'getApplicableApps'),
						'_groups'     => array('formatter'=> 'PayplansGroupFormatter',
										       'function' => 'getPlanGroups'),
						'params'      => array('formatter'=> 'PayplansFormatter',
										       'function' => 'getFormattedParams'));
		return $rules;
	}
	
	function getPlanName($key,$value,$data)
	{
		$key   = XiText::_('COM_PAYPLANS_LOG_KEY_PLAN');
		if(!empty($value)){
			$planName = PayplansHelperPlan::getName($value);
			$value = PayplansHtml::link(XiRoute::_("index.php?option=com_payplans&view=plan&task=edit&id=".$value, false), $value.'('.$planName.')');
		}
	}
	
	
}
