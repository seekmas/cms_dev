<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class  PayplansAppJusertype extends PayplansApp
{
	protected $_location	= __FILE__;
	protected $_resource	= 'com_user.usergroup';

	public function onPayplansSubscriptionAfterSave($prev, $new)
	{
		return $this->_triggerJusertype($prev,$new);
	}


	protected function _triggerJusertype($prev, $new)
	{
		// no need to trigger if previous and current state is same
		if($prev != null && $prev->getStatus() == $new->getStatus()){
			return true;
		}

		$subid 		=  $new->getId();
		
		$usersConfig 		= JComponentHelper::getParams( 'com_users' );
		$defaultUserGroup	= $usersConfig->get('new_usertype');
		
		$active	    		= $this->getAppParam('jusertypeOnActive', 0);
		$hold 	    		= $this->getAppParam('jusertypeOnHold', 0);
		$expire				= $this->getAppParam('jusertypeOnExpire', 0);
		$removeFromDefault	= $this->getAppParam('removeFromDefault', 0);
		
		$newstatus  =  $new->getStatus();
		$userid		=  $new->getBuyer();
			
		$active	= (is_array($active)) ? $active : array($active);
		$hold	= (is_array($hold)) ? $hold : array($hold);
		$expire	= (is_array($expire)) ? $expire : array($expire);
		
		//if subscription is active
		if($newstatus == PayplansStatus::SUBSCRIPTION_ACTIVE){	

			$holdActiveDiff		= array_diff($hold, $active);
			$expireActiveDiff	= array_diff($expire, $active);
		
			$result = $this->_setJusertype($userid, $active, $subid);
			
			$this->_unsetJusertype($userid, $holdActiveDiff, $subid);
			$this->_unsetJusertype($userid, $expireActiveDiff, $subid);
			
			if($removeFromDefault && !in_array($defaultUserGroup, $active)){
				XiHelperJoomla::removeUserFromGroup($userid, $defaultUserGroup);
			}
			
			return $result;
		}
		
		//if subscription is hold			
		if($newstatus == PayplansStatus::SUBSCRIPTION_HOLD){
			
			$activeHoldDiff		= array_diff($active, $hold);
			$expireHoldDiff		= array_diff($expire, $hold);
			
			if($this->_isRequiredDefault($userid) && $hold[0] == null){
				$hold[0] = $defaultUserGroup;
			}
			
			$result = $this->_setJusertype($userid, $hold, $subid);
			
			$this->_unsetJusertype($userid, $activeHoldDiff, $subid);
			$this->_unsetJusertype($userid, $expireHoldDiff, $subid);
			
			return $result;
		}
		
		//if subscription is expire			
		if ($newstatus == PayplansStatus::SUBSCRIPTION_EXPIRED){
			
			$activeExpireDiff	= array_diff($active, $expire);
			$holdExpireDiff		= array_diff($hold, $expire);
			
			if($this->_isRequiredDefault($userid) && $expire[0] == null){
				$expire[0] = $defaultUserGroup;
			}
			
			$result = $this->_setJusertype($userid, $expire, $subid);
			
			$this->_unsetJusertype($userid, $activeExpireDiff, $subid);
			$this->_unsetJusertype($userid, $holdExpireDiff, $subid);
			
			return $result;
		}

		return true;
	}

	protected function _setJusertype($userid, $group, $subid)
	{
		if(!is_array($group)){
			return true;
		}
		
		jimport('joomla.user.helper');
		foreach ($group as $groupid){
			XiHelperJoomla::addUserToGroup($userid, $groupid);
			$this->_addToResource($subid, $userid, $groupid, $this->_resource);
		}

		return true;
	}
	
	protected function _unsetJusertype($userid, $jusertype, $subid)
	{
		if(!is_array($jusertype)){
			return true;
		}
		
		foreach ($jusertype as $group){
			if($this->_removeFromResource($subid, $userid, $group, $this->_resource)){
				XiHelperJoomla::removeUserFromGroup($userid, $group);
			}
		}
		return true;
	}
	
	//render Widget
	public function renderWidgetHtml()
	{
		//get user id
		$userid     = XiFactory::getUser()->id;
		
		// do nothing if user is not logged in
		if(empty($userid)){
			return '' ;
		}
		//get joomla Usertype
		$jusertype  = XiHelperJoomla::getJoomlaUserGroups($userid);
		if(empty($jusertype)){
			return '' ;
		}
		$this->assign('joomla_usertypes',$jusertype);
		$data = $this->_render('widgethtml');
	    return $data;
	}
	
	function getNameFromResourceValue($resource, $value)
	{
		// if its a different resource
		if($resource != $this->_resource){
			return false;
		}
		
		$groups = XiHelperJoomla::getJoomlaGroups();
		return $groups[$value]->name;
	}
	
	//check if need to assign default user type
	protected function _isRequiredDefault($userid)
	{
		$user		= PayplansUser::getInstance($userid);
		$userPlans	= $user->getPlans();
		
		if(empty($userPlans))
			return true;
			
		//get all user type apps
		$userTypeApps = PayplansHelperApp::getAvailableApps('jusertype');
		
		foreach($userTypeApps as $app)
		{
			$appPlans = $app->getPlans();
			
			//if any active plan has attached user type app, we don't need default type
			if(array_intersect($userPlans, $appPlans))
				return false;
		}
		return true;
	}
}