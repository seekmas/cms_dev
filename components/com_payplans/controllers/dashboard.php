<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplanssiteControllerDashboard extends XiController
{
	protected $_defaultTask = 'frontview';

	// 	No model exist
	function getModel($name = '', $prefix = '', $config = array())
	{
		return null;
	}
	
	function noaccess($userId = null)
	{
		$userId = ($userId === null) ? XiFactory::getUser($userId)->id : $userId;
		
		$this->setTemplate('noaccess');
		return true;
	}
	
	public function trigger($event=null,$args=null)
	{
		parent::trigger($event,$args);
	}
	
	public function frontview()
	{
		$this->setTemplate('frontview');
	}
}