<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

/**
 * Class will help tp instantiate multiple joomla xiapps plugin with different paramter
 * @author meenal
 * 
 * XITODO : all functions need renaming
 */

class PayplansHelperApp
{
	/**
	 * Add a path from where we can load Apps
	 * @param string $path
	 */
	static function addAppsPath($path=null)
	{
		static $paths = array(PAYPLANS_PATH_APPS);

//		// This cache cleaning create issue, for non-core apps
//		if(XiFactory::cleanStaticCache()){
//			$paths = array(PAYPLANS_PATH_APPS);
//		}

		if($path != null){
			$paths[]= $path;
		}

		return $paths;
	}

	static $_apps = array(); 
	
	/**
	 * Load Apps from various folders
	 * @return Array of Apps
	 */
	static function getApps()
	{
		//already loaded
		if(self::$_apps){
			return self::$_apps;
		}

		//load apps from file systems
		foreach(self::addAppsPath() as $path){
			$newApps = JFolder::folders($path);
			
			if(!is_array($newApps)){
				continue;
			}

			// add to new apps discovered into list (only if app file exist in folder) 
			// also mark them autoload
			foreach($newApps as $app){
				if(JFile::exists($path.DS.$app.DS.$app.'.php')==true){
					PayplansHelperLoader::addAutoLoadFile($path.DS.$app.DS.$app.'.php',
								'PayplansApp'.$app);
					
					self::$_apps[$app] = $app;
				}
			}
		}
		
		// also sort for consistent behaviour
		sort(self::$_apps);
		return self::$_apps;
	}

	/**
	 * return Apps of given purpose e.g. payment
	 * In Default value return all apps
	 * @param string purpose
	 * @return Array of particular Purpose Apps
	 */
	static function getPurposeApps($purpose='')
	{
		static $purposeApps = array();

		$allApps 	 = self::getApps();

		// Return all apps
		if($purpose == ''){
			return $allApps;
		}

		//XITODO : implement cache clean
		// if already cached
		if(isset($purposeApps[$purpose]))
			return $purposeApps[$purpose];

		// not cached, add all classes
		$purposeApps[$purpose] = array();
		$purposeClass	= 'PayplansApp'.JString::ucfirst($purpose);
		foreach($allApps as $app){
			$appClass 		= 'PayplansApp'.JString::ucfirst($app);

			// bug in php, subclass having issue with autoloading multiple chained classes
			// http://bugs.php.net/bug.php?id=51570
			class_exists($appClass, true);

			if(is_subclass_of($appClass, $purposeClass)){
				$purposeApps[$purpose][] = $app;
			}
		}

		return $purposeApps[$purpose];
	}

	/**
	 * Load all the apps in the system
	 * means creating object of every app in table
	 * @return Array PayplansApp
	 */
	static function loadApps()
	{
		static $instances = null;

		//clean cache if required, required during testing
		if(XiFactory::cleanStaticCache()){
			$instances = null;
		}

		if($instances === null)
		{
			if (JDEBUG) {
				jimport( 'joomla.error.profiler' );
				$_PROFILER = JProfiler::getInstance( 'Application' );
			}
			JDEBUG ? $_PROFILER->mark( 'Payplans-App-Before-Load-Apps') : null;

			//at least load all classes
			self::getApps();

			//now load all records
			$queryFilters = array('published'=>1);
			$apps = XiFactory::getInstance('app', 'model')->loadRecords($queryFilters);

			//XITODO trigger on before load apps event to plugin

			$instances = array();
			foreach($apps as $app){
				//IMP : $app should be given, so it can bind with it rather then loading the data
				$instance = PayplansApp::getInstance( $app->app_id, $app->type, $app);
				if($instance === FALSE){
					continue;
				}
				
				$instances[$app->app_id] = $instance; 
			}

			//trigger on after load apps event to plugin
			$args	= array(&$instances);
			XiHelperPlugin::trigger('onPayplansAppsAfterLoad',$args);
			JDEBUG ? $_PROFILER->mark( 'Payplans-App-After-Load-Apps') : null;
		}

		return $instances;
	}

	/**
	 * Trigger all apps instances
	 * @param String $eventName
	 * @param array $args
	 * @param String $purpose
	 * @param unknown_type $refObject
	 * @return Array
	 */
	static function trigger($eventName, array &$args=array(), $purpose='',  $refObject=null)
	{
		//get Plugins objects
		$apps = self::loadApps();

		$results = array();

		//trigger all apps if they serve the purpose
		foreach($apps as $app)
		{
			if(method_exists($app, $eventName) && $app->hasPurpose($purpose) && $app->isApplicable($refObject, $eventName)){
				$results[$app->getId()] = call_user_func_array(array($app,$eventName), $args);
			}
		}

		return $results;
	}

	/**
	 * @deprecated in 1.2, use getXml instead
	 * XITODO:1.4 This will not be available in 1.4 release. Remove it
	 */
	static function getXmlData($what = 'name')
	{
		$result = array();
		foreach(self::getXml() as $key => $array){
			$result[$key] = isset($array[$what])? $array[$what] : null;
		}
		
		return $result;
	}

	static $tags	= null;
	static public function getTags($merged=false, $what = 'tags')
	{
		if(self::$tags === null){
			self::$tags = array();
			foreach(self::getXml() as $key => $array){
				self::$tags[$key] = isset($array[$what])? $array[$what] : array();
			}
		}
		
		// return only tags
		if($merged){
			$mtags= array();
			foreach(self::$tags as $apptag){
				$mtags = array_merge($mtags, $apptag);
			}
			// only unique and sorted
			return array_values(array_unique($mtags));
		}
		return self::$tags;
	}
	
	
	static $xmlData = null;
	static public function getXml()
	{
		$apps = self::getApps();
		
		if(self::$xmlData === null){
			foreach($apps as $app){
				$appInstance = PayplansApp::getInstance( null, $app);
				if($appInstance == false){
					continue;
				}		
				$xml = $appInstance->getLocation() . DS . $appInstance->getName() . '.xml';
	
			if (file_exists($xml)) {
					$xmlContent = simplexml_load_file($xml);
				}
				else {
					$xmlContent = null;
				}

				// if no tag was defined at least all tag is added
				self::$xmlData[$appInstance->getName()]['tags'] = array('all'); 
				self::$xmlData[$appInstance->getName()]['location'] = $appInstance->getLocation();
				self::$xmlData[$appInstance->getName()]['icon'] = JPATH_ROOT.DS.'components/com_payplans/media/images/icons/48/app.png';
				
				foreach ($xmlContent as $element=> $value){
					$value = (string)$value;
					if($element == 'tags'){
						$value = array_merge(array('all'), explode(',',$value));
					}
					if($element == 'icon'){
						$value = $appInstance->getLocation().DS.$value;
					}
					self::$xmlData[$appInstance->getName()][$element] = $value;
				}
			}
		}
		
		return self::$xmlData;
	}
	
	/**
	 * @deprecated Since 1.3 Use getApplicableApps
	 */
	static function getApplicationApps($purpose='', PayplansIfaceApptriggerable $refObject=null)
	{
		return self::getApplicableApps($purpose, $refObject);
	}
	
	/**
	 * 
	 * Get all apps which are of this purpose and 
	 * applicable on this refObject
	 * 
	 * @param String $purpose
	 * @param PayplansIfaceApptriggerable $refObject
	 * @since 1.3
	 */
	static function getApplicableApps($purpose='', PayplansIfaceApptriggerable $refObject=null)
	{
		//get Plugins classes names
		$apps = self::loadApps();

		$results = array();

		//trigger all apps if they serve the purpose
		foreach($apps as $app)
		{
			if($app->hasPurpose($purpose) && $app->isApplicable($refObject)){
				$results[$app->getId()] = $app;
			}
		}

		return $results;
	}

	/**
	 * Get apps instances of given purpose
	 * (Do not checks applicability)
	 * @param String $purpose
	 * @since 1.3
	 */
	static function getAvailableApps($purpose='')
	{
		//get Plugins classes names
		$apps = self::loadApps();

		$results = array();

		//trigger all apps if they serve the purpose
		foreach($apps as $app)
		{
			if($app->hasPurpose($purpose)){
				$results[$app->getId()] = $app;
			}
		}

		return $results;
	}

	static function getResourceModel()
    {
    	static  $rmodel = null;
    	
    	if($rmodel == null){
    		$rmodel = XiFactory::getInstance('resource', 'model');	
    	}
    	
    	return $rmodel;
    }
    
    //XITODO : use Lib instance of Resource instead of model
    public static function getResource($userid, $groupid, $resource)
	{		 
		$rmodel = self::getResourceModel();
		$record = $rmodel->loadRecords(array(	'user_id'  => $userid,
												'title' => $resource,
												'value'	   => $groupid));
		
		$record = array_shift($record);
		if(empty($record) || !$record){
			return false;
		}
		
		// always trim the string by comma (,)
		$record->subscription_ids = JString::trim($record->subscription_ids, ',');
		
		return $record;		
	}
	
	public static function addToResource($subId, $userid, $groupid, $resource, $count = 0)
	{
		$record 	= self::getResource($userid, $groupid, $resource);
		$id 		= 0;
		
		$data['subscription_ids'] 	= $subId;
		$data['value']				= $groupid;
		$data['title'] 				= $resource;
		$data['user_id']			= $userid;
		$data['count']				= $count;
		
		if($record){
			$id = $record->resource_id;
			$record->subscription_ids 	= empty($record->subscription_ids) ? array() : explode(',', $record->subscription_ids);
			$record->subscription_ids[] = $subId; 
			$data['subscription_ids'] 	= implode(',', $record->subscription_ids);			
			$data['count']				= $record->count + $count;
		}		
		 
		// each subscription id should be packed with comma (,)
		$data['subscription_ids'] = ','.$data['subscription_ids'].',';
		$rmodel = self::getResourceModel();
		return $rmodel->save($data, $id);
	}
	
	public static function removeFromResource($subId, $userid, $groupid, $resource, $count = 0)
	{
		$record 	= self::getResource($userid, $groupid, $resource);
		
		// should not remove from this group, if resource is not available
		if(!$record || empty($record)){
			return false;
		}
		
		$record->subscription_ids = explode(',', $record->subscription_ids);
		
		// do not remove from this group if user was not added by this subscription 
		if(!in_array($subId, $record->subscription_ids)){
			return false;
		}
		
		$data['value']				= $groupid;
		$data['title'] 				= $resource;
		$data['user_id']			= $userid;
		$data['count']				= $record->count - $count;

		// if count becomes negative then set it 0
		if($data['count'] < 0){
			$data['count'] = 0;
		}

		// remove the currenct sub id from ids
		$record->subscription_ids = array_diff($record->subscription_ids, array($subId));
		$data['subscription_ids'] 	= implode(',', $record->subscription_ids);
					 
		$rmodel = self::getResourceModel();
		$remove = false;
		
		// if ids are empty then return true, and remove from group
		// and delete the resource
		if(empty($data['subscription_ids'])){
			$rmodel->delete($record->resource_id);
			$remove = true;
		}
		// each subscription id should be packed with comma (,)
		$data['subscription_ids'] 	= ','.$data['subscription_ids'].',';
		// do not remove if any ids are there
		$rmodel->save($data, $record->resource_id);
		return $remove;
	}
}
