<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansSetupTax extends XiSetup
{
	public $_location = __FILE__;
	public $_message  = '';

	function isRequired()
	{
		$basicTax = XiHelperPlugin::getStatus('basictax','payplans');
		$euvat = XiHelperPlugin::getStatus('euvat','payplans');
		
 		if($basicTax == true && $euvat == true){
 			$this->_message = 'COM_PAYPLANS_SETUP_TAX_BOTH_PLUGIN_ENABLED';
 			return $this->_required=true;
 		}
 		
	}

	function doApply()
	{
		XiFactory::getApplication()->redirect("index.php?option=com_plugins");
	}
}