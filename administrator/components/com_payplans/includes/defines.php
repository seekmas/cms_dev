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

//define site path
require_once JPATH_SITE.DS.'components'.DS.'com_payplans'.DS.'includes'.DS.'defines.php';

// If file is already included
if(defined('PAYPLANS_DEFINE_ADMIN'))
	return;

define('PAYPLANS_DEFINE_ADMIN', true);

//define admin path
//define('PAYPLANS_PATH_COMPONENT_ADMIN', dirname(dirname(__FILE__)));

//all folder paths
define('PAYPLANS_PATH_CONTROLLER_ADMIN',	PAYPLANS_PATH_COMPONENT_ADMIN.DS.'controllers');
define('PAYPLANS_PATH_VIEW_ADMIN',			PAYPLANS_PATH_COMPONENT_ADMIN.DS.'views');
define('PAYPLANS_PATH_INCLUDE_ADMIN',		PAYPLANS_PATH_COMPONENT_ADMIN.DS.'includes');
define('PAYPLANS_PATH_TEMPLATE_ADMIN',		PAYPLANS_PATH_COMPONENT_ADMIN.DS.'templates');
define('PAYPLANS_PATH_INSTALLER_ADMIN',		PAYPLANS_PATH_COMPONENT_ADMIN.DS.'installer');

// define constants for statistics
define('PAYPLANS_STATISCTICS_DURATION_DAILY', 	101);
define('PAYPLANS_STATISCTICS_DURATION_WEEKLY', 	102);
define('PAYPLANS_STATISCTICS_DURATION_MONTHLY', 103);
define('PAYPLANS_STATISCTICS_DURATION_YEARLY', 	104);
define('PAYPLANS_STATISCTICS_DURATION_CUSTOM', 	105);
