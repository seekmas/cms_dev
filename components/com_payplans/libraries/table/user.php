<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansTableUser extends XiTable
{	
	//XITODO : IMP: proper implementation requires for cross table filtering
	function getProperties($public = true)
	{
		$vars = parent::getProperties(true);

		$username = JRequest::getVar('filter_payplans_user_username');
		$usertype = JRequest::getVar('filter_payplans_user_usertype');
		
		if($username || $usertype){
			$vars['username']='';
			$vars['usertype']='';
		}
		return $vars;
	}
}

