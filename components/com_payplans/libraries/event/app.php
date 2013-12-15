<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Loggers
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansEventApp
{
	public static function onPayplansSystemStart()
	{
		//add app paths to app loader
		$path = PAYPLANS_PATH_COMPONENT_SITE.DS.'apps';
		$appFolders = JFolder::folders($path);
		if($appFolders){
			foreach($appFolders as $folder){
				PayplansHelperApp::addAppsPath($path.DS.$folder);
			}
		}
		return true;
	}
}
