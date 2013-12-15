<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	API
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

/**
 * These functions are listed for wallet object 
 * @author JPayplans
 */
class PayplansIfaceApiWallet
{
	/**
	 * Gets the attached transaction of the wallet
	 * @param boolean $instanceRequire  Optional paramter to get the instance of the attached transaction
	 * @return mixed TransactionId or object of PayplansTransaction for TransactionId
	 */
	public function getTransaction($instanceRequire = false);

	
	/**
	 * Gets the amount of the wallet
	 * @return float  Value of the amount
	 */
	public function getAmount();

	
	/**
	 * Gets the buyer/user of the wallet
	 * @param boolean $requireinstance  If True return PayplansUser instance else Userid 
	 * 
	 * @return mixed Userid or instance of PayplansUser attached with the user
	 */
	public function getBuyer($requireinstance=false);

	
	/**
	 * Gets the invoice attached to the wallet
	 * 
	 * This invoice id is the one which has consumed the amount from wallet 
	 * 
	 * @param   integer $requireinstance  Optional parameter to get the instance of the Invoice
	 * @return  mixed  InvoiceId or object of PayplansInvoice for InvoiceId
	 */
	public function getInvoice($requireinstance=false);	
}