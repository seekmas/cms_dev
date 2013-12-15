<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	API
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

/**
 * These functions are listed for Transaction object 
 * @author bhavya
 *
 */
interface PayplansIfaceApiTransaction
{
	/**
	 * Gets the currency of the transaction
	 * @param string $format
	 * @return string
	 */
    public function getCurrency($format = null);
    
    /**
	 * Gets all tha parameter of the transaction
	 * @return object XiParameter
	 */
	public function getParams();
	
	/**
	 * Gets the gateway subscription id of the transaction
	 * 
	 * This parameter is available in recurring payments only.
	 * Gateway subscription id is the unique identifier referring
	 * to the profile id created at payment gateway end for the recurring subscription
	 * 
	 * @return string
	 */
	public function getGatewaySubscriptionId();
	
	/**
	 * Gets the payment gateway transaction id of the transaction
	 * Gateway Txn id is the unique identifier(reference) passed from 
	 * payment gateway indicating the transaction record at payment gateway end    
	 * 
	 * @retun string  Unique Identifier
	 */
	public function getGatewayTxnId();
	
	/**
	 * Gets the payment record attached to the transaction
	 * 
	 * @param boolean $requireinstance  Optional parameter to get the instance of the payment rather than payment id
	 * @return interger|object PaymentId or object of PayplansPayment for PaymentId
	 */
	public function getPayment($requireinstance=false);
	
	/**
	 * Gets the invoice attached to the transaction
	 * 
	 * @param   integer $requireinstance  Optional parameter to get the instance of the Invoice
	 * @return  mixed  InvoiceId or object of PayplansInvoice for InvoiceId
	 */
	public function getInvoice($requireinstance=false);
	
	/**
	 * Gets the wallet record for the transaction
	 * @return object PayplansWallet
	 */
	public function getWallet();
	
	/**
	 * Gets the amount of the transaction
	 * This amount is the actual amount received from the payment gateway
	 * 
	 * @return float  Value of the amount
	 */
	public function getAmount();
	
	/**
	 * Gets the buyer of the transaction
	 * 
	 * @param boolean $requireinstance  If True return PayplansUser instance else Userid 
	 * @return mixed Userid or instance of PayplansUser attached with the transaction
	 */
	public function getBuyer($requireinstance=false);
}