<?php
/**
* @copyright		Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license			GNU/GPL, see LICENSE.php
* @package			PayPlans
* @subpackage		Frontend
* @contact 			payplans@readybytes.in
*/

/**
 * Payplans Statistics
 * @author Puneet Singhal
 */
if(defined('_JEXEC')===false) die();

class PayplansHelperStatistics
{
	/**
	 * 
	 * Get sum of sales and revenue in between two dates ...
	 * @param XiDate $firstDate
	 * @param XiDate $lastDate
	 */
	public static function getPlanDataWithinDates(XiDate $firstDate, XiDate $lastDate)
	{
		$data   = array();
		$group  = array();

		//1. find data
		$query = new XiQuery();
		for($count = 1; $count <= 10; $count++){
			$query->select("sum(count_$count) as count_$count");
		}
		
		$select = array('date(`statistics_date`) as statistics_date', 'purpose_id_1 as plan_id', 'details_1 as title');
		foreach ($select as $value){
			$query->select("$value");
		}
		
		$query->from('#__payplans_statistics');
		
		$filter = array();
		$filter['statistics_type'] = 'plan';
		$filter['date(statistics_date)'] = array( array('>=' , "date('".$firstDate->toMySQL()."')"),
					array('<=' , "date('". $lastDate->toMySQL()."')"));
		
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
		
		$query->group('plan_id,statistics_date');

		$result  = $query->dbLoadQuery()->loadObjectList();
		
		
		// 2. build data structure
		$data['plans'] = array();
		foreach($result as $record){
			$date = strtotime($record->statistics_date);
			$plan = $record->plan_id;
			
			$data['plans'][$plan] = $record->title;
			if(isset($data[$date]) ==false){
				$data[$date]['sales_all'] = 0;
				$data[$date]['revenue_all'] = 0;
			}
			
			// plan specific
			$data[$date]['sales'][$plan] 	= intval($record->count_1);
			$data[$date]['revenue'][$plan] 	= intval($record->count_2);
			
			// total
			$data[$date]['sales_all'] 	+= intval($record->count_1);
			$data[$date]['revenue_all']	+= intval($record->count_2);
		}
		
		//For adding Donation in Revenue 
		// $group, $select, $filter use values from above only $filter['statistics_type'] = 'donation' 
		/*
		$filter['statistics_type'] = 'donation';
		$donation = XiFactory::getInstance('statistics', 'model')
							->getSumOfRecords($filter, $select, $group, 'statistics_date');
		
		foreach($donation as $date => $record){
			if(isset($data['revenue_all'][$date]) ==false){
				$data['revenue_all'][$date] = 0;
			}
			$data['revenue_all'][$date] 	+= $record->count_1; 
		}
		*/

		
		//3. Add zero data for missing dates
		$current = unserialize(serialize($firstDate));
		while($current < $lastDate)
		{
			$arraydate = $current;
			$date = strtotime($arraydate->toMySQL(false, '%Y-%m-%d'));
			
			if(!isset($data[$date])){
				$data[$date] = array();
				$data[$date]['sales_all']   = 0;
				$data[$date]['revenue_all']	= 0;
				
				foreach($data['plans'] as $plan => $t){
					$data[$date]['sales'][$plan] = 0;
					$data[$date]['revenue'][$plan] = 0;
				}
			}
			
			$current->addExpiration('000001000000');
		}

		// 4. sort as per dates
		ksort($data);
		return json_encode($data);
	}
	
	/**
	 * 
	 * get the total number of sales with-in two dates (part of numeric statistics) 
	 * @param $firstDate
	 * @param $lastDate
	 */
	public static function getNumericStatistics(XiDate $currentFirstDate, XiDate $currentLastDate, XiDate $previousFirstDate, XiDate $previousLastDate)
	{
		$current 	= self::_calculateNumericStatistics($currentFirstDate, $currentLastDate);
		$previous 	= self::_calculateNumericStatistics($previousFirstDate, $previousLastDate);
	   	$data 		= array_merge($current, $previous);
	   	
	   	// formula for calculation of percentage is as follows :- 
		// (($current - $previous) / $previous) * 100
	   	$data[] = ($data[4] == 0) ? 0 : number_format((($data[0] - $data[4]) / abs($data[4])) * 100,0);
	   	$data[] = ($data[5] == 0) ? 0 : number_format((($data[1] - $data[5]) / abs($data[5])) * 100,0);
	   	$data[] = ($data[6] == 0) ? 0 : number_format((($data[2] - $data[6]) / abs($data[6])) * 100,0);
	   	$data[] = ($data[7] == 0) ? 0 : number_format((($data[3] - $data[7]) / abs($data[7])) * 100,0);
		
	   	return $data;
	}
	
	protected static function _calculateNumericStatistics($firstDate, $lastDate)
	{
		$filter 	= array();
		$select = array('statistics_type');
		$group = array('statistics_type');
		$filter['date(statistics_date)'] = array( array('>=' , "'".$firstDate->toMySQL()."'"),
											array('<=' , "'". $lastDate->toMySQL()."'"));
		
	   	$records = XiFactory::getInstance('statistics', 'model')->getSumOfRecords($filter, $select, $group, 'statistics_type');
		
		//Checking of Donation added because Issue has occured for those user who created statistics before payplans adding donation in revenue
		$donation = isset($records['donation']) ? $records['donation']->count_1 :0;

	   	return array(
					(isset($records['plan']) ? $records['plan']->count_1 : 0),
				   	(isset($records['plan']) ? ($records['plan']->count_2 + $donation) : 0),
				   	(isset($records['subscription']) ? $records['subscription']->count_1 : 0),
				   	(isset($records['cart']) ? $records['cart']->count_1 : 0)
				   	);
	}
	
	public static function getDiscountStatistics(XiDate $currentFirstDate, XiDate $currentLastDate, XiDate $previousFirstDate, XiDate $previousLastDate)
	{
		$result = self::getComparisonStatistics('discount', $currentFirstDate, $currentLastDate, $previousFirstDate, $previousLastDate);
		
		return array('current_usage' 			=> $result[0],
					 'current_consumption' 			=> $result[1],
					 'current_discount' 					=> $result[2],
					 'previous_usage' 					=> $result[3],
					 'previous_consumption' 		=> $result[4],
					 'previous_discount' 				=> $result[5],
					  'percentage_usage' 				=> number_format($result[6], 1),
					 'percentage_consumption' 	=> number_format($result[7], 1),
					 'percentage_discount' 			=> number_format($result[8], 1)
					);
	}
	
	public static function getSubscriptionStatistics(XiDate $currentFirstDate, XiDate $currentLastDate, XiDate $previousFirstDate, XiDate $previousLastDate)
	{
		$result = self::getComparisonStatistics('subscription', $currentFirstDate, $currentLastDate, $previousFirstDate, $previousLastDate);
		
		return array('current_active' 	=> $result[0],
					 'current_renewal' 			=> $result[1],
					 'current_upgrade' 			=> $result[2],
					 'previous_active' 			=> $result[3],
					 'previous_renewal' 		=> $result[4],
					 'previous_upgrade' 		=> $result[5],
					  'percentage_active' 		=> number_format($result[6], 1),
					 'percentage_renewal' 	=> number_format($result[7], 1),
					 'percentage_upgrade' 	=> number_format($result[8], 1)
					);
	}
	
	public static function getComparisonStatistics($statistics_type, XiDate $currentFirstDate, XiDate $currentLastDate, XiDate $previousFirstDate, XiDate $previousLastDate)
	{
		$data   = array();
		$filter 	= array();

		$filter['statistics_type'] = $statistics_type;
		$filter['date(`statistics_date`)'] = array( array('>=' , "'".$currentFirstDate->toMySQL()."'"),
											array('<=' , "'".$currentLastDate->toMySQL()."'"));
		
		$current_result 	= PayplansFactory::getInstance('statistics', 'model')->getSumOfRecords($filter);
		$current_result		= array_shift($current_result);
		$data[] = intval($current_result->count_1);
		$data[] = intval($current_result->count_2);
		$data[] = intval($current_result->count_3); 

		$filter 	= array();
		$filter['statistics_type'] = $statistics_type;
		$filter['date(`statistics_date`)'] = array( array('>=' , "'".$previousFirstDate->toMySQL()."'"),
											array('<=' , "'".$previousLastDate->toMySQL()."'"));
		
	   	$previous_result 		= PayplansFactory::getInstance('statistics', 'model')->getSumOfRecords($filter);
		$previous_result 		= array_shift($previous_result);
		$data[] = intval($previous_result->count_1);
		$data[] = intval($previous_result->count_2);
		$data[] = intval($previous_result->count_3); 
		
		// formula for calculation of percentage is as follows :- 
		// (($current - $previous) / $previous) * 100
		$data[]	 = ($data[3] == 0) ? 0 : (($data[0] - $data[3]) / abs($data[3])) * 100; 
		$data[]	 = ($data[4] == 0) ? 0 : (($data[1] - $data[4]) / abs($data[4])) * 100;
		$data[] = ($data[5] == 0) ? 0 : (($data[2] - $data[5]) / abs($data[5])) * 100;
		
		return $data;
	}
	
	public static function getRecentSalesDetails()
    {
    	// Step 1 :- collect five recent subscriptions
    	// Step 2 :- get order_ids and user_ids from subscription records
    	// Step 3 :- collect invoices on the basis of order_ids
    	// Step 4 :- collect user details according to user_ids
    	// Step 5 :- arrange data according to required details  
    	 
		// Step 1
        $subscriptions		= PayplansFactory::getInstance('subscription', 'model')->getRecentSubscriptions(10);
        
        // if there are no sales then simply do not do any calculation and retrun 
        if(empty($subscriptions)){
        	return false;
        }
        
        $data  				=    array();
       	$user_ids      		=    array();
        $order_ids   		=    array();
        $filter 		    =    array();
       
		// Step 2
        foreach($subscriptions as $subscription){
            $user_ids[]      = $subscription->user_id;
            $order_ids[]     = $subscription->order_id;
        }
       
		// Step 3
        $filter['status'] 		=  PayplansStatus::INVOICE_PAID;
        $filter['object_id'] 	= array(array('IN', '('.implode(",", $order_ids).')'));
        $invoices				= PayplansFactory::getInstance('invoice', 'model')->loadRecords($filter, array(), false, 'object_id');
        
		// Step 4
		$filter       			= array();
        $filter       			= array('user_id' => array(array('IN', '('.implode(",", $user_ids).')')));
        $users        			= PayplansFactory::getInstance('user', 'model')->loadRecords($filter, array(), false, 'user_id');
        
        // Step 5
        foreach ($subscriptions as $id => $subscription){
        	if(!isset($invoices[$subscription->order_id]) || !isset($users[$subscription->user_id]) ){
        		continue;
        	}
        	$jsonObject 			= JRegistryFormatJSON::getInstance('JSON');
        	$invoice_params        	= $jsonObject->stringToObject($invoices[$subscription->order_id]->params);
        	$data[$id]['buyer'] 	= $users[$subscription->user_id]->realname;
        	$data[$id]['title']     = $invoice_params->title;
        	$data[$id]['amount'] 	= $invoices[$subscription->order_id]->total;
        	$data[$id]['subscription_id'] = $subscription->subscription_id;
        	$data[$id]['subscription_date'] = $subscription->subscription_date;
        }
       
        return $data;
    }
    
    public static function getPaymentGatewayDetails()
    {
		//	SELECT sum(`count_1`), `details_1` 
		//	FROM `j367_payplans_statistics`
		//	WHERE `statistics_type` = 'payment' and `purpose_id_1` = 3001 
		//	group by `purpose_id_2`
		
		// Process to collect data :- 
		// #1 get all statistics which has statistics_type = payment
		// #2 sum of count_1 will give us total usage of payment gateway
		// #3 details_1 will give name of app
		// #4 group all payment stats data with payment-app id

        $data	= array();
        $query 	= new XiQuery();

        $query->select('sum(`count_1`) as used')
				->select('`details_1` as title')
				->select('purpose_id_2')
				->select('statistics_date as lastused')
				->from('`#__payplans_statistics`')
				->where("`statistics_type` = 'payment'")
				->where('`purpose_id_1` = 3001')
				->where('`count_1` > 0')
				->group('`purpose_id_2`')
        		->order('statistics_date DESC');
        
        $data 	= $query->dbLoadQuery()->loadAssocList('purpose_id_2');
        
        if(!is_array($data)){
        	return array($data);
        }
        
        return $data;
    }
    
    public static function getRecentTransactionDetails()
    {
    	// Step 1 :- collect five recent transactions
    	// Step 2 :- get user_ids from transaction records
    	// Step 3 :- collect user details according to user_ids
    	// Step 4 :- arrange data according to required details

    	$transactions = XiFactory::getInstance('transaction', 'model')->getRecentTransactions(10);
    	
    	if(!isset($transactions) || empty($transactions)){
    		return false;
    	}
    	
    	$data 	  = array();
    	$user_ids = array();
    	
    	foreach ($transactions as $transaction){
    		$user_ids[]  = $transaction->user_id;
    	}
    	
    	$filter = array('user_id' => array(array('IN', '('.implode(",", $user_ids).')')));
        $users	= XiFactory::getInstance('user', 'model')->loadRecords($filter, array(), false, 'user_id');
    	
    	foreach ($transactions as $transaction_id => $transaction){
    		$data[$transaction_id]['id'] = $transaction->transaction_id;
    		$data[$transaction_id]['buyer'] = $users[$transaction->user_id]->realname;
    		$data[$transaction_id]['amount'] = $transaction->amount;
    		$data[$transaction_id]['message'] = $transaction->message;
    		$data[$transaction_id]['date'] = $transaction->created_date;
    	}
    	
    	return $data;
    }
    
	public static function getFirstAndLastDate($duration = PAYPLANS_STATISCTICS_DURATION_WEEKLY, $previous = false, $custom_dates = array())
	{
    	$year  			= date("Y");
    	$month 			= date("m");
    	$day 			= date("d");
    	$date 			= mktime(0, 0, 0, $month, $day, $year); 
		$week 			= (int)date('W', $date) - 1; 
		$allDates 	= array();
		
		// XITODO : use call_user_func : to call the functions and send them args
		if($duration == PAYPLANS_STATISCTICS_DURATION_WEEKLY)
		{
			$allDates = self::getCurrentWeekDates($week, $year);
			
			if($previous){
				$allDates = array_merge($allDates, self::getPreviousWeekDates($week, $year));
			}
		}
		
		elseif ($duration == PAYPLANS_STATISCTICS_DURATION_DAILY)
		{
			$allDates[] = new XiDate(mktime(0,0,0,$month,$day,$year));
			$allDates[] = new XiDate(mktime(23,59,59,$month,$day,$year));
			
			if($previous){
				$allDates[] = new XiDate(mktime(0,0,0,$month,$day-1,$year));
				$allDates[] = new XiDate(mktime(23,59,59,$month,$day-1,$year));
			}
		}
		
		elseif($duration == PAYPLANS_STATISCTICS_DURATION_MONTHLY)
		{
			$allDates = self::getCurrentMonthDates($month, $year);
			
			if($previous){
				$allDates = array_merge($allDates, self::getPreviousMonthDates($month, $year));
			}
		}
		
		elseif($duration == PAYPLANS_STATISCTICS_DURATION_YEARLY)
		{
			$allDates = self::getCurrentYearDates($year);
			
			if($previous){
				$allDates = array_merge($allDates, self::getPreviousYearDates($year));
			}
		}
		
		elseif ($duration = PAYPLANS_STATISCTICS_DURATION_CUSTOM)
		{
			list($current_first, $current_last)  = $custom_dates;
			$allDates[] 	= new XiDate($current_first);
			$allDates[] 	= new XiDate($current_last);
			
			if($previous){
				$days			= (($current_last - $current_first) / (60 * 60 * 24)) + 1;
				$previous_first = strtotime("-$days day", $current_first);
				$previous_last	= strtotime("-$days day", $current_last);
				$allDates[]  	= new XiDate($previous_first);
				$allDates[] 	= new XiDate($previous_last);
			}
		}
		
		return $allDates;
	}
	
	public static function getCurrentWeekDates($week, $year)
	{
		$currentFirstDate 	= self::getWeekStartDate($week, $year);
		$currentLastDate 	= new XiDate(strtotime("+6 days 23 hours 59 minutes 59 seconds",$currentFirstDate));
		$currentFirstDate 	= new XiDate($currentFirstDate);
		return array($currentFirstDate, $currentLastDate);
	}
	
	public static function getCurrentMonthDates($month, $year)
	{
		$currentFirstDate  = new XiDate(mktime(0,0,0,$month,1,$year));
		$currentLastDate   = new XiDate(mktime(23,59,59,$month+1,0,$year));
		return array($currentFirstDate, $currentLastDate);
	}
	
	public static function getCurrentYearDates($year)
	{
		$currentFirstDate  = new XiDate(mktime(0,0,0,1,1,$year));  //January, 01 2011
		$currentLastDate   = new XiDate(mktime(23,59,59,1,0,$year+1)); //December, 31 2011
		return array($currentFirstDate, $currentLastDate);
	}
	
	public static function getPreviousWeekDates($week, $year)
	{
			$previousFirstDate 	= self::getWeekStartDate($week-1, $year); 
			$previousLastDate 	= new XiDate(strtotime("+6 days 23 hours 59 minutes 59 seconds", $previousFirstDate)); 
			$previousFirstDate 	= new XiDate($previousFirstDate);
			return array($previousFirstDate, $previousLastDate);
	}
	
	public static function getPreviousMonthDates($month, $year)
	{
		$previousFirstDate  = new XiDate(mktime(0,0,0,$month-1,1,$year));
		$previousLastDate   = new XiDate(mktime(23,59,59,$month,0,$year));
		return array($previousFirstDate, $previousLastDate);
	}
	
	public static function getPreviousYearDates($year)
	{
		$previousFirstDate  = new XiDate(mktime(0,0,0,1,1,$year-1)); //January, 01 2010
		$previousLastDate   = new XiDate(mktime(23,59,59,1,0,$year)); //December, 31 2010
		return array($previousFirstDate, $previousLastDate);
	}
	
	public static function getWeekStartDate($wk_num, $yr, $first = 1) 
	{ 
		//indexing of weeks, start from 0 and end with 51.
	    $wk_ts  = strtotime('+' . $wk_num . ' weeks', strtotime($yr . '0101')); 
	    $mon_ts = strtotime('-' . date('w', $wk_ts) + $first . ' days', $wk_ts); 
	    return  $mon_ts; 
	}
	
	public static function calculateStatistics()
	{
		$plan_stats = new PayplansStatisticsPlan();
		$dates_to_process =  $plan_stats->getDates();
		if(!empty($dates_to_process)){
			$plan_stats->setDetails(array(), $dates_to_process);
		}
		
		$discount_stats = new PayplansStatisticsDiscount();
		$dates_to_process = $discount_stats->getDates();
		if(!empty($dates_to_process)){
			$discount_stats->setDetails(array(), $dates_to_process);
		}
		
		
		$cart_stats = new PayplansStatisticsCart();
		$dates_to_process = $cart_stats->getDates();
		if(!empty($dates_to_process)){
			$cart_stats->setDetails(array(), $dates_to_process);
		}
		
		$subs_stats = new PayplansStatisticsSubscription();
		$dates_to_process = $subs_stats->getDates();
		if(!empty($dates_to_process)){
			$subs_stats->setDetails(array(), $dates_to_process);
		}

		$donation = new PayplansStatisticsDonation();
		$dates_to_process = $donation->getDates();
		if(!empty($dates_to_process)){
			$donation = $donation->setDetails(array(), $dates_to_process);
		}

		$payment = new PayplansStatisticsPayment();
		$dates_to_process = $payment->getDates();
		if(!empty($dates_to_process)){
			$donation = $payment->setDetails(array(), $dates_to_process);
		}
	}
	
	//For Setting up limit of Rebuilding.
	//TODO: Show option for choosing frequency of rebuilding on dashboard
	public static function getRebuildLimit()
	{
		static $limit;
		if(!$limit) {
			// TODO :: Dont use hard code here
			$limit = JRequest::getVar('limit',10);
		} 
		return $limit;
	}

	//Returns total number of days to process
	public static function getDaysToProcess()
	{
		$stats		= new PayplansStatisticsSubscription();
		$first_date = new XiDate($stats->getOldestDate());
		$today_date = new XiDate('now');
		
		//Calculation for number of days		
		$days = abs((($today_date->toUnix()) - ($first_date->toUnix())) / 86400); // 86400 seconds in one day
		return intval($days);		
	}

}
