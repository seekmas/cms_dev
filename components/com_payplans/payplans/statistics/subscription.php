<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansStatisticsSubscription extends PayplansStatistics
{
	protected $_purpose_id		= '2001';
	public $_statistics_type	= 'subscription';
	
	public function setDetails($data=array(), $dates_to_process)
	{
		foreach ($dates_to_process as $id => $date){
			list($firstDate, $lastDate) = $this->getFirstAndLastDates($date);
			
			// set cart statistics details
			$key = $date->toUnix();
			$data[$key]['statistics_type'] 	= $this->_statistics_type;
			$data[$key]['purpose_id_1']		= $this->_purpose_id;
			$data[$key]['count_1']			= $this->_activeSubscription($firstDate, $lastDate) - $this->_expireSubscription($firstDate, $lastDate);
			$data[$key]['count_2']			= $this->_renewedSubscription($firstDate, $lastDate);
			$data[$key]['count_3']			= $this->_upgradedSubscription($firstDate, $lastDate);
			$data[$key]['statistics_date']	= $date;
		}
		return parent::setDetails($data);
	}
	
	protected function _activeSubscription(XiDate $firstDate, XiDate $lastDate)
	{
		// get a reference to the database
        $query = new XiQuery();
        
        $query->select('count(*)')
			  ->from('#__payplans_subscription')
			  ->where('subscription_date >= '. "'".$firstDate->toMySQL()."'")
			  ->where('subscription_date <= '. "'".$lastDate->toMySQL()."'");
		
        return $query->dbLoadQuery()->loadResult();
	}
	
	protected function _expireSubscription(XiDate $firstDate, XiDate $lastDate)
	{
		// get a reference to the database
        $query = new XiQuery();
        
        $query->select('count(*)')
			  ->from('#__payplans_subscription')
			  ->where('expiration_date >= '. "'".$firstDate->toMySQL()."'")
			  ->where('expiration_date <= '. "'".$lastDate->toMySQL()."'");
		
        return $query->dbLoadQuery()->loadResult();
	}
	
	 protected function _renewedSubscription(XiDate $firstDate, XiDate $lastDate)
	 {
		$query = new XiQuery();
		$query->select('group_concat(invoice.`invoice_id`) as invoice_group')
			  ->from('`#__payplans_invoice` AS invoice')
			  ->leftJoin('`#__payplans_transaction` AS transaction ON invoice.`invoice_id` = transaction.`invoice_id`')
			  ->where('invoice.`status` ='. PayplansStatus::INVOICE_PAID)
			  ->where('transaction.`created_date` >= '. "'".$firstDate->toMySQL()."'")
			  ->where('transaction.`created_date` <= '. "'".$lastDate->toMySQL()."'")
			  ->group('`object_id`');
		
		$invoiceRecords = $query->dbLoadQuery()->loadObjectList();
		
		$count = 0;
		foreach ($invoiceRecords as $record)
		{
			$result = 0;
			$query = new XiQuery();
			$query->select('count(*)')
						->from('`#__payplans_payment`')
						->where("`invoice_id` IN(". $record->invoice_group.")");
			
			$result = $query->dbLoadQuery()->loadResult();
			
			// IMP: Free Plan Case:- get renewal count on the basis of total paid Invoices
			if($result == 0){
				$result = count(array_unique(explode(',', $record->invoice_group)));
			}
			$result = $result - 1;
			$count = $count+$result;
		}
		return $count;
	}
	
	protected function _upgradedSubscription(XiDate $firstDate, XiDate $lastDate)
	{
//		SELECT count(DISTINCT pporder.order_id) 
//		FROM `j179_payplans_order` as pporder
//		INNER JOIN  `j179_payplans_invoice` as invoice
//		ON pporder.order_id = invoice.object_id
//		INNER jOIN `j179_payplans_transaction` AS transaction 
//		ON (invoice.`invoice_id` = transaction.`invoice_id`
//		AND date(transaction.`created_date`) = '2012-10-22'
//		AND pporder.params LIKE '%upgrading_from=%')

		$query = new XiQuery();
		$query->select('count(DISTINCT pporder.order_id)')
					->from('`#__payplans_order` as pporder')
					->innerJoin("`#__payplans_invoice` as invoice ON pporder.`order_id` = invoice.`object_id` AND pporder.`params` LIKE '%upgrading_from=%'")
					->innerJoin('`#__payplans_transaction` AS transaction 
										ON (invoice.`invoice_id` = transaction.`invoice_id`
										AND transaction.`created_date` >= '."'".$firstDate->toMySQL()."'"
										.'AND transaction.`created_date` <= '."'".$lastDate->toMySQL()."'"
										.')');

		return $query->dbLoadQuery()->loadResult();
	}
}
