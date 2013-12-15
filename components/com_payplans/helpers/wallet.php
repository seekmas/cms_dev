<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansHelperWallet
{
	
	public static function credit($userid, $txn_id = 0, $amount = '0.00', $message = 'COM_PAYPLANS_WALLET_AMOUNT_HAS_BEEN_CREDITED')
	{
		return PayplansHelperWallet::_addRecord($userid, $txn_id, $amount, $message);
	}
	
	public static function debit($userid, $txn_id = 0, $amount = '0.00', $message = 'COM_PAYPLANS_WALLET_AMOUNT_HAS_BEEN_DEBITED')
	{
		if($amount > 0){
			$amount = -$amount;
		}
		
		return PayplansHelperWallet::_addRecord($userid, $txn_id, $amount, $message);
	}
	
	protected static function _addRecord($userid, $txn_id, $amount, $message)
	{
		$wallet = PayplansWallet::getInstance();
		$wallet->set('user_id', $userid)
				->set('transaction_id', $txn_id)
				->set('amount', $amount)
				->set('message', $message);
			
		return $wallet->save();		
	}
	
	function createInvoice($userid, $amount)
	{
		$invoice = PayplansInvoice::getInstance();
		$invoice->set('object_type', 'PayplansWallet')
				->set('user_id', $userid)
				->set('subtotal', $amount)
				->set('total', $amount);
				
		$params 	= array('expirationtype' => 'fixed',
									 'expiration' => '000000000000', 
									 'recurrence_count' => 0, 
									 'price' => $amount,
									 'trial_price_1' => 0.00, 
									 'trial_time_1' => '000000000000', 
									 'trial_price_2' => 0.00, 
									 'trial_time_2' => '000000000000',
									 'title' => XiText::_('COM_PAYPLANS_WALLET_RECHARGE'));
			
		foreach($params as $param=>$value){
			$invoice->setParam($param, $value);
		}
		
		$invoice->save();
		
		return $invoice;
	}
}