<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHelperUser
{
	
	public static function get($userids)
	{
		// XITODO : aapy caching here
		// create the payplan user instance here
		// so that it will be cached for the next time, where-ever it will be used
		if(!is_array($userids)){
			$userids = array($userids);
		}
		
		array_unique($userids);
		
		$filter = array('user_id' => array(array('IN', '('.implode(",", $userids).')')));
		return XiFactory::getInstance('user', 'model')->loadRecords($filter);
	}
	
	/**
	 * @deprecated : use PayplansHelperUser::get
	 * @param unknown_type $userId
	 * 
	 */
	static function getName($userId)
	{
		$user = PayplansUser::getInstance( $userId);
		if($user === false){
			return XiText::_('COM_PAYPLANS_LOGGER_MODIFIER_DELETED');
		}

		return $user->getRealname();
	}
	
	/**
	 * @deprecated : use PayplansHelperUser::get
	 * @param unknown_type $userId
	 */
	static function getUserName($userId)
	{
		$user = PayplansUser::getInstance( $userId);
		if($user === false){
			return XiText::_('COM_PAYPLANS_LOGGER_MODIFIER_SYSTEM');
		}

		return $user->getUsername();
	}
	
	static function exists($what, $value)
	{
		$db = JFactory::getDBO();
		$query = 'SELECT id FROM #__users WHERE '.$db->quoteName($what).' = ' . $db->Quote( $value );
		$db->setQuery($query, 0, 1);
		if($db->loadResult()){
			return true;
		}
		
		return false;
	}
	
	static public function getID($payplansRegistration=true)
	{
		$id = XiFactory::getUser()->get('id');

		//get userId from session in case of autoRegistration
		if($id != null && $payplansRegistration){
			$id = XiFactory::getSession()->get('REGISTRATION_USER_ID');
		}
		
		return $id;
	}
	static public function getIP()
	{
		$ip = JRequest::getVar('HTTP_X_FORWARDED_FOR', null, 'SERVER');
		
		if($ip == null){
			$ip = JRequest::getVar('REMOTE_ADDR', null, 'SERVER');
		}

		if($ip == null){
			return XiText::_('COM_PAYPLANS_LOGGER_REMOTE_IP_NOT_DEFINED');
		}
		
		return $ip;
	}
	
	public static function getSubscription($userids)
	{		
		// XITODO : aapy caching here
		// create the payplan user instance here
		// so that it will be cached for the next time, where-ever it will be used
		if(!is_array($userids)){
			$userids = array($userids);
		}
		
		$filter = array('user_id' => array(array('IN', '('.implode(",", $userids).')')));
		return XiFactory::getInstance('subscription', 'model')->loadRecords($filter);
	}
}