<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansSetupOneclickcheckout extends XiSetup
{
	public $_location = __FILE__;
	public $_message  = '';

	function isRequired()
	{
		$oneclick = XiHelperPlugin::getStatus('oneclickcheckout','payplans');
		$skipfree = XiHelperPlugin::getStatus('skipfreeinvoice','payplans');
		
 		if($oneclick == true && $skipfree == true){
 			$this->_message = 'COM_PAYPLANS_SETUP_ONECLICK_BOTH_PLUGIN_ENABLED';
 			return $this->_required = true;
 		}
 		
	}

	function doApply()
	{
		XiFactory::getApplication()->redirect("index.php?option=com_plugins");
	}
}