<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();


 class PayplanssiteViewUser extends XiView
{
	function showLoginPopup()
	{
		$returnUrl   = base64_decode(JRequest::getVar('returnUrl'));
		$onClick     = "payplans.user.login('".$returnUrl."')";
		
		$this->_setAjaxWinTitle(XiText::_('COM_PAYPLANS_LOGIN_TITLE'));
		$this->_addAjaxWinAction('Login',$onClick,null,'btn btn-primary');
		$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_AJAX_CANCEL_BUTTON'), 'xi.ui.dialog.close();');
		$this->_setAjaxWinAction();
		$this->_setAjaxWinHeight('auto');
		$this->_setAjaxWinWidth('490');
		
		$this->setTpl('login');
		return true;
	}

    function userAuthenticationPopup()
	{
		$returnUrl   = base64_decode(JRequest::getVar('returnUrl'));
		$message     = JRequest::getVar('message');
		$this->assign('message',XiText::_('COM_PAYPLANS_LOGIN_SUBSCRIBE_MESSAGE'));
		if($message){
		   $this->assign('message',$message);
		}
		$onClick     = "payplans.user.login('".$returnUrl."')";
		
		$this->_addAjaxWinAction('Login',$onClick);
		$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_AJAX_CANCEL_BUTTON'), 'xi.ui.dialog.close();');
		$this->_setAjaxWinAction();
		$this->_setAjaxWinHeight('380');
		$this->_setAjaxWinWidth('760');
		
		$this->setTpl('user_authentication');
		return true;
	}
}

