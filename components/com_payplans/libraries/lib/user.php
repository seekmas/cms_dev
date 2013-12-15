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
 * User object
 */
class PayplansUser extends XiLib
	implements PayplansIfaceApptriggerable, PayplansIfaceApiUser
{
	protected	  $user_id	 	 = 0 ;
	protected	  $realname		 = '';
	protected	  $username		 = '';
	protected	  $email		 = '';
	protected	  $usertype		 = '';
	protected	  $registerDate	 = '';
	protected	  $lastvisitDate = '';
	protected 	  $params        = '';
	protected	  $address		 = '';
	protected 	  $state		 = '';
	protected	  $city			 = '';
	protected 	  $country		 = '';
	protected     $zipcode		 = '';
	protected     $preference	 = '';	
	
	protected 	  $_subscriptions= array();

	// skip these tokens in token rewriter
	public  $_blacklist_tokens = array('params','registerDate','lastvisitDate'); 
	
	public function __construct($config = array())
	{
		//return $this to chain the functions
		return $this->reset($config);
	}
	
	/**
	 * @return PayplansUser
	 * @param string $dummy is added just for removing warning with development mode(XiLib::getInstance is having 4 parameters)
	 */
	static public function getInstance($id=0, $type=null, $bindData=null, $dummy=null)
	{
		return parent::getInstance('user',$id, $type, $bindData);
	}

	// Reset to construction time.
	public function reset(Array $config=array())
	{
		$this->user_id	  	= 0 ;
		$this->username  	= '';
		$this->email		= '';
		$this->usertype		= '';
	  	$this->registerDate	= '';
		$this->lastvisitDate= '';
		
		// XITODO : bind params before using it
		$this->params		= new XiParameter();
		$this->_subscriptions= array();
		$this->address		= '';
		$this->state		= '';
		$this->city			= '';
		$this->country		= '';
		$this->zipcode		= '';
		$this->preference	= new XiParameter();
		return $this;
	}	
	
	protected function _loadSubscriptions($id)
	{
		// get all subscription records of this order
		$records = XiFactory::getInstance('subscription','model')
								->loadRecords(array('user_id'=>$id));

		foreach($records as $record){
			$this->_subscriptions[$record->subscription_id] = PayplansSubscription::getInstance( $record->subscription_id, null, $record);
		}

		return $this;
	} 
	
	public function afterBind($id = 0)
	{
		if(!$id) return $this;

		//load dependent records
		return $this->_loadSubscriptions($id);
	}
	
	function login($username, $password)
	{
		if(PayplansHelperUtils::isEmailAddress($username)){
			$db		= XiFactory::getDBO();

			$query	= " SELECT `username` FROM `#__users` " 
			        . " WHERE `email` = '$username' "
			        ;
	
			$db->setQuery($query);
			$username =  $db->loadResult();
		}
		
		$result = XiFactory::getApplication()->login(array('username'=>$username, 'password'=>$password));
		if($result instanceof JException || !$result){
			// no need to enqueue message login function itself do that
			//$app->enqueueMessage($result->message);
			return false;
		}
		
		return true;
	}

	/**
	 * Implementing interface Apptriggerable
	 * @return array
	 */
	public function getPlans($status=PayplansStatus::SUBSCRIPTION_ACTIVE)
	{
		if($this->getId() <= 0){
			return array();
		}
		
		//XITODO : implement caching
		$ret = array();

		$filter = array('user_id'=>$this->getId());

		if($status !== null){
			$filter = array('user_id'=>$this->getId(), 'status'=>$status);
		}
		
		//return only active subscriptions of the user if status is not mentioned
		$subscriptions = XiFactory::getInstance('subscription', 'model')
				->loadRecords($filter);

		foreach($subscriptions as $item){
			$ret[] = $item->plan_id;
		}

		return $ret;
	}
	
	public function getRealname()
	{
		return $this->realname;
	}
	
	public function getUsername()
	{
		return $this->username;
	}
	
	public function getEmail()
	{
		return $this->email;
	}
	
	public function getAvatar($size=64, $default = "")
	{
        return "http://www.gravatar.com/avatar/" . md5(strtolower(trim($this->getEmail()))) . "?d=" . urlencode($default) . "&s=" . $size;
	}
	
	public function getUsertype()
	{
		return $this->usertype;
	}
	
	public function getRegisterDate()
	{
		return $this->registerDate;
	}
	
	public function getLastvisitDate()
	{
		return $this->lastvisitDate;
	}
	
	public function setAddress($address ='')
	{
		$this->address = $address;
		return $this;
	}
	
	public function getAddress()
	{
		return $this->address;
	}
	
	public function setState($state ='')
	{
		$this->state = $state;
		return $this;
	}
	
	public function getState()
	{
		return $this->state;
	}
	
	public function setCity($city = '')
	{
		$this->city = $city;
		return $this;
	}
	
	public function getCity()
	{
		return $this->city;
	}
	
	public function setCountry($country = '')
	{
		$this->country = $country;
		return $this;
	}
	
	public function getCountry()
	{
		return $this->country;
	}
	
	public function setZipcode($zipcode = '')
	{
		$this->zipcode = $zipcode;
		return $this;
	}
	
	public function getZipcode()
	{
		return $this->zipcode;
	}
	
	public function setPreference($key, $value)
	{
		$this->preference->set($key,$value);
		return $this;
	}
	
	public function getPreference($key = null, $default = false)
	{
		if($key === null)
		{
			return $this->preference;
		}
		
		return $this->preference->get($key, $default);
	}
	
	
	/* ------ Implement API ------------ */
	
	/* (non-PHPdoc)
	 * @see PayplansIfaceApiUser::getSubscriptions()
	 */
	public function getSubscriptions($status=NULL)
	{
		$subs = array();
		foreach($this->_subscriptions as $id => $sub){
			if($status===null || (int)$status === (int)$sub->getStatus()){
				$subs[$id] = $sub;
			}
		}
		return $subs;
	}
	
	public function isAdmin()
	{
		return XiHelperJoomla::isAdmin($this->getId()); 
	}
	
	public function setParam($key, $value)
	{
		XiError::assert($this);
		$this->getParams()->set($key,$value);
		return $this;
	}
	
	/**
	 * 
	 * @return XiParameter
	 */
	public function getParams()
	{
		return $this->params;
	}

	public function getWalletBalance()
	{
		$balance = XiFactory::getInstance('user', 'model')
							->getWalletBalance($this->getId());
		
		return PayplansHelperFormat::price($balance);
	}
}

class PayplansUserFormatter extends PayplansFormatter
{

	function getIgnoredata()
	{
		$ignore = array('_trigger', '_component', '_errors', '_name', '_blacklist_tokens','_subscriptions');
		return $ignore;
	}
	
	// get rules to apply
	function getVarFormatter()
	{
		$rules = array( 'preference'      => array('formatter'=> 'PayplansUserFormatter',
										       	   'function' => 'getFormattedPreferences')
						);
		return $rules;
	}
	
    function formatter($content,$type=null)
	{   
	   $data=parent::formatter($content);
		
       //change country id to country name
	   $prev_country=PayplansHelperFormat::country(XiFactory::getCountry($data['previous']['country']));
	   $curr_country=PayplansHelperFormat::country(XiFactory::getCountry($data['current']['country']));
       
	   if(($prev_country === true) || ($prev_country == "Select Country"))
           $data['previous']['country'] = XiText::_('COM_PAYPLANS_LOG_COUNTRY_NONE');
		 else 
		   $data['previous']['country'] = $prev_country;
				        
	   if(($curr_country === true) || ($curr_country == "Select Country"))
	       $data['current']['country'] = XiText::_('COM_PAYPLANS_LOG_COUNTRY_NONE');	
       else 
		   $data['current']['country'] = $curr_country;
	
	   return $data;
	}

	/**
	 * Get Buyer name from id
	 * pass $key and $value through reference
	 * 
	 */
	function getBuyerName($key,$value,$data)
	{
		$name = PayplansHelperUser::getName($value);
		$value = PayplansHtml::link(XiRoute::_("index.php?option=com_payplans&view=user&task=edit&id=".$value, false), $name);
	}
	
	// format user preferences
	function getFormattedPreferences($key,$value,$data)
	{
		$key= XiText::_('COM_PAYPLANS_LOG_KEY_PREFERENCES');
		$preferences = explode("\n",$value);
		$value  = implode("<br/>", $preferences);
		
	}
}