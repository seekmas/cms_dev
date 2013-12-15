<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

//XITODO : Remove this file and  merege functions with LibPlan
class PayplansHelperPlan
{
	static function isValidPlan($planId)
	{
		// is exist and published
		$record = XiFactory::getInstance('plan', 'model')
							->loadRecords(array('plan_id'=>$planId, 'published'=>1), array('limit'));
		
		// is accessible to current user
		if(isset($record[$planId]) && !empty($record[$planId]))
			return true;

		return false;
	}

	// returns plan details
	static function getDetails($planId)
	{
		return array();
	}
	
	static function getName($planId)
	{	
		if(PayplansPlan::getInstance($planId) == false){
			return XiText::_("COM_PAYPLANS_SUBSCRIPTION_PLAN_DOES_NOT_EXIST");
		}	
		return PayplansPlan::getInstance($planId)->getTitle(); 
	}
	
	/**
	 * @deprecated since 2.1.6
	 */
	static function convertExpirationTime($period, $unit)
	{
		$days = $months= $years = 0;
		switch($unit)
		{
			case 'Y':
				$years	= $period ;
				break;

			case 'M' :
				if($period >= 12 ){
					$years	= $period / 12 ;
					$period = $period % 12 ;
				}
				
				$months  = $period % 12 ;
				break;
				
			case 'W' :
				//convert into number of days
				// let days system handle it.
				$period = $period * 7 ;
			
			case 'D' :	
				if($period >= 365 ){
					$years	= $period / 365 ;
					$period = $period % 365 ;
				}
				
				if($period >= 30 ){
					$months = $period / 30 ;
					$period = $period % 30 ;
				}
				
				$days 	= $period % 30 ;

				break;				 
		}
		
		$time =  ($years<10  ? '0':'').number_format($years, 0)
				.($months<10 ? '0':'').number_format($months, 0)
				.($days<10   ? '0':'').number_format($days, 0)
				."000000";
		return $time;
	}
	
	static function convertIntoTimeArray($rawTime)
	{
		$time['year']    = "00";
		$time['month']   = "00";
		$time['day']     = "00";
		$time['hour']    = "00";
		$time['minute']  = "00";
		$time['second']  = "00";

		if($rawTime != 0)
		{
			$rawTime = str_split($rawTime, 2);
			$time = array();
			$time['year']    =  array_shift($rawTime);
			$time['month']   = array_shift($rawTime);
			$time['day']     =  array_shift($rawTime);
			$time['hour']    =  array_shift($rawTime);
			$time['minute']  =  array_shift($rawTime);
			$time['second']  =  array_shift($rawTime);
		}

		return $time;
	}
	
	static function buildPlanCloumnClasses($rowPlans,$planCount)
	{
		//setup defaults
		if(empty($rowPlans) || in_array(0,$rowPlans)){
			if($planCount%5 == 0){
				$rowPlans = array(3,2);		
			}elseif($planCount%4 == 0){
				$rowPlans = array(4);		
			}elseif($planCount%3 == 0){
				$rowPlans = array(3);		
			}else{
				$rowPlans = array(2);		
			}
		}
		

		$planClasses = array();
		
		
		//set default 3
		$columns = 3;
		
		//calculate span classes for plans
        for($totalCount = $planCount,$rows=array(); $totalCount > 0; $totalCount=($totalCount-$columns)){
        	if(!empty($rowPlans)){
        		$columns = array_shift($rowPlans);
        	}
        	
        	$span = (int)(12/$columns);
        	$columns = ($columns > $totalCount)?$totalCount:$columns;
        	
        	for($i=1;$i <= $columns; $i++){
        		$planClasses[] =' span'.$span;
        	}
        	
        	$rows[] = $columns;
        }
       
		return array($planClasses,$rows);
	}
	
	/**
	 * @deprecated it, use get function
	 */
	static function getPlans($filter = array('published' => 1, 'visible' => 1), $instanceRequire = true)
	{
		return self::get($filter, $instanceRequire);
	}
	
	public static function get($filter, $instanceRequire = true)
	{
		// XiTODO : use instance type argument and apply it for all entities
		$plans = XiFactory::getInstance('plan', 'model')
						->loadRecords($filter);
						
		if($instanceRequire !== PAYPLANS_INSTANCE_REQUIRE){
			return array_keys($plans);
		}
			
		$instances = array();
		foreach($plans as $plan){
			$instances[$plan->plan_id] = PayplansPlan::getInstance($plan->plan_id, null, $plan);
		}
		
		return $instances;	
	}
}
