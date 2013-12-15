<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/

if(defined('_JEXEC')===false) die();

class PayplansSetupCron extends XiSetup
{
	public $_location = __FILE__;
	public $_message  = 'COM_PAYPLANS_CRON_RUNNING_PROPERLY';

	function isRequired()
	{
		$config = XiFactory::getConfig();
		if($config->expert_run_automatic_cron == 0)
		{
			$this->_message = 'COM_PAYPLANS_CRON_IS_NOT_RUNNING_AUTOMATICALLY';
			return $this->_required=false;
		}

		$last_cron_time 	= $config->cronAcessTime;
		if(empty($last_cron_time)){
			$this->_message = 'COM_PAYPLANS_CRON_NOT_RUNNING_PROPERLY';
			return $this->_required=true;
		}
		
		$frequency	= (isset($config->microsubscription) && $config->microsubscription ) ? $config->cronFrequency/PAYPLANS_CONFIG_CRONFREQUENCY_DIVIDER : $config->cronFrequency;
		
  		$time           = $this->convertSecondsTohhmmss($frequency);
		$date           = new XiDate($last_cron_time);
  		$expiryDateTime = $date->addExpiration($time);
  		$unixTimeStamp  = $expiryDateTime->toUnix(); 
  		$current_time   = new Xidate();
  		$currentUnixTime= $current_time->toUnix();

  		if($currentUnixTime > $unixTimeStamp){
			$this->_message = 'COM_PAYPLANS_CRON_NOT_RUNNING_PROPERLY';
			return $this->_required=true;
		}
		return $this->_required=false;
	}

    //Convert Seconds to hh:mm:ss format
	function convertSecondsTohhmmss($frequency)
	{
		$hh = intval($frequency / 3600);
		$mm = (intval($frequency /60) % 60)*4;
		$ss = $frequency % 60;
 		if(strlen($hh) == 1) {
   		$hh = "0".$hh;
  		}
   		if(strlen($mm) == 1) {
   		$mm = "0".$mm;
  		}
   		if(strlen($ss) == 1) {
   		$ss = "0".$ss;
  		}
  		$time="000000".$hh.$mm.$ss;
  		return $time;
	}
}
