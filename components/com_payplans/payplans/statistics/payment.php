<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
* @author		Puneet Singhal
*/
if(defined('_JEXEC')===false) die();

class PayplansStatisticsPayment extends PayplansStatistics
{
	protected $_purpose_id		= '3001';
	public $_statistics_type	= 'payment';
	
	public function setDetails($data=array(), $dates_to_process)
	{
		foreach ($dates_to_process as $id => $date){
			list($firstDate, $lastDate) = $this->getFirstAndLastDates($date);
			
			$records = $this->_getGatewayDetails($firstDate, $lastDate);
			foreach ($records as $app_id => $record){
				// set cart statistics details
				$key = $date->toUnix();
				$key = $key + $app_id;
				$data[$key]['statistics_type'] 	= $this->_statistics_type;
				$data[$key]['purpose_id_1']		= $this->_purpose_id;
				$data[$key]['purpose_id_2']		= $record['app_id'];
				$data[$key]['count_1']			= $record['used'];
				$data[$key]['details_1']		= $record['title'];
				$data[$key]['statistics_date']	= $date;
			}
		}
		return parent::setDetails($data);
	}
	
	protected function _getGatewayDetails($firstDate, $lastDate)
	{
		//	SELECT p.app_id, count(p.payment_id)
		//	FROM `j367_payplans_payment` as p 
		//	inner join`j367_payplans_transaction` as t
		//	on (p.payment_id = t.payment_id and date(t.created_date) = '2013-03-08' and t.amount <> 0)
		//	group by p.app_id
		
		$start 	= $firstDate->toMySQL(false, '%Y-%m-%d');
		$end 	= $lastDate->toMySQL(false, '%Y-%m-%d');
		$data	= array();
		
        $query 	= new XiQuery();
        $query->select('payment.app_id as app_id')
				->select('count(payment.payment_id) as used')
				->from('`#__payplans_payment` as payment')
				->innerJoin("`#__payplans_transaction` as transaction 
								ON (payment.payment_id = transaction.payment_id 
									and transaction.amount <> 0 
									and date(transaction.created_date) >= '$start'
									and date(transaction.created_date) <= '$end'
								)
							")
				->group('app_id')
				->order('used DESC');
        
        $payments 	= $query->dbLoadQuery()->loadObjectList('app_id');
        $apps 		= XiFactory::getInstance('app', 'model')->loadRecords();
        
		// IMP: if loadObjectList returns false value then do not execute foreach loop
		if(!is_array($payments)){
        	return $data;
        }

        foreach ($payments as $app_id => $payment){
        	$data[$app_id]['app_id'] 	= $app_id;
        	$data[$app_id]['title']		= $apps[$app_id]->title;
        	$data[$app_id]['used']		= $payment->used;
        }
        
        return $data;
	}
}