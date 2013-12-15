<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplanssiteViewPayment extends XiView
{
	public function complete()
	{
		$payment_id  = $this->getModel()->getId();
		$payment     = PayplansPayment::getInstance($payment_id);  
		$invoice     = $payment->getInvoice(PAYPLANS_INSTANCE_REQUIRE);
		
		$this->assign('user', $invoice->getBuyer(PAYPLANS_INSTANCE_REQUIRE));
		$this->assign('invoice', $invoice);
		return true;
	}
	
	function pay()
	{
		return true;
	}
	
	function invoice()
	{
			$paymentId  = $this->getModel()->getId();
			$payment	= PayplansPayment::getInstance( $paymentId);
			$order      = PayplansOrder::getInstance( $payment->getOrder());		
			$subscription = $order->getSubscriptions('order_id');

			$price 		= ( $order->getFirstSubtotal() !== PAYPLANS_UNDEFINED) ? $order->getFirstSubtotal() : $order->getSubtotal();

			$tax   		= $order->getTaxAmount();
			$discount	= $order->getDiscount();
			
			$firstpaymentId = $order->getFirstPaymentId();

			// update price, discount and tax amount in case the invoice is of first payment  
			if($paymentId == $firstpaymentId)
			{				
				// display proper discount in all cases (with trial and regular) 
				
				$discount = $order->getFirstPriceDiscount();
				
				//display the tax amount on first price accordingly not the tax amount set on the order
				if($price !== PAYPLANS_UNDEFINED){
					$tax = $order->getFirstPriceTax();
				}
			}
				$this->assign('user', 	PayplansUser::getInstance($order->getBuyer()));
				$this->assign('order',  $order);
				$this->assign('payment',  $payment);
				$this->assign('subscriptions',  $subscription);
				$this->assign('price',  $price);
				$this->assign('tax',  $tax);
				$this->assign('discount',  $discount);
				return true;
	}

	public function _getDynamicJavaScript()
	{
		$js = '';
		
		ob_start(); 
		
		?>
		payplans.jQuery(document).ready(function(){
			payplans.jQuery('#checkout_form').submit(function(){
				payplans.jQuery('#payplans_payment_btn').attr('disabled', true);
				setTimeout("payplans.jQuery('#payplans_payment_btn').attr('disabled', false)", 5000);
				return true;
			});
		});
		<?php
		
		$js = ob_get_contents();
		ob_end_clean();
        
		return $js;
	}

}
