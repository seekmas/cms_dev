<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansSetupRegistration extends XiSetup
{
	public $_location = __FILE__;
	public $_message  = 'COM_PAYPLANS_SETUP_REGISTRATION_ENABLE_PLUGIN';

 	function isRequired()
 	{
		if(XiHelperPlugin::getStatus(XiFactory::getConfig()->registrationType, 'payplansregistration')){
			$this->_message = 'COM_PAYPLANS_SETUP_REGISTRATION_SETUP_PROPERLY';
 			return $this->_required=false;
		}

 		return $this->_required=true;
 	}


 	function doApply()
 	{
 		XiHelperPlugin::changeState(XiFactory::getConfig()->registrationType, 'payplansregistration');
 	}
}