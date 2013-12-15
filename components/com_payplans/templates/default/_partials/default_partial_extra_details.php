<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();

$config = XiFactory::getConfig();
if(isset($config->add_token) && !empty($config->add_token))
{
	$customContent = nl2br($config->add_token);
	echo PayplansFactory::getRewriter()->rewrite($customContent, $invoice);
}
