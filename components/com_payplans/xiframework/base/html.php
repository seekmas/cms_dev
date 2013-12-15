<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class XiHtml
{
	public static function _($key)
	{
		$parts = explode('.', $key);
		$prefix 	= 'XiHtml';
		$className	= $prefix.ucfirst($parts[0]);
		$extraArgs 	= func_get_args();
		
		if (class_exists( $className , true ))
		{
			$extraArgs[0] = isset($parts[1]) ? $prefix.'.'.$parts[0].'.'.$parts[1] : $prefix.'.'.$parts[0];
		}
		
		return call_user_func_array( array( 'JHTML', '_' ), $extraArgs );	
	}
	
	public static function stylesheet($filename, $attribs = array(), $relative = false, $path_only = false, $detect_browser = true, $detect_debug = true)
	{
		//load minimized css if required
		if(isset($config->expert_useminjs) && $config->expert_useminjs){
			$filename = XiHtml::minFile($filename, 'css');
		}
		
		$filename = XiHelperTemplate::mediaURI($filename,false);
		
		return JHTML::stylesheet("$filename", $attribs);
	}

	public static function script($filename, $framework = false, $relative = false, $path_only = false, $detect_browser = true, $detect_debug = true)
	{	
		$config =  XiFactory::getConfig();
		if(isset($config->expert_useminjs) && $config->expert_useminjs){
			$filename = XiHtml::minFile($filename);
		}
		
		$filename = XiHelperTemplate::mediaURI($filename, false);
		return JHTML::script("$filename", true);

	}
	
	public static function link($url, $text, $attribs = null)
	{
		return JHTML::link($url, $text, $attribs);
	}
	
	static function minFile($filename, $ext='js')
	{
		//use minified scripts
		$newFilename = JFile::stripExt($filename) . '-min.'.$ext;

		// no need to add path
		if(strpos($newFilename, 'http') === 0) {
			return $newFilename;
		}
		
		// add absolute root path
		if(strpos($newFilename, JPATH_ROOT) !== 0) {
			$newFilename =  JPATH_ROOT.DS.$newFilename;
		}
		
		// use minified only if it exists
		if(JFile::exists("$newFilename")){
			return $newFilename;
		}
		
		return $filename;
	}
	
	public static function image($file, $alt, $attribs = null, $relative = false, $path_only = false)
	{
		return JHtml::image($file, $alt, $attribs, $relative, $path_only);
	}
}