<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansadminViewSupport extends XiView
{

	function _displayList()
	{
		return true;
	}

	function display($tpl=null)
	{
		return true;
	}

	function _basicFormSetup()
	{
		return true;
	}

	protected function _adminToolbar()
	{
		$this->_adminToolbarTitle();
	}
	
	function installsuccess()
	{
		$this->setTpl(__FUNCTION__);
		return true;
	}
	
	protected function _adminToolbarTitle()
	{
		// Set the titlebar text
		XiHelperToolbar::title( XiText::_(XiText::_('COM_PAYPLANS_SM_THANK_YOU')), "xi-installation-complete");
	}
	
}

