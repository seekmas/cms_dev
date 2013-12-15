<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHelperUtils extends XiHelperUtils
{

	static public function markExit($msg='NO_MESSAGE')
	{
		// if not already set
		if(defined('PAYPLANS_EXIT')==false){
			define('PAYPLANS_EXIT',$msg);
			return true;
		}

		//already set
		return false;
	}
	
	static public function getMethodsDefinedByClass($class)
	{
	    $rClass = new ReflectionClass($class);
	    $array = array();
	    foreach ($rClass->getMethods() as $rMethod)
	    {
	        try
	        {
	            // check whether method is explicitly defined in this class
	            if ($rMethod->getDeclaringClass()->getName()
	                == $rClass->getName())
	            {
	                $array[] =  $rMethod->getName();
	            }
	        }
	        catch (exception $e)
	        {    /* was not in parent class! */    }
	    }
	   
	    return $array;
	}
	
	static public function ignoreImplode($array, $glue= '<br />' , $ignore=array(true,false)){
		$first = true ;
		$return = '';
		foreach ($array as $value) {
			if(in_array($value, $ignore, true))
				continue;
			
			//
			$return .= $value;
		}		
		return $return;
	}
	
	function loadLanguage($extension = '', $basePath = JPATH_BASE)
	{
		if(empty($extension)) {
			$extension = 'plg_'.$this->_type.'_'.$this->_name;
		}

		$lang =& JFactory::getLanguage();
		return $lang->load( strtolower($extension), $basePath);
	}
	
	
	static function pathFS2URL($fsPath='')
	{
		// For multisite websites 
		// ROOT IS : /home/xxxx/public_html
		// FSPath  : /home/xxxx/subdomains/master/public_html/components/com_payplans/helpers/utils.php
		// URL Path: /home/xxxx/subdomains/master/public_html/components/com_payplans/helpers/utils.php 
		// So JPATH ROOT should be calculated as : dirname(dirname(dirname(dirname(__FILE))));
		
		// get reference path from root
		$urlPath	= XiHelperUtils::str_ireplace( PayplansHelperUtils::getJPATH_ROOT().DS , '', $fsPath);
		
		// replace all DS to URL-slash
		$urlPath	= JPath::clean($urlPath, '/');
		
		// prepend URL-root
		return JURI::root().$urlPath;
	}
	
	static function getJPATH_ROOT()
	{
		return dirname( dirname( dirname( dirname(__FILE__) ) ) );
	}
	
	
	//XITODO::decrease the parameters
	function saveUploadedFile($storagepath='',$filepath='',$filename='',$supportedExtensions=array(),$savedname="default")
	{
	   //no file selected then do nothing
	   if(empty($filepath))
		  {
		  	return false;
		  }
		
	    $app = XiFactory::getApplication();
		//remove backslashes from file name
		$filename1 = stripslashes($filename);
		//get file extension
	   	$extension = JFile::getExt($filename1);
	   	//to lower case
	  	$extension = strtolower($extension);
	  	//check if file has supported extensions or not
	    if(!in_array($extension, $supportedExtensions))
		   {
			$app->enqueueMessage(XiText::_('COM_PAYPLANS_CONFIG_CUSTOMIZATION_EDIT_EXTENSION_NOT_SUPPORTED'));
		    return false;
		    }
	    //check if folder exist or not. If not exists then create it.
	    if(JFolder::exists(JPATH_ROOT.DS.$storagepath)==false)
			JFolder::create(JPATH_ROOT.DS.$storagepath);
			
	    //select the path for image storage
		$imgname = JPATH_ROOT.DS.$storagepath.DS.$savedname.'.'.$extension;
		 
	    $img1= $storagepath.'/'.$savedname.'.'.$extension;
	    copy($filepath, $imgname);
		
		return $img1;
	}
	
	function isEmailAddress($email)
	{

		// Split the email into a local and domain
		$atIndex	= strrpos($email, "@");
		$domain		= substr($email, $atIndex+1);
		$local		= substr($email, 0, $atIndex);

		// Check Length of domain
		$domainLen	= strlen($domain);
		if ($domainLen < 1 || $domainLen > 255) {
			return false;
		}

		// Check the local address
		// We're a bit more conservative about what constitutes a "legal" address, that is, A-Za-z0-9!#$%&\'*+/=?^_`{|}~-
		$allowed	= 'A-Za-z0-9!#&*+=?_-';
		$regex		= "/^[$allowed][\.$allowed]{0,63}$/";
		if ( ! preg_match($regex, $local) ) {
			return false;
		}

		// No problem if the domain looks like an IP address, ish
		$regex		= '/^[0-9\.]+$/';
		if ( preg_match($regex, $domain)) {
			return true;
		}

		// Check Lengths
		$localLen	= strlen($local);
		if ($localLen < 1 || $localLen > 64) {
			return false;
		}

		// Check the domain
		$domain_array	= explode(".", rtrim( $domain, '.' ));
		$regex		= '/^[A-Za-z0-9-]{0,63}$/';
		foreach ($domain_array as $domain ) {

			// Must be something
			if ( ! $domain ) {
				return false;
			}

			// Check for invalid characters
			if ( ! preg_match($regex, $domain) ) {
				return false;
			}

			// Check for a dash at the beginning of the domain
			if ( strpos($domain, '-' ) === 0 ) {
				return false;
			}

			// Check for a dash at the end of the domain
			$length = strlen($domain) -1;
			if ( strpos($domain, '-', $length ) === $length ) {
				return false;
			}

		}

		return true;
	}
	
	public static function postDataByCurl($url, $string, $get_info = false)
	{			
		$version = urlencode('51.0');
		// Set the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		
		// Turn off the server and peer verification (TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		
		// Set the API operation, version, and API signature in the request.
		
		// Set the request as a POST FIELD for curl.
		curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
		
		// do not track the handle's request string.
		curl_setopt($ch, CURLINFO_HEADER_OUT, false);
		
		// Get response from the server.
		$content = curl_exec($ch);
		
		// get info of content
		$info = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
				
		if($get_info){
			return array($info, $content);
		}
		
		return $content;
	}
	
}