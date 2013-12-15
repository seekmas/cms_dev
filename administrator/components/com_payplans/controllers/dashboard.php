<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();

class PayplansadminControllerDashboard extends XiController
{
	// No model exist
	function getModel($name = '', $prefix = '', $config = array())
	{
		return null;
	}
	
	public function modsearch($searchText=null)
	{
		// do search
		$searchText = JRequest::getVar('searchText', $searchText);
		$results = PayplansHelperSearch::doSearch($searchText);
		
		// setup view
		$this->getView()->assign('results', $results);
		$this->setTemplate('modsearch');
		return true;
	}
	
	public function migrate($pluginKey=null, $action='Pre', $step=0)
	{
		// do start migration
		$pluginKey 	= JRequest::getVar('plugin', $pluginKey);
		$action		= JRequest::getVar('action', $action);
		$step		= JRequest::getVar('step', $step);
		
		//IMP : need to load plugins
		XiHelperPlugin::loadPlugins('payplansmigration');
		
		$args[] 	=  $pluginKey;
		$results  	=  PayplansHelperEvent::trigger('onPayplans'.$action.'Migration', $args);
		
		// setup display
		$this->getView()->assign('results', 	$results);
		$this->getView()->assign('action', 		$action);
		$this->getView()->assign('pluginKey', 	$pluginKey);
		
		$this->setTemplate('migrate');
		return true;
	}
	
	
	public function statisticsCharts()
	{
		return true;
	}
	
	public function markRead()
	{
		$logId		= JRequest::getVar("logId", false);
		$isAjax		= JRequest::getVar("isAjax", false);
		if (isset($logId))
		{
			PayplansFactory::getInstance('log','model')->markRead($logId);
		}
		
		if($isAjax)
		{
			$ajaxResponse = PayplansFactory::getAjaxResponse();
			$ajaxResponse->sendResponse();
		}
		return true;
	}
	
	public function calculateNewStatistics()
	{
		PayplansHelperStatistics::calculateStatistics();
		return true;
	}
	
	public function customStatistics()
	{
		return true;
	}
	
	public function rebuildstats()
	{
		if('do' == JRequest::getVar('action',false) ) {
			if(JRequest::getVar('start') == 0){
				PayplansFactory::getInstance('statistics', 'model')->truncateStatistics();
			}
			PayplansHelperStatistics::calculateStatistics();
		}
		$this->setTemplate('rebuildstats');
		return true;
	}
	
	public function removeSetupCheckListMessage()
	{
		$model = PayplansFactory::getInstance('config','model');
		$model->save(array('expert_show_setup_checklist'=>0));
						
		return true;
	}
}