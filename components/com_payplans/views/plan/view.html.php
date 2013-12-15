<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplanssiteViewPlan extends XiView
{
	public function subscribe()
	{
		$returnUrl='';
		$itemId = $this->getModel()->getId();

		$groupId = JRequest::getVar('group_id', 0);
		$model = $this->getModel();
		
		$queryFilters = array('published'=>1, 'visible'=>1 );
		
		$param=XiFactory::getConfig();
		
		// Plan Count for rows
		$this->assign('row_plans', explode(',', $param->row_plan_counter));
		$this->assign('vertical_layout', ($param->layout=='vertical'));
		
		// groups is disable then use it
		if(isset(XiFactory::getConfig()->useGroupsForPlan)==false
			|| XiFactory::getConfig()->useGroupsForPlan==false){
				$this->assign('groups', array());
				$this->assign('plans',  $model->loadRecords($queryFilters));
				return true;
		}
			
		// if both are not set then need to show all groups and ungrouped plans
		if(!$itemId && $groupId <= 0){			
			$groupFilter = array_merge($queryFilters, array('parent' => 0));
			$groups 		 = XiFactory::getInstance('group', 'model')
										->loadRecords($groupFilter);										
			
			$plans = 	$model->getUngrouppedPlans($queryFilters);				
			
			$this->assign('plans',  $plans);
			$this->assign('groups', $groups);
			return true;
		}
		
		// if group_id is set
		if($groupId > 0){
			$plans 	 = $model->getGrouppedPlans($queryFilters, $groupId);
		    // filter groups to get only those child groups which are published and visible
			$groupFilter = array_merge($queryFilters, array('parent' => $groupId));				
			$groups  = XiFactory::getInstance('group', 'model')
								->loadRecords($groupFilter);										
			
			$returnUrl = XiRoute::_('index.php?option=com_payplans&view=plan&task=subscribe');
			$this->assign('groups', $groups);
			$this->assign('plans',  $plans);
			$this->assign('link',  $returnUrl);
			return true;
		}
		
		$this->assign('groups', array());
		$this->assign('plans',  array());

		return true;
	}

    public function login()
    {
		$planId = $this->getModel()->getState('id');
    	$this->assign('plan', PayplansPlan::getInstance($planId));
		return true;
    }
    
    public function trigger()
    {
    	$this->setTpl('partial_position');
    	return true;
    }
}