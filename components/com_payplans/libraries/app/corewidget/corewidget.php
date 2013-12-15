<?php

/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		Payplans
* @subpackage	Discount
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansAppCorewidget extends PayplansApp
{
	protected $_location	= __FILE__;
	
	public function isApplicable($refObject = null, $eventName='')
	{   
		// always applicable on frontend dashboard
		if($refObject instanceof PayplanssiteViewDashboard 
				&& $eventName === 'onPayplansViewBeforeRender'){
			return true;
		}
		
		return parent::isApplicable($refObject, $eventName);
	}
	
	public function onPayplansViewBeforeRender(XiView $view, $task)
	{   
		if($task != 'frontview'){
			return '';
		}
		// get params
		$apptype 	= $this->getAppParam('app_type');
	    $title    	= $this->getAppParam('widget_title');
		$position 	= $this->getAppParam('widget_position');
		$suffix 	= $this->getAppParam('widget_class_suffix');
		
		
		$app 		= PayplansApp::getInstance(0,$apptype);
		$app_purpose   = PayplansHelperApp::getAvailableApps($apptype);
		
		
		// app not available
		if(!$app_purpose || method_exists($app, 'renderWidgetHtml')==false){
			return '';
		}
		
	    // create widget object first, so app can change any parameter if required
		$widget 	= new XiWidget();
		$widget->id($this->getId());
		$widget->setOption('title',$title);
		$widget->setOption('class_suffix',$suffix);
		
		// if output is empty
		$renderHtml = $app->renderWidgetHtml($widget);
		if(empty($renderHtml) || in_array($renderHtml , array(false,true), true)){
			return '';
		}
		

        $widget->html($renderHtml);
       
        // render widget
        return array($position => $widget->draw());
	}
}
