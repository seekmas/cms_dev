<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansSetupPayment extends XiSetup
{
	public $_location = __FILE__;
	public $_message  = 'COM_PAYPLANS_SETUP_PAYMENT_CREATE_PAYMENT_APP_INSTANCE';

 	function isRequired()
 	{
		//XITODO : this logic should move to app helper
		$apps = PayplansHelperApp::getAvailableApps('payment');

		$required = true;
		foreach($apps as $app){
			if(!($app->getPublished()))
				continue;

			if($app->getType() == 'adminpay')
				continue;

			//if at least on app is found.
			$required = false;
			break;
		}

 		//if payment apps do not exist
 		if(!$required){
 			$this->_message = 'COM_PAYPLANS_SETUP_PAYMENT_PAYMENT_APP_INSTANCE_EXIST';
 			return $this->_required=false;
 		}

 		return $this->_required=true;
 	}


 	function doApply()
 	{
		// we should redirect to configuration screen and highlight the configuration
		XiFactory::getApplication()->redirect("index.php?option=com_payplans&view=app");
 	}
}