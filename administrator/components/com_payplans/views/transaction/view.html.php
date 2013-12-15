<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansadminViewTransaction extends XiView
{
	protected function _adminGridToolbar()
	{
		XiHelperToolbar::addNew('newTransaction');
		XiHelperToolbar::editList();
		XiHelperToolbar::divider();
		XiHelperToolbar::openPopup('searchRecords', 'search', 'search.png', 'COM_PAYPLANS_TOOLBAR_SEARCH', true );
	}
	
	protected function _adminEditToolbar()
	{   
		$itemId = $this->getModel()->getState('id');
		
		if(!$itemId){
        	XiHelperToolbar::apply();
			XiHelperToolbar::save();
		}
		
		XiHelperToolbar::cancel();
	}
	
	function edit($tpl=null, $itemId=null)
	{	
		$itemId = ($itemId === null)? $this->getModel()->getState('id') : $itemId;
		
		$transaction = PayplansTransaction::getInstance($itemId);
		$user		 = PayplansUser::getInstance($transaction->getBuyer());
		
		if(!$itemId){
			$invoiceId = JRequest::getVar('invoice_id', 0);

			$invoice   = PayplansInvoice::getInstance($invoiceId);
			$payment   = $invoice->getPayment();
			$paymentId = 0;
			if($payment){
				$paymentId = $payment->getId();
			}
			$user	   = $invoice->getBuyer(PAYPLANS_INSTANCE_REQUIRE);

			$transaction->set('user_id',    $user->getId())
						->set('payment_id', $paymentId)
						->set('invoice_id', $invoiceId)
						->set('amount', 	$invoice->getTotal());
			$this->setTpl('new');
		}
		
		$logRecords	 = XiFactory::getInstance('log', 'model')
								->loadRecords(array('object_id'=>$itemId, 'class'=>'PayplansTransaction'));
		
		$paymentId    = $transaction->getPayment();
		$payment      = $transaction->getPayment(PAYPLANS_INSTANCE_REQUIRE);
		$txnRecord    = '';
		
		if(!empty($paymentId)  && ($payment instanceof PayplansPayment)){
			$paymentApp = $payment->getApp(PAYPLANS_INSTANCE_REQUIRE);
			$txnRecord 	= $paymentApp->onPayplansTransactionRecord($transaction);
		}
		$form = $transaction->getModelform()->getForm($transaction);
		$this->assign('form', $form );
		$this->assign('transaction_html', $txnRecord);
		$this->assign('transaction', 	  $transaction);
		$this->assign('user', 		 	  $user);
		$this->assign('log_records', 	  $logRecords);

		if($itemId && !empty($paymentId)  && ($payment instanceof PayplansPayment)){
		// display refund button	
		$invoice = $transaction->getInvoice(PAYPLANS_INSTANCE_REQUIRE);
		$show 	 = $invoice->displayRefundButton($payment); 
		$this->assign('show_refund_option', $show);
		}		
		return true;
	}
	
	public function refund()
	{	
		$itemId = $this->getModel()->getState('id');
		if(empty($itemId)){
			$itemId = JRequest::getVar('transaction');
		}
		
		$transaction = PayplansTransaction::getInstance($itemId);
	
		if($this->confirm == false){
			$this->setTpl(__FUNCTION__.'_confirm');
			$url = 'index.php?option=com_payplans&view=transaction&task=refund&confirm=1&transaction='.$itemId;
					
			$this->_setAjaxWinTitle(XiText::_('COM_PAYPLANS_TRANSACTION_REFUND_CONFIRM_WINDOW_TITLE'));
			$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_TRANSACTION_DETAIL_REFUND'), "payplans.admin.transaction.refund('".$url."'); ","payplans_refund_confirm");
			$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_AJAX_CANCEL_BUTTON'),'xi.ui.dialog.close();');
			$this->_setAjaxWinAction();
			$this->_setAjaxWinHeight('250');
			
			$this->assign('transactionAmt', $transaction->getAmount());
			$this->assign('url', $url);
			return true;
		}
		
		$successOrFail = PayplansHelperTransaction::refundRequest($transaction, $this->refund_amount);
		
		$tpl = 'failure';
		$this->_setAjaxWinTitle(XiText::_('COM_PAYPLANS_TRANSACTION_DETAIL_REFUND_FAIL'));
		
		if($successOrFail == true){
			$tpl = 'success';
			$this->_setAjaxWinTitle(XiText::_('COM_PAYPLANS_TRANSACTION_DETAIL_REFUND_SUCCESS'));
		}
		 
		$this->setTpl(__FUNCTION__.'_'.$tpl);
		$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_AJAX_CLOSE_BUTTON'),'xi.ui.dialog.close(); window.location.reload();');
		$this->_setAjaxWinAction();
		$this->_setAjaxWinHeight('170');

		$this->assign('refund_amount', $this->refund_amount);
		return true;
	}
	
	public function _getDynamicJavaScript()
	{
		$url		 =	"index.php?option=com_{$this->_component}&view={$this->getName()}";

		ob_start(); ?>

		payplansAdmin.transaction_newTransaction = function()
		{
			payplans.url.modal("<?php echo "$url&task=newTransaction";?>");
			return false;
		}

		<?php
		$js = ob_get_contents();
		ob_end_clean();
		return $js;
	}

	public function newTransaction()
	{
		$this->_setAjaxWinTitle(XiText::_('COM_PAYPLANS_TRANSACTION_NEW_TEXT_POPUP_TITLE'));

		$onClick = "xi.submitAjaxForm('payplans-transaction-new-next');";
		$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_AJAX_CANCEL_BUTTON'),'xi.ui.dialog.close();');
		$this->_setAjaxWinAction();
		$this->_setAjaxWinHeight('150');
		return true;
	}
	
	function _displayGrid($records)
	{
		$uesrids = array();
		foreach($records as $record){
			$userids[] = $record->user_id;
		}
		
		$users = PayplansHelperUser::get($userids);
		$this->assign('users', $users);
		
		return parent::_displayGrid($records);
	}
}

