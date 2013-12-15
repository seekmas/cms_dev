<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplanssiteViewOrder extends XiView
{
	public function terminate()
	{
		$orderKey = JRequest::getVar('order_key', false);
		
		if($this->confirm == false){
			$this->setTpl(__FUNCTION__.'_confirm');
			$url = 'index.php?option=com_payplans&view=order&task=terminate&confirm=1&order_key='.$orderKey;
			
			$this->_setAjaxWinTitle(XiText::_('COM_PAYPLANS_ORDER_TERMINATE_CONFIRM_WINDOW_TITLE'));
			$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_ORDER_TERMINATE_CONFRM_WINDOW_YES'), "payplans.site.onclickYes('".$url."');", 'button-clickonyes');
			$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_AJAX_CANCEL_BUTTON'),'xi.ui.dialog.close();');
			$this->_setAjaxWinAction();
			$this->_setAjaxWinHeight('200');
			return true;
		}
		
		$this->setTpl(__FUNCTION__);
		$this->_setAjaxWinTitle(XiText::_('COM_PAYPLANS_ORDER_TERMINATE_STATUS_WINDOW_TITLE'));
		$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_ORDER_TERMINATE_STATUS_WINDOW_CLOSE'),'xi.ui.dialog.close(); window.location.reload();');
		$this->_setAjaxWinAction();
		$this->_setAjaxWinHeight('200');
		
		return true;
	}		
}
