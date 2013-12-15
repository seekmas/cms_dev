<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Order
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansEventWallet
{
	public static function onPayplansWalletUpdate($transaction, $amount)
	{ 
		$invoice 	 = $transaction->getInvoice(PAYPLANS_INSTANCE_REQUIRE);

		//when the wallet amount is consumed then blank invoice object will be created
		// so no need to proceed further.
		if(!$invoice || !$invoice->getId()){
		   return true;
		}
		
		$wallet = $invoice->getReferenceObject(PAYPLANS_INSTANCE_REQUIRE);

		if(!is_a($wallet, 'PayplansWallet')){
			return true;
		}

		$amount = $transaction->getAmount();
		
		//when refund is made externally then mark invoice as refunded
		if( floatval($amount) < floatval(0) ){

			//XITODO : handle partial refund case
			$invoice->set('status', PayplansStatus::INVOICE_REFUNDED)->save();
			
			$user = $invoice->getBuyer(PAYPLANS_INSTANCE_REQUIRE);
			$walletBalance = $user->getWalletBalance();
			//when wallet balance becomes negative
			if(floatval($walletBalance) < floatval(0)){
				$message  =  XiText::_('COM_PAYPLANS_ERROR_LOG_NEGATIVE_WALLET_BALANCE_FOR_USER');
				$content  = array('user_id'=>$user->getId(), 'invoice_id'=>$invoice->getId(), 'detail'=>XiText::_('COM_PAYPLANS_REFUND_OTHER_INVOICES_TO_EQUATE_WALLET_BALANCE_TO_ZERO'));
				return PayplansHelperLogger::log(XiLogger::LEVEL_ERROR, $message, $invoice, $content);
			}
		}
		
		//when there is amount difference then don't mark invoice as wallet recharge
		if(floatval($amount) != floatval($invoice->getTotal())){
			return true;
		}
		
		//invoice is for wallet recharge so set the proper status
		$invoice->set('status', PayplansStatus::INVOICE_WALLET_RECHARGE)->save();
		
		return true;
		
	}

  static function onPayplansRewriterReplaceTokens($refObject, $rewriter)
	{	
		if($refObject instanceof PayplansInvoice && $refObject->getObjectType() == 'PayplansWallet'){
		    $rewriter->setMapping($refObject, false);
		}
		return ;
	}
}