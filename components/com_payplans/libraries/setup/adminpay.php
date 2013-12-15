<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansSetupAdminpay extends XiSetup
{
	public $_location = __FILE__;
	public $_message  = 'COM_PAYPLANS_SETUP_CREATE_ADMINPAY';

	function isRequired()
	{
		$apps = PayplansHelperApp::getAvailableApps('payment');

		$required = true;
		foreach($apps as $app){
			if(!($app->getPublished()))
				continue;

			if($app->getType() != 'adminpay')
				continue;

			//if at least on app is found.
			$required = false;
			break;
		}

		if(!$required){
			$this->_message = 'COM_PAYPLANS_SETUP_ADMINPAY_CREATED';
 			return $this->_required=false;
 		}

 		return $this->_required=true;
	}

	function doApply()
	{
		// do not create adminpay app many times
		if($this->isRequired()!==true){
			return true;
		}
		
		$db = XiFactory::getDBO();
		$sql =  "INSERT INTO ".$db->quoteName('#__payplans_app')
		       ."( "
		       .$db->quoteName('title').", "
		       .$db->quoteName('type').", "
		       .$db->quoteName('description').", "
		       .$db->quoteName('core_params').", "
		       .$db->quoteName('published')." "
		       .") VALUES ("
		       .$db->Quote('Admin Pay').", "
		       .$db->Quote('adminpay').", "
		       .$db->Quote(XiText::_('COM_PAYPLANS_APP_ADMINPAY_DESCRIPTION')).", "
		       .$db->Quote('applyAll=1\n\n').", "
		       .$db->Quote(1).") ";
		       
		$db->setQuery($sql);
		
		$app = XiFactory::getApplication();
		if($db->query()){
			$app->enqueueMessage(XiText::_('COM_PAYPLANS_APP_ADMINPAY_CREATED'));
		}
		else{
			XiError::assert(0, XiText::_('COM_PAYPLANS_APP_ADMINPAY_CREATION_ERROR'), XiError::WARNING);
		}		       
	}
}
