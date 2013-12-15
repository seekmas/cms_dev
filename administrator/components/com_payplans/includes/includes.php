<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();

// if already loaded do not load
if(defined('PAYPLANS_LOADED')){
	return;
}

//load basic defines
require_once dirname(__FILE__).DS.'defines.php'	;
require_once dirname(__FILE__).DS.'functions.php'	;

//load includes from SITE
require_once PAYPLANS_PATH_INCLUDE.DS.'includes.php';

// System profiler
if (JDEBUG) {
	jimport( 'joomla.error.profiler' );
	$_PROFILER = JProfiler::getInstance( 'Application' );
}

JDEBUG ? $_PROFILER->mark( 'PayPlans-Backend-Before-autoload' ) : null;

//load backend requirements
// setup autoloading for classes
$format	= JRequest::getCmd('format','html');
PayplansHelperLoader::addAutoLoadViews(PAYPLANS_PATH_VIEW_ADMIN, 		$format, 'Payplansadmin');
PayplansHelperLoader::addAutoLoadFolder(PAYPLANS_PATH_CONTROLLER_ADMIN,	'Controller',	 'Payplansadmin');

JDEBUG ? $_PROFILER->mark( 'PayPlans-Backend-After-autoload' ) : null;

//load language file
$filename = 'com_payplans';
$language = JFactory::getLanguage();
$language->load($filename, JPATH_ADMINISTRATOR);
$language->load($filename.'_override', JPATH_ADMINISTRATOR);