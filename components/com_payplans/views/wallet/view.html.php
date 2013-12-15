<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplanssiteViewWallet extends XiView
{
	public function display()
	{
		$userid 		= XiFactory::getUser()->id;
		$wallets 		= XiFactory::getInstance('wallet', 'model')
									->loadRecords(array('user_id' => $userid));
		$transactions 	= XiFactory::getInstance('transaction', 'model')
									->loadRecords(array('user_id' => $userid));
		
		$this->assign('wallets', $wallets);
		$this->assign('transaction', $transactions);
		return true;
	}

	// display pop-up for recharge request, contain fields for amount and payment method
	public function rechargeRequest()
	{
		$onClick = "payplans.wallet.recharge(0);";		
		$this->_setAjaxWinTitle(XiText::_('COM_PAYPLANS_WALLET_RECHARGE_DETAIL'));
		$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_WALLET_RECHARGE_PROCEED'),$onClick, 'onproceed');
		$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_AJAX_CANCEL_BUTTON'), 'xi.ui.dialog.close();');
		$this->_setAjaxWinAction();
		$this->_setAjaxWinHeight('300');
		
		$this->setTpl('recharge_request_details');
		return true;		
	}
}

