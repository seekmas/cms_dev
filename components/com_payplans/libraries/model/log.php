<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansModelLog extends XiModel
{

	//this is to fetch the cross field from which table 
	public $crossFilterTable = array(
								"cross_users_username" => "users"
	);
	
	public $crossTableNetwork 	= array(
								"users"=>array('users')
	);

	//this is to ftech on condition for cross table
	public $innerJoinCondition = array(
								'tbl-users'			=> ' #__users as cross_users on tbl.user_id = cross_users.id'
	);
	
	
	//XITODO : move it to variable rather then a function call
	public $filterMatchOpeartor = array(
										'message'	=> array('LIKE'),
										'level' 	=> array('='),
										'class' 	=> array('='),
										'user_ip' 	=> array('LIKE'),
										'object_id' => array('='),
										'created_date' => array('>=', '<='),
										'cross_users_username' => array('LIKE')
										);
										
	public function getLogsOnDashboard($log_level = XiLogger::LEVEL_ERROR, $limit = 5, $offset = 0)
	{
		$now   			= new XiDate();
		$previousDate   = $now->subtractExpiration('000100000000')->toMySQL();
		$query 			= new XiQuery();
		$query->select('*')
			  ->from('`#__payplans_log`')
			  ->where('`level` = '. $log_level)
			  ->where('`read` = 0')
			  ->where("`created_date` >= '".$previousDate."'")
			  ->order('`created_date` DESC')
			  ->limit($limit,$offset);

		return $query->dbLoadQuery()->loadObjectList();
	}
	
	public function markRead($logId)
	{
		$query  	= new XiQuery();
		$query->update('`#__payplans_log`')
		 	  ->set('`read` = 1')
		 	  ->where('`log_id` ='.$logId);
		
		if(!$query->dbLoadQuery()->query()){
			return false;
		}
		return true;
	}
}

class PayplansModelformLog extends XiModelform {}
