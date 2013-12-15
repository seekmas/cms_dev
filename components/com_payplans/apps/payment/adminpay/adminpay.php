<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class  PayplansAppAdminpay extends PayplansAppOfflinepay
{
	protected $_location	= __FILE__;

	/*
	 * This application is only for admin use
	 * Override , no need to call parent
	 * Should run in admin panel only
	 */
	
	public function isApplicable($refObject = null, $eventName='')
	{ 
		if(XiFactory::getApplication()->isAdmin() === FALSE){
			return false;
		}
		
		return parent::isApplicable($refObject, $eventName);
	}
	
}
