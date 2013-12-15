<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();

 class PayplansadminControllerSupport extends XiController
{
	// No model exist
	function getModel()
	{
		return null;
	}

	function setup()
	{
		$action = JRequest::getVar('action', 'doApply');
		$name 	= JRequest::getVar('name');
		$returl	= JRequest::getVar('from');

		XiError::assert(!empty($name), XiText::_('COM_PAYPLANS_ERROR_SETUP_RULE_NAME_IS_EMPTY'));

		$setup = XiSetup::getInstance($name);
		$setup->$action();

		if($returl){
			$returl = base64_decode($returl);
			XiFactory::getApplication()->redirect($returl);
		}

		return false;
	}
	
	function patch()
	{
		PayplansHelperPatch::applyPatches();
			
		// the last patch
		XiHelperPatch::updateVersion();
		
		XiFactory::getApplication()->redirect(XiRoute::_('index.php?option=com_payplans&view=support&task=installsuccess'));
		return false;
	}
	
	function installsuccess()
	{
		return true;
	}
	
	// render the data in formatted way
	function format()
	{
		//XITODO : generalize it
		$this->setTemplate('partial_format_'.JRequest::getVar('object',''));
		// index.php ? option=com_payplans view=support task=format object=timer data={}
		//XITODO : data should be array values
		$this->getView()->assign('timer', JRequest::getVar('timer', '000100000000'));
		return true;
	}
}