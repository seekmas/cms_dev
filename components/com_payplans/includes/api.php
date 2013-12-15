<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	API
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

require_once  dirname(__FILE__).DS.'includes.php';

class PayplansApi
{
	/**
	 * @param integer $userid : User Id
	 * @return PayplansUser Object, on which you can call many functions 
	 *  You can call all function defined in PayplansIfaceApiUser
	 */
	static public function getUser($userid)
	{
		return PayplansUser::getInstance($userid);
	}
	
	
	/**
	 * Get all plans available in the system, which can be sold
	 * By default returns all published plan, you can change filter
	 * to get different subsets.
	 * 
	 * @param  Array filter : which records to collect, default to published 
	 * @return Array 
	 * @since  1.2
	 */
	static public function getPlans($filter=array('published'=> 1))
	{
		//clear where so filter can impact as per user request 
		return XiFactory::getInstance('plan','model')->loadRecords($filter, array('where'));
	}
	
	/**
	 * @param integer $planid : Plan number
	 * @return PayplansPlan Object,
	 *  You can call all function defined in PayplansIfaceApiPlan
	 */
	static public function getPlan($planid)
	{
		return PayplansPlan::getInstance($planid);
	}
	
	
	/**
	 * Get available groups
	 * By default returns all published groups, you can change filter
	 * to get different subsets.
	 * 
	 * @param  Array filter : which records to collect, default to published 
	 * @return Array 
	 * @since  2.0.5
	 */
	static public function getGroups($filter=array('published'=> 1))
	{
		//clear where so filter can impact as per user request 
		return XiFactory::getInstance('group','model')->loadRecords($filter, array('where'));
	}
	
	/**
	 * @param integer $groupId : Group ID
	 * @return PayplansGroup Object,
	 *  You can call all function defined in PayplansIfaceApiGroup
	 */
	static public function getGroup($groupId)
	{
		return PayplansGroup::getInstance($groupId);
	}
	
	/**
	 * Creates a new plan object and return it.
	 * 
	 * @return PayplansPlan
	 * IMP : To save Plan permanently, call save() on returned object.
	 */
	static public function createPlan()
	{
		return PayplansPlan::getInstance(0);
	}
	
	
	
	/**
	 * Get all orders available in the system, By default returns all 
	 * orders, you can change filter to get different subsets.
	 * 
	 * @param  Array filter : which records to collect 
	 * @return Array
	 * @since  1.2
	 */
	static public function getOrders($filter=array())
	{		
		//clear where so filter can impact as per user request 
		return  XiFactory::getInstance('order','model')->loadRecords($filter, array('where'));
	}
	
	
	/**
	 * @param integer $orderid : Order number
	 * @return PayplansOrder Object,
	 *  You can call all function defined in PayplansIfaceApiOrder
	 */
	static public function getOrder($orderid)
	{
		return PayplansOrder::getInstance($orderid);
	}
	
	/**
	 * Creates a new Order object and return it.
	 * 
	 * @return PayplansOrder
	 * IMP : To save order permanently, call save() on returned object.
	 */
	static public function createOrder()
	{
		return PayplansOrder::getInstance(0);
	}
	
	
	/**
	 * Get all subscriptions available in the system, By default returns all 
	 * subscriptions, you can change filter to get different subsets.
	 * 
	 * @param  Array filter : which records to collect 
	 * @return Array 
	 * @since  1.2
	 */
	static public function getSubscriptions($filter=array())
	{
		//clear where so filter can impact as per user request 
		return XiFactory::getInstance('subscription','model')->loadRecords($filter, array('where'));
	}
	
	/**
	 * Get the subscription object of given ID
	 * @param integer $subscriptionid : Subscription number
	 * @return PayplansSubscription Object,
	 * 
	 * You can call all function defined in PayplansIfaceApiSubscription
	 * over the object returned.
	 */
	static public function getSubscription($subscriptionid)
	{
		return PayplansSubscription::getInstance($subscriptionid);
	}
	
	/**
	 * Creates a new Subscription object and return it.
	 * 
	 * @return PayplansSubscription
	 * IMP : To save Subscription permanently, call save() on returned object.
	 */
	static public function createSubscription()
	{
		return PayplansSubscription::getInstance(0);
	}
	
	
	
	
	/**
	 * Get all payments available in the system, By default returns all 
	 * payments, you can change filter to get different subsets.
	 * 
	 * @param  Array filter : which records to collect 
	 * @return Array of PayplansPayment Object
	 * @since  1.2
	 */
	static public function getPayments($filter=array())
	{
		//clear where so filter can impact as per user request 
		return XiFactory::getInstance('payment','model')->loadRecords($filter, array('where'));
	}
	
	/**
	 * @param integer $paymentid : Payment number
	 * @return PayplansPayment Object,
	 *  You can call all function defined in PayplansIfaceApiPayment
	 */
	static public function getPayment($paymentid)
	{
		return PayplansPayment::getInstance($paymentid);
	}
	
	/**
	 * @return stdClass Object
	 * If you update configuration here, it will NOT be saved 
	 * into database. The updated configuration will only work 
	 * for current execution cycle
	 */
	static public function getConfig()
	{
		return XiFactory::getConfig();
	}
	
	
	/**
	 * Check If given user have subscription to given plan.
	 * 
	 * @param $userid : user id
	 * @param $planid : the plan to check against
	 * @param $staus  : Status can be one of below 3. 
	 *        PayplansStatus::SUBSCRIPTION_ACTIVE
	 *        PayplansStatus::SUBSCRIPTION_HOLD
	 *        PayplansStatus::SUBSCRIPTION_EXPIRED
	 *        
	 * Imp: By default it checks subscription status = active 
	 *
	 * @return : false
	 */
	static function haveSubscription($userid, $planid, $status=PayplansStatus::SUBSCRIPTION_ACTIVE)
	{
		$subscriptions = XiFactory::getInstance('subscription','model')
								->loadRecords(array('user_id'=>$userid, 'plan_id' => $planid, 'status'=>$status));
								
		return count($subscriptions);
	}
	
	/**
	 * Get all invoices available in the system, By default returns all 
	 * invoices, you can change filter to get different subsets.
	 * 
	 * @param  Array filter : which records to collect 
	 * @return Array of PayplansInvoice Object
	 * @since  2.0
	 */
	static function getInvoices($filter=array())
	{
		return XiFactory::getInstance('invoice','model')->loadRecords($filter, array('where'));
	}
	
	static public function getInvoice($invoice_id)
	{
		return PayplansInvoice::getInstance($invoice_id);
	}
	
	/**
	 * Get all transactions available in the system, By default returns all 
	 * transaction, you can change filter to get different subsets.
	 * 
	 * @param  Array filter : which records to collect 
	 * @return Array of PayplansTransaction Object
	 * @since  2.0
	 */	
	static public function getTransactions($filter=array())
	{
		return XiFactory::getInstance('transaction','model')->loadRecords($filter, array('where'));
	}
	
	static public function getTransaction($transaction_id)
	{
		return PayplansTransaction::getInstance($transaction_id);
	}
	
	/**
	 * Get the balance available in the 
	 * user's wallet
	 * 
	 * @param PayplansUser $user
	 * @since 2.0
	 */
	static public function getWalletBalance($user)
	{
		return $user->getWalletBalance();
	}

	/**
	 * Gets the instance of PayplansWallet with the provided wallet identifier
	 * 
	 * @param integer $wallet_id Unique identifier for wallet
	 */
	public function getWallet($wallet_id)
	{
		return PayplansWallet::getInstance($wallet_id);					
	}
	
	
	/**
	 * Get all wallets records available, By default returns all 
	 * wallet records, you can change filter to get different subsets.
	 * 
	 * @param  Array $filter Filter to fetch the selected wallet records 
	 * @return Array Array of PayplansWallet Object
	 * @since  2.0
	 */	
	public function getWallets($filter=array())
	{
		return XiFactory::getInstance('wallet','model')->loadRecords($filter, array('where'));		
	}
	
	/**
	 * Gets the instance of PayplansModifier with the provided modifier identifier
	 * 
	 * @param integer $modifier_id Unique identifier of modifier
	 */
	public function getModifier($modifier_id)
	{
		return PayplansModifier::getInstance($modifier_id);
	}
	
	/**
	 * Get all modifiers available in the system, By default returns all 
	 * modifiers, you can change filter to get different subsets.
	 *
	 * @param  Array $filter Filter to fetch the selected modifier records
	 * @return Array Array of PayplansModifier Object
	 * @since  2.0
	 */
	public function getModifiers($filter=array())
	{
		return XiFactory::getInstance('modifier','model')->loadRecords($filter, array('where'));
	}
}