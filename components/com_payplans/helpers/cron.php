<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHelperCron
{
	public static function getURL()
	{
		// Give public URL
		return JURI::root().'index.php?option=com_payplans&view=cron&task=trigger';
	}

	static public function genereateFileTreeCache()
	{
		$dirs = array();
		$dirs[] = JPATH_ROOT.DS.'components'.DS.'com_payplans'.DS;
		$dirs[] = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_payplans'.DS;
		$dirs[] = JPATH_PLUGINS.DS.'payplans'.DS;
		$dirs[] = JPATH_PLUGINS.DS.'payplansregistration'.DS;
		
		XiFileTree::generateFileTree($dirs);
	}
	/*
	 * Expire all
	 */

   //IMP:-IGNORE THIS BUG:-IF SUBSCRIPTION GET EXPIRE,THEN STILL IT WILL WAIT FOR GRACE PERIOD TO EXPIRE.
	static function doSubscriptionExpiry(XiDate $time=null)
	{
		//get all records which need to be marked expired now
		$subscriptions = XiFactory::getInstance('subscription','model')
							->getActiveSubscriptionsWhichAreExpried($time);

		foreach($subscriptions as $sub_id => $subscription){
			$subscription = PayplansSubscription::getInstance($sub_id, null, $subscription);
			
			// subscription is fixed
			if($subscription->isRecurring() == false){
				$subscription->setStatus(PayplansStatus::SUBSCRIPTION_EXPIRED)->save();
				continue;
			}
			
			// if order is already cancelled or expired
		   if(in_array($subscription->getOrder(PAYPLANS_INSTANCE_REQUIRE)->getStatus(), array(PayplansStatus::ORDER_CANCEL, PayplansStatus::ORDER_EXPIRED)))
			 {
				$subscription->setStatus(PayplansStatus::SUBSCRIPTION_EXPIRED)->save();
				continue;
			 } 
			
			// for recurring subscription, ask for next payment 
			$now = new XiDate();

			if(XiFactory::getUser($subscription->getBuyer())->id){
				$args = array(&$subscription);
				PayplansHelperEvent::trigger('onPayplansNewPaymentRequest', $args);
			}
						
			// check the new expiry date of subscription, if payment was successfull, it must have updated the expiry time
			$newExpDate = $subscription->load($subscription->getId())->getExpirationDate()->getClone(); 
			if(XiFactory::getConfig()->expert_wait_for_payment != '000000000000'){
			     $newExpDate->addExpiration(XiFactory::getConfig()->expert_wait_for_payment);
			 }
			
			// if grace period is finished, expire it
			if($newExpDate->toUnix() < $now->toUnix()){
	            $subscription->setStatus(PayplansStatus::SUBSCRIPTION_EXPIRED)->save();
	            continue;
			}
	   }
		return true;
	}
	
	public static function checkRequired($config=null, $now = null, $defaultExecutionTime = 60)
	{
		if($config === null){
			$config = XiFactory::getConfig();
		}
		
		$frequency 			= (isset($config->microsubscription) && $config->microsubscription ) ? $config->cronFrequency/PAYPLANS_CONFIG_CRONFREQUENCY_DIVIDER : $config->cronFrequency;
		$accessTime 		= $config->cronAcessTime;
		$currentAccessTime 	= 0;
		if(isset($config->currentCronAcessTime) && $config->currentCronAcessTime != 0){
			$currentAccessTime 	= $config->currentCronAcessTime;
		}
		
		if(empty($currentAccessTime)) {
			return true;
		}
		
		if($now === null){
			$now = new XiDate();
			$now = $now->toUnix();
		}	

		// if diff of $accessTime and $currentAccessTime is greater than  $defaultExecutionTime than probaly there is cron failure
		if(($currentAccessTime - $accessTime) > $defaultExecutionTime){
			return true;
		}
		
		// if diff of $now and $currecAccessTime is greater than $frequency then return true
		if(($now - $currentAccessTime) > $frequency){
			return true;
		}	
		
		return false;	
	}
	
	public static function doAutoDeleteDummyOrders()
	{
		$periodToSubtract = XiFactory::getConfig()->expert_auto_delete;
		
		if($periodToSubtract == "NEVER"){
			return ;
		}
		
		$date = new XiDate();
		$modifiedDate = $date->subtractExpiration($periodToSubtract);
		
		//XITODO : only delete n records at a time (e.g. 5)
		// PayplansStatus::NONE is added for checking subscription's status also
		$items = XiFactory::getInstance('order','model')
										->getDummyOrders($modifiedDate, array(PayplansStatus::NONE, PayplansStatus::ORDER_CONFIRMED),  PayplansStatus::NONE);


		foreach($items as $id => $record){
			$items = XiLib::getInstance('order', $id, null, $record)
						->delete();
		}
		
		return true;
	}

	public static function deleteCronLogs()
	{
		$query = new XiQuery();
		$query->select('*')
				->from('#__payplans_log')
				->where('class = "Payplans_Cron"')
				->order('created_date DESC');
				
		$cron_logs  = $query->dbLoadQuery()->loadObjectList('log_id');
		$count_logs =count($cron_logs);
		
		if($count_logs>PAYPLANS_CRON_LOGS_COUNT)
		{
			$logIds		= array_slice($cron_logs,PAYPLANS_CRON_LOGS_COUNT,NULL,true);
			$logIds 	= array_keys($logIds);
			$condition 	= '('.implode(",",$logIds).')';

			// delete log records
			XiFactory::getInstance('log', 'model')
						->deleteMany(array('log_id'=>$condition),'','IN');
		}
	}
}