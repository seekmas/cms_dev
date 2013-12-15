<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansSetupPlugins extends XiSetup
{
	public $_location = __FILE__;
	public $_message  = 'COM_PAYPLANS_SETUP_PLUGINS_DO_ENABLE';

	function isRequired()
	{
		$this->_status =  XiHelperPlugin::getStatus('payplans','system');
		if($this->_status){
			$this->_message = 'COM_PAYPLANS_SETUP_PLUGINS_DONE';
			return $this->_required=false;
		}

		return $this->_required=true;
	}

	function doApply()
	{
		return XiHelperPlugin::changeState('payplans','system', XI_ENABLE_STATE);
	}

	function doRevert()
	{
		return XiHelperPlugin::changeState('payplans','system', XI_DISABLE_STATE);
	}
}