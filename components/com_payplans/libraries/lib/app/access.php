<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

abstract class PayplansAppAccess extends PayplansApp
		 implements PayplansIfaceAppAccess
{	
	protected $_applicableOn = array('self' => false, 'admin'=>false);
	
	abstract public function getResource();
	abstract public function getResourceOwner();
	abstract public function isViolation();
	
	public function getResourceAccessor()
	{
		// mostly logged in user
		return XiFactory::getUser()->id;
	}

		
	public function getResourceCount()
	{
		// default 0
		return 0;
	}
	
	public function handleViolation()
	{
		// handling might be in various ways
		// Ajax reply / html reply / redirect
	}
}