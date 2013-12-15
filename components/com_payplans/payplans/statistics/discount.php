<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansStatisticsDiscount extends PayplansStatistics
{
	protected $_purpose_id		= '1005';
	public $_statistics_type	= 'discount';
	
	public function setDetails($data=array(), $dates_to_process)
	{
		foreach ($dates_to_process as $id => $date){
			list($firstDate, $lastDate) = $this->getFirstAndLastDates($date);
			
			$key = $date->toUnix();
			$data[$key]['statistics_type'] 	= $this->_statistics_type;
			$data[$key]['purpose_id_1'] 	= $this->_purpose_id;
			$data[$key]['count_1']			= $this->getTotalUsage($firstDate, $lastDate);
			$data[$key]['count_2']			= $this->getActualConsumption($firstDate, $lastDate);
			$data[$key]['count_3'] 			= $this->getDiscount($firstDate, $lastDate);
			$data[$key]['statistics_date']	= $date;
		}
		
		return parent::setDetails($data);
	}
	
	public function getDiscount(XiDate $firstDate, XiDate $lastDate)
	{
		$query = new XiQuery();
		$query->select('invoice.*')
					->from('#__payplans_invoice as invoice')
					->innerJoin('#__payplans_wallet as wallet ON ( invoice.`invoice_id` = wallet.`invoice_id` ) ')
					->where('wallet.`created_date` >= '."'". $firstDate->toMySQL() . "'")
					->where('wallet.`created_date` <= '."'". $lastDate->toMySQL() . "'")
					->where('invoice.subtotal > 0');
					
		$invoices = $query->dbLoadQuery()->loadObjectList('invoice_id');
		$discount = 0;
		
		foreach ($invoices as $invoice){
			$discount = $discount + PayplansInvoice::getInstance($invoice->invoice_id, null, $invoice)->getDiscount();
		}
		
		return $discount;
	}
	
	/**
	 * Get total usage of the given reference and type
	 */
	function getTotalUsage(XiDate $firstDate, XiDate $lastDate, $serial=array(20,25))
	{
		$query = new XiQuery();
		return $query->select('count(*)')
					 ->from('`#__payplans_modifier` as modifier')
					 ->leftJoin('`#__payplans_invoice` as invoice ON invoice.`invoice_id` = modifier.`invoice_id`')
					 ->where('modifier.`serial` IN ('.implode(',',$serial).')')
					 ->where('invoice.`created_date` >= '."'".$firstDate->toMySQL()."'")
					 ->where('invoice.`created_date` <= '."'".$lastDate->toMySQL()."'")
					 ->dbLoadQuery()
					 ->loadResult();
	}
	
	/**
	 * Get actual consumption of the given reference and type
	 */
	function getActualConsumption($firstDate, $lastDate, $serial=array(20,25))
	{
		// Step 1 :- collect all invoices with-in two wallet.created_date dates
		// Step 2 :- then collect discount related modifier records according to those invoice_ids 
		
		// Step 1:-
		$query = new XiQuery();
		$query->select('invoice.*')
					->from('#__payplans_invoice as invoice')
					->innerJoin('#__payplans_wallet as wallet ON ( invoice.`invoice_id` = wallet.`invoice_id`'
								.' and wallet.`created_date` >='."'". $firstDate->toMySQL() . "'"
								.' and wallet.`created_date` <='."'". $lastDate->toMySQL() . "'"
								.' )' );
					
		$invoices = $query->dbLoadQuery()->loadObjectList('invoice_id');
		$invoice_ids = array_keys($invoices);
		
		if(empty($invoice_ids)){
			return 0;
		}
		
		// Step 2:-		
		$query = new XiQuery();
	 	return $query->select('count(*)')
					 ->from('`#__payplans_modifier` as modifier')
					 ->innerJoin('`#__payplans_invoice` as invoice ON (invoice.`invoice_id` = modifier.`invoice_id` and invoice.`invoice_id` IN ('.implode(',', $invoice_ids).') )')
					 ->where('modifier.`serial` IN ('.implode(',',$serial).')')
					 ->where('invoice.`status` = '.PayplansStatus::INVOICE_PAID)
					 ->dbLoadQuery()
					 ->loadResult();
		
	}

}