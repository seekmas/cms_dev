<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansFactory extends XiFactory
{
	/**
	 * @return PayplansRewriter
	 */
	public static function getRewriter($reset=false)
	{
		static $instance=null;

		if($instance !== null && $reset===false)
			return $instance;

		$instance	= new PayplansRewriter();

		return $instance;
	}
	
	/**
	 * @return PayplansFormatter
	 */
	public static function getFormatter($class, $log_class)
	{
		$mappings = array(
						'PayplansFormatterLibApp'			=> 'PayplansAppFormatter',
						'PayplansFormatterLibConfig'		=> 'PayplansConfigFormatter',
						'PayplansFormatterLibGroup' 		=> 'PayplansGroupFormatter',
						'PayplansFormatterLibInvoice'		=> 'PayplansInvoiceFormatter',
						'PayplansFormatterLibOrder'  		=> 'PayplansOrderFormatter',
						'PayplansFormatterLibPayment' 		=> 'PayplansPaymentFormatter',
						'PayplansFormatterLibPlan'    		=> 'PayplansPlanFormatter',
						'PayplansFormatterLibSubscription'	=> 'PayplansSubscriptionFormatter',
						'PayplansFormatterLibTransaction' 	=> 'PayplansTransactionFormatter',
						'PayplansFormatterLibUser'       	=> 'PayplansUserFormatter',
						'PayplansFormatterEmail'			=> 'PayplansFormatter'
		);

		if(isset($mappings[$class])){
			$class = $mappings[$class];
		}
		
		// For cron logs and email logs as they use PayplansFormatter class
		if($class == 'PayplansFormatter' || $class == 'XiFormatter'){
			return new PayplansFormatter();
		}
		
		// find lib class
		$libClass = str_replace('Formatter', '', $class);
		
		// if log-class extends PayplansAppFormatter
		if($libClass == 'PayplansApp'){
			$customAppFormatter = $log_class.'Formatter';
			if(class_exists($customAppFormatter, true) && is_subclass_of($customAppFormatter, 'PayplansAppFormatter')){
				$class = $customAppFormatter;
				return new $class();
			}
			return new PayplansAppFormatter();
		}
		
		// if object found return it
		if(class_exists($libClass,true) && class_exists($class,true)){
			return new $class();
		}
		return false;
	}
	
	public static function redirect($redirectUrl, $isAjax = false)
	{
		if(!$isAjax){
			XiFactory::getApplication()->redirect($redirectUrl);
		}
		
		$response = self::getAjaxResponse();
		$response->addScriptCall('payplans.url.redirect', $redirectUrl);
		$response->sendResponse();
	}
}
