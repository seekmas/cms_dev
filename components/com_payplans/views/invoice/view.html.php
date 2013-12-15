<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplanssiteViewInvoice extends XiView
{
	public function confirm()
	{
		//Only one order will be confirmed at a time
		$itemId = $this->getModel()->getId();

		//now record is always an array, pick the first only
		$record 	= $this->getModel()->loadRecords(array('id'=>$itemId),array(), true);
		$record 	= array_shift($record);

		// get the instance of invoice
		$invoice = PayplansInvoice::getInstance($itemId, null, $record);
		
		// get payment all application, application on this plan
		$paymentAppsInstance = array();
		// trigger apps
		if($invoice instanceof PayplansIfaceApptriggerable){
			$paymentAppsInstance = PayplansHelperApp::getApplicableApps('payment', $invoice);
		}
		
		// raise error when plan is not free plan and no payment app is attached with it
		if(floatval(0) != floatval($invoice->getTotal())){
			// assert when no payment application is available
			XiError::assert(!empty($paymentAppsInstance), XiText::_('COM_PAYPLANS_NO_APPLICATION_AVAILABLE_FOR_PAYMENT'));
		}

		// XITODO : move to helper
		$paymentApps = array();
		foreach($paymentAppsInstance as $appId => $app){
			$paymentApp['id'] 	  = $appId;
			$paymentApp['title'] = $app->getTitle();
			array_push($paymentApps, $paymentApp);
		}

		// assign required variables
		$this->assign('payment_apps',  	$paymentApps);
		$this->assign('invoice',$invoice);
		$this->assign('user', 	$invoice->getBuyer(PAYPLANS_INSTANCE_REQUIRE));

		return true;
	}
	
	public function _getDynamicJavaScript()
	{
		$js = '';
		
		// confirm button is not clicked by user
        if($this->getTask() === 'confirm' && JRequest::getVar('payplans_invoice_confirm', 'BLANK', 'POST') === 'BLANK'){
        
			ob_start(); 
			
			?>
			payplans.jQuery(document).ready(function(){
			
			payplans.jQuery("#payplans-order-confirm").addClass('disabled');
			
			setInterval(function(){
			
				
				if(!(payplans.jQuery('form[name="site<?php echo $this->getName(); ?>Form"]').find("input,textarea,select").not(':hidden').jqBootstrapValidation("hasErrors"))) {
					payplans.jQuery('#payplans-order-confirm').removeClass('disabled');
				}
				else{
					payplans.jQuery("#payplans-order-confirm").addClass('disabled');
				}

			},100);
			
				payplans.jQuery('form[name="site<?php echo $this->getName(); ?>Form"]').submit(function(){
					payplans.jQuery('#payplans-order-confirm').addClass('disabled');
					setTimeout("payplans.jQuery('#payplans-order-confirm').removeClass('disabled')", 5000);
					return true;
				});
			});
			<?php
			
			$js = ob_get_contents();
			ob_end_clean();
        }
        
		return $js;
	}
	
	
	function display($cachable = false, $urlparams = false)
	{
		$itemId = $this->getModel()->getId();
		if($itemId){
			$invoice = PayplansInvoice::getInstance($itemId);
			$user    = $invoice->getBuyer(PAYPLANS_INSTANCE_REQUIRE);
			$this->assign('invoice', $invoice);
			$this->assign('user', $user);
			return true;
		}
		
		$object_key  = JRequest::getVar('object_key', '');
		$object_type = JRequest::getVar('object_type', '');		
		$object_id   = XiHelperUtils::getIdFromKey($object_key);
		$object 	 = call_user_func(array($object_type, 'getInstance'), $object_id);
		
		$invoices = XiFactory::getInstance('invoice', 'model')->loadRecords(array('object_id' => $object_id, 'object_type' => $object_type));
		
		$invoice  = PayplansInvoice::getInstance();
		$this->assign('invoice',  $invoice);
		$this->assign('invoices', $invoices);
		return true;
	}
	
	function thanks()
	{
		$itemId = $this->getModel()->getId();
		if($itemId){
			$invoice = PayplansInvoice::getInstance($itemId);
			$user    = $invoice->getBuyer(PAYPLANS_INSTANCE_REQUIRE);
			$this->assign('invoice', $invoice);
			$this->assign('user', $user);
			$this->setTpl('complete');
			return true;
		}
	}
}

