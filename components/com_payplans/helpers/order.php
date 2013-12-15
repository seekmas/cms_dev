<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHelperOrder
{
	public static function getSubscriptions($orderIds)
	{
		$orderIds = is_array($orderIds) ? $orderIds : array($orderIds);
		array_unique($orderIds);
		
		$filter = array('order_id' => array(array('IN', '('.implode(",", $orderIds).')')));
		return XiFactory::getInstance('subscription', 'model')->loadRecords($filter, array(), false, 'order_id');
	}
	
	public static function getInvoices($orderIds, $object_type = 'PayplansOrder')
	{
		$orderIds = is_array($orderIds) ? $orderIds : array($orderIds);
		array_unique($orderIds);
		
		$filter 				= array();
		$filter['object_type'] 	= $object_type;
		$filter['object_id'] 	= array(array('IN', '('.implode(",", $orderIds).')'));
		return XiFactory::getInstance('invoice', 'model')->loadRecords($filter, array(), false, 'object_id');
	}
	
}