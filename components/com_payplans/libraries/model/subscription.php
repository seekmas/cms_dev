<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansModelSubscription extends XiModel
{

	public $crossTableNetwork 	= array(
								"users"=>array('users'),
								"usergroups"=>array('user_usergroup_map','usergroups')	
	);

	//this is to ftech on condition for cross table
	public $innerJoinCondition = array(
								'tbl-users'						=> ' #__users as cross_users on tbl.user_id = cross_users.id',
								'tbl-user_usergroup_map'		=> ' #__user_usergroup_map as cross_user_usergroup_map on tbl.user_id = cross_user_usergroup_map.user_id',
								'user_usergroup_map-usergroups' => ' #__usergroups as cross_usergroups on cross_user_usergroup_map.group_id = cross_usergroups.id'
								
	);
	
	//need a opetor for newly addded cross field
	public $filterMatchOpeartor = array(
										'plan_id'			=> array('='),
										'status'			=> array('='),
										'subscription_date' => array('>=', '<='),
										'expiration_date'	=> array('>=', '<='),
										'cross_users_username' 	=> array('LIKE'),
										'cross_usergroups_title' => array('LIKE')
	);

	
	// XITODO : HIGH : Apply validation when it is applied all over
	function validate(&$data, $pk=null,array $filter = array(),array $ignore = array())
	{
		return true;
	}

	/**
	 * find all active subscription
	 * which are expected to expire on given time
	 */
	public function getActiveSubscriptionsWhichAreExpried(XiDate $time=null)
	{
		$query = $this->getQuery();

		//there might be no table and no query at all
		if($query === null )
			return array(null);

		if($time === null){
		 	$time = new XiDate();
		}

		$tmpQuery = $query->getClone();
		$tmpQuery->clear('where')
				 ->where("`tbl`.`expiration_date` < '".$time->toMySQL()."' AND `tbl`.`expiration_date` <> '0000-00-00 00:00:00'")
				 ->where('`tbl`.`status` = '.PayplansStatus::SUBSCRIPTION_ACTIVE);

		return $tmpQuery->dbLoadQuery()->loadObjectList($this->getTable()->getKeyName());
	}
	
	public function getPreExpirySubscriptions(Array $plans, $preExpiryTime, $onAllPlans = false)
	{
		//get query
		$query = $this->getQuery();
		$tmpQuery = $query->getClone();

		// E1 : preexpiry time as per last cron run
		// E2 : preexpiry time as per current cron run
		// if expiration time is between E1 & E2
		// we will trigger PreExpiry event for subcsription
		$e1 = new XiDate(XiFactory::getConfig()->cronAcessTime);
		$e2	= new XiDate('now');
	
		$e1->addExpiration($preExpiryTime);
		$e2->addExpiration($preExpiryTime);

		$tmpQuery->clear('where')
				 ->where("`tbl`.`expiration_date` > '".$e1->toMySQL()."'" )
				 ->where("`tbl`.`expiration_date` < '".$e2->toMySQL()."'" )
				 ->where('`tbl`.`status` = '.PayplansStatus::SUBSCRIPTION_ACTIVE);
				
		if($onAllPlans !== true){
			$tmpQuery->where("`tbl`.`plan_id` in ( ". implode(',', $plans)." )" );
		} 

		return $tmpQuery->dbLoadQuery()->loadObjectList($this->getTable()->getKeyName());
	}
	
	public function getPostExpirySubscriptions(Array $plans, $postExpiryTime, $onAllPlans = false)
	{
		//get query
		$query = $this->getQuery();
		$tmpQuery = $query->getClone();

		// E1 : postexpiry time as per last cron run
		// E2 : postexpiry time as per current cron run
		// if post expiration time is between E1 & E2
		// we will trigger PostExpiry event for subcsription
		$e1 = new XiDate(XiFactory::getConfig()->cronAcessTime);
		$e2	= new XiDate('now');
	
		$e1->subtractExpiration($postExpiryTime);
		$e2->subtractExpiration($postExpiryTime);

		$tmpQuery->clear('where')
				 ->where("`tbl`.`expiration_date` > '".$e1->toMySQL()."'" )
				 ->where("`tbl`.`expiration_date` < '".$e2->toMySQL()."'" )
				 ->where('`tbl`.`status` = '.PayplansStatus::SUBSCRIPTION_EXPIRED);
				
		if($onAllPlans !== true){
			$tmpQuery->where("`tbl`.`plan_id` in ( ". implode(',', $plans)." )" );
		} 

		return $tmpQuery->dbLoadQuery()->loadObjectList($this->getTable()->getKeyName());
		
	} 
	
	public function getPostActivationSubscriptions(Array $plans, $postActivationTime, $onAllPlans = false)
	{
		//get query
		$query = $this->getQuery();
		$tmpQuery = $query->getClone();

		// A1 : postactivation time as per last cron run
		// A2 : postactivation time as per current cron run
		// if post expiration time is between A1 & A2
		// we will trigger PostActivation event for subcsription
		$a1 = new XiDate(XiFactory::getConfig()->cronAcessTime);
		$a2	= new XiDate('now');
	
		$a1->subtractExpiration($postActivationTime);
		$a2->subtractExpiration($postActivationTime);

		$tmpQuery->clear('where')
				 ->where("`tbl`.`subscription_date` > '".$a1->toMySQL()."'" )
				 ->where("`tbl`.`subscription_date` < '".$a2->toMySQL()."'" )
				 ->where('`tbl`.`status` = '.PayplansStatus::SUBSCRIPTION_ACTIVE);
				
		if($onAllPlans !== true){
			$tmpQuery->where("`tbl`.`plan_id` in ( ". implode(',', $plans)." )" );
		} 

		return $tmpQuery->dbLoadQuery()->loadObjectList($this->getTable()->getKeyName());
		
	}
	
    /**
     * Returns total sales according to their respective plans, between given starting date and ending date
    */
    public function getSalesOfPlans(XiDate $firstDate, XiDate $endDate)
    {
        $query = new XiQuery();

		$query->select('plan_id, count(subscription_id) as sales')
				->from('`#__payplans_subscription`')
				->where('subscription_date >= '."'".$firstDate->toMySQL()."'")
				->where('subscription_date <= '."'".$endDate->toMySQL()."'")
				->group('plan_id');
		
		return $query->dbLoadQuery()->loadObjectList('plan_id');
    	
    }
    
    public function getRecentSubscriptions($limit = 5, $offset = 0)
    {
		$query = new XiQuery();
		$query->select('*')
				->from('#__payplans_subscription')
				->where('`subscription_date` <>'."'".'0000-00-00 00:00:00'."'")
				->order('`subscription_date` DESC')
				->limit($limit, $offset);
				 		
		return $query->dbLoadQuery()->loadObjectList('subscription_id');
    }
}


class PayplansModelformSubscription extends XiModelform {}
