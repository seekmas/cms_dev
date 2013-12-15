<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansModelPlanapp extends XiModel
{
	// XITODO : Apply validation when it is applied all over
	function validate(&$data, $pk=null,array $filter = array(),array $ignore = array())
	{
		return true;
	}

	static $_planapps = null;
	static $_appplans = null;
	
	protected static function _loadCache($query)
	{		
		//$query = clone($this->getQuery());
		$query->clear('select')->clear('where');

		$app = $query->select('app_id,plan_id')
					 ->dbLoadQuery()
					 ->loadObjectList();

		self::$_planapps = array();
		self::$_appplans = array();
		foreach($app as $obj){
			if(isset(self::$_planapps[$obj->plan_id]) ==false){
				self::$_planapps[$obj->plan_id] = array();
			}
			
			array_push(self::$_planapps[$obj->plan_id], $obj->app_id);
			
			if(isset(self::$_appplans[$obj->app_id]) ==false){
				self::$_appplans[$obj->app_id] = array();
			}
			array_push(self::$_appplans[$obj->app_id], $obj->plan_id);
		}
	}
	
	function getPlanApps($planId)
	{
		XiError::assert($planId, "INVALID PLAN ID $planId");
		if(self::$_planapps === null){
			self::_loadCache(clone($this->getQuery()));
		}

		if(isset(self::$_planapps[$planId]) ===false){
			return array();
		}
		
		return self::$_planapps[$planId];
	}

	
	function getAppPlans($appId)
	{
		XiError::assert($appId, XiText::_('COM_PAYPLANS_INVALID_APP_ID'));

		// Perfomance Fix :  Only check required is cache loaded or not, else it will generate false cache loading
		// as It is not neccessary that every app have some attached plans
		// /* !isset(self::$_appplans[$appId]) ||*/ 
		if(self::$_appplans === null){
			self::_loadCache(clone($this->getQuery()));
		}
		
		if(isset(self::$_appplans[$appId])===false){
			return array();
		}
		
		return self::$_appplans[$appId];
	}
}

