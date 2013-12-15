<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

// If file is already included
if(defined('PAYPLANS_DEFINE_SITE'))
	return;

define('PAYPLANS_DEFINE_SITE', true);

// define the joomla version
$version = new JVersion();

// FAMILY must remain same untill there is a major change
if($version->RELEASE >='3.0'){
	define('PAYPLANS_JVERSION_FAMILY', '35');
}
else{
	define('PAYPLANS_JVERSION_FAMILY', '16');
}

define('PAYPLANS_COM_USER', 'com_users');
define('PAYPLANS_COM_USER_VIEW_REGISTER', 'registration');

//XiTODO: Remove it from everywhere
define('PAYPLANS_JVERSION_15', false);

if($version->RELEASE==='1.6'){
	define('PAYPLANS_JVERSION_16', true);
	define('PAYPLANS_JVERSION_17', false);
	define('PAYPLANS_JVERSION_25', false);
	define('PAYPLANS_JVERSION_30', false);
}

if($version->RELEASE==='1.7'){
	define('PAYPLANS_JVERSION_16', false);
	define('PAYPLANS_JVERSION_17', true);
	define('PAYPLANS_JVERSION_25', false);
	define('PAYPLANS_JVERSION_30', false);
}

if($version->RELEASE==='2.5'){
    define('PAYPLANS_JVERSION_16', false);
	define('PAYPLANS_JVERSION_17', false);
	define('PAYPLANS_JVERSION_25', true);
	define('PAYPLANS_JVERSION_30', false);
}

if($version->RELEASE >='3.0'){
    define('PAYPLANS_JVERSION_16', false);
	define('PAYPLANS_JVERSION_17', false);
	define('PAYPLANS_JVERSION_25', false);
	define('PAYPLANS_JVERSION_30', true);
}

define('XI_COMPONENT_NAME','payplans');

define('PAYPLANS_VERSION', '3.0.6');
define('PAYPLANS_REVISION','4045');

define('PAYPLANS_PATH_COMPONENT_SITE', dirname(dirname(__FILE__)));
define('PAYPLANS_PATH_COMPONENT_ADMIN', JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_payplans');

//all folder paths
define('PAYPLANS_PATH_CONTROLLER',	PAYPLANS_PATH_COMPONENT_SITE.DS.'controllers');
define('PAYPLANS_PATH_VIEW',		PAYPLANS_PATH_COMPONENT_SITE.DS.'views');
define('PAYPLANS_PATH_INCLUDE',		PAYPLANS_PATH_COMPONENT_SITE.DS.'includes');
define('PAYPLANS_PATH_TEMPLATE',	PAYPLANS_PATH_COMPONENT_SITE.DS.'templates');
define('PAYPLANS_PATH_LIBRARY',		PAYPLANS_PATH_COMPONENT_SITE.DS.'libraries');
define('PAYPLANS_PATH_HELPER',		PAYPLANS_PATH_COMPONENT_SITE.DS.'helpers');
define('PAYPLANS_PATH_MEDIA',		PAYPLANS_PATH_COMPONENT_SITE.DS.'media');
define('PAYPLANS_PATH_ELEMENTS',	PAYPLANS_PATH_COMPONENT_SITE.DS.'elements');
define('PAYPLANS_PATH_INTERFACE',	PAYPLANS_PATH_LIBRARY.DS.'iface');
define('PAYPLANS_PATH_APPS',		PAYPLANS_PATH_LIBRARY.DS.'app');
define('PAYPLANS_PATH_EVENT',		PAYPLANS_PATH_LIBRARY.DS.'event');
define('PAYPLANS_PATH_SETUP',		PAYPLANS_PATH_LIBRARY.DS.'setup');
define('PAYPLANS_PATH_XML',		PAYPLANS_PATH_LIBRARY.DS.'model'.DS.'xml');
define('PAYPLANS_PATH_FORMATTER',	PAYPLANS_PATH_LIBRARY.DS.'formatter');
define('PAYPLANS_PATH_THEMES',		PAYPLANS_PATH_MEDIA.DS.'themes');

//names which will not vary
define('PAYPLANS_COMPONENT_NAME','com_payplans');

// URL def's
define('PAYPLANS_URL_MEDIA',	"components/com_payplans/media");
define('PAYPLANS_URL_TEMPLATE',	"components/com_payplans/templates");


define('PAYPLANS_CONST_NONE', 0);
define('PAYPLANS_CONST_ALL', -1);
define('PAYPLANS_CONST_ANY', -2);
define('PAYPLANS_EUVAT_NOT_APPLICABLE', 'not applicable');

// Configuration Keys
define('PAYPLANS_CONFIG_BASIC', 	1);
define('PAYPLANS_CONFIG_ADVANCE', 	2);
define('PAYPLANS_CONFIG_INVOICE',   3);
define('PAYPLANS_CONFIG_EXPERT',    4);
define('PAYPLANS_CONFIG_CUSTOMIZATION', 5);
define('PAYPLANS_CONFIG_CRONFREQUENCY_DIVIDER', 5);

define('PAYPLANS_INSTANCE_REQUIRE', true);

// contans for subscrition type
define('PAYPLANS_SUBSCRIPTION_FIXED', 99);
define('PAYPLANS_RECURRING', 100);
define('PAYPLANS_RECURRING_TRIAL_1', 101);
define('PAYPLANS_RECURRING_TRIAL_2', 102);
	
define('PAYPLANS_AJAX_REQUEST', JRequest::getBool('isAjax',	false));
define('PAYPLANS_UNDEFINED', 'UNDEFINED');
define('PAYPLANS_CRON_LOGS_COUNT', 8);

define('PAYPLANS_TEST_MODE_FIELDS',0);
