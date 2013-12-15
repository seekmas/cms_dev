<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansSetupCurl extends XiSetup
{
	public $_location = __FILE__;
	public $_message  = 'COM_PAYPLANS_SETUP_CURL_DO_ENABLE';

	function isRequired()
	{
		if (in_array ('curl', get_loaded_extensions())) {		
			$this->_message = 'COM_PAYPLANS_SETUP_CURL_DONE';
			return $this->_required=false;
		}
		return $this->_required=true;
	}
}