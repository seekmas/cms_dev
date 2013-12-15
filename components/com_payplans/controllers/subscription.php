<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplanssiteControllerSubscription extends XiController
{
	protected 	$_defaultTask = 'display';
	
	/*
	 * expects key instead of id
	 */
    protected   $_requireKey  = true;
    
    public function display($userId = null, $urlparams = false)
    {
    	$userId = XiFactory::getUser($userId)->id;

		//if user is not logged in
		// currently sending to login page
		if(!$userId){
			$return	= JURI::getInstance()->toString();
			$url    = 'index.php?option='.PAYPLANS_COM_USER.'&view=login';
			$url   .= '&return='.base64_encode($return);
			$this->setRedirect($url, XiText::_('COM_PAYPLANS_SUBSCRIPTION_YOU_MUST_LOGIN_FIRST'));
			return false;
		}

		$this->setTemplate('display');
		return true;
    }
    
	public function trigger($event=null,$args=null)
	{
		parent::trigger($event,$args);
		return false;
	}
	
}