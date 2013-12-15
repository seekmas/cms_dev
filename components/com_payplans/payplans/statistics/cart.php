<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansStatisticsCart extends PayplansStatistics
{
	protected $_purpose_id		= '1001';
	public $_statistics_type	= 'cart';
	
	public function setDetails($data=array(), $dates_to_process)
	{
		foreach ($dates_to_process as $id => $date){
			list($firstDate, $lastDate) = $this->getFirstAndLastDates($date);
			
			// set cart statistics details
			$key = $date->toUnix();
			$data[$key]['statistics_type'] 	= $this->_statistics_type;
			$data[$key]['purpose_id_1']		= $this->_purpose_id;
			$data[$key]['count_1'] 			= $this->_unUtilizedInvoices($firstDate, $lastDate); 
			$data[$key]['statistics_date']	= $date;
		}
		return parent::setDetails($data);
	}
	
	protected function _unUtilizedInvoices(XiDate $firstDate, XiDate $lastDate)
	{
		$status = array(PayplansStatus::NONE, PayplansStatus::INVOICE_CONFIRMED);
    	$unutilizedInvoices = PayplansFactory::getInstance('invoice', 'model')
												->getUnUtilizedInvoices($status, $firstDate, $lastDate);
		return $unutilizedInvoices;
	}
}