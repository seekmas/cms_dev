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
if(defined('_JEXEC')===false) die();?>

<?php

PayplansHtml::stylesheet(PAYPLANS_PATH_MEDIA.'/css/payplans.css');
//add css for j25
if(PAYPLANS_JVERSION_FAMILY === '16'){
	//PayplansHtml::stylesheet(PAYPLANS_PATH_MEDIA.'/css/payplans-j25.css');
	PayplansHtml::stylesheet(dirname(__FILE__).'/_media/css/admin-j25.css');
}
else{
	PayplansHtml::stylesheet(dirname(__FILE__).'/_media/css/admin-j35.css');
}

PayplansHtml::stylesheet(dirname(__FILE__).'/_media/css/admin.css');
PayplansHtml::script(dirname(__FILE__).'/_media/js/admin.js');
