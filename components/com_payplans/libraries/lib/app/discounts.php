<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

/**
 * Event names should start always as
 * onPayplansDiscount
 * @author shyam
 */

abstract class PayplansAppDiscounts extends PayplansApp
	implements PayplansIfaceAppDiscount
{

	// All discount app should have some common params
	// Like :
	// publish_start
	// publish_end
	public function _isApplicable(PayplansIfaceApptriggerable $refObject = null, $eventName='')
	{
		//if coupon is published as per dates
		$publish_start 	= $this->getAppParam('publish_start', '');
		$publish_end	= $this->getAppParam('publish_end','');

		$now = new XiDate();
		if($publish_start != ''){
			$start = new XiDate($publish_start);
			if($start->toUnix() > $now->toUnix()){
				return false;
			}
		}

		if($publish_end != ''){
			$end = new XiDate($publish_end);
			if($end->toUnix() < $now->toUnix()){
				//also disable the discount
				$this->published = false;
				$this->save();
				return false;
			}
		}
		
		return parent::_isApplicable($refObject);
	}
	
	/**
	 * Simply Checks, if disocunt-app is attached to given subscription
	 * Tips : Avoid overriding this function
	 */
	public function _doCheckApplicable(PayplansIfaceDiscountable $object)
	{
		//Check if not applicable on given subscription
		if($this->getParam('applyAll',false)){
			return true;
		}

        $plans    = $object->getPlans();
		$subPlan  = array_shift($plans);
		return in_array($subPlan,$this->getPlans());
	}
	
	/**
	 * Iteratively apply discount 
	 * 
	 * Tips : Avoid overriding this function
	 */
	function _doApplyDiscount(PayplansIfaceDiscountable $object)
	{
		// apply dicount on invoice
		$discountUsed = false;
		$price 		= $object->getSubTotal();
		$discount 	= $object->getDiscount();

		if($this->_doCheckApplicable($object, $price, $discount) ==false){
			return $discountUsed;
		}

		list($amount, $isPercentage) = $this->_doCalculateDiscount($object, $price, $discount);
		
		$modifier = PayplansModifier::getInstance();
		$modifier->set('message', XiText::_('COM_PAYPLANS_APP_BASIC_DISCOUNT_MESSAGE'))
				 ->set('invoice_id', $object->getId())
				 ->set('user_id', $object->getBuyer())
				 ->set('type', $this->getType())
				 ->set('amount', -$amount) // Discount should be negative
				 ->set('reference', $this->getAppParam('coupon_code', ''))
				 ->set('percentage', $isPercentage ? true : false)
				 ->set('frequency', $this->getAppParam('onlyFirstRecurringDiscount', false) ? PayplansModifier::FREQUENCY_ONE_TIME : PayplansModifier::FREQUENCY_EACH_TIME);
				 				 
		/**
		 * V.V.IMP : this is very impotant for applying discount in which serial
		 * @see PayplansModifier
		*/
		$serial = ($isPercentage === true) 
							? PayplansModifier::PERCENT_DISCOUNT 
							: PayplansModifier::FIXED_DISCOUNT;
									
		// XITODO : add error checking
		$modifier->set('serial', $serial)->save();
		
		// refresh the object after applying discount
		$object->refresh();

		if($this->getAppParam('onlyFirstRecurringDiscount', false) && $object->isRecurring() == PAYPLANS_RECURRING){
				$params = $object->getParams()->toArray();
				$object->setParam('expirationtype', 'recurring_trial_1');
				$object->setParam('recurrence_count', ($params['recurrence_count']> 0 ) ? $params['recurrence_count']-1 : 0);
				$object->setParam('trial_price_1', $params['price']);
				$object->setParam('trial_time_1', $params['expiration']);
		}
		
		$object->save();		
		return true;
	}
}