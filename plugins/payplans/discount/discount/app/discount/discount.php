<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		Payplans
* @subpackage	Discount
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


/**
 * Discount System
 * @author shyam
 */
class PayplansAppDiscount extends PayplansAppDiscounts
{
	//inherited properties
	protected $_location	= __FILE__;

	
	/**
	 * Entry Function
	 * (non-PHPdoc)
	 * @see components/com_payplans/libraries/iface/app/PayplansIfaceAppDiscount::onPayplansApplyDiscount()
	 * @param $dObject PayplansIfaceDiscountable : discountable object
	 */ 
	public function onPayplansApplyDiscount(PayplansIfaceDiscountable $object, $discountCode)
	{
		$result = $this->_doCheckAllowed($object, $discountCode);
		
		//Important Check : use strict !== , Do not use != 
		if($result !== true){
			return $result;
		}
		
		//apply discount on each invoice
		$result = $this->_doApplyDiscount($object);

		//set total usage of the discount code
		if($result){
			$this->setAppParam('totalUsage', PayplansHelperModifier::getTotalUsage($this->getAppParam('coupon_code'), 'discount'));
			$this->setAppParam('totalConsumption' , PayplansHelperModifier::getActualConsumption($this->getAppParam('coupon_code'), 'discount'));
	    	$this->save();	
		}
		return $result;

	}

	//Check if current discount should be applied as per discount purpose
	public function _doCheckAllowed(PayplansIfaceDiscountable $object, $discountCode)
	{
		// is equal to my discount code
		if($discountCode != $this->getAppParam('coupon_code', false)){
			return false;
		}

		//do not allow user to apply the same 
		//discount code again and again on the same invoice
		$modifiers = $object->getModifiers(array('type' => $this->getType(), 'reference' => $discountCode));
		if(!empty($modifiers)){
			return XiText::_('COM_PAYPLANS_APP_DISCOUNT_ERROR_ALREADY_USED');
		}

		//if multiple discount on same invoice is not allowed then check for the perviously applied discount 
		if(!(XiFactory::_getConfig()->multipleDiscount) && $object->getDiscount() != 0){	
				return XiText::_('COM_PAYPLANS_APP_DISCOUNT_CANT_APPLY_MULTIPLE_DISCOUNT');
		}
		
		$reusable   	= $this->getAppParam('reusable', 'yes');

		$userId		    = $object->getBuyer();
		$modifiers	    = XiFactory::getInstance('modifier','model')
							->loadRecords(array('user_id'=> $userId, 'reference' => $discountCode, 'type' => $this->getType() ));
								
		//restrict user to use the same discount code on different subscriptions if reusable parameter is set to no
		if($reusable == 'no'){
			//user already used the mentioned discount code, not allowed to use it again
			if(count($modifiers) > 0 ){
				return XiText::_('COM_PAYPLANS_APP_DISCOUNT_ERROR_ALREADY_USED');
			}	
		}
		
		$modifiers = XiFactory::getInstance('modifier','model')
							->loadRecords(array('reference' => $discountCode, 'type' => $this->getType() ));
		
		// if coupon have been used completely	
		// unlimited usage if allowed quantity is ''
		$allowedQuantity = $this->getAppParam('allowed_quantity', '');
		if($allowedQuantity !== '' 	&& $allowedQuantity <= count($modifiers)){
			return XiText::_('COM_PAYPLANS_APP_DISCOUNT_ERROR_CODE_ALLOWED_QUANTITY_CONSUMED');
		}
		
		return true;
	}

	public function _doCalculateDiscount(PayplansIfaceDiscountable $object, $price, $discount)
	{
		if($price <= 0){
			return 0;
		}

		//calculate discount
		if($this->getAppParam('coupon_amount_type','fixed') === 'percentage'){
			return array($this->getAppParam('coupon_amount'), true);
		}

		return array($this->getAppParam('coupon_amount'), false);
	}
}