<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage		Subscription Module
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class ModPayplansSubscriptionHelper
{
	const APPLY_ALL   =  'APPLY_ALL';
	
    public static function isRenewAppApplicable()
    {
    	// select all renewal apps 
    	$renewalApp = PayplansHelperApp::getAvailableApps('renewal');
    	
    	if(!isset($renewalApp))
    	{
    		return array();
    	}
    	
    	$applicablePlans = array();
	    foreach($renewalApp as $app){
	 		if($app->getParam('applyAll')){
	 			$applicablePlans = array(self::APPLY_ALL);
	 			break;
	 		}
	 		else{
	 			$applicablePlans = array_merge($applicablePlans, $app->getPlans()) ;
	 		}
	    }
    	return array_unique($applicablePlans);
    }
    
	public static function showRenewLink($subscription)
    {
    	$link = '';
    	if((($subscription->isRecurring()) 
		&& 
		in_array($subscription->getStatus(), array(PayplansStatus::SUBSCRIPTION_EXPIRED))))
		{
			$link = "<a class='renew' href="; 
			$link .= XiRoute::_('index.php?option=com_payplans&view=order&task=trigger&event=onPayplansOrderRenewalRequest&subscription_key='.$subscription->getKey());
			$link .= "><img title=".XiText::_('MOD_SUBSCRIPTION_RENEW_MESSAGE')." src=".PayplansHelperUtils::pathFS2URL(dirname(__FILE__).DS.'images'.DS.'renew.png')." /></a>";
		}
		elseif(($subscription->getExpirationType() == 'fixed') 
		&& in_array($subscription->getStatus(), array(PayplansStatus::SUBSCRIPTION_EXPIRED, PayplansStatus::SUBSCRIPTION_ACTIVE)))
		{
			$link = "<a class='renew' href=";
			$link .= XiRoute::_('index.php?option=com_payplans&view=order&task=trigger&event=onPayplansOrderRenewalRequest&subscription_key='.$subscription->getKey());
			$link .= "><img title=".XiText::_('MOD_SUBSCRIPTION_RENEW_MESSAGE')." src=".PayplansHelperUtils::pathFS2URL(dirname(__FILE__).DS.'images'.DS.'renew.png')." /></a>";
		}
		return $link;
    }
    
}
