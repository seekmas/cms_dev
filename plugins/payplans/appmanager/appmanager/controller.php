<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Prodiscount
* @contact 		payplans@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();

class PayplansadminControllerAppmanager extends XiController
{
	protected	$_defaultOrderingDirection = 'ASC';

	// No model exist
	function getModel($name = '', $prefix = '', $config = array())
	{
		return null;
	}
	
	public function uninstall($eid = 0)
	{
		$eid	= JRequest::getVar('eid', 0);
		//XITODO : if eid is not set, what to do
		
		$confirm = JRequest::getVar('confirm', 0);
		if(!$confirm){
			$this->setTemplate('uninstall_confirm');
			return true;
		}
		
		// un-install the extension
		$result = PayplansHelperAppmanager::uninstall($eid);
		
		$tpl = 'uninstall_success';
		$response = +1;
		if(!$result){
			$tpl = 'uninstall_error';
			$response = -1;
		}
		
		//tracking of uninstallation is done here
		XiFactory::getAjaxResponse()->addScriptCall('payplans.plg.appmanager.tracking',JRequest::getVar('appName',''),JRequest::getVar('appType',''),JRequest::getVar('extension_type', 'plg'),JRequest::getVar('client_id', '0'),$response);
	
		$this->setTemplate($tpl);

		return true;
	}	
	
	public function install()
	{
		$app_element 	= JRequest::getVar('app_element', '');
		$app_folder 	= JRequest::getVar('app_folder', '');
		$extension_type = JRequest::getVar('extension_type','plg');
		$client_id 		= JRequest::getVar('client_id',0);
		
		if(empty($app_element) && empty($app_folder)){
			// XITODO : show the proper message
			return false;
		}		
		
		$json_response = PayplansHelperAppmanager::install($app_folder, $app_element, $extension_type, $client_id);
		
		//IMP :: handel here when uninstallation fetch the extension id to uninstall do not use plugin only
		$eid = PayplansHelperAppmanager::fetchInstalledExtensions($app_folder, $app_element, $extension_type, $client_id);
		
		$url = XiRoute::_('index.php?option=com_payplans&view=appmanager&task=uninstall&eid='.$eid.'&appType='.$app_folder.'&appName='.$app_element.'&extension_type='.$extension_type.'&client_id='.$client_id);
		
		$ajax_response = PayplansFactory::getAjaxResponse();
		$ajax_response->addScriptCall('payplans.plg.appmanager.install_response', $app_folder, $app_element, $json_response,$url);
		$ajax_response->sendResponse();
	}
	
	function credential()
	{
		$action = JRequest::getVar('action', 'view');
		if($action == 'view'){
			$this->setTemplate(__FUNCTION__.'_view');
		}
		
		if($action === 'set'){
			$args  = $this->_getArgs();
			$response = PayplansHelperAppmanager::setCredential($args['username'], $args['password']);
			$response = json_decode($response);
			
			$ajax_response = XiFactory::getAjaxResponse();

			if($response->response_code == PayplansHelperAppmanager::SUCCESS){			
				$ajax_response->addScriptCall('payplans.plg.appmanager.show_success_message');
			}
			else{
				$ajax_response->addScriptCall('payplans.jQuery(".pp-appmanager-credential-err").html', XiText::_('PLG_PAYPLANS_APPMANAGER_JS_MANAGE_ERROR_CODE_'.$response->error_code));
				$ajax_response->addScriptCall('payplans.jQuery(".processing-request").removeClass', 'loading');
			}

			$ajax_response->sendResponse();	
		}
		
		return true;
	}

	//to hide the description of app manager
	public function description()
	{
		$action = JRequest::getVar('action', 'hide');
		if($action == 'hide'){
			$model = PayplansFactory::getInstance('config','model');
			$model->save(array('show_appmanager_description'=>'hide'));
		}
	}

	//to hide upgrade message
	public function removeUpgradeMessage()
	{
		$action = JRequest::getVar('action', 'hide');
		if($action == 'hide'){
			$model = PayplansFactory::getInstance('config','model');
			$model->save(array('expert_show_upgrade_message'=>0));
		}
	}
	
	function manualupdatecache()
	{
		PayplansHelperAppmanager::clearCache();
		$result = PayplansHelperAppmanager::updateCache();
		
		$ajax_response = XiFactory::getAjaxResponse();
		
		$ajax_response->addScriptCall('xi.ui.dialog.body', XiText::_('PLG_PAYPLANS_APPMANAGER_JS_MANAGE_ERROR_CODE_'.$result)); 
		$ajax_response->addScriptCall('xi.ui.dialog.title', XiText::_('PLG_PAYPLANS_APPMANAGER_TOOLBAR_MANUALUPDATECACHE'));
		$ajax_response->addScriptCall('xi.ui.dialog.autoclose',3000);
		
		$ajax_response->sendResponse();
		return true;
	}
}
