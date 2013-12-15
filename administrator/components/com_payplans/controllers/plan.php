<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();

class PayplansadminControllerPlan extends XiController
{
	public function _save(array $data, $itemId=null, $type=null)
	{
		// if expiration type is forever then set expiration to 000000000000
	    if($data['details']['expirationtype']=='forever'){
	    	$data['details']['expiration'] = '000000000000';
		}

		// XITODO : Remove it when enable multi currency
 		// always set global currency 
		$data['details']['currency'] = XiFactory::getConfig()->currency;
		
		//XITODO : SECURITY : Should we html_encode the data ?			
		$data['description']= JRequest::getVar( 'description', $data['description'], 'post', 'string', JREQUEST_ALLOWRAW );
		return parent::_save($data, $itemId);
	}
	
	public function _copy($itemId)
	{
		$plan = PayplansPlan::getInstance($itemId);
		$plan ->setId(0);
		$plan ->set('title', XiText::_("COM_PAYPLANS_COPY_OF").$plan->getTitle());
		return $plan->save();
	}
	
	public function recurrencevalidation()
	{
		return true;
	}
}