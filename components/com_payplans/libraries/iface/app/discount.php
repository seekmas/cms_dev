<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


interface PayplansIfaceAppDiscount
{
	// all should implement how to apply discount
	//returns true/false or error-string
	function onPayplansApplyDiscount(PayplansIfaceDiscountable $object, $discountCode);
	
	//Check if given code is allowed on this order, all sort of checking 
	// should be done in this function 
	function _doCheckAllowed(PayplansIfaceDiscountable $object, $discountCode);
	
	// This function will apply discount on every subscription
	function _doApplyDiscount(PayplansIfaceDiscountable $object);
	
	// Check if coupon is allowed to be used on this subscription
	// Should return true or ErrorMessage
	function _doCheckApplicable(PayplansIfaceDiscountable $object);
	
	// Calculate the discount on this price as per your app rules
	function _doCalculateDiscount(PayplansIfaceDiscountable $object, $price, $discount);
}
