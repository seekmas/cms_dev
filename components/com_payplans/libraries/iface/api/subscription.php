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
 * These functions are listed for Subscription object
 * @author ssv445
 *
 */
interface PayplansIfaceApiSubscription
{
	
	/**
	 * @example  
	 * 	PayplansSubscription::getInstance(5)->isActive();
	 * 
	 * @return boolean 
	 * 		Subscription is Active : True 
	 * 		else : False
	 */
	public function isActive();
	
	
	/**
	 * Setup the subscription object for given plan
	 * It will Update -
	 * 	1. Price  = Equal to Plan price
	 * 	2. Discount = reset to Zero 
	 *  3. Status = None
	 *  4. Subscription Date = current date
	 *  5. Expiration Date	 = current date + expiration time of plan
	 *  
	 * @param Integer/PayplansPlan $plan
	 */
	public function setPlan($plan);
	
	/**
	 * @return mixed Userid or instance of PayplansUser attached with the subscription
	 * @param boolean $requireinstance If True return PayplansUser instance else Userid 
	 */
	public function getBuyer($requireinstance=false);
	
	/**
	 * Update the PayplansSubscription object
	 * It will update
	 * 1. buyer Id = equal to buyer id of the order
	 * 2. order id = equal to the id of the $order
	 * @return object PayplansSubscription Instance of PayplansSubscription
	 * @param PayplansOrder $order
	 */
	public function setOrder(PayplansOrder $order);

	/**
	 * @return mixed Instance of PayplansOrder or Orderid this subscription is linked with
	 * @param boolean $requireInstance If True return PayplansOrder instance else return order_id
	 */
	public function getOrder($requireinstance=false);
	
	/**
	 * Gets the expiration time of the subscription
	 * 
	 * @param integer $for  An integer constant indicating expiration type 
	 * 
	 * @return array  An array containing expiration values for year, month, day and so on
	 */
	public function getExpiration($for = PAYPLANS_SUBSCRIPTION_FIXED);
	
	/**
	 * Gets the recurrence count of the subscription
	 * @return integer
	 */
	public function getRecurrenceCount();
	
	/**
	 * Refund the subscription
	 * Mark the subscription status to refund and save
	 * 
	 * @return object PayplansSubscription Instance of PayplansSubscription
	 */
	public function refund();
	
	/**
	 * Gets the expiration type of the subscription
	 * 
	 * @return  string 
	 */
	public function getExpirationType();
	
	/**
	 * Is subscriotion reccuring ?
	 * @return mixed  Integer constant if subscription is of recurring type else False
	 */
	public function isRecurring();
	
	/**
	 * Renew the subscription
	 * 
	 * Activate the subscription and add the given 
	 * expiration time to the existing expiration time of the subscription.
	 * Extend the sibscription is already active
	 * 
	 * 
	 * @param string $expiration  12 digits numeric string 
	 * each 2 digits denotes the value for year, month, day, minute, 
	 * hour and second in the same sequence, starting from year(starting 2 digits indicate year)
	 * 
	 * @return object PayplansSubscription
	 */
	public function renew($expiration);
	
	/**
	 * Sets the buyer for the subscription
	 * @param  integer $userId  UserId to which the subscription will be attached
	 * @return object  PayplansSubscription  Instance of PayplansSubscription
	 */
	public function setBuyer($userId=0);
	
	/**
	 * Gets the status of the subscription
	 * 
	 * @return integer  Value of the status
	 */
	public function getStatus();
	
	/**
	 * Gets the total amount of the subscription
	 * Subscription total is exclusive of tax and discount.
	 * @return float  Value of the total
	 */
	public function getTotal();
	
	/**
	 * returns float Price of subscription
	 * @param integer $type
	 * if type is not set then return the regular/normal price
	 * if it is set to RECURRING_TRIAL_1 then return first trial price 
	 * if it is set to RECURRING_TRIAL_2 then return second trial price 
	 */
	public function getPrice($type = PAYPLANS_SUBSCRIPTION_FIXED);
	
	/**
	 * Gets the title of the subscription
	 * Subscription title is the title of the plan
	 * @return string 
	 */
	public function getTitle();
}