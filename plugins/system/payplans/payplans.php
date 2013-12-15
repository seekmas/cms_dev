<?php

/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @contact		shyam@joomlaxi.com
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


// check if Payplans installed or not
jimport('joomla.filesystem.file');

if(!defined('DS')){
	define('DS', DIRECTORY_SEPARATOR);
}

// Load particular autoloading required
$app = JFactory::getApplication();
$basepath	= $app->isAdmin() ? JPATH_ADMINISTRATOR : JPATH_SITE ;
$fileName 	= $basepath . DS . 'components'.DS.'com_payplans'.DS.'includes'.DS.'includes.php';

if(!JFile::exists($fileName))
{
	return true;
}
else
{
	$option	= JRequest::getVar('option');
	//do not load payplans when component is com_installer
	if($option == 'com_installer'){
		return true;
	}

	require_once $fileName;

	/**
	 * Payplans System Plugin
	 *
	 * @package	Payplans
	 * @subpackage	Plugin
	 */
	class  plgSystemPayplans extends XiPlugin
	{
		public $_app = null;

		function __construct(& $subject, $config = array())
		{
			parent::__construct($subject, $config);
			$this->_app = JFactory::getApplication();
		}

		function _accessCheck()
		{
			// Do not affect backend
			if ($this->_app->isAdmin()){
				return true;
			}
			
			//
			$user = XiFactory::getUser();
			$pUser = PayplansUser::getInstance($user->id);
			
			if(!($pUser instanceof PayplansUser) || $pUser->isAdmin()){
				return true;
			}
			
			// Any App and plugin can handle this event
			$args = array($pUser, $options=array());
			$result  = PayplansHelperEvent::trigger('onPayplansAccessCheck', $args, '', null);
			
			// is access check failed
			if(in_array(false, $result,true)){
				$result  = PayplansHelperEvent::trigger('onPayplansAccessFailed', $args, '', null);
				return false;
			}
			return true;
		}
		
		function onAfterRoute()
		{
			if (JDEBUG) {
				jimport( 'joomla.error.profiler' );
				$_PROFILER = JProfiler::getInstance( 'Application' );
			}
			JDEBUG ? $_PROFILER->mark( 'PayPlans-onAfterRoute-Before-Execute') : null;
			
			// Let us do access check
			self::_accessCheck();
			
			$option	= JRequest::getVar('option');
			$view 	= JRequest::getVar('view');
			$task	= JRequest::getVar('task');
			$document = JFactory::getDocument();
			if($document->getType() != 'html'){
				JDEBUG ? $_PROFILER->mark( 'PayPlans-onAfterRoute-After-Execute') : null;
				return;
			}

			if(JRequest::getVar('option',null, 'REQUEST') != 'com_payplans'){
				JDEBUG ? $_PROFILER->mark( 'PayPlans-onAfterRoute-After-Execute') : null;
				return true;
			}
			
			// from Payplans 2.0 payment notification will be 
			// processed on payment=>notify rather then order=>notify
			if(($view == 'order') && ($task=='notify')){
				JRequest::setVar('view', 'payment');
			}

			// load it automatically on payplans pages
			PayplansHelperTemplate::loadAssets();

			JDEBUG ? $_PROFILER->mark( 'PayPlans-onAfterRoute-After-Execute') : null;
			return true;
		}

		public function onAfterDispatch()
		{
			$option = JRequest::getVar('option');
			if($option == 'com_payplans' && JFactory::getUser()->id != 0)
			{
			  $app = JFactory::getApplication();		
				 if($app->isAdmin()){
				 	 $cron = PayplansSetupCron::getInstance('cron');
				 	if($cron->isRequired() == true)
				  			$app->enqueueMessage(XiText::_('COM_PAYPLANS_CRON_IS_NOT_RUNNING_PROPERLY'), 'Error');
				  }
			}
			
			if (JDEBUG) {
				jimport( 'joomla.error.profiler' );
				$_PROFILER = JProfiler::getInstance( 'Application' );
			}
			JDEBUG ? $_PROFILER->mark( 'PayPlans-onAfterDispatch-Before-Execute') : null;
			
			// add language text to javascript  
			XiText::autoLoadJS();
			JDEBUG ? $_PROFILER->mark( 'PayPlans-onAfterDispatch-After-Execute') : null;
			return true;
		}
		/**
		 * Add a image just before </body> tag
		 * which will href to cron trigger.
		 */
		function onAfterRender()
		{
			//V. IMP. : During uninstallation of Payplans
			// after uninstall this function get executed
			// so prevent it
			$option = JRequest::getVar('option');
			if($option == 'com_installer'){
				return true;
			}
			
			// PayPlans was not included and loaded
			if(defined('PAYPLANS_DEFINE_ONSYSTEMSTART')==false){
				return;
			}

			// Only do if configuration say so : expert_run_automatic_cron is set to 1
			if(XiFactory::_getConfig()->expert_run_automatic_cron != 1){
				return;
			}
			
			// Only render for HTML output
			if (JFactory::getDocument()->getType() !== 'html' ) { return; }

			//only add if required, then add call back
			if(PayplansHelperCron::checkRequired()== true){
				// Add a cron call back			
				$cron = '<img src="'.PayplansHelperCron::getURL().'" style="display:none;" />';
				$body = JResponse::getBody();
				$body = str_replace('</body>', $cron.'</body>', $body);
				JResponse::setBody($body);
			}
		}

		function onAfterInitialise()
		{
			if (JDEBUG) {
				jimport( 'joomla.error.profiler' );
				$_PROFILER = JProfiler::getInstance( 'Application' );
			}
			JDEBUG ? $_PROFILER->mark( 'PayPlans-onAfterInitialise-Before-Execute') : null;
			
			//trigger system start event after loading of joomla framework
			if(defined('PAYPLANS_DEFINE_ONSYSTEMSTART')==false){
				// bug in php, subclass having issue with autoloading multiple chained classes
				// http://bugs.php.net/bug.php?id=51570
				class_exists('XiPlugin', true);
				
				//IMP : Do not load system plugins
				PayplansHelperEvent::trigger('onPayplansSystemStart');
				//XiHelperPlugin::trigger('onPayplansSystemStart');
				define('PAYPLANS_DEFINE_ONSYSTEMSTART', true);
			}
			
			/// load registration polugin of payplans
			XiHelperPlugin::loadPlugins('payplansregistration');
			
			//load override language file after all plugin has been loaded
			$filename = 'com_payplans';
			$language = JFactory::getLanguage();
			$language->load($filename.'_override', JPATH_SITE, null, false, false);
			
			JDEBUG ? $_PROFILER->mark( 'PayPlans-onAfterInitialise-After-Execute') : null;
		}
		

		/*
		 * XITODO : MED : Remove these functions and move to some testing plugins
		 * as these are for testing only
		 * */
		function prefixJustForTestTrue()
		{
			return true;
		}

		function justForTestFalse()
		{
			return false;
		}

		function xiTestingTriggerGiven($given)
		{
			return $given;
		}

		function prefixTestingTriggerVisibility()
		{
			return false;
		}

		// Joomla 1.6 compatibility
		public function onBeforeDeleteUser($user)	
		{
	 	    return $this->onUserBeforeDelete($user);
		}
		
		function onUserBeforeDelete($user)
		{
			$userId = $user['id'];

			//delete order,subscription,transaction,payment and wallet entries
			$orderRecords = XiFactory::getInstance('order','model')
								->loadRecords(array('buyer_id'=>$userId));
			foreach($orderRecords as $record)
			{
				$order = PayplansOrder::getInstance( $record->order_id, null, $record);
				$order->delete();	
			}

			$invoice     = XiFactory::getInstance('invoice','model')
								      ->deleteMany(array('user_id' => $userId));

			$transaction = XiFactory::getInstance('transaction','model')
									  ->deleteMany(array('user_id' => $userId));

			$payment     = XiFactory::getInstance('payment','model')
									  ->deleteMany(array('user_id' => $userId));
				
			$wallet      = XiFactory::getInstance('wallet','model')
								     ->deleteMany(array('user_id' => $userId));
		}
		
		// XITODO : no need to do this
		function onPrepareContent($item, $params, $limitstart)
		{
			$args = array(&$item, &$params, $limitstart);
			$results = PayplansHelperEvent::trigger('onPrepareContent', $args);
			return true;
		}
		
		function onContentPrepare($context, &$row, &$params, $page = 0)
		{
			$args = array($context, &$row, &$params, $page);
			$results = PayplansHelperEvent::trigger('onContentPrepare', $args);
			return true;
		}
	}
}

