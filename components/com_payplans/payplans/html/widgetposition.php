<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHtmlWidgetposition
{
	static function edit($name, $value, $attr=null)
	{   
		$apps = PayplansHelperApp::getAvailableApps('Corewidget');
		$positions = array ('payplans-dashboard-right','payplans-dashboard-footer');
		$custom = array();
		foreach($apps as $app){
			 $custom[] = $app->getAppParam('widget_position');
		}
		
		$positions = array_merge($positions,$custom);
	    
	    $options  = array();
		foreach ($positions as $val){
			$options[$val] = PayplansHtml::_('select.option', $val, $val);
		}
		
		return PayplansHtml::_('combo.edit',  $options, $name, null, 'value', 'text', $value);
		
	}
}