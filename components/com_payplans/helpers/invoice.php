<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHelperInvoice
{
	static function getWallet($invoiceIds)
	{
		if(!is_array($invoiceIds)){
				$invoiceIds = array($invoiceIds);
		}
		
		array_unique($invoiceIds);
		$filter = array('invoice_id' => array(array('IN', '('.implode(",", $invoiceIds).')')));
		return XiFactory::getInstance('wallet', 'model')->loadRecords($filter, array(), false, 'invoice_id');
	}
	
	function getWalletWithinDates($startDate, $endDate, $limitStart, $limit)
	{
		$walletModel = XiFactory::getInstance('wallet', 'model');
		$query = new XiQuery();
	    $query->select('*')
		      ->from('`#__payplans_wallet`')
		      ->where("Date(`created_date`)   >= '".$startDate."'")
		      ->where("Date(`created_date`)   <= '".$endDate."'" )
		      ->where("`invoice_id` <> 0")
		      ->limit($limit,$limitStart);
		$walletModel->set('_query',$query);

		return $walletModel->loadRecords(array(),array(),false,'invoice_id'); 
	}

	public static function isRenewalInvoice($invoice)
	{
		// Step 1:- Check whether invoice is PayplansInvoice 
		// Step 2:- get order from invoice and ensure that it is object of PayplansOrder 
		// Step 3:- Check first_Master_Invoice_Id and current_invoice_id should not be equal.
		//			(if first_Master_Invoice_Id == current_invoice_id) then invoice is not for renewal
		// Step 4:- Check invoice has any payment record or not 
		//  		(if invoice do not have payment record then it will be treated as child invoice)
		//			(and if invoice has payment record attched then it will be considered as master invoice)
		
		// IMP:- return true if invoice is for renewal else return false
		
		// Step 1
		if(empty($invoice)){
			return false;
		}
		
		// if $invoice is not instance of PayplansInvoice then get the instance first
		if(($invoice instanceof PayplansInvoice) == false){
			$invoice = PayplansInvoice::getInstance($invoice);
			//XITODO: check $invoice
		}
		
		// Step 2
		$order = $invoice->getReferenceObject(PAYPLANS_INSTANCE_REQUIRE);
		if(($order instanceof PayplansOrder) == false){
			return false;
		}
		
		// Step 3
		if($order->getFirstInvoice() == $invoice->getId()){
			return false;
		}
		
		// Step 4
		$payment = $invoice->getPayment();
		if(($payment instanceof PayplansPayment) == false){
			// V.IMP:- Handle special case of free plan
			$total = $invoice->getTotal();
			if(number_format($total, 2) == 0.00){
				return true;
			}
			return false;
		}
		
		return true;
	}
}