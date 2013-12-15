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

// If Payplans System Plugin disabled then do nothing
$state = JPluginHelper::isEnabled('system','payplans');
if(!$state){
	return true;
}

//XiTODO: Remove DS
if(!defined('DS')){
	define('DS', DIRECTORY_SEPARATOR);
}

require_once  dirname(__FILE__).DS.'includes'.DS.'includes.php';

//IMP: Do not pick default task, let controller decide default task
$task 	= JRequest::getCmd('task');
$view	= JRequest::getCmd('view', 		'plan');
$format	= JRequest::getCmd('format',	'html');
$isAjax	= JRequest::getBool('isAjax',	false);

// now we need to create a object of proper controller
$args	= array();
$argsView 			= JString::strtolower($view);
$argController		= JString::strtolower($view);
$argTask 			= JString::strtolower($task);
$argFormat 			= JString::strtolower($format);

$args['view'] 			=  &$argsView;
$args['controller']		=  &$argController;
$args['task'] 			=  &$argTask;
$args['format'] 		=  &$argFormat;

// trigger apps, so that they can override the behaviour
// if somebody overrided it, then they must overwrite $args['controllerClass']
// in this case they must include the file, where class is defined
$results  =	PayplansHelperEvent::trigger('onPayplansControllerCreation', $args);

//we have setup autoloading for controller classes
//perform the task now
$controllerClass = $args['controller'];
$controller = XiFactory::getInstance($controllerClass, 'controller', 'Payplanssite');
$controller->execute($task);

// A simple way, by which we can exit after controller request.
if(defined('PAYPLANS_EXIT')){
	exit(PAYPLANS_EXIT);
}

//trigger system end event
XiHelperPlugin::trigger('onPayplansSystemEnd');

//if ajax call, send response
if($isAjax){
	XiFactory::getAjaxResponse()->sendResponse();
}

//redirect request if required
$controller->redirect();
