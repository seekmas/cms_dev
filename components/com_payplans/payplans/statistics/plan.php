<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansStatisticsPlan extends PayplansStatistics
{
	public $_statistics_type	= 'plan';
	
	public function setDetails($data=array(), $dates_to_process)
	{
		foreach ($dates_to_process as $id => $process_date){
			list($firstDate, $lastDate) = $this->getFirstAndLastDates($process_date);
			
			$plans					= PayplansFactory::getInstance('plan', 'model')->loadRecords(array());
			$salesOfPlans 	= PayplansFactory::getInstance('subscription', 'model')->getSalesOfPlans($firstDate, $lastDate);
			$revenueOfPlans = PayplansFactory::getInstance('transaction', 'model')->getRevenuesOfPlans($firstDate, $lastDate);
			
			// addup sales of plans
			$sales = array();
			foreach ($salesOfPlans as $plan_id => $sub){
				$sales[$plan_id] = intval($sub->sales);
			}
			
			// addup revenue of plans
			$revenue = array();
			foreach ($revenueOfPlans as $plan_id => $sub){
				$revenue[$plan_id] = intval($sub->amount);
			}
			
			$key = "'".$process_date->toUnix()."'";
			foreach ($plans as $pid => $plan){
				$key .= $pid;
				$data[$key]['purpose_id_1'] 	= $pid;
				$data[$key]['statistics_type']	= $this->_statistics_type;
				$data[$key]['count_1'] 			= isset($sales[$pid]) ? $sales[$pid] : 0; // Sales Per Plan
				$data[$key]['count_2'] 			= isset($revenue[$pid]) ? $revenue[$pid] : 0; // Revenue Per Plan
				$data[$key]['details_1']		= $plan->title;
				$data[$key]['statistics_date']	= $process_date;
			}
		}
		return parent::setDetails($data);
	}
	
}