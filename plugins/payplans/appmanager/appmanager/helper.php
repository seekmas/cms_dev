<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

if(!defined('APPMANAGER_FILE_CACHE_PATH'))
	define('APPMANAGER_FILE_CACHE_PATH', JPATH_ROOT.DS."media".DS."payplans".DS."app".DS."appmanager".DS);
/**
 * Heler for managing the apps 
 * @since 2.2
 */
class PayplansHelperAppmanager
{
	const SUCCESS 	= 200;
	const ERROR		= 500;
	const NOT_FOUND = 404;

	/**
	 * contains the list of apps from remote file
	 * @var Array
	 * @since 2.2.
	 */
	public static $applist = array();
	public static $releaselist = array();
	
	/**
	 * load the app list from remote file
	 * @since 2.2
	 */
	protected static function loadList()
	{
		
		self::clearFileCache();
		
		$url 	  = trim(self::getServerUrl(), '/').'&object=content&action=list';	
		$contents = PayplansHelperUtils::postDataByCurl($url, 'version='.self::getPayplansVersion());	
		if ($contents == false){
			return false;
		}
	
		self::$applist = json_decode($contents);	
	}
	
	/**
	 * returns the list of all available apps
	 * @since 2.2
	 * @return Array
	 */
	public static function getAppList()
	{
		if(!empty(self::$applist)){
			return self::$applist;
		}

		self::$applist = self::getFileData('appville.json');
		
		return self::$applist;
	
	}
	
	
	public static function getFileData($file = 'appville.json')
	{

		if(!is_dir(APPMANAGER_FILE_CACHE_PATH)) {
			self::updateCache();
		}
		
		$files 			= JFolder::files(APPMANAGER_FILE_CACHE_PATH);

		if(empty($files) || count($files) !=2)
		{
			self::updateCache();
		}		
		
		$files 			= JFolder::files(APPMANAGER_FILE_CACHE_PATH);
		
		for ($i = 0; $i<2;$i++)
		{
			if(stristr($files[$i],$file))
				$appFilename = $files[$i];
		}
		
		if(!file_exists(APPMANAGER_FILE_CACHE_PATH.DS.$appFilename)){
			self::updateCache();
		}
		
		$contents		= file_get_contents(APPMANAGER_FILE_CACHE_PATH.DS.$appFilename);
		
		return json_decode($contents);
	}	
	/**
	 * marks the app installed or not in the applist
	 * @since 2.2
	 * @return array(stdclass)
	 * $requireExt created from appville.json this are the apps that we need to fetch not the all
	 */
	public static function getInstalledPlugins()
	{
		static $plugins = array();
		
		if(count($plugins)){
			return $plugins;
		}
		
		$ext_mapping = array('plugin'=>'plg','module'=>'mod','component'=>'com','library'=>'lib','language'=>'lang','file'=>'file');
		
		$query = new XiQuery();
		
		$extensions = $query->select('*')->from('`#__extensions`')->dbLoadQuery()->loadObjectList();

		foreach ($extensions as $extension)
		{
			$extension->xml_params = json_decode($extension->manifest_cache);
			$version = (isset($extension->xml_params->version))? explode('.', $extension->xml_params->version) : null;
			$version = (empty($version)) ? 0 : array_pop($version);
			$extension->build_version = $version;
			$extension->type = (isset($ext_mapping[$extension->type]))? $ext_mapping[$extension->type] : 'plg';
			$plugins[$extension->type.'_'.$extension->folder.'_'.$extension->element.'_'.$extension->client_id] = $extension;
		}
		
		return $plugins;
	}
	
	/**
	 * returns the release apps withe therie build number
	 * @since 2.2
	 * @return Array 
	 */
	public static function getReleasedAppVersion()
	{
		
		if(!empty(self::$releaselist)){
			return self::$releaselist;
		}
		
		self::$releaselist = self::getFileData('release.json');
		
		return self::$releaselist;
		
	}
	
	/**
	 * un-install the extension
	 * @param integer $eid
	 * @since 2.2
	 * @return boolean
	 */
	public static function uninstall($eid)
	{
		require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_installer'.DS.'models'.DS.'manage.php';		
		$model = new InstallerModelManage();
		return $model->remove(array($eid));
	} 
	
	/**
	 * install the extension
	 * @param $app_element string
	 * @param $app_folder string
	 * @since 2.2 
	 */
	public static function install($app_folder, $app_element, $extension_type, $client_id)
	{
		list($user_name, $password) = self::getCredential();
		
		$version = self::getPayplansVersion();
		
		$string 			= "app_element=".$app_element
							."&app_folder=".$app_folder
							."&username=".$user_name
							."&password=".$password
							."&version=".$version
							."&extension_type=".$extension_type
							."&client_id=".$client_id;
							
		$url 				= self::getServerUrl().'&object=release&action=fetch';
				
		//XITODO : disable the debug mode before asking for file
		list($info, $response)	= PayplansHelperUtils::postDataByCurl($url, $string, true);
		
		
		if(strtolower($info) === 'application/zip'){
			$tmp_file_name 	 	= JPATH_ROOT.DS.'tmp'.DS.$extension_type.'_'.$app_folder.'_'.$app_element.'_'.$client_id.'.zip';
			$tmp_folder_name 	= JPATH_ROOT.DS.'tmp'.DS.$extension_type.'_'.$app_folder.'_'.$app_element.'_'.$client_id;
			
			// create a file
			JFile::write($tmp_file_name, $response);		
			
			jimport('joomla.filesystem.archive');
			jimport( 'joomla.installer.installer' );
			jimport('joomla.installer.helper');
			JArchive::extract($tmp_file_name, $tmp_folder_name);
			$installer = JInstaller::getInstance();	
			
			if($installer->install($tmp_folder_name)){
				if($extension_type == 'plg'){
					XiHelperPlugin::changeState($app_element, $app_folder,'1');
				}			$response = json_encode(array('response_code' => self::SUCCESS, 'error_code' => 'INSTALLATION_SUCCESS'));
			}
			else{
				$response = json_encode(array('response_code' => self::ERROR, 'error_code' => 'INSTALLATION_ERROR'));
			}
			
			JFolder::delete($tmp_folder_name);
			JFile::delete($tmp_file_name);
		}
			
		return $response;
	}
	
	/**
	 * return the user name password  
	 */
	public static function getCredential()
	{
		$config 	= PayplansFactory::getConfig();
		$username 	= isset($config->jpayplansUsername) ? $config->jpayplansUsername : '';
		$password 	= isset($config->jpayplansPassword) ? $config->jpayplansPassword : ''; 
		return array($username, $password);
	}
	
	/**
	 * set the username & password in configuration  
	 */
	public static function setCredential($username, $password)
	{	
		$url 	= self::getServerUrl().'&object=user&action=verify';
		$string = 'username='.$username.'&password='.$password;

		list($info, $response)	= PayplansHelperUtils::postDataByCurl($url, $string, true);
			
		$decoded_response = json_decode($response);
		if($decoded_response->response_code == self::ERROR){
			return $response;
		}
		
		$model = PayplansFactory::getInstance('config','model');
		
		if(!$model->save(array('jpayplansUsername' => $username, 'jpayplansPassword' => $password)))
		{
			$response['response_code'] 	= self::ERROR;
			$response['error_code']		= 'ERROR_IN_SAVE';
			return json_encode($response);
		};	
							
		return $response;
	}
	
	public static function getServerUrl()
	{
		static $server_url = null;
		
		if($server_url === null){
			$file_path  = 'http://pub.jpayplans.com/app-manager/server.json';						
			$contents   =  json_decode(self::makeCurlRequestToReadFile($file_path));
			$server_url = $contents->server_url.'/index.php?option=com_payplans&plugin=ppappserver';
		}
		
		return $server_url;
	}
	
	public static function getCacheFrequency()
	{
		static $timestamp = 0;
	
		if($timestamp == 0){
			$file_path  = 'http://pub.jpayplans.com/app-manager/server.json';						
			$contents   = json_decode(self::makeCurlRequestToReadFile($file_path));			
			$timestamp  = isset($contents->cache_frequency) ? $contents->cache_frequency : 86400;
		}
	
		return $timestamp;
	}
	
	public static function getPayplansVersion()
	{
		$version = explode(".", PAYPLANS_VERSION);
		unset($version[2]);
		return implode(".", $version);
	}
	
	/*---function to create an curl request to read file content from server---*/
	public static function makeCurlRequestToReadFile($file_path)
	{
			$curl 	    = curl_init();
			curl_setopt ($curl, CURLOPT_URL, $file_path);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$response   = curl_exec($curl);
			curl_close($curl);
			
			return $response;
	}
	
	
	public static function checkForClearCache()
	{
		$files 			 = JFolder::files(APPMANAGER_FILE_CACHE_PATH);
		$file 			 = array_shift($files);
		$timestamp		 = strtok($file,'_');
		$marginTimeStamp = self::getCacheFrequency()+(int)$timestamp;
		if(time() >= $marginTimeStamp) {
			return true;
		}
		
		return false;
	}

	public static function clearCache()
	{
		//XITODO : what happens when folder can't be deleted
		if(is_dir(APPMANAGER_FILE_CACHE_PATH)){
			JFolder::delete(APPMANAGER_FILE_CACHE_PATH);
		}
	}

	public static function updateCache()
	{	
		if(!is_dir(APPMANAGER_FILE_CACHE_PATH)){
			JFolder::create(APPMANAGER_FILE_CACHE_PATH);
		}
		
		//refreshing release.json
		$url     = PayplansHelperAppmanager::getServerUrl().'&object=release&action=list';
		$release_content = PayplansHelperUtils::postDataByCurl($url, 'version='.PayplansHelperAppmanager::getPayplansVersion());
			
		if ($release_content == false){
			return false;
		}

		$filename 		= time()."_release.json";
		$handler 		= fopen(APPMANAGER_FILE_CACHE_PATH.DS.$filename,"w+");
		fwrite($handler, $release_content);
		fclose($handler);
			
		//refreshing both the files
		$url       = trim(PayplansHelperAppmanager::getServerUrl(), '/').'&object=content&action=list';
		$appville_content = PayplansHelperUtils::postDataByCurl($url, '');
			
		if ($appville_content == false){
			return false;
		}
		
		$filename 		= time()."_appville.json";
		$handler 		= fopen(APPMANAGER_FILE_CACHE_PATH.DS.$filename,"w+");
		fwrite($handler, $appville_content);
		fclose($handler);
			
		return true;
	}
	
	public function fetchInstalledExtensions($app_folder, $app_element, $extension_type, $client_id)
	{
		switch ($extension_type)
		{
			case 'plg' : $extension_type = 'plugin';
							 break;
							 
			case 'mod' : $extension_type = 'module';
							 break;
			
			case 'com' : $extension_type = 'component';
				 			 break;
				
			case 'lang' : $extension_type = 'language';
							  break;
			
			case 'lib' : $extension_type = 'library';
							  break;
							 
			case 'file'	    : $extension_type = 'file';
							  break;
							
			default		: $extension_type = 'plugin';
							 
		}
		
		$db 	= PayplansFactory::getDbo();
		$query 	= "select * from `#__extensions` where `type` ='$extension_type' and `element` = '$app_element' and `folder`= '$app_folder' and `client_id`= '$client_id'";		
		$db->setQuery($query);
		$extension = $db->loadObject();
		
		return $extension->extension_id;
	}

}

