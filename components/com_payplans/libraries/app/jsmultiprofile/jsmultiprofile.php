<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class  PayplansAppJsmultiprofile extends PayplansApp
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
		// no need to trigger if previous and current state is same
		if($prev != null && $prev->getStatus() == $new->getStatus()){
			return true;
		}

		$newstatus  = $new->getStatus();
		$userid		= $new->getBuyer();
		
		//if subscription is active
		if($newstatus == PayplansStatus::SUBSCRIPTION_ACTIVE){
			$jsmultiprofile = $this->getAppParam('jsmultiprofileOnActive', 0);
			return $this->_setJsmultiprofile($userid, $jsmultiprofile);
		}
		
		//if subscription is hold
		if($newstatus == PayplansStatus::SUBSCRIPTION_HOLD){
			$jsmultiprofile = $this->getAppParam('jsmultiprofileOnHold', 0);
			return $this->_setJsmultiprofile($userid, $jsmultiprofile);
		}
		
		// if subscription is expire
		if($newstatus == PayplansStatus::SUBSCRIPTION_EXPIRED){
			$jsmultiprofile = $this->getAppParam('jsmultiprofileOnExpire', 0);
			return $this->_setJsmultiprofile($userid, $jsmultiprofile);
		}

		return true;
	}

	protected function _setJsmultiprofile($userId, $jsmultiprofile)
	{
		//check if there is any multiprofile to set
		if(empty($jsmultiprofile)){
				return true;
		}
			
		require_once(JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');
		
		$user = CFactory::getUser($userId);
		$user->set('_profile_id', $jsmultiprofile);
		return $user->save();
	}
	
	// to restrict user to change profile type from front-end
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
		
		if($option !== 'com_community' || $view !== 'multiprofile' || $task !== 'changeprofile'){
			return true;
		}

		$profiletype = JRequest::getVar('profileType', 0);
		$paid_profiletype = $this->getAppParam('jsmultiprofileOnActive', 0);
		
		//when profiletype is not posted or when app does not 
	    //have any profiletype attached with its active status then do nothing
	    if(empty($profiletype) || empty($paid_profiletype)){
	    	return true;
	    }
		
		//perform the check when data has been submitted
		if((JRequest::getVar('submit', 'BLANK', 'POST') != 'BLANK') && ($profiletype == $paid_profiletype)){
				$userplans = $user->getPlans();
				
			// when user have no active subscription then return to plan page
			if(empty($userplans)){
				$msg = XiText::_('COM_PAYPLANS_APP_JSPROFILETYPE_UPDATE_PLAN_TO_CHANGE_DESC');
				XiFactory::getApplication()->redirect(XiRoute::_('index.php?option=com_payplans&view=plan&task=subscribe'), $msg);
			}
			
			// when user have no active subscription for the required plan
			if($this->getParam('applyAll',false) == false){
				$appplans = $this->getPlans();
				$plans = array_intersect($appplans, $userplans);

				if(count($plans) <= 0){
					$msg = XiText::_('COM_PAYPLANS_APP_JSPROFILETYPE_UPDATE_PLAN_TO_CHANGE_DESC');
					XiFactory::getApplication()->redirect(XiRoute::_('index.php?option=com_payplans&view=plan&task=subscribe'), $msg);
				}
			}
		}

		return true;
	}
	
	public static function getJsprofiletype()
	{
		// if table does not exists then return false
		if(!XiHelperTable::isTableExist('#__community_profiles')){
			return false;
		}

		$db = XiFactory::getDBO();
		$sql = ' SELECT * FROM '.$db->quoteName('#__community_profiles');
		$db->setQuery($sql);
		return $db->loadObjectList('id');
	}
}

class PayplansAppJsmultiprofileFormatter extends PayplansAppFormatter
{
	function getVarFormatter()
	{
		$rules = array('_appplans'       => array('formatter'=> 'PayplansAppFormatter',
										       'function' => 'getAppPlans'),
					   'app_params'      => array('formatter'=> 'PayplansAppJsmultiprofileFormatter',
										       'function' => 'getJsMultiprofiles'));
		return $rules;
	}
	
	// replace id with js multiprofiletype names
	function getJsMultiprofiles($key,$value,$data)
	{
		if(JFolder::exists(JPATH_ROOT.DS.'components'.DS.'com_community') == false){
			return false;
		}
		$jsMultiprofiles = PayplansAppJsmultiprofile::getJsprofiletype();
		$params          = PayplansHelperParam::iniToArray($value);
		$params['jsmultiprofileOnActive'] = $jsMultiprofiles[$params['jsmultiprofileOnActive']]->name;
		$params['jsmultiprofileOnHold']   = $jsMultiprofiles[$params['jsmultiprofileOnHold']]->name;
		$params['jsmultiprofileOnExpire'] = $jsMultiprofiles[$params['jsmultiprofileOnExpire']]->name;
		$value = PayplansHelperParam::arrayToIni($params);
	}
	
}