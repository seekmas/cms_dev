<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansadminViewPlan extends XiView
{
	function edit($tpl=null,$itemId=null)
	{
		$itemId = ($itemId === null) ? $this->getModel()->getState('id') : $itemId;
		$editor 	= XiFactory::getEditor();

		$plan	= PayplansPlan::getInstance( $itemId); 
		
		//display all core apps
		$apps = XiFactory::getInstance('app','model')->loadRecords();
		$coreApp = array();
		foreach($apps as $app){
			$appInstance = PayplansApp::getInstance($app->app_id);
			
			if($appInstance === FALSE){
				continue;
			}
			
			$coreParam = $appInstance->getParam('applyAll');
			if($coreParam==1){
				array_push($coreApp, $app->title);
			}	
		}
		$logRecords	= XiFactory::getInstance('log', 'model')
								->loadRecords(array('object_id'=>$itemId, 'class'=>'PayplansPlan'));
                
        $form = $plan->getModelform()->getForm($plan);
		$this->assign('form', $form );
		$this->assign('editor', $editor);
		$this->assign('plan', $plan);
		$this->assign('core_apps', $coreApp);
		$this->assign('log_records', $logRecords);

		return true;
	}

	
	public function recurrencevalidation()
	{
		$this->_setAjaxWinTitle(XiText::_('COM_PAYPLANS_PLAN_EDIT_RECURRENCE_VALIDATION_TITLE'));
		$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_AJAX_CANCEL_BUTTON'),'xi.ui.dialog.close();');

		$this->_setAjaxWinAction();
		$this->_setAjaxWinHeight('350');
		$this->_setAjaxWinWidth('800');
		
		$planId =  $this->getModel()->getId();
		$plan = PayplansPlan::getInstance($planId);
		$expTime = $plan->getExpiration();

		// get empty instances all payment type apps
		$apps 	= PayplansHelperApp::getPurposeApps('payment');
		
		$time = array();
		foreach($apps as $app){
			$instance = PayplansApp::getInstance(null, $app);
			if(method_exists($instance, 'getRecurrenceTime')){
				$time[$instance->getName()] = $instance->getRecurrenceTime($expTime);
			}
		}
		// get appnames
		$this->assign('appnames', PayplansHelperApp::getXml());
		$this->assign('time', $time);
		
		$this->setTpl('recurrence_validation');
		
		return true;
	}
}
