<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansHelperEvent
{
	static protected $_events = null;
	static protected $_paths  = array(PAYPLANS_PATH_EVENT);
	/**
	 * Add a path from where we can load event classes
	 * @param string $path
	 */
	static function addEventsPath($path=null)
	{
		if($path != null){
			self::$_paths[]= $path;
		}

		return self::$_paths;
	}

	/**
	 * Load Event from various folders
	 * @return Array of Event Class Names
	 */
	static function getEvents()
	{
		//already loaded
		if(self::$_events){
			return self::$_events;
		}

		//load apps from file systems
		$events = array();
		foreach(self::addEventsPath() as $path){
			$newEvents = JFolder::files($path, '.php$');

			//also mark them autoload
			foreach($newEvents as $event){
				$class = 'PayplansEvent'.JString::ucfirst(JFile::stripExt($event));
				$events[$class] = $class;
			}
		}

		// also sort for consistent behaviour
		sort($events);
		self::$_events = $events;
		return $events;
	}

	/**
	 * Trigger all observers of this events instances
	 * @param String $eventName
	 * @param array $args
	 * @param String $purpose
	 * @param unknown_type $refObject
	 * @return Array
	 */
	static function trigger($eventName, array &$args=array(), $purpose='',  $refObject=null)
	{
		// Dont required onPayplansWalletUpdate trigger when Migration is running.
		if( defined('PAYPLANS_MIGRATION_START') && !defined('PAYPLANS_MIGRATION_END') && $eventName == 'onPayplansWalletUpdate'){
			return true;
		}
		
		// IMP : Plugins should be triggered before Apps
		$pluginResults = array();
		if(JString::stristr($eventName, "onPayplans")){
			$pluginResults 	=  XiHelperPlugin::trigger($eventName, $args);
		}
		
		$coreResults 	=  self::_trigger($eventName, $args);
		$appResults 	=  PayplansHelperApp::trigger($eventName, $args, $purpose, $refObject);	
		
		return array_merge($pluginResults, $coreResults, $appResults);
	}
	
	static protected function _trigger($eventName, array &$args=array())
	{
		//get Plugins objects
		$observers = self::getEvents();

		$results = array();

		//trigger all apps if they serve the purpose
		foreach($observers as $observer)
		{
			if(method_exists($observer, $eventName)){
				$results[] = call_user_func_array(array($observer,$eventName), $args);
			}
		}

		return $results;
	}
}
