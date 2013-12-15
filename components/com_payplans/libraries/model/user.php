<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

/**
 * Class assumes that user should always be synched-up with
 * joomla core user system
 * This will not support those user who are not listed in payplans_user table
 * IMP : Will maintain synchup during installation and while visiting users in backend 
 * @author meenal
 *
 */
class PayplansModelUser extends XiModel
{
	protected $recordId ;
		
	public $crossTableNetwork 	= array(
								"subscription"=> array('subscription'),
								"users"		  => array('users')	
	);
							  
	public $innerJoinCondition	= array(
								'tbl-subscription'	 => ' `#__payplans_subscription` as cross_subscription on cross_subscription.user_id = tbl.id'							
	);
	
	public $filterMatchOpeartor = array(
										'username' 	=> array('LIKE'),
										'usertype'	=> array('='),
										'cross_subscription_plan_id'	=> array('='),
										'cross_subscription_status'		=> array('=')
	);
	
	/**
     * Builds FROM tables list for the query
     */
    protected function _buildQueryFrom(XiQuery &$query)
    {
    	// Xi: ticket #1789
    	// to get multiple records
    	if(!is_numeric($this->recordId)){
	    	$query->from('`#__users` AS tbl');
    		
    		return;
    	}
    	
    	// Xi: ticket #1789
    	// To get one user we have to write query like this
    	//SELECT tbl.*
		//FROM (	
		//	SELECT tmpjuser.*, ppuser.*
		//	FROM 
		//		(SELECT  joomla_user_fields) AS tmpjuser
		//	LEFT JOIN 
		//		(SELECT  payplans_user_fields) AS ppuser 
		//	ON (tmpjuser.id = ppuser.user_id)
		//	
		//) AS tbl

    	$q1 = new XiQuery();
    	$q2 = new XiQuery();
    	$q3 = new XiQuery();
    	
    	$q1->select(' j.`id` AS user_id')
    	   ->select(' j.`name` AS realname')
    	   ->select(' j.`username` AS username')
    	   ->select(' j.`email` As email')
    	   ->select(' j.`registerDate` AS registerDate')
    	   ->select(' j.`lastvisitDate` AS lastvisitDate')
    	   ->from('`#__users` AS j')
    	   ->where('j.`id`='.$this->recordId);
    	
    	$q2->select(' t.`address` ')
    		  ->select(' t.`state` ')
    	      ->select(' t.`city` ')
    	   	  ->select(' t.`country` ')
	    	  ->select(' t.`zipcode` ')
	    	  ->select(' t.`preference` ')
	    	  ->select(' t.`params` ')
	    	  ->select('t.`user_id` AS puser_id')
	    	  ->from('#__payplans_user AS t')
	    	  ->where('t.`user_id`='.$this->recordId);
	    	  
	     $q3->select('tmpjuser.*, ppuser.*')
	        ->from('('.$q1.') AS tmpjuser')
	        ->leftJoin('('.$q2.') AS ppuser ON (tmpjuser.user_id = ppuser.puser_id)');
    	
    	$query->from('('.$q3.') AS tbl');
    	
    }
	
    
	/**
     * Builds a generic ORDER BY clasue based on the model's state
     */
	// Xi: ticket #1789
    protected function _buildQueryOrder(XiQuery &$query)
    {
    	
	    	$order      = $this->getState('filter_order');
	    	if(!isset($order) || empty($order)){
	    		$order = 'id';
	    	}
	       	$direction  = strtoupper($this->getState('filter_order_Dir'));
	       	if(!isset($direction) || empty($direction)){
	       		$direction = "ASC";
	       	}
		// if there are multiple records to fetch 
		// then we have only one table which is joomla_user table
		// XiTODO: alias
	    if(!is_numeric($this->recordId)){
	    		return $query->order("$order $direction");	
	    }
    }
    
    
	protected function _buildQueryFields(XiQuery &$query)
    {
		// when we collect multiple records of users then we use only joomla_user table.
		// There are various functions which are working on name of alias.
		// 
    	if(!is_numeric($this->recordId)){
    		$query->select(' tbl.`id` AS user_id')
    			   ->select(' tbl.`name` AS realname')
    			   ->select(' tbl.`username` AS username')
    			   ->select(' tbl.`email` AS email')
    			   ->select(' tbl.`registerDate` AS registerDate')
    			   ->select(' tbl.`lastvisitDate` AS lastvisitDate');
    			   return ;	
    	}
		
    			   
    	$query->select('tbl.*');
    }
    
	//added filter for user so it is necessary to override _buildQueryFilter function here 
	//so that proper query can be build corresponding to applied filter
	protected function _buildQueryFilter(XiQuery &$query, $key, $value,&$temp)
    {
    	// Only add filter if we are working on bulk records
		if($this->getId()){
			return $this;
		}
		
    	XiError::assert(isset($this->filterMatchOpeartor[$key]), "OPERATOR FOR $key IS NOT AVAILABLE FOR FILTER");
    	XiError::assert(is_array($value), XiText::_('COM_PAYPLANS_VALUE_FOR_FILTERS_MUST_BE_AN_ARRAY'));

    	$cloneOP    = $this->filterMatchOpeartor[$key];
    	$cloneValue = $value;
    	
    	while(!empty($cloneValue) && !empty($cloneOP)){
    		$op  = array_shift($cloneOP);
    		$val = array_shift($cloneValue);

			// discard empty values
    		if(!isset($val) || '' == JString::trim($val))
    			continue;
    		if(stristr($key,"cross_"))
    		{
    			//seprate the variables 
    			$key   		= str_replace("cross_", "",$key); 			// key = cross_filtertable_fieldname
    			$crosstable = strtok($key,'_');				  			// crosstable = filtertable
    			$key   		= str_replace("{$crosstable}_", "",$key); 	// key = fieldname
    		   			
 				if(isset($this->crossTableNetwork[$crosstable]))
	 			{
    				$travesingTables = $this->crossTableNetwork[$crosstable];
	 				$prevTable 		 = "tbl";
	 				foreach ($travesingTables as $traversed)
	   				{  
	   					if(!isset($temp["{$prevTable}-{$traversed}"]))
	   					{
	   						$temp["{$prevTable}-{$traversed}"] = "";
	   					}
	   					if($crosstable == $traversed)
	   					{	$corssValue = "'$val'";
	   						if(JString::strtoupper($op) == 'LIKE'){
	   							$corssValue = "'%{$val}%'";
					    	  
					    	}
						$temp["{$prevTable}-{$traversed}"] .= " AND cross_{$crosstable}.`$key` $op $corssValue ";
			    	  	$prevTable = $traversed;
						continue;

	   					}
	   					
	   					$temp["{$prevTable}-{$traversed}"] .= "";	   					
	   					$prevTable = $traversed;

					}
 				}
 				continue;
				//CROSS FILTERING ENDS HERE
			}  		
			
    		if(JString::strtoupper($op) != 'LIKE'){
				if($key == 'usertype'){
					//this subquery will fetch all the users with the desired usertype 
					// Xi: ticket #1789
					$query->where("  `tbl`.`id` IN( SELECT map.`user_id` 
											     FROM `#__usergroups` as groups, `#__user_usergroup_map` as map 
											     WHERE ( map.group_id = groups.id AND groups.title = '$val'))	");
						continue;
					}
				$query->where("`$key` $op '$val'");
				continue;
			}
		
			// filter according to username, realname and email
   			if($key == 'username'){
   				$nameKey = 'realname';
   				if(!is_numeric($this->recordId)){
   					$nameKey = 'name';
   				}
    	  		$query->where("( `$key` $op '%{$val}%' || `$nameKey` $op '%{$val}%' || `email` $op '%{$val}%' )");
    	  	}
	    	else {
	    	  	$query->where("`$key` $op '%{$val}%'");			
	    	}
		}
    }

	function save($data, $pk=null, $new=false)
    {
		$new = $this->getTable()->load($pk)? false : true;
		return parent::save($data, $pk, $new);
    }
    
	public function loadRecords(Array $queryFilters=array(), Array $queryClean = array(), $emptyRecord=false, $orderby = null)
	{	
		//it is required to decide which query to execute in FROM
		// for single record or multiple record
		if(!empty($queryFilters)){
			foreach($queryFilters as $key =>$value){
				// Xi: ticket #1789
				if($key === 'id'){
					$this->recordId = $value;
				}
			}
		}
		
		$query = $this->getQuery();

		//there might be no table and no query at all
		if($query === null )
			return null;

		//Support Query Filters, and query cleanup
		$tmpQuery = clone ($query);

		foreach($queryClean as $clean){
			$tmpQuery->clear(JString::strtolower($clean));
		}

		foreach($queryFilters as $key=>$value){
			// Xi: ticket #1789
			// Imp : Do NOT update 'id' in case of multiple record, as it will get `id`
			if(is_numeric($this->recordId)){
				// for single records
				//support id too, replace with actual name of key
				$key = ($key==='id')? $this->getTable()->getKeyName() : $key;
			}
			
			// V.IMP : if any filter sends user_id or realname statically
			// then convert user_id into id and realname into name
			if(!is_numeric($this->recordId)){
				$key = ($key==='user_id') ? 'id' : $key;
				$key = ($key==='realname') ? 'name' : $key;
			}
			
			// only one condition for this key
			if(is_array($value)==false){
				$tmpQuery->where("`tbl`.`$key` =".$this->_db->Quote($value));
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
				$tmpQuery->where("`tbl`.`$key` $operator ".$val);
			}
			
		}

		//we want returned record indexed by columns
		$this->_recordlist = $tmpQuery->dbLoadQuery()
		 							  ->loadObjectList($this->getTable()->getKeyName());

		//handle if some one required empty records, only if query records were null
		if($emptyRecord && empty($this->_recordlist)){
			$this->_recordlist = $this->getEmptyRecord();
		}

		$data = $this->_recordlist;

		//get usertype of the user and append it with the data
		$this->getUsertype($data);
			
		return $data;
	}
	
	public function getQuery()
	{
		//create a new query
		$this->_query = new XiQuery();

		// Query builder will ensure the query building process
		// can be overridden by child class
		if($this->_buildQuery($this->_query))
			return $this->_query;

		//in case of errors return null
		//XITODO : Generate a 500 Error Here
		return null;
	}
	
	protected function getUsertype(&$users)
	{
		$user_ids = array_keys($users);
		
		//when there is nothing in users
		if(empty($user_ids)){
			return $users;
		}
		
		$query = new XiQuery();
		
		//if only single record exists 
		if(count($users) == 1){
			$query->where(' usergroupmap.user_id = '.array_shift($user_ids));
		}
		
		else { 
				//in case of multiple users, user_usergroup_map table 
				//contains multiple records for a single user thats why 
				//group by with user_id is required
				$query->where(' usergroupmap.user_id IN ('.implode(',', $user_ids).') ')
			  		  ->group(' usergroupmap.user_id ');
		}
		
		$query->select('group_concat(groups.`title`) as usertype, usergroupmap.`user_id` as user_id')
			  ->from('`#__user_usergroup_map` as usergroupmap , `#__usergroups` as groups')
			  ->where(' usergroupmap.group_id = groups.id ');
			    
		$userGroups[] = $query->dbLoadQuery()
							  ->loadObjectList('user_id');

		$groups = array_shift($userGroups);
		foreach ($users as $user){
			$user->usertype = $groups[$user->user_id]->usertype;
		}
		
	}
	
	/*
	 * Count number of total records as per current query
	 * clean the query element
	 */
	public function getTotal($queryClean = array('select','limit','order'))
	{
		if($this->_total){
			return $this->_total;
		}

		$query 	= $this->getQuery();

		//Support query cleanup
		$tmpQuery = clone ($query);

		foreach($queryClean as $clean){
			$tmpQuery->clear(JString::strtolower($clean));
		}

		$tmpQuery->select('COUNT(*)');
				
        $this->_total 	= $tmpQuery->dbLoadQuery()->loadResult();

		return $this->_total;
	}
      
	public function getWalletBalance($userId)
	{
		//XiTODO : use query element
		$db = XiFactory::getDbo();
		$query = 'SELECT sum(`amount`) FROM  `#__payplans_wallet` WHERE `user_id` = '. $userId;
		$db->setQuery($query);
		
		return $db->loadResult();
	}
	
}

class PayplansModelformUser extends XiModelform {}

