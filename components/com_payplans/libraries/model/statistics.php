<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansModelStatistics extends XiModel
{
	public $filterMatchOpeartor = array(
										'purpose_id_1' 	  => array('='),
										'statistics_date' => array('>=', '<=')
										);
	
	public function insertStatiscticsData($values)
	{
		$db		= XiFactory::getDBO();
		
	    $query 	=  'INSERT INTO #__payplans_statistics '. 
		          		'(`statistics_type`, `purpose_id_1`, `purpose_id_2`, 
	          				`count_1`, `count_2`, `count_3`, 
	          				`count_4`, `count_5`, `count_6`, 
	          				`count_7`, `count_8`, `count_9`, 
	          				`count_10`, `details_1`, `details_2`, 
	          				`message`, `statistics_date`, `modified_date`
	          			)'.
	          			' VALUES '. $values;
	    					
		$db->setQuery($query);
		
		if(!$db->query()){
			return false;
		}
		
	}
	
	public function updateStatiscticsData($values)
	{
		$now 				= new XiDate('now');
		$modified_date 		= $now->toMySQL();
		$statistics_date	= $values['statistics_date'];
		
		$query = new XiQuery();
		$query->update('#__payplans_statistics')
					->set('`purpose_id_2` = '.$values['purpose_id_2']);
					
		for($count = 1; $count <= 10; $count++){
			$query->set("`count_$count` = ".$values['count_'.$count]);
		}			
					
		$query->set('`details_1` = '.$values['details_1'])
			  ->set('`details_2` = '.$values['details_2'])
			  ->set('`message` = '.$values['message'])
			  ->set('`modified_date` = '."'".$modified_date."'")
			  ->where('`statistics_type` = '.$values['statistics_type'])
			  ->where('`purpose_id_1` = '.$values['purpose_id_1'])
			  ->where('date(`statistics_date`) = ' ."'". $statistics_date->toMySQL(false, '%Y-%m-%d')."'");
		
		if(!$query->dbLoadQuery()->query()){
			return false;
		}
	}
	
	public function getLatestStatisticsDate($type)
	{
		$query = new XiQuery();
		
		return $query->select('max(`statistics_date`) as latest')
						->from('`#__payplans_statistics`')
						->where('`statistics_type` = '."'".$type."'")
						->dbLoadQuery()
						->loadResult();
	}
	
	public function getSumOfRecords($filter = array(), $select = array(), $group_by = array(), $list_by = null)
	{
		$query = new XiQuery();
		
		for($count = 1; $count <= 10; $count++){
			$query->select("sum(count_$count) as count_$count");
		}
		
		foreach ($select as $value){
			$query->select("$value");
		}
		
		$query->from('#__payplans_statistics');
		
		foreach($filter as $key=>$value){
			// only one condition for this key
			if(is_array($value)==false){
				$query->where("`$key` ="."'".$value."'");
				continue;
			}
			
			// multiple keys are there
			foreach($value as $condition){
				// not properly formatted
				if(is_array($condition)==false){
					continue;
				}
				
				// first value is condition, second one is value
				list($operator, $val)= $condition;
				$query->where("$key $operator ".$val);
			}
		}
		
		foreach ($group_by as $value){
			$query->group("$value");
		}
		
		$records = ($list_by != null) ? $query->dbLoadQuery()->loadObjectList($list_by) 
									  : $query->dbLoadQuery()->loadObjectList(); 
		
		return $records; 
	}
	
	public function truncateStatistics()
	{
		$query = new XiQuery();
		
		$query->truncate('`#__payplans_statistics`');
		
		if(!$query->dbLoadQuery()->query()){
			return false;
		}
		return true;
	}
	
}

class PayplansModelformstatistics extends XiModelform {}