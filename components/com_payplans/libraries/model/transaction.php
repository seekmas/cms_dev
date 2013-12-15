<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansModelTransaction extends XiModel
{
	
	public $crossTableNetwork 	= array(
								"users"=>array('users'),
								"payment"=>array('payment')	
	);

	//this is to ftech on condition for cross table
	public $innerJoinCondition = array(
								'tbl-users'			=> ' #__users as cross_users on tbl.user_id = cross_users.id',
								'tbl-payment'		=> ' #__payplans_payment as cross_payment on cross_payment.payment_id = tbl.payment_id'
							
								
	);
	
	public $filterMatchOpeartor = array(
										'invoice_id'   			=> array('='),
										'user_id'	   			=> array('='),
										'amount'	   			=> array('>=', '<='),
										'created_date' 			=> array('>=', '<='),
										'cross_users_username' 	=> array('LIKE'),
										'cross_payment_app_id' 	=> array('=')
	);

	public function getRecentTransactions($limit = 5, $offset = 0)
    {
    	$query = new XiQuery();
    	
		$query->select('*')
				->from('#__payplans_transaction')
				->order('`created_date` DESC')
				->limit($limit, $offset);
				 		
		return $query->dbLoadQuery()->loadObjectList('transaction_id');
    }
    
    /**
     * Returns total revenue of individual plans between given starting date and ending date
    */
    public function getRevenuesOfPlans(XiDate $firstDate, XiDate $lastDate)
    {
//    	SELECT subscription.`plan_id` as plan_id, sum(transaction.`amount`) as amount 
//			FROM `j285_payplans_transaction` as transaction
//			INNER JOIN `j285_payplans_invoice` as invoice 
//					ON transaction.`invoice_id` = invoice.`invoice_id` 
//						and transaction.`created_date` >= '2012-10-02 00:00:00' 
//						and transaction.`created_date` <= '2012-10-02 23:59:59'
//			LEFT JOIN `j285_payplans_subscription` as subscription
//					ON invoice.`object_id` = subscription.`order_id`
//			GROUP BY subscription.`plan_id`
    	
    	$query = new XiQuery();
		$query->select('subscription.`plan_id` as plan_id, sum(transaction.`amount`) as amount')
					->from('`#__payplans_transaction` as transaction')
					->innerJoin('`#__payplans_invoice` as invoice ON transaction.`invoice_id` = invoice.`invoice_id`'
						 .'and transaction.`created_date` >= '."'". $firstDate->toMySQL() . "'"  
						 .'and transaction.`created_date` <= '."'". $lastDate->toMySQL() . "'")
					->leftJoin('`#__payplans_subscription` as subscription ON invoice.`object_id` = subscription.`order_id`')
					->group('subscription.`plan_id`');
					
		return $query->dbLoadQuery()->loadObjectList('plan_id');
    }
    
    /**
     * Returns total revenue of donation between given starting date and ending date
    */
    public function getRevenuesOfDonation(XiDate $firstDate, XiDate $lastDate)
    {
    	//Reason for using #__payplans_transaction is, #__payplans_invoice only have modified date which can be changed anytime as invoice updates 
	   	$query = new XiQuery();
		$query->select('sum(transaction.`amount`) as amount')
					->from('`#__payplans_transaction` as transaction')
					->innerJoin('`#__payplans_invoice` as invoice ON transaction.`invoice_id` = invoice.`invoice_id`'
						 .'and invoice.`object_type` = '."'PayplansDonation'"
						 .'and transaction.`created_date` >= '."'".$firstDate->toMySQL()."'"  
						 .'and transaction.`created_date` <= '."'".$lastDate->toMySQL()."'"
						 .'and transaction.`amount` > 0' );					
		$data = $query->dbLoadQuery()->loadResult();
		return $data;
    }
}

class PayplansModelformTransaction extends XiModelform {}
