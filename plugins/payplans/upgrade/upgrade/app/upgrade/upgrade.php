<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
* 
* Highly Inspired from AEC micro integration for docman
*/
if(defined('_JEXEC')===false) die();

class PayplansAppUpgrade extends PayplansApp
{
	protected $_location	= __FILE__;	
	const ALL_PLAN   =  'ALL_PLAN';
	
	public function onPayplansUpgradeTo()
	{
		// only published and visible plans
		$plans = PayplansHelperPlan::getPlans(array('published' => 1));
		
		$upgradeTo = $this->getAppParam('upgrade_to', array());
		$upgradeTo = is_array($upgradeTo) ? $upgradeTo : array($upgradeTo);
		
		$return  = array();
		foreach($upgradeTo as $pid){
			if(isset($plans[$pid])){
				$return[$pid] = $plans[$pid];
			}
		}
		
		return $return;
	}
	
	//render Widget
	public function renderWidgetHtml(XiWidget $widget=null)
	{   
		//get user id       
        $userid = XiFactory::getUser()->id;
		$user 	= PayplansUser::getInstance($userid);
		$userPlans      = $user->getPlans();
		if(!$userid || empty($userPlans)){
			return false;
		}
		$appPlans       = array();
		$applicableApps = PayplansHelperApp::getAvailableApps('upgrade',$user);
		
		foreach ($applicableApps as $app)
		{
			if($app->getParam('applyAll'))
			{
	 			$appPlans = array(self::ALL_PLAN);
	 			break;
			 }
			else 
				 $appPlans = array_merge($appPlans, $app->getPlans()) ;
		}
		if(!in_array(self::ALL_PLAN, $appPlans))
		{
			if(count(array_intersect($userPlans, $appPlans))<=0){
				return false;
			}
		}
		// widget can append some styling 
		if(isset($widget) && $widget != null){
			$widget->setOption('style_class', 'payplans-app-upgrade-widget');
		}
		
		// create widget object
        $data = $this->_render('widgethtml');
        return $data;
	}
}

class PayplansAppUpgradeFormatter extends PayplansAppFormatter
{
	// get rules to apply
	function getVarFormatter()
	{
		$rules = array( '_appplans'       => array('formatter'=> 'PayplansAppFormatter',
										       'function' => 'getAppPlans'),
						'app_params'      => array('formatter'=> 'PayplansAppUpgradeFormatter',
										       	   'function' => 'getFormattedParams')
						);
		return $rules;
	}
	
	function getFormattedParams($key, $value, $data)
	{
		$params  = PayplansHelperParam::iniToArray($value);
		if(is_array($params['upgrade_to']))
		{
			foreach ($params['upgrade_to'] as $param){
				$planName = PayplansHelperPlan::getName($param);
				$plans[] = PayplansHtml::link(XiRoute::_("index.php?option=com_payplans&view=plan&task=edit&id=".$param, false), $param.'('.$planName.')');
			}
			$params['upgrade_to']= $plans;
		}
		else{
			$planName = PayplansHelperPlan::getName($params['upgrade_to']);
			$params['upgrade_to'] = PayplansHtml::link(XiRoute::_("index.php?option=com_payplans&view=plan&task=edit&id=".$params['upgrade_to'], false), $params['upgrade_to'].'('.$planName.')');
	
		}
		
		$value   = PayplansHelperParam::arrayToIni($params);
	}
}
