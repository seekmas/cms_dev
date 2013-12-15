<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

abstract class PayplansStatistics 
{
	protected $_column_count	= 10;
	
	public function setDetails($data = array(), $dates_to_process = array())
	{
		foreach ($data as $value){
			$is_exists = $this->selectStatistics($value);
			
			if($is_exists == 0){
				$this->insertStatistics($value);
			}
			else {
				$this->updateStatistics($value);
			}
		}
		
		return true;
	}
	
	protected function selectStatistics($values)
	{
		$now = new XiDate('now');
		$purpose_id_2 = isset($values['purpose_id_2']) ? $values['purpose_id_2'] : 0;
		$query = new XiQuery();
		
		$query->select('count(*)')
					->from('#__payplans_statistics')
					->where('`statistics_type` = '."'".$values['statistics_type']."'")
			  		->where('`purpose_id_1` = '.$values['purpose_id_1'])
			  		->where('`purpose_id_2` = '.$purpose_id_2)
			  		->where('date(`statistics_date`) = ' ."'". $values['statistics_date']->toMySQL(false, '%Y-%m-%d')."'");
		
  		return $query->dbLoadQuery()->loadResult();
	}
	
	protected function insertStatistics($value)
	{
		$str		= '';
		$today = new XiDate('now');
		
		$str .= '('."'".$value['statistics_type'] . "'"
						.','.$value['purpose_id_1']
						.','.(isset($value['purpose_id_2']) ? $value['purpose_id_2'] : 0);
							
		for($count = 1; $count <= $this->_column_count; $count++){
			$str .= ','.(isset($value['count_'.$count]) ? $value['count_'.$count] : 0);
		}
		
		$str .= ','. "'". (isset($value['details_1']) ? htmlentities($value['details_1'], ENT_QUOTES) : ' ') . "'"
					.','. "'". (isset($value['details_2']) ? htmlentities($value['details_2'], ENT_QUOTES) : ' ') . "'"
					.','. "'". (isset($value['message']) ? htmlentities($value['message'], ENT_QUOTES) : ' ') . "'"
					.','."'" . $value['statistics_date']->toMySQL() . "'"
					.','."'" . $today->toMySQL() . "'"
					.')';
					
		return PayplansFactory::getInstance('statistics', 'model')->insertStatiscticsData($str);
	}

	protected function updateStatistics($value)
	{
		$values = array();
		$values['statistics_type'] 	= "'".$value['statistics_type']."'";
		$values['purpose_id_1']		=	$value['purpose_id_1'];
		$values['purpose_id_2']		=	(isset($value['purpose_id_2']) ? $value['purpose_id_2'] : 0);
		
		for($count = 1; $count <= $this->_column_count; $count++){
			$values['count_'.$count]	=	(isset($value['count_'.$count]) ? $value['count_'.$count] : 0);
		}
		
		$values['details_1']	=	"'". (isset($value['details_1']) ? htmlentities($value['details_1'], ENT_QUOTES) : ' ') ."'";
		$values['details_2']	=	"'". (isset($value['details_2']) ? htmlentities($value['details_2'], ENT_QUOTES) : ' ') ."'";
		$values['message']		=	"'". (isset($value['message']) ? htmlentities($value['message'], 	 ENT_QUOTES) : ' ') ."'";
		$values['statistics_date'] = $value['statistics_date'];
		
		return PayplansFactory::getInstance('statistics', 'model')->updateStatiscticsData($values);
	}
	
	/**
	 * 
	 * @return array XiDate
	 */
	public function getDates()
	{
		if(!empty($this->dates_to_process)){
			return $this->dates_to_process;
		}
		
		// Step 1 :- select latest date available in the statistics record
						// Step 1.1 :-  if statistics table do not contain any data 
						// Step 1.2 :- then go to subscription table and get the oldest date
		// Step 2 :- if latest date of statistics is equal to today's date then calculate today's data
		// Step 3 :- if latest date of statistics is less than today's date then firstly calculate previous data
		
		$latestDate = $this->getLatestDate($this->_statistics_type);
		// if still $latestDate == empty then return blank array()
		if(empty($latestDate)){
			return array();
		}
		
		$latestDate = new XiDate($latestDate);
		$today 		= new XiDate('now');
		
		$dates_to_process 	= array();

		// Step 2
		if($latestDate->toMySQL(false, '%Y-%m-%d') == $today->toMySQL(false, '%Y-%m-%d')){
			$dates_to_process[] = $today;
		}
		
		$limit = (PayplansHelperStatistics::getRebuildLimit());
		if($latestDate->toMySQL(false, '%Y-%m-%d') < $today->toMySQL(false, '%Y-%m-%d')){
			// Step 3 
			while (($latestDate->toUnix() < $today->toUnix()) && ($limit >= 0)){
				$copy_date				= unserialize(serialize($latestDate));
				$dates_to_process[] 	= $copy_date;
				$latestDate->addExpiration('000001000000');
				$limit--;
			}
		}
		
		$this->dates_to_process = $dates_to_process;
		return $this->dates_to_process;
	}
	
	public function getLatestDate($statistics_type)
	{
		// Step 1.1
		$model = PayplansFactory::getInstance('statistics', 'model');
		
		$this->latestDate = $model->getLatestStatisticsDate($statistics_type);
		
		// Step 1.2
		if(empty($this->latestDate)){
			$this->latestDate = $this->getOldestDate();
		}
		
		return $this->latestDate;
	}

	/**
	 * Returns date of oldest subscription
	 */
	public function getOldestDate(){
		
		$query = new XiQuery();
		$query->select('min(date(`modified_date`)) as latest')
			  ->from('#__payplans_subscription');
		
		//V. V. IMP:- This is the oldest date			
		return $query->dbLoadQuery()->loadResult(); 
	}
	
	/**
	* returns timestamp for starting and ending time of the day.
	*/
	public function getFirstAndLastDates(XiDate $date)
	{
		$year 			= $date->toMySQL(false, '%Y');
		$month 			= $date->toMySQL(false, '%m');
		$day 			= $date->toMySQL(false, '%d');
		$firstDate  	= new XiDate(mktime(0,0,0,$month,$day,$year));
		$lastDate  		= new XiDate(mktime(23,59,59,$month,$day,$year));

		return array(unserialize(serialize($firstDate)), unserialize(serialize($lastDate)));
		
	}
	
}
