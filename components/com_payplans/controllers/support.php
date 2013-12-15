<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplanssiteControllerSupport extends XiController
{
	protected 	$_defaultTask = 'notask';
  
	function emailform($user = null)
	{
		return true;
	}
	
	function sendemail()
	{
		$args 	= JRequest::getVar('event_args', array());
		
		// IMP : we are working for ajax only for now
		if(JRequest::getBool('isAjax',	false) == false){
			return false;
		}
		
		$user = PayplansFactory::getUser();		
		$subject = $args[0];
		$from	 = $args[1];
		$body	 = sprintf(XiText::_('COM_PAYPLANS_SUPPORT_EMAIL_BODY'),$user->username,$args[1],$args[2]);	
		
		$admins = XiHelperJoomla::getUsersToSendSystemEmail();		
		
		$first = array_shift($admins);
		// get other super admin users email
		$cc = null;
		foreach ( $admins as $admin )
		{
			$cc[] = $admin->email;
		}
		
        $mail = XiFactory::getMailer();
		if(!$mail->sendMail($from, $from, $first->email, $subject, $body, 0, null, $cc)){
			$this->setTemplate('error');
			return true;
		}
		
		$this->setTemplate('sent');
		return true;
	}
	
	// render the data in formatted way
	function format()
	{
		$this->setTemplate('partial_format_'.JRequest::getVar('object',''));
		$this->getView()->assign('timer', JRequest::getVar('timer', null));		
		return true;
	}
}

