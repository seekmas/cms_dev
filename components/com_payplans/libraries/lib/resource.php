<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

/**
 * Lib class for Resource
 * @author Gaurav Jain
 * @since 1.3
 *
 */
class PayplansResource extends XiLib
{
	// Resource shoould not be triggered
	public 		$_trigger   		= false;
		
	protected	$resource_id		= 0 ;
	protected	$title				= '';
	protected   $user_id			= 0;
	protected	$value				= 0;
	protected	$count				= 0;
	protected	$subscription_ids	= '';
	
	const PAYPLANS_RESOURCE_NAME_REQUIRED	= true;
	
	
	/**
	 * @return PayplansResource
	 * @param string $dummy is added just for removing warning with development mode(XiLib::getInstance is having 4 parameters)
	 * @param string $dummy1 is added just for removing warning with development mode
	 * @since 1.3
	 */
	static public function getInstance($id=0, $bindData=null, $dummy=null, $dummy1=null)
	{
		return parent::getInstance('resource',$id, null, $bindData);
	}

	// Reset to construction time.
	public function reset(Array $config=array())
	{
		$this->resource_id		= 0 ;
		$this->title			= '';
		$this->user_id			= 0;
		$this->value			= 0;
		$this->count			= 0;
		$this->subscription_ids	= '';
		
		return $this;
	}
	
	/**
	 * get title of the resource
	 * @return String
	 * @since 1.3
	 */
	function getTitle()
	{
		return $this->title;
	}
	
	/**
	 * get user id 
	 * @return Integer
	 * @since 1.3
	 */
	function getUser()
	{
		return $this->user_id;
	}
	
	/**
	 * get value of the resource
	 * @return String/Integer
	 * @since 1.3
	 */
	function getValue($resourceNameRequired = false)
	{
		$name = '';
		
		if($resourceNameRequired === self::PAYPLANS_RESOURCE_NAME_REQUIRED){
			$apps = PayplansHelperApp::getApps();
			foreach($apps as $app){
				$className = 'PayplansApp'.JString::ucfirst(JString::strtolower($app));
				$instance = new $className();
				
				if(!method_exists($instance, 'getNameFromResourceValue')){
					continue;
				}

				$name = $instance->getNameFromResourceValue($this->getTitle(), $this->value);
				
				if($name !== false){
					break;
				}
			}
		}

		if(!empty($name)){
			return $name;
		}
		
		return $this->value;
	}
	
	/**
	 * get count of the resource
	 * @return integer
	 * @since 1.3
	 */
	function getCount()
	{
		return $this->count;
	}
	
	/**
	 * get the subscription ids attached with this resource
	 * @return array
	 */
	function getSubscriptions()
	{
		return explode(',', $this->subscription_ids);
	}
	
	public function save()
	{
		if(is_array($this->subscription_ids)){
			$this->subscription_ids = implode(',' , $this->subscription_ids);
		}
		
		return parent::save();
	}
}
