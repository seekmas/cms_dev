<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansModelConfig extends XiModel
{
	function save($data =array(), $pk = NULL, $new = false)
	{		
		$keys = array_keys($data);
		$db   = xiFactory::getDbo();
		$delete = " DELETE FROM `#__payplans_config` WHERE `key` IN ('".implode("', '", $keys)."')" ;
		
		$db->setQuery($delete)
		   ->query();
		
		
		$query  =  "INSERT INTO `#__payplans_config` (`key`, `value`) VALUES ";
		$queryValue = array();
		
		foreach ($data as $key => $value){
			if(is_array($value)){
				$value  = json_encode($value);
			}
			$queryValue[] = "(".$db->quote($key).",". $db->quote($value).")";
		}
		$query .= implode(",", $queryValue);
		return $db->setQuery($query)
		   		  ->query();  
		
	}
	
	// XITODO : Apply validation when it is applied all over
	function validate(&$data, $pk=null,array $filter = array(),array $ignore = array())
	{
		return true;
	}
}

class PayplansModelformConfig extends XiModelform {}