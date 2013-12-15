<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	payplans Installer
* @contact 		payplans@readybytes.in
*/

// no direct access
defined('_JEXEC') or die();

if(!defined('DS')){
	define('DS', DIRECTORY_SEPARATOR);
}


require_once dirname(__FILE__).DS.'defines.php';

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_ppinstaller')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

$controller	= new PpinstallerController();
$controller->execute(JRequest::getCmd('task','display'));
$controller->redirect();
