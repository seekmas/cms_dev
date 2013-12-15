<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplanssiteViewSupport extends XiView
{
	function emailform()
	{
		$this->setTpl(__FUNCTION__);
				
		$this->assign('from', XiFactory::getUser()->get('email'));
		$this->_setAjaxWinTitle(XiText::_('COM_PAYPLANS_SUPPORT_EMAILFORM_TITLE'));
		$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_SUPPORT_EMAILFORM_SEND_BUTTON'),'payplans.support.sendemail();',null,'btn btn-primary');
		$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_AJAX_CANCEL_BUTTON'),'xi.ui.dialog.close();');
		$this->_setAjaxWinAction();
		$this->_setAjaxWinHeight('325');
		
		return true;
	}
	
	function sendemail()
	{
		$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_AJAX_CLOSE_BUTTON'),'xi.ui.dialog.close();');
		$this->_setAjaxWinAction();
		$this->_setAjaxWinAutoclose(3000);
		$this->_setAjaxWinHeight('210');
		return true;
	}
	
}

