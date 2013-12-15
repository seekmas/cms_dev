<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansadminViewInvoice extends XiView
{
	protected function _adminGridToolbar()
	{
		XiHelperToolbar::editList();
		XiHelperToolbar::divider();
		XiHelperToolbar::delete();
		XiHelperToolbar::openPopup('searchRecords', 'search', 'search.png', 'COM_PAYPLANS_TOOLBAR_SEARCH', true );
	}
	
	protected function _adminEditToolbar()
	{   
        $itemId = $this->getModel()->getId();

		XiHelperToolbar::apply();
		XiHelperToolbar::save();
		XiHelperToolbar::cancel();
		XiHelperToolbar::divider();
		//don't display delete button when creating new instance of object 

	    if($itemId)
		{
	    	$invoice	= PayplansInvoice::getInstance($itemId);
	    	$txnRecords = $invoice->getTransactions();
	    	if($invoice->getStatus() != PayplansStatus::INVOICE_PAID && empty($txnRecords))
	    	{
				XiHelperToolbar::deleteRecord();
				XiHelperToolbar::divider();
	    	}

			XiHelperToolbar::addNew('sendInvoiceLink','COM_PAYPLANS_INVOICE_TOOLBAR_SEND_INVOICE');
	    }
}
	
	function edit($tpl=null, $itemId=null)
	{
		$itemId = ($itemId === null) ? $this->getModel()->getState('id') : $itemId;
		$invoice	= PayplansInvoice::getInstance($itemId);

		$logRecords	  	= XiFactory::getInstance('log', 'model')
								->loadRecords(array('object_id'=>$itemId, 'class'=>'PayplansInvoice'));

		// get lib instance of subscription/payment
		$user		  	= PayplansUser::getInstance($invoice->getBuyer());
		
		if($itemId){
			$modifiers	   = $invoice->getModifiers();
			$walletRecord  = XiFactory::getInstance('wallet', 'model')
									->loadRecords(array('invoice_id'=>$itemId));
			$txnRecords = XiFactory::getInstance('transaction', 'model')
									->loadRecords(array('invoice_id'=>$itemId));

			$this->assign('txn_records', $txnRecords);			
			$this->assign('wallet_records', $walletRecord);
			$this->assign('modifiers', $modifiers);
		}
		
		$form = $invoice->getModelform()->getForm($invoice);
		$this->assign('form', $form );
		
		$this->assign('user', 		  	$user);
		$this->assign('invoice', 		$invoice);
		$this->assign('log_records', 	$logRecords);
		
		return true;
	}
	
	public function sendInvoiceLink()
	{ 
		$editor = XiFactory::getEditor();
		
		$itemId     = $this->getModel()->getId();
		$invoice	= PayplansInvoice::getInstance($itemId);

		$this->assign('editor', $editor);
		$this->assign('invoice', $invoice);	
		return true;
		
	}
	
	public function _getDynamicJavaScript()
	{
		$url	=	"index.php?option=com_payplans&view={$this->getName()}";
		$itemId = $this->getModel()->getId();
		ob_start(); ?>

		payplansAdmin.invoice_newInvoice = function()
		{
			payplans.url.modal("<?php echo "$url&task=newInvoice"; ?>");
			
			// do not submit form
			return false;
		}
		payplansAdmin.invoice_sendInvoiceLink = function()
		{
			var theurl = 'index.php?option=com_payplans&view=invoice&task=sendInvoiceLink&invoice_id=<?php echo $itemId;?>&tmpl=component';
			
			xi.ui.dialog.create(
				{url:theurl, data:{iframe:true, id:'pp-admin-invoice-sendlink'}},
				'<?php echo XiText::_('COM_PAYPLANS_INVOICE_EMAIL_INVOICE_LINK');?>',
				750, 550
			);
			
    		xi.ui.dialog.button(
    			[
	    			{
	    				id :    "button-send-invoice",	    			
	    				click : 'payplans.invoice.mailInvoiceLink();',
	    				text  : '<?php echo XiText::_('COM_PAYPLANS_AJAX_SEND_MAIL_BUTTON');?>'
	    			},
	    			{
	    				click : 'xi.ui.dialog.close();',  
	    				text: '<?php echo XiText::_('COM_PAYPLANS_AJAX_CLOSE_BUTTON');?>'
	    			}
    			]
    		);
    		
    		return false;
		}
		
		payplansAdmin.invoice_deleteModifier= function(url)
		{
			xi.jQuery.apprise('<?php echo XiText::_("COM_PAYPLANS_JS_ARE_YOU_SURE_TO_DELETE");?>', 
					{'verify':true}, 
					function(r){
						if(r){
							payplans.ajax.go(url);
						} 
						else{
							return false;
						}
					});
		}
		<?php
		$js = ob_get_contents();
		ob_end_clean();
		return $js;
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
	
	function mailInvoice()
	{
		return true;
	}
	
	public function statusHelp()
	{
		$this->_setAjaxWinTitle(XiText::_('COM_PAYPLANS_INVOICE_STATUS_DISPLAY'));
		$this->_setAjaxWinAction();
		$this->_setAjaxWinHeight('350');
		return true;
		
    }
}

