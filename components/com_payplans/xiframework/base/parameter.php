<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

//jimport('joomla.html.parameter');

class XiParameter extends JRegistry {
    /**
	 * 
	 * @param $data
	 * @param $format
	 */
	function bind($data, $format='JSON')
	{
		if ( is_array($data) ) {
			return $this->loadArray($data);
		} 
		
		if ( is_object($data)) {
			return $this->loadObject($data);
		} 

		//XiTODO: Remove it after converting all the previous records to json
		//if ini data then handle it here only
		//required for timer element, otherwise we can remove JRegistryFormatXiINI class
		if ((substr($data, 0, 1) != '{') && (substr($data, -1, 1) != '}'))
		{
			$ini = JRegistryFormat::getInstance('XiINI');
			$obj =  $ini->stringToObject($data);
			return $this->loadObject($obj);
		}
		
		return $this->loadString($data, $format);
	}
	
}
