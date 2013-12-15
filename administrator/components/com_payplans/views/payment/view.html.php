<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansadminViewPayment extends XiView
{
	protected function _adminGridToolbar()
	{
		XiHelperToolbar::openPopup('newPayment','preview','Create Plugin');
		XiHelperToolbar::editList();
		XiHelperToolbar::divider();
		XiHelperToolbar::openPopup('searchRecords', 'search', 'search.png', 'COM_PAYPLANS_TOOLBAR_SEARCH', true );
	}
	
	protected function _adminEditToolbar()
	{   
        $model = $this->getModel();
		XiHelperToolbar::apply();
        XiHelperToolbar::save();
		XiHelperToolbar::cancel();
		XiHelperToolbar::divider();
	}

	function edit($tpl=null, $itemId=null)
	{
		$itemId  = ($itemId === null) ? $this->getModel()->getState('id') : $itemId;

		$payment = PayplansPayment::getInstance( $itemId);

		// if new payment is created the get data from post
		if(!$itemId){
			$adminApps = array_shift(XiFactory::getInstance('app', 'model')
											->loadRecords(array('type'=>'adminpay', 'published'=> 1)));

			$appId = $adminApps->app_id;

			XiError::assert($appId, XiText::_('COM_PAYPLANS_ERROR_INVALID_APPLICATION_ID'));

			$payment->set('app_id',   $appId);
		}

		$transactions = XiFactory::getInstance('transaction', 'model')
									->loadRecords(array('payment_id'=>$itemId));

		$logRecords	= XiFactory::getInstance('log', 'model')
								->loadRecords(array('object_id'=>$itemId, 'class'=>'PayplansPayment'));

		// get app transaction record html
		$args = array($payment);
		$results = PayplansHelperEvent::trigger('onPayplansPaymentRecord', $args,'payment',$payment);

		$transaction = PayplansTransaction::getInstance();
		
		$gateway_params = $payment->getGatewayParams()->toArray();
		$form = $payment->getModelform()->getForm($payment);
		$this->assign('form', $form );
		$this->assign('gateway_params',$gateway_params);
		$this->assign('transaction_html', implode(' ',$results));
		$this->assign('payment', $payment);
		$this->assign('user', PayplansUser::getInstance($payment->getBuyer()));
		$this->assign('log_records', 	 $logRecords);
		$this->assign('transaction_records', 	 $transactions);
		$this->assign('transaction', 	 $transaction);
		
		return true;
	}

	public function _getDynamicJavaScript()
	{
		$url		 =	"index.php?option=com_{$this->_component}&view={$this->getName()}";

		ob_start(); ?>

		payplansAdmin.payment_newPayment = function()
		{
			payplans.url.modal("<?php echo "$url&task=newPayment";?>");
			return false;
		}

		<?php
		$js = ob_get_contents();
		ob_end_clean();
		return $js;
	}

	public function newPayment()
	{
		$this->_setAjaxWinTitle(XiText::_('COM_PAYPLANS_PAYMENT_NEW_TEXT_POPUP_TITLE'));

		$onClick = "xi.submitAjaxForm('payplans-payment-new-next');";
		//$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_AJAX_NEXT_BUTTON'),$onClick );
		$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_AJAX_CANCEL_BUTTON'),'xi.ui.dialog.close();');
		$this->_setAjaxWinAction();
		$this->_setAjaxWinHeight('150');
		return true;
	}
	
	public function statusHelp()
	{
		$this->_setAjaxWinTitle(XiText::_('COM_PAYPLANS_STATUS_DISPLAY_DETAIL'));
		$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_AJAX_CANCEL_BUTTON'),'xi.ui.dialog.close();');
		$this->_setAjaxWinAction();
		$this->_setAjaxWinHeight('200');
		return true;
		
    }
}

