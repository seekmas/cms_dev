<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplanssiteControllerUser extends XiController
{
	protected 	$_defaultTask = 'notask';
  
	function noaccess($userId = null)
	{
		$userId = ($userId === null) ? XiFactory::getUser($userId)->id : $userId;
		
		$this->setTemplate('noaccess');
		return true;
	}
	
	public function trigger($event=null,$args=null)
	{
		parent::trigger($event,$args);
	}
	
	public function checkusername()
	{
		$args 	= $this->_getArgs();
		
		$result = XiFactory::getInstance('user', 'model')->loadRecords(array('username' => $args[1]));
				
		$response = XiFactory::getAjaxResponse();
		
		$msg = '';
		$valid = true;
		if(count($result) >= 1){
			$msg = XiText::_('COM_PAYPLANS_USER_USERNAME_ALREADY_REGISTERED');
			$valid = false;
		}
		$response->addScriptCall('xi.registration.validate', $args[0], $valid, $msg);
		$response->sendResponse();
	}
	
	public function checkemail()
	{
		$args 	= $this->_getArgs();
		
		$response = XiFactory::getAjaxResponse();
		
		$msg = '';
		$valid = true;
		
		jimport('joomla.mail.helper');
		if(!JMailHelper::isEmailAddress($args[1])){
			$msg = XiText::_('COM_PAYPLANS_INVALID_EMAIL_ADDRESS');
			$valid = false;
		}
		
		if($valid){
			$result = XiFactory::getInstance('user', 'model')->loadRecords(array('email' => $args[1]));
			if(count($result) >= 1){
				$msg = XiText::_('COM_PAYPLANS_EMAIL_ALREADY_REGISTERED');
				$valid = false;
			}
		}
		
		$response->addScriptCall('xi.registration.validate', $args[0], $valid, $msg);
		$response->sendResponse();
	}

	function showloginpopup()
	{
		return true;
	}

    function userAuthenticationPopup()
	{
		return true;
	}
	
	function login()
	{		
		$args 	   = $this->_getArgs();
		$response  = XiFactory::getAjaxResponse(); 
		$user 	   = PayplansUser::getInstance();
		
		//if any user is already login then first logout and then again login as another
		$userId = PayplansFactory::getUser()->id;
		if($userId){
			XiFactory::getApplication()->logout($userId);
			$response->addScriptCall('payplans.user.login',$args['returnUrl']);
			$response->sendResponse();
		}
		
		if(!$user->login($args['username'], $args['password'])){
			$response->addScriptCall('payplans.jQuery("span.err-payplansLoginError").html', "<div class='pp-row'>".XiText::_('COM_PAYPLANS_LOGIN_FAILED')."</div>");
			$response->addScriptCall('payplans.jQuery("input.payplansLoginUsername").val', "");
			$response->addScriptCall('payplans.jQuery("input.payplansLoginPassword").val', "");
			$response->sendResponse();
		}
		
		PayplansFactory::redirect(XiRoute::_($args['returnUrl']), true);
	}
 
}

