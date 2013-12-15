<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class XiAbstractFactoryBase extends JFactory
{
	//Returns a MVCT object
	static function getInstance($name, $type='', $prefix='Payplans', $refresh=false)
	{
		static $instance=array();

		
		//generate class name
		$className	= $prefix.$type.$name;

		// Clean the name
		$className	= preg_replace( '/[^A-Z0-9_]/i', '', $className );

		//if already there is an object
		if(isset($instance[$className]) && !$refresh)
			return $instance[$className];

		//class_exists function checks if class exist,
		// and also try auto-load class if it can
		if(class_exists($className, true)===false)
		{
			self::getErrorObject()->setError("Class $className not found");
			return false;
		}

		//create new object, class must be autoloaded
		$instance[$className]= new $className();

		return $instance[$className];
	}

	/**
	 * @return JObject
	 */
	static function getErrorObject($reset=false)
	{
		static $instance=null;

		if($instance !== null && $reset===false)
			return $instance;

		$instance	= new JObject();

		return $instance;
	}

	/**
	 * @return XiSession
	 */
	static function _getSession($options = array())
	{
		static $instance=null;

		if($instance !== null && (isset($options['reset']) && $options['reset']))
			return $instance;

		$instance	= new XiSession();

		return $instance;
	}

	/**
	 * @return XiAjaxResponse
	 */
	static public function getAjaxResponse()
  	{
  		//We want to send our DB object instead of Joomla Object
  		//so that we can check our sql performance on the fly.
 		static $response = null;

 		if ($response === null)
 			$response = XiAjaxResponse::getInstance();

  		return $response;
  	}
  	
  	/**
	 * get all configuration parameter available
	 * @return stdClass object of configuration params
	 */
	static $config = null;
  	static public function _getConfig($file = null, $type = 'PHP', $namespace = '')
  	{
		//XITODO : Implement reset logic for whole component
		if(self::$config && XiFactory::cleanStaticCache() != true)
			return self::$config;
	
		$config 	= self::getInstance('config', 'model')->loadRecords();
		$arr        = array();
	
		foreach ($config as $record){
			$arr[$record->key] = isset($record->value)?$record->value:'';
		}
	
		$form       = JForm::getInstance('config',PAYPLANS_PATH_XML.DS.'config.xml',array(),false, '//config');
		$fieldsets  = $form->getFieldsets();
	
		foreach ($fieldsets as $name => $fieldSet){
			foreach ($form->getFieldset($name) as $field){
				$value  = $field->value;
				if(isset($arr[$field->fieldname])){										
					$value = $arr[$field->fieldname];
				}
			
				//json decode if multiple is set to true since array value is saved as json encoded
				if($field->multiple == true){
					$value = json_decode($value);
				}

				$arr[$field->fieldname] = $value;
			}
		}
	
		$jConfig	  = JFactory::getConfig($file = null, $type = 'PHP', $namespace = '')->toArray();
		$arr          = array_merge($arr, $jConfig);
	
		// Let plugin modify config
		$args = array(&$arr);
		PayplansHelperEvent::trigger('onPayplansConfigLoad', $args);

		// convert array of config to object
		return self::$config = (object)$arr;
	}

	static public function cleanStaticCache($set = null)
	{
		static $reset = false;

		if($set !== null)
			$reset = $set;

		return $reset;
	}

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
	 * @return XiEncryptor
	 */
	public static function getEncryptor($reset=false)
	{
		static $instance=null;

		if($instance !== null && $reset===false)
			return $instance;

		// XITODO : raise error if key is not defined
		$key = JString::strtoupper(self::_getConfig()->expert_encryption_key);
		$instance	= new XiEncryptor($key);

		return $instance;
	}
	
	/**
	 * @return XiLogger
	 */
	static protected $_logger = array();
	public static function getLogger($name='')
	{
		$className = 'XiLogger'.$name;
		if(isset(self::$_logger[$className])===false){
			self::$_logger[$className] = new $className();
		}
		
		return self::$_logger[$className];
	}
	
	// from j16
	
	/**
	 * @return XiSession
	 */
	public static function getSession(array $options = array())
	{
		return self::_getSession($options);
	}

	/**
	 * @return stdClass
	 */
	static function getConfig($file = null, $type = 'PHP', $namespace = '')
	{
		return self::_getConfig($file = null, $type = 'PHP', $namespace = '');
	}
}



// Include the Joomla Version Specific class, which will ad XiAbstractFactory class automatically
XiError::assert(class_exists('XiAbstractJ'.PAYPLANS_JVERSION_FAMILY.'Factory',true), XiError::ERROR);
