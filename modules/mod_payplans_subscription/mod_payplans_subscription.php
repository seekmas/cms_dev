<?php 
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

// If Payplans System Plugin disabled then do nothing
$systemPlugin = JPluginHelper::isEnabled('system','payplans');
if(!$systemPlugin){
	return true;
}

require_once JPATH_ROOT.DS.'components'.DS.'com_payplans'.DS.'includes'.DS.'includes.php';

$userId = XiFactory::getUser()->id;
if($userId == 0)
{
	return true;
}

$user  		   = PayplansUser::getInstance($userId);
$NumRecord     = $params->get('no_subscription');
$renew 		   = $params->get('allow_renewal_link');
$subscriptions = array();
$status 	   = is_array($params->get('subscribe_status')) ? $params->get('subscribe_status') : array($params->get('subscribe_status'));

foreach($status as $items){
	$subscriptions = array_merge($subscriptions, $user->getSubscriptions($items));
}
		if( JString::trim($params->get('date_format_text')) != "")
			$dateFormat = $params->get('date_format_text');
		else 
			$dateFormat = $params->get('date_format_list');

// include the helper file
require_once(dirname(__FILE__).DS.'helper.php');			

require_once JModuleHelper::getLayoutPath('mod_payplans_subscription', 'default');
