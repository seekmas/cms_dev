<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHelperParam
{

	// Collect params and load into params
	static public function collectParams($data, $key='')
	{
		// XITODO : remove this function
		return self::arrayToIni($data, $key);
	}

	/**
	 * Convert given iniData to array
	 * @param $data : Can be Object/Array
	 * @param $key  : String key to access data
	 * @return : Array, if no data then blank array
	 */
	static public function iniToArray($data, $key='')
	{
		$retVal = array();
		$tmpData = null;

		if($key === ''){
			$tmpData = $data;
		}
		else{
			//if array
			if(is_array($data) && isset($data[$key]) && $data[$key]){
				$tmpData = $data[$key];
			}

			// if object
			if(is_object($data) && isset($data->$key) && $data->$key){
				$tmpData = $data->$key;
			}
		}

		if($tmpData){
			$retVal = (array) JRegistryFormatJSON::stringToObject($tmpData);
		}

		return $retVal;
	}

	/**
	 * XITODO : Name does not match the funtionality
	 * Convert given array to INI string
	 * @param  Array $inidata
	 * @return String
	 */
	static public function arrayToIni($inidata, $key='')
	{
		$retVal = '';
		$tmpData = null;

		if($key === ''){
			$tmpData = (array)$inidata;
		}
		else{
			//if array
			if(is_array($inidata) && isset($inidata[$key]) && $inidata[$key]){
				$tmpData = (array) $inidata[$key];
			}

			// if object
			if(is_object($inidata) && isset($inidata->$key) && $inidata->$key){
				$tmpData = (array) $inidata->$key;
			}
		}

		if($tmpData){
			$tmpData = (object)$tmpData;
			$retVal = JRegistryFormatJSON::objectToString($tmpData, null);
		}

		return $retVal;
	}
}