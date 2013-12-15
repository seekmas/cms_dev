<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

// if already loaded do not load
if(defined('PAYPLANS_LOADED')){
	return;
}

define('XI_PATH_JOOMLA_ROOT', dirname(dirname(dirname(dirname(__FILE__)))));

// Load XiFramework
if(defined('XI_FRAMEWORK_LOADED')==false){
	require_once dirname(dirname(__FILE__)).DS.'xiframework'.DS.'includes.php';
}

//load basic defines
require_once dirname(__FILE__).DS.'defines.php'	;
require_once PAYPLANS_PATH_HELPER.DS.'loader.php'	;

//$language->load($filename.'_override', JPATH_SITE);

// System profiler
if (JDEBUG) {
	jimport( 'joomla.error.profiler' );
	$_PROFILER = JProfiler::getInstance( 'Application' );
}
JDEBUG ? $_PROFILER->mark( 'payplans-Frontend-Before-Autoload' ) : null;

//autoload helpers
PayplansHelperLoader::addAutoLoadFolder(PAYPLANS_PATH_HELPER,	'Helper',	'Payplans');

//autoload library
PayplansHelperLoader::addAutoLoadFolder(PAYPLANS_PATH_LIBRARY.DS.'model',	'Model');
PayplansHelperLoader::addAutoLoadFolder(PAYPLANS_PATH_LIBRARY.DS.'model',	'Modelform', 'Payplans');
PayplansHelperLoader::addAutoLoadFolder(PAYPLANS_PATH_LIBRARY.DS.'table',	'Table');

PayplansHelperLoader::addAutoLoadFolder(PAYPLANS_PATH_LIBRARY.DS.'lib',		'');
PayplansHelperLoader::addAutoLoadFolder(PAYPLANS_PATH_SETUP,				'Setup');
PayplansHelperLoader::addAutoLoadFolder(PAYPLANS_PATH_INTERFACE,			'Iface');
PayplansHelperLoader::addAutoLoadFolder(PAYPLANS_PATH_EVENT, 'Event');
PayplansHelperLoader::addAutoLoadFolder(PAYPLANS_PATH_FORMATTER,			'formatter');
// setup autoloading for classes
$format	= JRequest::getCmd('format','html');
PayplansHelperLoader::addAutoLoadViews(PAYPLANS_PATH_VIEW, $format, 'Payplanssite');
PayplansHelperLoader::addAutoLoadFolder(PAYPLANS_PATH_CONTROLLER, 'Controller', 'Payplanssite');

PayplansHelperLoader::addAutoLoadFolder(PAYPLANS_PATH_ELEMENTS, 'Element', 'J');
//element file loaded for fields, as we have class there
PayplansHelperLoader::addAutoLoadFolder(PAYPLANS_PATH_ELEMENTS, 'FormField', 'J');

PayplansHelperLoader::addAutoLoadFolder(PAYPLANS_PATH_COMPONENT_SITE.DS.'payplans', '');

//Other folders should be added here as we need those
//PayplansHelperLoader::addAutoLoadFolder(PAYPLANS_PATH_COMPONENT_SITE.DS.'libraries'.DS.'payments', 'XiModel');

JDEBUG ? $_PROFILER->mark( 'payplans-Frontend-After-Autoload' ) : null;

//Include admin classes also
require_once PAYPLANS_PATH_COMPONENT_ADMIN.DS.'includes'.DS.'includes.php';

//include basic required files
jimport('joomla.html.pane');

//Load language file for plugins
//loading this before com_payplans.ini to overcome the problem of translation
$filename = 'com_payplans_plugins';
$language = JFactory::getLanguage();
$language->load($filename, JPATH_SITE);

//load language file
$filename = 'com_payplans';
$language->load($filename, JPATH_SITE);

// Framework Loaded
define ('PAYPLANS_LOADED', true);
require_once  dirname(__FILE__).DS.'api.php';
