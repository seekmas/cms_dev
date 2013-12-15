<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


 class PayplansadminViewUser extends XiView
{
	protected function _adminGridToolbar()
	{
		XiHelperToolbar::editList();
		XiHelperToolbar::divider();	
		XiHelperToolbar::custom( 'selectPlan', 'assign.png', 'assign.png', 'COM_PAYPLANS_USER_TOOLBAR_APPLY_PLAN', true );
		XiHelperToolbar::openPopup('searchRecords', 'search', 'search.png', 'COM_PAYPLANS_TOOLBAR_SEARCH', true );	
	}
	
	protected function _adminEditToolbar()
	{
		XiHelperToolbar::custom('rechargeWallet', 'wallet.png', 'wallet.png', 'COM_PAYPLANS_USER_TOOLBAR_RECHARGE_WALLET', true );
		XiHelperToolbar::apply();
		XiHelperToolbar::save();
		XiHelperToolbar::cancel();
	}
	
	function _displayGrid($records)
	{
		parent::_displayGrid($records);

		$subRecords   = array();
		$subscriptions = array();
		// get subscription of each user
		$userids = array_keys($records);
		$subRecords = PayplansHelperUser::getSubscription($userids);
		
		// get all plans also
		$planids = array();
		if(empty($subRecords)){
			$this->assign('plans', $planids);
			$this->assign('subscriptions', $subscriptions);
			return true;
		}
		foreach($subRecords as $sub){
			if(!isset($plans[$sub->plan_id])){
				$planids[$sub->plan_id] = $sub->plan_id; 
			}
			
			$subscriptions[$sub->user_id][] = $sub;
		}

		$filter = array('plan_id' => array(array('IN', "(".implode(",", $planids).")")));
		$this->assign('plans', PayplansHelperPlan::get($filter));
		$this->assign('subscriptions', $subscriptions);
		return true;
	}
	
	function edit($tpl=null,$itemId = null)
	{
		$itemId  = ($itemId === null) ?  $this->getModel()->getState('id') : $itemId;
		
		// assert if user id is not available
		XiError::assert($itemId, XiText::_('COM_PAYPLANS_ERROR_INVALID_USER_ID'));
		
		$orderRecords = XiFactory::getInstance('order', 'model')
									->loadRecords(array('buyer_id'=>$itemId));
		$logRecords   = XiFactory::getInstance('log', 'model')
								->loadRecords(array('object_id'=>$itemId, 'class'=>'PayplansUser'));
		$walletRecords = XiFactory::getInstance('wallet', 'model')
									->loadRecords(array('user_id'=>$itemId));
		$walletRechargeInvoice = XiFactory::getInstance('invoice','model')
									->loadRecords(array('user_id'=>$itemId, 'status'=>PayplansStatus::INVOICE_WALLET_RECHARGE));
																		
		$user 		  = PayplansUser::getInstance( $itemId);
		$order 		  = PayplansOrder::getInstance();
		$payment	  = PayplansPayment::getInstance();
		$wallet		  = PayplansWallet::getInstance();
		$walletBalance = $user->getWalletBalance();

		
		$form = $user->getModelform()->getForm($user);
		//load extra xml file 
		$form->loadFile(PAYPLANS_PATH_XML.DS.'user.preference.xml', false, '//config');
		$preference    = $user->getPreference();
	    $data          = array('preference'=>$preference->toArray());
	    $form->bind($data);
		$this->assign('form', $form );
		
		
		
		$this->assign('user', 			$user);
		$this->assign('order', 			$order);
		$this->assign('payment', 		$payment);
		$this->assign('wallet', 		$wallet);
		
		$this->assign('order_records',  	$orderRecords);
		$this->assign('log_records', 	    $logRecords);
		$this->assign('wallet_records', 	$walletRecords);
		$this->assign('wallet_balance', 	$walletBalance);
		$this->assign('wallet_Invoice',     $walletRechargeInvoice);
		
		return true;
	}
	
	public function selectPlan()
	{
		$this->_setAjaxWinTitle(XiText::_('COM_PAYPLANS_AJAX_APPLY_PLAN'));
		$onClick = "payplans.admin.user.applyPlan();";
		$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_AJAX_APPLY_BUTTON'),$onClick);
		$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_AJAX_CLOSE_BUTTON'),'xi.ui.dialog.close();');

		$this->_setAjaxWinAction();
		$this->_setAjaxWinHeight('220');

		return true;
	}
	
	public function rechargeWallet()
	{
		$userId = $this->getModel()->getId();
		
		$onClick = "payplans.wallet.recharge($userId);";		
		$this->_setAjaxWinTitle(XiText::_('COM_PAYPLANS_WALLET_RECHARGE_DETAIL'));
		$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_WALLET_RECHARGE_PROCEED'),$onClick, 'onproceed');
		$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_AJAX_CANCEL_BUTTON'), 'xi.ui.dialog.close();');
		$this->_setAjaxWinAction();
		$this->_setAjaxWinHeight('220');
		
		return true;		
	}
	
	public function _getDynamicJavaScript()
	{
		$url	=	"index.php?option=com_payplans&view={$this->getName()}";
		$itemId = $this->getModel()->getId();
		ob_start(); ?>

		payplansAdmin.user_selectPlan = function()
		{
			payplans.url.modal("<?php echo "$url&task=selectPlan"; ?>");
			
			// do not submit form
			return false;
		}
		
		payplansAdmin.user_rechargeWallet = function()
		{
			payplans.url.modal("<?php echo "$url&task=rechargeWallet&user_id=".$itemId; ?>");
			
			// do not submit form
			return false;
		}

		<?php
		$js = ob_get_contents();
		ob_end_clean();
		return $js;
	}

	public function search()
	{
		$this->assign('json', $this->get('results'));
		return true;
	}
}

