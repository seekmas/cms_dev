<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHelperSearch
{
	static public function addSearchFunction($func=null, $class = 'PayplansHelperSearch')
	{
		static $funcs = null;
		
		
		if($func !==null){
			$funcs[md5($class.$func)] = array($class,$func);
		}
		
		return $funcs;
	}
	
	static public function doSearch($text)
	{
		//add all functions required
		self::addSearchFunction('searchOPS');
		self::addSearchFunction('searchUsers');
		self::addSearchFunction('searchPG');
		
		// do search
		$results = array();
		foreach(self::addSearchFunction() as $func){
			$results = array_merge($results, call_user_func($func,$text));
		}
		
		return $results;
	}
	
	
	static public function searchOPS($text)
	{
		$results = array();
		
		$id = $text;
		if(!is_numeric($text)){
			$id =  XiHelperUtils::getIdFromKey($text);
		}
		
		// if its blank
		if($id == 0){
			return array();
		}
		
		// if order exist add it to results
		$order = PayplansOrder::getInstance($id);
		if($order !== false){
			$config = array('prefix'=>true, 'link'=>true, 'admin'=>true, 'attr'=>'');
			$results[] = PayplansHelperFormat::order($order, $config);
		}
		
		$payment = PayplansPayment::getInstance($id);
		if($payment !== false){
			$config = array('prefix'=>true, 'link'=>true, 'admin'=>true, 'attr'=>'');
			$results[] = PayplansHelperFormat::payment($payment, $config);
		}
		
		$subscription = PayplansSubscription::getInstance($id);
		if($subscription !== false){
			$config = array('prefix'=>true, 'link'=>true, 'admin'=>true, 'attr'=>'');
			$results[] = PayplansHelperFormat::subscription($subscription, $config);
		}
		
		$invoice   = PayplansInvoice::getInstance($id);
		if($invoice !== false){
			$config    = array('prefix'=>true, 'link'=>true, 'admin'=>true, 'attr'=>'');
			$results[] = PayplansHelperFormat::invoice($invoice, $config);
		}
		
		return $results;
	}
	
	static public function searchUsers($text)
	{
		// no need to search if text is empty
		if(empty($text)){
			return true;
		}
		
		$config = array('prefix'=>true, 'link'=>true, 'admin'=>true, 'attr'=>'');
		
		$user  = array();
		$model = XiFactory::getInstance('user', 'model');
		
		$query = $model->getQuery();
		$tmpQuery = $query->getClone();
		$tmpQuery->clear('where')
				 ->where("`tbl`.`id` LIKE '%".$text."%' ","OR")
				 ->where("`tbl`.`username` LIKE '%".$text."%' ","OR")
				 ->where("`tbl`.`name` LIKE '%".$text."%' ","OR")
				 ->where("`tbl`.`email` = '$text'", "OR");
				 
		$users = $tmpQuery->dbLoadQuery()->loadObjectList($model->getTable()->getKeyName());
		
		$result= array();
		foreach($users as $user){
			$result[] = PayplansHelperFormat::user(PayplansUser::getInstance($user->user_id, null, $user), $config);
		}
		
		return $result;
	}
	
	/**
	 * Serach plans and grooup from here
	 * @param numeric $text
	 */
	static public function searchPG($text)
	{
		$results = array();
		
		$id = $text;
		$config = array('prefix'=>true, 'link'=>true, 'admin'=>true, 'attr'=>'');
			
		if(!is_numeric($text) && !empty($text)){
			$model = XiFactory::getInstance('plan', 'model');
			
			$query = $model->getQuery();
			$tmpQuery = unserialize(serialize($query));
			$tmpQuery->clear('where')
					 ->where("`tbl`.`title` LIKE '%".$text."%' ");
					 
			$plans = $tmpQuery->dbLoadQuery()->loadObjectList($model->getTable()->getKeyName());
			
			foreach($plans as $plan){
				$results[] = PayplansHelperFormat::plan(PayplansPlan::getInstance($plan->plan_id, null, $plan), $config);
			}
			
			$model = XiFactory::getInstance('group', 'model');
			
			$query = $model->getQuery();
			$tmpQuery = $query->getClone();
			$tmpQuery->clear('where')
					 ->where("`tbl`.`title` LIKE '%".$text."%' ");
					 
			$groups = $tmpQuery->dbLoadQuery()->loadObjectList($model->getTable()->getKeyName());
			
			foreach($groups as $group){
				$results[] = PayplansHelperFormat::group(PayplansGroup::getInstance($group->group_id, null, $group), $config);
			}
			
			return $results;
		}
		
		// if its blank
		if($id == 0){
			return array();
		}
		
		// if plan exist add it to results
		$plan = PayplansPlan::getInstance($id);
		if($plan !== false){
			$results[] = PayplansHelperFormat::plan($plan, $config);
		}
		
		// if plan exist add it to results
		$group = PayplansGroup::getInstance($id);
		if($group !== false){
			$results[] = PayplansHelperFormat::group($group, $config);
		}		
		
		return $results;
	}
	
}