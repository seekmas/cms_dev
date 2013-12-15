<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansModelPlangroup extends XiModel
{
	// XITODO : Apply validation when it is applied all over
	function validate(&$data, $pk=null,array $filter = array(),array $ignore = array())
	{
		return true;
	}

	static $_plangroups = null;
	static $_groupplans = null;
	protected static function _loadCache($query)
	{		
		$query->clear('select')->clear('where');

		$group = $query->select('group_id, plan_id')
					 ->dbLoadQuery()
					 ->loadObjectList();
					 
		self::$_plangroups = array();
		self::$_groupplans = array();
		foreach($group as $obj){
			if(isset(self::$_plangroups[$obj->plan_id]) ==false){
				self::$_plangroups[$obj->plan_id] = array();
			}
			array_push(self::$_plangroups[$obj->plan_id], $obj->group_id);
			
			if(isset(self::$_groupplans[$obj->group_id]) ==false){
				self::$_groupplans[$obj->group_id] = array();
			}
			array_push(self::$_groupplans[$obj->group_id], $obj->plan_id);
		}
	}
	
	function getPlanGroups($planId)
	{
		XiError::assert($planId, "INVALID PLAN ID $planId");
		if(self::$_plangroups === null){
			self::_loadCache(clone($this->getQuery()));
		}

		if(isset(self::$_plangroups[$planId]) ===false){
			return array();
		}
		
		return self::$_plangroups[$planId];
	}

	
	function getGroupPlans($groupId)
	{
		XiError::assert($groupId, XiText::_('COM_PAYPLANS_INVALID_GROUP_ID'));

		if(self::$_groupplans === null){
			self::_loadCache(clone($this->getQuery()));
		}
		
		if(isset(self::$_groupplans[$groupId]) ===false){
			return array();
		}
		
		return self::$_groupplans[$groupId];
	}
}

