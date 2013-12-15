<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansSetupPlans extends XiSetup
{
	public $_location = __FILE__;
	public $_message  = 'COM_PAYPLANS_SETUP_PLANS_CREATE_PLAN';

	function isRequired()
	{
		$plans = XiFactory::getInstance('plan','model')->loadRecords();
		if(count($plans)){
			$this->_message = 'COM_PAYPLANS_SETUP_PLANS_CREATED';
 			return $this->_required=false;
 		}

 		return $this->_required=true;
	}

	function doApply()
	{
		XiFactory::getApplication()->redirect("index.php?option=com_payplans&view=plan&task=add");
	}
}