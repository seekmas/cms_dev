<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansadminViewDashboard extends XiView
{
	function _displayList()
	{
		return true;
	}

	function display($tpl=null)
	{
		$error_logs = PayplansFactory::getInstance('log', 'model')->getLogsOnDashboard(XiLogger::LEVEL_ERROR, 10);
		$this->assign('error_logs', $error_logs);
		$this->assign('clean_checklist', $this->isSetupCheckListClean());
		$this->_calculateSatisticsData();
		return true;
	}

	function _basicFormSetup()
	{
		return true;
	}

	protected function _adminGridToolbar()
	{
		XiHelperToolbar::custom('pprefresh', 'pprefresh.png', 'pprefresh.png', 'COM_PAYPLANS_REFRESH_TOOLBAR', true );
		XiHelperToolbar::openPopup('rebuildstats', 'cog', 'rebuildstats.png', 'COM_PAYPLANS_PPRECREATE_TOOLBAR', true );
		XiHelperToolbar::divider();
		XiHelperToolbar::openPopup('searchRecords', 'search', 'search.png', 'COM_PAYPLANS_TOOLBAR_SEARCH', true );
	}

	protected function _adminToolbarTitle()
	{
		// Set the titlebar text
		XiHelperToolbar::title("PayPlans", "favicon.png");
	}
	
	public function modsearch()
	{
		return true;
	}

	//Called when clicks on Rebuildstats Buton
	public function rebuildstats()
	{
		$action = JRequest::getVar('action','start').'RebuildStats';
		//Calls Apropriate Action+Task(RebuildStats)
		return $this->$action();
	}

	//for Starting Rebuild Process
	public function startRebuildStats() 
	{
		//gets array of dates to process
		$days_to_process = PayplansHelperStatistics::getDaysToProcess();
		
		//sets $days_to_process for further calculation
		$session	= XiFactory::getSession();
		$session->set('rebuild_total', $days_to_process);
		$this->assign('rebuild_total',$days_to_process);

		//Catchs action of pop-up to rebuild statistics
		$this->_setAjaxWinTitle(XiText::_('COM_PAYPLANS_DASHBOARD_REBUILD_START_DIALOG_TITLE'));

		$onClick = "payplans.admin.dashboard.rebuildstats.start()";
		$onClickText = XiText::_('COM_PAYPLANS_AJAX_START_BUTTON');
		$this->_addAjaxWinAction($onClickText, $onClick,null ,'btn btn-primary' );
		
		//close dialog box when clicked on cancel
		$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_AJAX_CANCEL_BUTTON'),'xi.ui.dialog.close();');
		$this->_setAjaxWinAction();
		return true;
	}
	
	//Continues Process of Rebuilding By Ajax Requests
	public function doRebuildStats() 
	{
		$ajax = XiFactory::getAjaxResponse();
		//Gets Value of Start From Session for calculating Next records
		$session	= XiFactory::getSession();
		$total 		= $session->get('rebuild_total');
		
		//Start == 0 For very first request by default
		$start = JRequest::getVar('start', 0);
		
		//Gets no. of days to rebuild
		$limit = PayplansHelperStatistics::getRebuildLimit();
		
		//$exeCount used for calculating processed days
		$exeCount = $start + $limit;
		
		//When Rebuilding Completes
		if($exeCount >= $total) {
			$this->assign('exeCount', $exeCount);
			$this->assign('rebuild_total',$total);
			//Calls completeRebuilStats through javascript
			$ajax->addScriptCall('payplans.admin.dashboard.rebuildstats.complete');
			return true;
		}

		//For increasing width of progress bar.
		$progress = ($exeCount/$total)*100;
		//Assigned for use in template
		$this->assign('progress', $progress);
		$this->assign('exeCount', $exeCount);
		$this->assign('rebuild_total',$total);
		
		//For Calculating Next records
		$ajax->addScriptCall('payplans.admin.dashboard.rebuildstats.update',$exeCount);
		return true;
	}
	
	//When Rebuilding Process Completed
	public function completeRebuildStats() 
	{
		$ajax = XiFactory::getAjaxResponse();
		//For Closing Complete dialog box and Dashboard page.
		$ajax->addScriptCall('payplans.admin.dashboard.rebuildstats.close');
		return true;
	}
	
	public function closeRebuildStats()
	{
		//Refreshes Dashboard Page aftercompleting rebuilding process
		PayplansFactory::redirect(XiRoute::_('index.php?option=com_payplans&view=dashboard'),true);
	}
	
	public function migrate()
	{
		$func = '_'.$this->get('action').'Migration';
		return $this->$func();
	}
	
	protected function _preMigration()
	{
		$this->_setAjaxWinTitle(XiText::_('PLG_PAYPLANS_'.JString::strtoupper($this->get('pluginKey')).'_MIGRATION_MESSAGE' ));

		$componentExist=false;
		foreach($this->get('results') as $ret){
			if($ret !== false){
				$componentExist=true;
				break;
			}	
		}
		
		if($componentExist === false){
			$this->assign('results', array(XiText::_('COM_PAYPLANS_MIGRATION_COMPONENT_NOT_AVAILABLE')));
			return true;
		}
		
		$onClick = "xi.dashboard.doMigration('{$this->get('pluginKey')}');";
		$onClickText = XiText::_('PLG_PAYPLANS_'.JString::strtoupper($this->get('pluginKey')).'_MIGRATION_DO_BUTTON');
		$this->_addAjaxWinAction($onClickText, $onClick );
		$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_AJAX_CANCEL_BUTTON'),'xi.ui.dialog.close();');
		$this->_setAjaxWinAction();
		return true;
	}
	
	protected function _doMigration()
	{
		$this->_setAjaxWinTitle(XiText::_('PLG_PAYPLANS_'.JString::strtoupper($this->get('pluginKey')).'_MIGRATION_PROGRESS'));
		XiFactory::getAjaxResponse()->addScriptCall('xi.dashboard.updateMigration', $this->get('pluginKey'));
		return true;
	}
	
	protected function _postMigration()
	{
		$this->_setAjaxWinTitle(XiText::_('PLG_PAYPLANS_'.JString::strtoupper($this->get('pluginKey')).'_MIGRATION_COMPLETE'));
		$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_AJAX_DONE_BUTTON'),'xi.ui.dialog.close();');
		$this->_setAjaxWinAction();
		return true;
	}
	
/* Removed
 * 	
	public function statisticsCharts()
	{
		$duration = JRequest::getVar('duration', 101);

		// load template
		$this->assign('duration', $duration);
		$this->_calculateSatisticsData($duration);
		$html = $this->loadTemplate('charts');

		$response	= XiFactory::getAjaxResponse();
		$response->addScriptCall('payplans.jQuery(\'#pp-dashboard-statisctics\').html', $html);
		$response->sendResponse();		
	}
*/	
	public function calculateNewStatistics()
	{
		$this->_calculateSatisticsData();
		$html = $this->loadTemplate('charts');

		$response	= XiFactory::getAjaxResponse();
		$response->addScriptCall('payplans.jQuery(\'#pp-dashboard-statistics\').html', $html);
		$response->sendResponse();
	}
	
	public function customStatistics()
	{
		$custom_dates 	= array();
		$first = JRequest::getVar('statisticsFirstDate');
		$last  = JRequest::getVar('statisticsLastDate');
		$custom_dates[] = strtotime($first);
		$custom_dates[] = strtotime($last);
		$this->_calculateSatisticsData(PAYPLANS_STATISCTICS_DURATION_CUSTOM, $custom_dates);

		$error_logs = PayplansFactory::getInstance('log', 'model')->getLogsOnDashboard(XiLogger::LEVEL_ERROR, 10);
		$this->assign('error_logs', $error_logs);
		$this->assign('clean_checklist', $this->isSetupCheckListClean());

		return true;
	}
	
	protected function _calculateSatisticsData($duration = PAYPLANS_STATISCTICS_DURATION_MONTHLY, $custom_dates=array())
	{
		$allDates 				= PayplansHelperStatistics::getFirstAndLastDate($duration, true, $custom_dates);
		$currentFirstDate  		= $allDates[0];
		$currentLastDate   		= $allDates[1];
		$previousFirstDate		= $allDates[2];
		$previousLastDate		= $allDates[3];
		$numeric_stats 			= PayplansHelperStatistics::getNumericStatistics($currentFirstDate, $currentLastDate, $previousFirstDate, $previousLastDate);
		$discount_stats 		= PayplansHelperStatistics::getDiscountStatistics($currentFirstDate, $currentLastDate, $previousFirstDate, $previousLastDate);
		$records_per_day 		= PayplansHelperStatistics::getPlanDataWithinDates($currentFirstDate, $currentLastDate);
		$subscription_stats 	= PayplansHelperStatistics::getSubscriptionStatistics($currentFirstDate, $currentLastDate, $previousFirstDate, $previousLastDate);
		
		$recent_sales			= PayplansHelperStatistics::getRecentSalesDetails();
		$recent_transactions	= PayplansHelperStatistics::getRecentTransactionDetails();
		$gateway_info			= PayplansHelperStatistics::getPaymentGatewayDetails();
		
		$this->assign('duration', $duration);
		$this->assign('currentFirstDate', $currentFirstDate);
		$this->assign('currentLastDate', $currentLastDate);
		$this->assign('previousFirstDate', $previousFirstDate);
		$this->assign('previousLastDate', $previousLastDate);
		
		// numeric chart data
		$this->assign('currentSales', $numeric_stats[0]);
		$this->assign('previousSales', $numeric_stats[4]);
		$this->assign('percentageSales', $numeric_stats[8]);

		$this->assign('currentRevenue', $numeric_stats[1]);
		$this->assign('previousRevenue', $numeric_stats[5]);
		$this->assign('percentageRevenue', $numeric_stats[9]);

		$this->assign('currentActive', $numeric_stats[2]);
		$this->assign('previousActive', $numeric_stats[6]);
		$this->assign('percentageActive', $numeric_stats[10]);

		$this->assign('currentUnutilized', $numeric_stats[3]);
		$this->assign('previousUnutilized', $numeric_stats[7]);
		$this->assign('percentageUnutilized', $numeric_stats[11]);
		
		//discount chart details
		$this->assign('currentDiscount', $discount_stats['current_discount']);
		$this->assign('previousDiscount', $discount_stats['previous_discount']);
		$this->assign('percentageDiscount', $discount_stats['percentage_discount']);
		$this->assign('currentUsage', $discount_stats['current_usage']);
		$this->assign('previousUsage', $discount_stats['previous_usage']);
		$this->assign('percentageUsage', $discount_stats['percentage_usage']);
		$this->assign('currentConsumption', $discount_stats['current_consumption']);
		$this->assign('previousConsumption', $discount_stats['previous_consumption']);
		$this->assign('percentageConsumption', $discount_stats['percentage_consumption']);
		
		//renewal chart details
		$this->assign('currentRenewal', $subscription_stats['current_renewal']);
		$this->assign('previousRenewal', $subscription_stats['previous_renewal']);
		$this->assign('percentageRenewal', $subscription_stats['percentage_renewal']);
		
		//upgrade chart details
		$this->assign('currentUpgrade', $subscription_stats['current_upgrade']);
		$this->assign('previousUpgrade', $subscription_stats['previous_upgrade']);
		$this->assign('percentageUpgrade', $subscription_stats['percentage_upgrade']);
		
		// line chart specific data
		$this->assign('recordsPerDay', $records_per_day);
		
		// Recent Details data
		$this->assign('recentSales', $recent_sales);
		$this->assign('recentTransactions', $recent_transactions);
		$this->assign('gatewayInfo', $gateway_info);
	}
	
	public function isSetupCheckListClean()
	{
		// get setup check list check
        //get all files required for setup
		foreach(XiHelperSetup::getOrderedRules() as $setup){
			//get object of class
			$object = XiSetup::getInstance($setup);
			if($object->isApplicable() && $object->isRequired()){
			  return false;
			}
		}
		
		return true;
	}
	
	public function _getDynamicJavaScript()
	{
		$url	=	"index.php?option=com_payplans&view={$this->getName()}";
		
		ob_start(); ?>
		
		payplansAdmin.dashboard_pprefresh = function()
		{
			var url = 'index.php?option=com_payplans&view=dashboard&task=calculateNewStatistics';
			var args = {};
			payplans.ajax.go(url, args);
			return false;
		}
		
		payplansAdmin.dashboard_rebuildstats = function()
		{
			payplans.url.modal("<?php echo "$url&task=rebuildstats&action=start"; ?>");
			// do not submit form
			return false;
		}
		<?php
		$js = ob_get_contents();
		ob_end_clean();

		return $js;	
	}
}
