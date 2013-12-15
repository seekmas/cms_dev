<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansModelPlan extends XiModel
{
	public $filterMatchOpeartor = array(
										'title' 	=> array('LIKE'),
										'published' => array('='),
										'visible' 	=> array('=')
										);
	
	// XITODO : Apply validation when it is applied all over
	function validate(&$data, $pk=null,array $filter = array(),array $ignore = array())
	{
		return true;
	}
	
	public function delete($pk=null)
	{
		if(!parent::delete($pk))
		{
			$db = JFactory::getDBO();
			XiError::raiseError(500, $db->getErrorMsg());
		}
		// delete plans from planapp table
	       XiFactory::getInstance('planapp', 'model')
						 	 ->deleteMany(array('plan_id' => $pk));

        // delete plan from plangroup table						 	 
		return XiFactory::getInstance('plangroup', 'model')
						 	 ->deleteMany(array('plan_id' => $pk));
	}
	
	public function getUngrouppedPlans($queryFilters = array())
	{
		$db = XiFactory::getDBO();
		
		$sql = " SELECT plans.* "
				." FROM ".$db->quoteName('#__payplans_plan')." as plans "
				." WHERE plans.`plan_id` NOT IN ( "
					." SELECT DISTINCT ".$db->quoteName('plan_id')." "
					." FROM ".$db->quoteName('#__payplans_plangroup')." )";

		foreach($queryFilters as $key=>$value){
			$sql .= " AND ".$db->quoteName($key) ." = '".$value."' ";
		}
		
		$sql .= " ORDER BY plans.`ordering` ASC";
		
		$db->setQuery($sql);
		
		return $db->loadObjectList('plan_id');
	}
	
	public function getGrouppedPlans($queryFilters, $groupId)
	{
		$db = XiFactory::getDBO();
		
		$sql = " SELECT plans.* "
				." FROM ".$db->quoteName('#__payplans_plan')." as plans "
				." WHERE plans.`plan_id` IN ( "
					." SELECT DISTINCT ".$db->quoteName('plan_id')." "
					." FROM ".$db->quoteName('#__payplans_plangroup')." "
					." WHERE ".$db->quoteName('group_id')." = ". $groupId ." )";

		foreach($queryFilters as $key=>$value){
			$sql .= " AND ".$db->quoteName($key) ." = '".$value."' ";
		}
		
		$sql .= " ORDER BY plans.`ordering` ASC";
		
		$db->setQuery($sql);
		
		return $db->loadObjectList('plan_id');
	}
}

class PayplansModelformPlan extends XiModelform {}
