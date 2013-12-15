<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	API
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

/**
 * These functions are listed for Payment object
 * @author bhavya
 *
 */
interface PayplansIfaceApiPayment
{	
	/**
	 * @return buyer(user) linked with the current payment
	 * if $instance is PAYPLANS_INSTANCE_REQUIRE then return user instance
	 * else return userid 
	 */
	public function getBuyer($instance=false);
	
	/**
	 * @return created date of payment
	 */
	public function getCreatedDate();
	
	/**
	 * @return date when payment is last modified
	 */
	public function getModifiedDate();
	
	/**
	 * @return payment app this payment has made from
	 * if $requireInstance is PAYPLANS_INSTANCE_REQUIRE then return instance of payment app
	 * else payment app id
	 */
	public function getApp($instance = false);

	/**
	 * Gets the transaction attached with the payment
	 * @return array  Array of transaction object (PayplansTransaction)
	 */
	public function getTransactions();
	
	/**
	 * Gets the invoice linked with the current payment
	 * @param  boolean  $requireInstance  Optional parameter to get the object (PayplansInvoice)
	 * @return mixed  InvoiceId or object of PayplansInvoice for InvoiceId
	 */
	public function getInvoice($requireInstance = false);
	
	/**
	 * Gets the gateway params of the payment
	 * Gateway params are payment gateway specific parameters 
	 * like pending recurrence cycle to process, subscribe id etc 
	 * @return  object XiParameter
	 */
	public function getGatewayParams();
}