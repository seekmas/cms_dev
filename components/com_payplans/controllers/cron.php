<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplanssiteControllerCron extends XiController
{
	protected 	$_defaultTask = 'trigger';

	// No Model
	public function getModel()
	{
		return null;
	}

	/**
	 * The function called which will trigger the cron action
	 */
	public function trigger()
	{
		header("Content-type: image/png");
	    echo file_get_contents(PAYPLANS_PATH_MEDIA.DS.'images'.DS.'cron.png');
	
 		// IMP : No need to check security, as we can always check if we require to process or not	    

		// check if we need to trigger, dont trigger too frequently
		if(PayplansHelperCron::checkRequired()==false){
			PayplansHelperUtils::markExit('Cron Job NOT REQUIRED');
			return false;
		}

      	PayplansHelperCron::deleteCronLogs();
			
		// If simultaneous requests are coming then allow only one and reject the other request
		// XiTODO: We can increase timeOut instead of 0, 
		// if we want to execute the other request to wait for some given timeout
		$lock =  XiLock::getInstance('payplansCron');
		
		if($lock->getLockResult()){
			$date = new XiDate();
			
			$model = PayplansFactory::getInstance('config','model');
			$model->save(array('currentCronAcessTime'=>$date->toUnix()));
			
			PayplansFactory::$config = false;
			
			// trigger plugin and apps
			$args = array();
			PayplansHelperEvent::trigger('onPayplansCron', $args);
			
			// Mark exit
			$msg = XiText::_('COM_PAYPLANS_LOGGER_CRON_EXECUTED');
			PayplansHelperUtils::markExit($msg);
			$content = array('Message'=>$msg);
			PayplansHelperLogger::log(XiLogger::LEVEL_INFO, $msg, null, $content, 'PayplansFormatter','Payplans_Cron');
	
			//XITODO : make it independent of XML file
			$date = new XiDate(); 
			$model = PayplansFactory::getInstance('config','model');
			$model->save(array('cronAcessTime'=>XiFactory::getConfig()->currentCronAcessTime));
			
			return false;
		}

		//create a log for rejection of one of simultaneous cron request, in case debug is on
		if(JDEBUG){
			$msg = XiText::_('COM_PAYPLANS_LOGGER_SIMULTANEOUS_CRON_REQUEST_REJECTED');
			PayplansHelperUtils::markExit($msg);
			$content = array('Message'=>$msg);
			PayplansHelperLogger::log(XiLogger::LEVEL_INFO, $msg, null, $content, 'PayplansFormatter','Payplans_Cron');
		}

	   	return false;
	}
}

