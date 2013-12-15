<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansStatisticsDonation extends PayplansStatistics
{
	protected $_purpose_id		= '2005';
	public 	  $_statistics_type	= 'donation';
	
	public function setDetails($data=array(), $dates_to_process)
	{
		foreach ($dates_to_process as $id => $process_date){
			list($firstDate, $lastDate) = $this->getFirstAndLastDates($process_date);
			$donation = PayplansFactory::getInstance('transaction', 'model')
										->getRevenuesOfDonation($firstDate, $lastDate);
			
			// addup revenue of donation
			$key = "'".$process_date->toUnix()."'";
			$data[$key]['purpose_id_1'] 	= $this->_purpose_id;
			$data[$key]['statistics_type']	= $this->_statistics_type;
			$data[$key]['count_1'] 			= $donation;
			$data[$key]['statistics_date']	= $process_date; //IMP: it should be an object of XiDate
		}
		return parent::setDetails($data);
	}
	
}