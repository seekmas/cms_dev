<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class XiHelperJoomla extends XiAbstractHelperJoomla  
{
	/**
	 *
	 * @param unknown_type $eventName
	 * @param array $data
	 * @return array
	 */
	static function triggerPlugin($eventName,array &$data =array(), $prefix='')
	{
		static $dispatcher = null;

		//load dispatcher if required
		if($dispatcher===null){
			$dispatcher = JDispatcher::getInstance();
		}

		//load payplans plugins
		self::loadPlugins();
		//$eventName = $prefix.JString::ucfirst($eventName);
		return $dispatcher->trigger($eventName, $data);
	}

	/**
	 * Loads plugin of given type
	 * @param $type
	 */
	static function loadPlugins($type='payplans')
	{
		static $loaded = array();

		//is already loaded
		if(isset($loaded[$type]))
			return true;

		//import plugins
		JPluginHelper::importPlugin($type);

		//set that plugins are already loaded
		$loaded[$type]= true;
		return true;
	}

	public static function getPluginStatus($element, $folder = 'system')
	{
		return JPluginHelper::isEnabled($folder, $element);
	}
	
	public static function getPluginInstance($type, $name)
	{
		
		$observers = JDispatcher::getInstance()->get('_observers');
				
		foreach ($observers as $observer){
			if (is_array($observer) && isset($observer['_type']) && $observer['_type'] == $type && $observer['_name'] == $name){
					return $observer;
			}
			elseif (is_object($observer) && ($observer->get('_type') == $type) && ($observer->get('_name') == $name)){
					return $observer;
			}
		}

		return null;	
	}
		
	public static function getLogoutLink($routed=true)
	{
		$link = 'index.php?option='.PAYPLANS_COM_USER;
			
         $link .= '&task=user.logout';
		 // add token
		 $link .= '&'.JSession::getFormToken().'=1';
		
		//set return in url to redirect to home page after logout
		$sitename = JURI::root();
		$returnurl = base64_encode($sitename);
		$link.='&return='.$returnurl;
		
		if($routed){
			return XiRoute::_($link);
		}
		
		return $link;
	}

	public static function getLoginLink($routed=true)
	{
		$link = 'index.php?option='.PAYPLANS_COM_USER;
	
		$link .= '&task=login';
		// add token
		$link .= '&'.JSession::getFormToken().'=1';
		
		//set return in url to redirect to home page after login
		$sitename  = JURI::getInstance()->toString();
		$returnurl = base64_encode($sitename);
		$link.='&return='.$returnurl;
		
		if($routed){
			return XiRoute::_($link);
		}
		
		return $link;
	}
	
		/**
        *
        * @return currently used langauge code
        * Also language and locale seperated
        */
       public static function getLanguageCode()
       {
               //XITODO : fixit for Joomfish

               $lang = XiFactory::getLanguage();
               // as if now no way to collect language code
               //XITODO : fixit for 1.7
                $code = $lang->get('tag');
               
               list ($langCode, $localCode)=explode('-', $code, 2);
               return array('code' => $code, 'language' => $langCode, 'local' => $localCode);
       }
       
    public static function isLocalHost()
	{
		$root = JURI::root();
		if(JString::strpos($root, 'localhost/') === false){
			return false;
		}
		
		return true;
	}	
	
	static function getRootPath()
	{
		// in case of multi-site, we need to refer correct files
		return dirname(dirname(dirname(XI_PATH_FRAMEWORK)));
	}

	public static function getJoomlaUsers($id = false)
	{
		$query = new XiQuery();
		if(!$id){
		return $query->select(' `id`, `name`, `username` ')
					 ->from('`#__users`')
					 ->dbLoadQuery()
					 ->loadObjectList('id');
		}
		if(is_array($id)==false){
			$id = array($id);
		}
		$ids = implode(',', $id);
		return $query->select(' `id`, `name`, `username` ')
					 ->from('`#__users`')
					 ->where('`id` IN ('.$ids.')')
					 ->dbLoadQuery()
					 ->loadObjectList('id');
	}
	
	// get joomla categories
	public static function getJoomlaCategories($extension = null)
	{
		$db 	= PayplansFactory::getDBO();
		
		$query = 'SELECT  `id`  as category_id, title'
			 	. ' FROM #__categories';
		
		if(!empty($extension)){
			$query .= " WHERE extension = '$extension'";
		}
		
	 	$db->setQuery( $query );
	 	return $db->loadObjectList('category_id');
	}
	
	// get joomla articles
	public static function getJoomlaArticles()
	{
		$query = new XiQuery();
		return $query->select(' `id`, `title` ')
					 ->from('`#__content`')
					 ->dbLoadQuery()
					 ->loadObjectList('id');
	}
	
}
