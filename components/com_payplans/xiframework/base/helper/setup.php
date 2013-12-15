<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class XiHelperSetup
{
	public static function getOrderedRules()
	{
		static $attr = null;
		
		//clean cache if required
		if(XiFactory::cleanStaticCache()){
			$attr = null;
		}
		
		if($attr === null){
			$xml		= PAYPLANS_PATH_SETUP.DS.'order.xml';
			$xmlContent = simplexml_load_file($xml);
			
			foreach($xmlContent as $child ){
				 $attribute = $child->attributes();
				 $attr[] = (string)$attribute;
			}
		}
		return $attr;
	}
}
