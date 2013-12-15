<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansadminViewOrder extends XiView
{
	protected function _adminGridToolbar()
	{
		XiHelperToolbar::addNew('new');
		XiHelperToolbar::editList();
		XiHelperToolbar::divider();
		XiHelperToolbar::delete();
		XiHelperToolbar::divider();
		XiHelperToolbar::openPopup('searchRecords', 'search', 'search.png', 'COM_PAYPLANS_TOOLBAR_SEARCH', true );
	}
	
	function edit($tpl=null, $itemId=null)
	{
		$itemId = ($itemId === null) ? $this->getModel()->getState('id') : $itemId;
		$order	= PayplansOrder::getInstance($itemId);
			
		// get all subscription/payment of this order id
		$subsRecord 	= $order->getSubscription();
		$userRecord 	= $order->getBuyer(PAYPLANS_INSTANCE_REQUIRE);
		$logRecords		= XiFactory::getInstance('log', 'model')
								->loadRecords(array('object_id'=>$itemId, 'class'=>'PayplansOrder'));
		$invRecords		= $order->getInvoices();
		
		// when we are going to create new order then donot perform following check
 		// show or hide the recurring order cancellation button
		if($order->getSubscription() && $order->isRecurring()){
			$invoice = $order->getLastMasterInvoice(PAYPLANS_INSTANCE_REQUIRE);
			if($invoice && ($payment = $invoice->getPayment())){
				$payment = $invoice->getPayment();
				$app = $payment->getApp(PAYPLANS_INSTANCE_REQUIRE);
				$this->assign('show_cancel_option', $app->getAppParam('allow_recurring_cancel', false));
			}
		}

		$txnRecords   = array();
		$invoice_ids  = array();
		foreach ($invRecords as $record){
			$invoice_ids[] = $record->getId();
		}
		
		//fetch all the related transaction records
		if(!empty($invoice_ids)){
			$txnRecords = XiFactory::getInstance('transaction', 'model')
									->loadRecords(array('invoice_id'=> array(array('IN', "(".implode(",", $invoice_ids).")"))));
		}
		
		$form = $order->getModelform()->getForm($order);
		
		$this->assign('form',$form);
		$this->assign('order', 		  	$order);
		$this->assign('user',  			$userRecord);
		$this->assign('subscr_record',  $subsRecord);
		$this->assign('log_records', 	$logRecords);
		$this->assign('invoice_records',$invRecords);
		$this->assign('txn_records', 	$txnRecords);
		return true;
	}
	
	public function terminate()
	{
		$itemId = $this->getModel()->getState('id');
	
		if($this->confirm == false){
			$this->setTpl(__FUNCTION__.'_confirm');
			$url = 'index.php?option=com_payplans&view=order&task=terminate&confirm=1&order_id='.$itemId;
			
			$this->_setAjaxWinTitle(XiText::_('COM_PAYPLANS_ORDER_TERMINATE_CONFIRM_WINDOW_TITLE'));
			$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_ORDER_TERMINATE_CONFRM_WINDOW_YES'), "payplans.ajax.go('".$url."'); ");
			$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_AJAX_CANCEL_BUTTON'),'xi.ui.dialog.close();');
			$this->_setAjaxWinAction();
			$this->_setAjaxWinHeight('150');
			return true;
		}
		
		$this->setTpl(__FUNCTION__);
		$this->_setAjaxWinTitle(XiText::_('COM_PAYPLANS_ORDER_TERMINATE_STATUS_WINDOW_TITLE'));
		$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_ORDER_TERMINATE_STATUS_WINDOW_CLOSE'),'xi.ui.dialog.close(); window.location.reload();');
		$this->_setAjaxWinAction();
		$this->_setAjaxWinHeight('150');
		
		return true;
	}
	
	function _displayGrid($records)
	{
		// for user caching
		$uesrids = array();
		foreach($records as $record){
			$userids[] 	= $record->buyer_id;
			$orderIds[] 	= $record->order_id;
		}
		
		$subscriptions = PayplansHelperOrder::getSubscriptions($orderIds);
		$this->assign('subscriptions', $subscriptions);
		$users = PayplansHelperUser::get($userids);
		$this->assign('users', $users);
		return parent::_displayGrid($records);
	}
}

