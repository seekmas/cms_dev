<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

//for admin acl
if (!JFactory::getUser()->authorise('core.manage', 'com_payplans'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

//If Payplans System Plugin disable then do nothing 
$app = JFactory::getApplication();
$app->JComponentTitle = 'Payplans'; // Set component title due to template issue
$state = JPluginHelper::isEnabled('system','payplans');
if(!$state){
	$app->enqueueMessage(JText::_('Payplans System Plugin Disabled'));
	return true;
}


require_once  dirname(__FILE__).DS.'includes'.DS.'includes.php';

//now decide what to do
$view	= JString::strtolower(JRequest::getCmd('view', 		'dashboard'));
$task 	= JString::strtolower(JRequest::getCmd('task'));
$format	= JString::strtolower(JRequest::getCmd('format',	'html'));
$isAjax	=	JRequest::getBool('isAjax',false);
if($isAjax){
	$ajaxResponse	=	XiFactory::getAjaxResponse();
}

// now we need to create a object of proper controller
$args	= array();

$argsView 			= JString::strtolower($view);
$argController		= JString::strtolower($view);
$argTask 			= JString::strtolower($task);
$argFormat 			= JString::strtolower($format);

$args['view'] 			= & $argsView;
$args['controller']		= & $argController;
$args['task'] 			= & $argTask;
$args['format'] 		= & $argFormat;

// trigger apps, so that they can override the behaviour
// if somebody overrided it, then they must overwrite $args['controllerClass']
// in this case they must include the file, where class is defined
$results  =	PayplansHelperEvent::trigger('onPayplansControllerCreation', $args);

//we have setup autoloading for controller classes
//perform the task now
$controllerClass = $args['controller'];
$controller = XiFactory::getInstance($controllerClass, 'controller', 'Payplansadmin');
$controller->execute($task);

//trigger system end event
XiHelperPlugin::trigger('onPayplansSystemEnd');

if($isAjax){
	XiFactory::getAjaxResponse()->sendResponse();
}

$controller->redirect();
