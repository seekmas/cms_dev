<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class  PayplansAppXiprofiletype extends PayplansApp
{
	protected $_location	= __FILE__;
	
	public function isApplicable($refObject = null, $eventName='')
	{
		if($eventName === 'onPayplansAccessCheck'){
			return true;
		}

		return parent::isApplicable($refObject, $eventName);
	}

	public function onPayplansSubscriptionAfterSave($prev, $new)
	{
		// 	XITODO : raise error when file does not exists
		$filename = JPATH_ROOT.DS.'components'.DS.'com_xipt'.DS.'api.xipt.php';
		if(!file_exists($filename)){
			return true;
		}
		require_once $filename;
		// check whether jspt is integrated with payplans or not
		$subsIntegrate     = XiptAPI::getGlobalConfig('subscription_integrate', 0);
		$integrateWith     = XiptAPI::getGlobalConfig('integrate_with', 0);
		if($subsIntegrate == 0 || $integrateWith == 'aec'){
			return true;
		}
		// no need to trigger if previous and current state is same
		if($prev != null && $prev->getStatus() == $new->getStatus()){
			return true;
		}

		$newstatus  = $new->getStatus();
		$userid		= $new->getBuyer();

		$filename = JPATH_ROOT.DS.'components'.DS.'com_xipt'.DS.'api.xipt.php';
		if(!file_exists($filename)){
			return true;
		}
		require_once $filename;
		
		//if subscription is active
		if($newstatus == PayplansStatus::SUBSCRIPTION_ACTIVE){
			$xiprofiletype  = $this->getAppParam('xiprofiletypeOnActive', 0);
			return $this->_setXiprofiletype($userid, $xiprofiletype);
		}
		
		//if subscription is hold
		if($newstatus == PayplansStatus::SUBSCRIPTION_HOLD){
			$xiprofiletype  = $this->getAppParam('xiprofiletypeOnHold', 0);
			return $this->_setXiprofiletype($userid, $xiprofiletype);
		}
		
		// if subscription is expire
		if($newstatus == PayplansStatus::SUBSCRIPTION_EXPIRED){
			$xiprofiletype  = $this->getAppParam('xiprofiletypeOnExpire', 0);
			return $this->_setXiprofiletype($userid, $xiprofiletype);
		}

		return true;
	}

	protected function _setXiprofiletype($userId, $xiprofiletype)
	{
		//check if there is any profiletype to set
		if(empty($xiprofiletype)){
				return true;
		}

		// set profile type in session
		$session = XiFactory::getSession();
		$session->set('SELECTED_PROFILETYPE_ID', $xiprofiletype, 'XIPT');

		return XiptAPI::setUserProfiletype($userId, $xiprofiletype);
	}
	
	function onPayplansPlanAfterSelection($plansId, $planContoller)
	{	
		$filename = JPATH_ROOT.DS.'components'.DS.'com_xipt'.DS.'api.xipt.php';
		if(!file_exists($filename)){
			return true;
		}
		require_once $filename;
		
		// check whether jspt is integrated with payplans or not
		$subsIntegrate     = XiptAPI::getGlobalConfig('subscription_integrate', 0);
		$integrateWith     = XiptAPI::getGlobalConfig('integrate_with', 0);
		if($subsIntegrate == 0 || $integrateWith == 'aec'){
			return true;
		}
		// get global config of JSPT
		//if show_ptype_during_reg is true, then we do not need to do anything
		if(XiptAPI::getGlobalConfig('show_ptype_during_reg', true) == true){
			return true;
		}
		
		// This is for registration process, so consider only subscription active status 
		if(!$this->getAppParam('xiprofiletypeOnActive')){
			return true;
		}
		// in other case we need to get the xi profile type attached with this app
		// set thi ptype in session, so that at registration time it will be available
		$session = XiFactory::getSession();
		$session->set('SELECTED_PROFILETYPE_ID', $this->getAppParam('xiprofiletypeOnActive', XiptAPI::getDefaultProfiletype()), 'XIPT');
		
		return true;
	}
	
	public function onPayplansAccessCheck(PayplansUser $user)
	{
		if(!$user->getId()){
			return true;
		}
		
		// Is user Admin/SuperAdmin
		if($user->isAdmin()){
			return true;
		}
		
		// should we block user to change ptype 
		if($this->getAppParam('block_ptype_change', true) == false){
			return true;
		}

		$option = JRequest::getVar('option', false);
		$task 	= JRequest::getVar('task', false);
		$view 	= JRequest::getVar('view', false);
		
		if($option !== 'com_community' || $view !== 'profile' || $task !== 'edit'){
			return true;
		}

		$fieldid = "field".$this->_getJSProfileFieldId();
		$profiletype = JRequest::getVar($fieldid, 0);
	    $paid_profiletype = $this->getAppParam('xiprofiletypeOnActive', 0);
		
	    //when profiletype is not posted or when app does not 
	    //have any profiletype attached with its active status then do nothing
	    if(empty($profiletype) || empty($paid_profiletype)){
	    	return true;
	    }
	    
		if((JRequest::getVar('action', 'BLANK', 'POST') == 'save')  && ($profiletype == $paid_profiletype)){
			
			$userplans = $user->getPlans();
					
			// when user have no active subscription then return to plan page
			if(empty($userplans)){
				$msg = XiText::_('COM_PAYPLANS_APP_XIPROFILETYPE_UPDATE_PLAN_TO_CHANGE_DESC');
				XiFactory::getApplication()->redirect(XiRoute::_('index.php?option=com_payplans&view=plan&task=subscribe'), $msg);
			}
				
			// when user have no active subscription for the required plan
			if($this->getParam('applyAll',false) == false){
				$appplans = $this->getPlans();
				$plans = array_intersect($appplans, $userplans);
	
				if(count($plans) <= 0){
					$msg = XiText::_('COM_PAYPLANS_APP_XIPROFILETYPE_UPDATE_PLAN_TO_CHANGE_DESC');
					XiFactory::getApplication()->redirect(XiRoute::_('index.php?option=com_payplans&view=plan&task=subscribe'), $msg);
				}
			}
		}

		return true;
	}
	
	private function _getJSProfileFieldId()
	{
		$db = XiFactory::getDBO();
		
		$query = 'SELECT `id` '
			 	. ' FROM #__community_fields'
			 	. ' WHERE `fieldcode` = "XIPT_PROFILETYPE"'
			 	;
 		$db->setQuery( $query );
	 	return $db->loadResult();
	}
}

class PayplansAppXiprofiletypeFormatter extends PayplansAppFormatter
{
	// get rules
	function getVarFormatter()
	{
		$rules = array('_appplans'    => array('formatter'=> 'PayplansAppFormatter',
										       'function' => 'getAppPlans'),
						'app_params'  => array('formatter'=> 'PayplansAppXiprofiletypeFormatter',
										       'function' => 'getXiProfiletypes'));
		return $rules;
	}
	
	// replace profiletype ids with name
	function getXiProfiletypes($key,$value,$data)
	{
		$file = JPATH_ROOT.DS.'components'.DS.'com_xipt'.DS.'api.xipt.php';
		// do nothing if jspt is not installed
		if(!JFile::exists($file)){
			return false;
		}
		require_once JPATH_ROOT.DS.'components'.DS.'com_xipt'.DS.'api.xipt.php';
		$profiletypes = XiptAPI::getProfiletypeInfo();
		$params       = PayplansHelperParam::iniToArray($value);
		$params['xiprofiletypeOnActive'] = $profiletypes[$params['xiprofiletypeOnActive']]->name;
		$params['xiprofiletypeOnHold']   = $profiletypes[$params['xiprofiletypeOnHold']]->name;
		$params['xiprofiletypeOnExpire'] = $profiletypes[$params['xiprofiletypeOnExpire']]->name;
		$value = PayplansHelperParam::arrayToIni($params);
		
	}
}