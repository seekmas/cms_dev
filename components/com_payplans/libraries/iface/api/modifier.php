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
 * These functions are listed for Modifier object
 * @author JPayplans
 * @since 2.0
 */

class PayplansIfaceApiModifier
{
	/**
	 * Gets the Amount of Modifier
	 * @return float Modifier amount.
	 */
	public function getAmount();	

	
	/**
	 * Checks the Modifier amount is fixed or in percentage.
	 * @return boolean true/false  True when amount is in percentage else False.
	 */
	public function isPercentage();
	
	
	/**
	 * Type of a modifier is known as a serial.
	 * 
	 * Discountable Modifier means any addition or substraction before applying discount/tax
	 * FIXED_DISCOUNTABLE = 10, PERCENT_DISCOUNTABLE = 15
	 * 
	 * Discount Modifier means discount on invocie
	 * FIXED_DISCOUNT = 20, PERCENT_DISCOUNT = 25
	 *  
	 * Tax Modifier means tax on invoice
	 * FIXED_TAX = 30, PERCENT_TAX = 35
	 * 
	 * NON-TAXABLE Modifier means any addition or substraction after applying discount/tax
	 * FIXED_NON_TAXABLE = 40, PERCENT_NON_TAXABLE = 45
	 * 
	 * @return integer Constant value of the serial.
	 */	
	public function getSerial();
	
		
	/**
	 * Gets string which specifies how much time the modifier can be used.
	 * Like FREQUENCY_ONE_TIME and FREQUENCY_EACH_TIME
     * Modifier with frequency of FREQUENCY_ONE_TIME will be applicable only one time whereas 
	 * FREQUENCY_EACH_TIME indicates that the modifier will be applicable every time.
     *
	 * @return string One Time for FREQUENCY_ONE_TIME and Each Time for FREQUENCY_EACH_TIME
	 */
	public function getFrequency();
	
	
	/**
	 * Gets Type of modifier
	 * Type of the modifier indicates the Name of App or any other resource by which the modifier was created.
	 *
	 * @return String Name of the app or other resource which has created the modifier. 
	 */
	public function getType();
	
	
	
	/**
	 * Gets a message in string format.
	 * @return String Message attached with the modifier.
	 */
	public function getMessage();
	
	
	/**
	 * Gets the reference.
	 * In case of Discount, Coupon code is treated as Reference.
	 * In case of Upgrade, Old Invoice_key is treated as Reference.
	 * @return String Reference code by which modifier has been applied.
	 */
	public function getReference();
}