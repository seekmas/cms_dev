<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
* 
* Highly Inspired from AEC micro integration for docman
*/
if(defined('_JEXEC')===false) die();

class PayplansAppDocman extends PayplansApp
{
	protected $_location	= __FILE__;
	protected $_resource	= 'com_docman.group';
	
	public function isApplicable($refObject = null, $eventName='')
	{
		if($refObject instanceof PayplanssiteViewDashboard 
				&& $eventName === 'onPayplansViewBeforeRender'){
			return true;
		}
		
		return parent::isApplicable($refObject, $eventName);
	}
	
	// applicable only if docman exist
	public function _isApplicable(PayplansIfaceApptriggerable $refObject, $eventname='')
	{
		return JFolder::exists(JPATH_SITE .DS.'components'.DS.'com_docman');
	}
	

	public function onPayplansSubscriptionAfterSave($prev, $new)
	{
		// no need to trigger if previous and current state is same
		if($prev != null && $prev->getStatus() == $new->getStatus()){
			return true;
		}

		$newStatus  = $new->getStatus();
		
		$active = $this->getAppParam('addToGroupOnActive');
		$active	= (is_array($active)) ? $active : array($active);
		
		$hold 	= $this->getAppParam('addToGroupOnHold');
		$hold	= (is_array($hold)) ? $hold : array($hold);
		
		$expire = $this->getAppParam('addToGroupOnExpire');
		$expire	= (is_array($expire)) ? $expire : array($expire);
			
		$subid  = $new->getId();
		$userid = $new->getBuyer();
		 
		// #1 : remove from groups assigned from other status (if applicable)
		// #2 : add to group and add resource for current status 
		
		// if subscription is active
		if($newStatus == PayplansStatus::SUBSCRIPTION_ACTIVE){
			$this->_removeFromGroup($userid, $hold, $subid);
			$this->_removeFromGroup($userid, $expire, $subid);
			$this->_addToGroup($userid, $active, $subid);
			
			return true;
		}
		
		if($newStatus == PayplansStatus::SUBSCRIPTION_HOLD){
			$this->_removeFromGroup($userid, $active, $subid);
			$this->_removeFromGroup($userid, $expire, $subid);
			$this->_addToGroup($userid, $hold, $subid);
			
			return true;
		}
		
		if($newStatus == PayplansStatus::SUBSCRIPTION_EXPIRED){
			$this->_removeFromGroup($userid, $active, $subid);
			$this->_removeFromGroup($userid, $hold, $subid);
			$this->_addToGroup($userid, $expire, $subid);
			
			return true;
		}
	
		return true;
	}


	protected function _addToGroup( $userid, $groupids, $subId)
	{
		if(!is_array($groupids)){
			return false;
		}
		
		foreach($groupids as $groupid){
			if(empty($groupid)){
				continue;
			}
			
			$users = $this->_getGroupMembers($groupid);
	
			if(in_array( $userid, $users ) ) {
				$this->_addToResource($subId, $userid, $groupid, $this->_resource);
				$message = sprintf(XiText::_('COM_PAYPLANS_LOGGER_DOCMAN_LOG_ALREADY_ADDED_TO_GROUP'), $userid, $groupid);
				$content = array('previous' => array( 'added_user'=>$userid ,
												 'docman_user_group' => $groupid));
				PayplansHelperLogger::log(XiLogger::LEVEL_INFO, $message, $this, $content,'PayplansAppDocmanFormatter');
				continue;
			} 
			
			// Make sure we have no empty value
			$search = 0;
			while ( $search !== false ) {
				$search = array_search( '', $users );
				if( $search !== false ){
					unset( $users[$search] );
				}
			}
	
			$users[] = $userid;
			
			if($this->_setGroupMembers($groupid, $users)){
				$this->_addToResource($subId, $userid, $groupid, $this->_resource);
				$message = sprintf(XiText::_('COM_PAYPLANS_LOGGER_DOCMAN_LOG_ADDED_TO_GROUP'), $userid, $groupid);
				$content = array('previous' => array( 'added_user'=>$userid , 
													  'docman_user_group' => $groupid));
				PayplansHelperLogger::log(XiLogger::LEVEL_INFO, $message, $this, $content,'PayplansAppDocmanFormatter');
			}
		}
		
		return true;
	}

	protected function _removeFromGroup($userid, $groupids, $subId)
	{
		if(!is_array($groupids)){
			return false;
		}
		
		foreach($groupids as $groupid){
			if(empty($groupid)){
				continue;
			}
			
			$users = $this->_getGroupMembers($groupid);

			if(!in_array($userid,$users)){
				continue;
			}
			
			$key = array_search( $userid, $users );
			unset( $users[$key] );
	
			// Make sure we have no empty value
			$search = 0;
			while ( $search !== false ) {
				$search = array_search( '', $users );
				if ( $search !== false ) {
					unset( $users[$search] );
				}
			}
			
			if($this->_removeFromResource($subId, $userid, $groupid, $this->_resource) 
					&& $this->_setGroupMembers($groupid, $users)){
				$message = sprintf(XiText::_('COM_PAYPLANS_LOGGER_DOCMAN_LOG_REMOVED_FROM_GROUP'), $userid, $groupid);
				$content = array('previous' => array( 'removed_user'=>$userid ,
													  'docman_user_group' => $groupid));
				PayplansHelperLogger::log(XiLogger::LEVEL_INFO, $message, $this, $content,'PayplansAppDocmanFormatter');
			}
		}
		
		return true;
	}
	
	protected function _getGroupMembers($groupid)
	{
		$db = JFactory::getDBO();

		$query = 'SELECT `groups_members`'
			. ' FROM #__docman_groups'
			. ' WHERE `groups_id` = \'' . $groupid . '\''
			;
		$db->setQuery( $query );
		return explode(',', $db->loadResult());
	}
	
	protected function _setGroupMembers($groupid, $users)
	{
		$db = JFactory::getDBO();
		$query = 'UPDATE #__docman_groups'
			. ' SET `groups_members` = \'' . implode( ',', $users ) . '\''
			. ' WHERE `groups_id` = \'' . $groupid . '\''
			;
		$db->setQuery( $query );
		return $db->query();
	}
	
	public static function getGroups()
	{
		$db = JFactory::getDBO();
		$query = 'SELECT groups_id, groups_name, groups_description'
			 	. ' FROM #__docman_groups'
			 	;
	 	$db->setQuery( $query );
	 	return $db->loadObjectList('groups_id'); 
	} 
	
	//render Widget
	public function renderWidgetHtml()
	{   
		//get user id
		$userid     = XiFactory::getUser()->id;
		if(!$userid){
			return '';
		}
		//get user's docman groups
	    $docman_groups = $this->_getUserDocmanGroup($userid);
	    if(empty($docman_groups))
	    	return '';

	    $this->assign('docman_groups',$docman_groups);
        $data = $this->_render('widgethtml');
        return $data;
	}	
	
	protected function _getUserDocmanGroup($userid)
	{
		$db = JFactory::getDBO();
		$groups_in = array();
		//Add DOCman groups
        $db->setQuery("SELECT groups_name,groups_members " .
                            "\n FROM #__docman_groups");
        $all_groups = $db->loadObjectList();
	    if (count($all_groups)) {
            foreach ($all_groups as $a_group) {
                $group_list = array();
                $group_list = explode(',', $a_group->groups_members);
                if (in_array($userid , $group_list))
				{
				  	$groups_in[] = $a_group->groups_name;
                }
            }
        }
	 	
		return $groups_in;
	}
	
	function getNameFromResourceValue($resource, $value)
	{
		// if its a different resource
		if($resource != $this->_resource){
			return false;
		}
		
		$groups = $this->getGroups();
		return $groups[$value]->groups_name;
	}
}

class PayplansAppDocmanFormatter extends PayplansAppFormatter
{
	//get Rules 
	function getVarFormatter()
	{
		$rules = array('_appplans'       => array('formatter'=> 'PayplansAppFormatter',
										       'function' => 'getAppPlans'),
					   'app_params'      => array('formatter'=> 'PayplansAppDocmanFormatter',
										       'function' => 'getFormattedParams'),
						'added_user'     => array('formatter'=> 'PayplansUserFormatter',
												  'function' => 'getBuyerName'),
						'removed_user'    => array('formatter'=> 'PayplansUserFormatter',
												  'function' => 'getBuyerName'),
						'docman_user_group' => array('formatter'=> 'PayplansAppDocmanFormatter',
										       'function' => 'getDocmanGroups'));
		return $rules;
	}
	
	// formatt params
	function getFormattedParams($key,$value,$data)
	{
		//do nothing if docman is not installed
		if(!JFolder::exists(JPATH_SITE .DS.'components'.DS.'com_docman')){
				return false;
			}
		$groups  = PayplansAppDocman::getGroups();
		$params  = PayplansHelperParam::iniToArray($value);
		$params['addToGroupOnActive']= $groups[$params['addToGroupOnActive']]->groups_name;
		$params['addToGroupOnHold']  = $groups[$params['addToGroupOnHold']]->groups_name;
		$params['addToGroupOnExpire']= $groups[$params['addToGroupOnExpire']]->groups_name;
		$value                       = PayplansHelperParam::arrayToIni($params);
		
	}
	
	function getDocmanGroups($key,$value,$data)
	{
		if(!JFolder::exists(JPATH_SITE .DS.'components'.DS.'com_docman')){
				return false;
			}
		$groups  = PayplansAppDocman::getGroups();
		$value   = $groups[$value]->groups_name;
	}
}