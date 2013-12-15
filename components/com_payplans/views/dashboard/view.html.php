<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplanssiteViewDashboard extends XiView
{
	function _displayList()
	{
		return true;
	}

	function display($tpl=null)
	{
		return true;
	}

	function _basicFormSetup()
	{
		// which template to load in main section
		$this->set('dashboard_main_template', $this->getTpl());
		$this->set('dashboard_messages', XiFactory::getDashboardMessage());
		
		$this->setTpl(null);
		
		return true;
	}
	
	public function noaccess()
	{
		return true;
	}
	
	public function frontview()
	{
		$userId = XiFactory::getUser()->id ;
		$user = PayplansUser::getInstance($userId);
		
		$allowedStatus = XiFactory::getConfig()->subscription_status;
		$allStatus	= array(PayplansStatus::NONE, PayplansStatus::SUBSCRIPTION_ACTIVE, PayplansStatus::SUBSCRIPTION_HOLD, PayplansStatus::SUBSCRIPTION_EXPIRED);
		
		// if there is not set any subscription status at configuartion then collect records according to all subscription status
		if(!isset($allowedStatus) || empty($allowedStatus)){
			$allowedStatus = $allStatus;
		}

		$allowedStatus =  is_array($allowedStatus) ? $allowedStatus : array($allowedStatus);
		$filter       = array('user_id' => $userId , 'status' =>array(array('IN', '('.implode(",", $allowedStatus).')')));
		$subscriptionRecords = XiFactory::getInstance('subscription','model')->loadRecords($filter);
		$this->assign('subscription_records', $subscriptionRecords);
		$this->assign('user', $user);

	    return true;
	}	
}
