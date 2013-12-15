<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansSetupPhpversion extends XiSetup
{
	public $_location = __FILE__;
	public $_message  = 'COM_PAYPLANS_SETUP_PHPVERSION_DO_ENABLE';

	function isRequired()
	{
		if(version_compare(PHP_VERSION, '5.2', '>=')){
			$this->_message = 'COM_PAYPLANS_SETUP_PHPVERSION_DONE';
			return $this->_required=false;
		}
		return $this->_required=true;
	}
}