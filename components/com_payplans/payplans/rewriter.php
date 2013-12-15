<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansRewriter extends JObject
{
	protected $mapping = array();
	
	public function setConfigMapping()
	{
		static $configMapping = false;
		if($configMapping){
			return true;
		}
		
		// initialize it with some hard coded tokens
		
        $config                         = new stdClass();
		$ppConfig 						= XiFactory::getConfig();
		$config->site_name  			= rtrim($ppConfig->sitename, '/');
		$config->company_name   		= $ppConfig->companyName;
		$config->company_address 		= $ppConfig->companyAddress;
		$config->company_city_country 	= $ppConfig->companyCityCountry;
		$config->company_phone   		= $ppConfig->companyPhone;
		$config->site_url   			= rtrim(JURI::root(), '/');
		$config->name  					= 'config';
		$config->plan_renew_url			= '';
		$config->dashboard_url          = '';
		$config->order_details_url      = '';

		$this->setMapping($config, false);
		$configMapping = true;
		return true;
	}

	/**
	 * @param string $str
	 * @param XiLib $refObject
	 * @param boolean $requireRelative set to false if do not need to get relative lib instance
	 */
	function rewrite($str, $refObject, $requireRelative = true)
	{
		//XITODO : remove setmapping here, do not mix two functions
		$this->setMapping($refObject, $requireRelative);
		
		// set config mapping
		$this->setConfigMapping();
		
		//trigger apps for mapping rewriter tokens
		$args = array(&$refObject, $this);
		PayplansHelperEvent::trigger('onPayplansRewriterReplaceTokens', $args);
		
		
		foreach($this->mapping as $key => $value){
			// XITODO : Support for array also
			if(!is_array($value) && isset($value) && !empty($value)){
				$str =  preg_replace('/\[\['.$key.'\]\]/', $value, $str);
			}
		}
		
		return $str;
	}
	
	function setMapping($refObject, $requireRelative = true)
	{
		$refObjects = array();
		$refObjects = ($requireRelative) ? PayplansHelperRewriter::getRelativeObjects($refObject) : array($refObject);

		foreach($refObjects as $object){
			if(!$object){
				continue;
			}
			
			if(method_exists($object, 'getName')){
				$name = $object->getName();
			}
			else{
				$name = $object->name;
				unset($object->name);
			}

			$props = (method_exists($object, 'toArray')) ? $object->toArray(true, true) : (array)$object;
			if(isset($props['_blacklist_tokens'])){
				foreach($props as $key=>$value){
					if(in_array($key, $props['_blacklist_tokens'])){
						unset($props[$key]);
					}
				}
			}

			$map   = array();

			foreach($props as $key => $value){
				// if key name starts with _ then continue
				if('_' == JString::substr(JString::trim($key), 0, 1)){
					continue;
				}

    			// JParameter will be an array, so handle it
				if(is_array($value)){
					foreach($value as $childKey => $childValue){
						$map[JString::strtoupper($name.'_'.$key.'_'.$childKey)] = $childValue;
					}
				}
				else{

					if(JString::Strtolower($key)== 'status'){
						$value = XiText::_('COM_PAYPLANS_STATUS_'.PayplansStatus::getName($value));
					}
 					if(($object instanceOf PayplansIfaceMaskable) && JString::Strtolower($key)== 'currency')
					{
						$value = $object->getCurrency();
					}
					$map[JString::strtoupper($name.'_'.$key)] = $value;
				}
			}
			
			// XITODO : clean this code, move the below code from forloop
			$this->mapping = array_merge($this->mapping, $map);
			// add key of PayplansIfaceMaskable object 
			if($object instanceOf PayplansIfaceMaskable){
				$this->mapping[JString::strtoupper($object->getName()).'_KEY'] = $object->getKey();
			}

			if($name == 'invoice'){
			$this->mapping['INVOICE_INVOICE_SCREEN_LINK'] = JURI::root()."index.php?option=com_payplans&view=invoice&task=confirm&invoice_key=".XiHelperUtils::getKeyFromId($this->mapping['INVOICE_INVOICE_ID']);
			}
			//Assign subscription Renew Link.
			if (isset($this->mapping['SUBSCRIPTION_SUBSCRIPTION_ID'])){
				$this->mapping['CONFIG_PLAN_RENEW_URL'] = JURI::root()."index.php?option=com_payplans&view=subscription&task=display&subscription_key=".XiHelperUtils::getKeyFromId($this->mapping['SUBSCRIPTION_SUBSCRIPTION_ID']);
			}
		
			//token rewriter for dashboard and order details page
			if($name == 'config'){
				$this->mapping['CONFIG_DASHBOARD_URL']     = JURI::root()."index.php?option=com_payplans&view=dashboard";
				$this->mapping['CONFIG_ORDER_DETAILS_URL'] = JURI::root()."index.php?option=com_payplans&view=order";
			}

			//token rewriter for users wallet balance
			if($name == 'user'){
				$user = PayplansUser::getInstance($this->mapping['USER_USER_ID']);
				$walletBalance = $user->getWalletBalance();
				$this->mapping['USER_WALLET_BALANCE'] = $walletBalance;	
				$countryCode  = $user->getCountry();
				$items   = PayplansFactory::getInstance('country', 'model')->loadRecords(array('id'=>$countryCode));
				$this->mapping['USER_COUNTRY'] = '';
				if(!empty($items)){
              	  $this->mapping['USER_COUNTRY'] = PayplansHelperFormat::country(array_shift($items));
				}						
			}
		}
		
		return $this;
	}
	
	public function reset()
	{
		$this->mapping = array();
		return $this;
	}
}
