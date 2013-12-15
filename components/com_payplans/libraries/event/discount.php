<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Prodiscount
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansEventDiscount
{
	/**
	 * on render of invoice, display output
	 */ 
	public static function onPayplansDiscountRequest($invoiceId, $discountCode)
	{
		//Do nothing if enableDiscount is not set
		if(!XiFactory::getConfig()->enableDiscount){
			return true;
		}

		$invoice      = PayplansInvoice::getInstance($invoiceId);
		
		$args         = array($invoice, $discountCode);
		$error 	      = '';
		$allFalse     = true;
		$triggerEvent = true;
		
		//check if invoice is paid/refunded, and don't apply discount
		if( in_array($invoice->getStatus(), array(PayplansStatus::INVOICE_PAID,PayplansStatus::INVOICE_REFUNDED)) ){
			$triggerEvent = false;
			$error        = XiText::_('COM_PAYPLANS_PRODISCOUNT_CANT_APPLY_DISCOUNT_ON_THIS_INVOICE');
			$allFalse 	  = false;
		}
		
		//trigger the discount
		if($triggerEvent){
			$results   = PayplansHelperEvent::trigger('onPayplansApplyDiscount', $args, '', $invoice);

			foreach($results as $result){
				// check if app returned true/false OR error string
				if(is_bool($result)==false){
					$error .= $result . ' ';
				}
	
				if($result !== false){
					$allFalse = false;
				}
			}
		}

		if($allFalse){
			$error = XiText::_('COM_PAYPLANS_PRODISCOUNT_ERROR_INVALID_CODE');
			//check for direct discount by admin
			if(XiFactory::getApplication()->isAdmin())
			{	$error = self::_checkForDirectDiscount($invoice, $discountCode); 
		
		    }
		}

		// order have been updated
		$response = XiFactory::getAjaxResponse();
		
		// IMP: When discount applied successfully then trigger the event
		if(!$allFalse){
			PayplansHelperEvent::trigger('onPayplansDiscountAfterApply', $args, '', $invoice);
		}
		
		// this is for when appliying discount through admin panel
		if(XiFactory::getApplication()->isAdmin()){
			$currency  = $invoice->getCurrency();
			$modifiers = $invoice->getModifiers();
			
			$response->addScriptCall('payplans.jQuery(\'div[name="discount"]\').html',self::getFormattedAmount($currency,$invoice->getDiscount()));
			$response->addScriptCall('payplans.jQuery(\'span[id="pp-discount-spinner"]\').removeClass',"loading");	
			$response->addScriptCall('payplans.jQuery(\'div[name="taxamount"]\').html',self::getFormattedAmount($currency,$invoice->getTaxAmount())); 
			$response->addScriptCall('payplans.jQuery(\'div[name="total"]\').html',self::getFormattedAmount($currency, $invoice->getTotal()));
			$response->addScriptCall('payplans.jQuery(\'div[name="ppmodifiers"]\').html',PayplansHelperTemplate::partial('default_partial_modifier_table', compact('invoice', 'modifiers')));
			$response->addScriptCall('payplans.discount.error',$error);					
			$response->sendResponse();
		}
		
		// if error = '', then no error will be shown
		$response->addScriptCall('payplans.discount.error',$error);
		$response->addScriptCall('payplans.jQuery(\'.payplans\').find(\'.discount-amount > .pp-amount\').html', PayplansHelperFormat::displayAmount($invoice->getDiscount()));
		$response->addScriptCall('payplans.jQuery(\'.payplans\').find(\'.first-tax-amount > .pp-amount\').html', PayplansHelperFormat::displayAmount($invoice->getTaxAmount()));
		$response->addScriptCall('payplans.jQuery(\'.payplans\').find(\'.first-discount-amount > .pp-amount\').html', PayplansHelperFormat::displayAmount($invoice->getDiscount()));

		//because of discount, tax will be updated so update that also
		$response->addScriptCall('payplans.jQuery(\'.payplans\').find(\'.tax-amount > .pp-amount\').html', PayplansHelperFormat::displayAmount($invoice->getTaxAmount()));
		$response->addScriptCall('payplans.jQuery(\'.payplans\').find(\'.regular-amount > .pp-amount\').html',PayplansHelperFormat::displayAmount($invoice->getRegularAmount())); 
		$response->addScriptCall('payplans.jQuery(\'.payplans\').find(\'.first-amount > .pp-amount\').html',PayplansHelperFormat::displayAmount($invoice->getTotal()));
		$response->addScriptCall('payplans.jQuery(\'.total-first-amount\').show');
		$response->addScriptCall('payplans.jQuery(\'#app_discount_code_submit\').button(\'reset\');');		
		
		// If total amount is 0 after applying discount code then disable payment method
		if(floatval($invoice->getTotal()) == floatval(0) && !$invoice->isRecurring()){
		   $response->addScriptCall('payplans.jQuery(\'.payplans\').find(\'.pp-payment-method\').html',XiText::_('COM_PAYPLANS_ORDER_NO_PAYMENT_METHOD_NEEDED'));
		}

		$response->sendResponse();
	}
	
    public static function getFormattedAmount($currency, $amount)
	{
		return PayplansHelperTemplate::partial('default_partial_amount', compact('currency', 'amount'));
	}
    /**
	 * Check if the given discount is a direct discount
	 */
	protected static function _checkForDirectDiscount($object, $discount)
	{
		$params     = new stdClass();
		$percentage = false;
		$error ="";
		
		//Percentage discount
		if(strrpos($discount, '%')){
			$percentage = true;
			$discount   = substr($discount, 0, strrpos($discount, '%'));
			$params->serial = PayplansModifier::PERCENT_DISCOUNT;
		}
		
		//if discount is not numeric and not greater than 0 then do nothing
		if(!is_numeric( $discount ) && $discount <= 0){
			return $error = XiText::_('COM_PAYPLANS_PRODISCOUNT_ERROR_INVALID_CODE');
		} 
		 
		$discountAmount = $discount;
		//if discount is in terms of percentage then calculate the
	    //percentage amount.
		if($percentage){
			$discountAmount= ($discount * $object->getTotal())/100;
		}	
        	
		// if amount of applied percentage is greater than total or 
		// fixed discount is greater then subtotal then do nothing 
		if( $discountAmount > $object->getTotal()){
			return XiText::_('COM_PAYPLANS_PRODISCOUNT_ERROR_EXCEED_TOTAL_AMOUNT');
		}
		
		//add modifier if it is a direct discount
	   	$params->message    = XiText::_('COM_PAYPLANS_PRODISCOUNT_DIRECT_DISCOUNT_BY_ADMIN');
	   	$params->amount     = -1 * $discount;
	   	$params->percentage = $percentage;
		$object->addModifier($params);
		//also save invoice when modifier applies
        $object->save();
        return $error;
	}
}
