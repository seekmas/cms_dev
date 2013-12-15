<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansSetupConfiguration extends XiSetup
{
	public $_location = __FILE__;
	public $_message  = 'COM_PAYPLANS_SETUP_CONFIGURATION_CONFGIURE_PAYMENT_APP';
	public $_required =true;

 	function isRequired()
 	{
 		// at least one application should be configured as default payment
 		if(XiFactory::getConfig()->paymentApp){
 			$this->_message = 'COM_PAYPLANS_SETUP_CONFIGURATION_PAYMENT_APP_CONFIGURED';
 			return $this->_required=false;
 		}

 		return $this->_required=true;
 	}

 	function doApply()
 	{
 		//we should redirect to configuration screen and highlight the configuration
	 	XiFactory::getApplication()->redirect("index.php?option=com_payplans&view=config");
 	}
}