<?php

/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @contact		shyam@joomlaxi.com
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * Payplans Registration Joomla Plugin
 *
 * @package		Payplans
 * @subpackage	Plugin
 */

class  plgPayplansregistrationJoomla extends XiPluginRegistration
{	
	protected $_registrationUrl = '';

	function __construct(& $subject, $config = array())
	{
		parent::__construct($subject, $config);
		$this->_registrationUrl = 'index.php?option=' . PAYPLANS_COM_USER.'&view='.PAYPLANS_COM_USER_VIEW_REGISTER.'&fromPayplans=1';
	}
	
	function _isRegistrationUrl()
	{               
		$vars = $this->_getVars();
		if($vars['option'] == PAYPLANS_COM_USER && $vars['view'] == PAYPLANS_COM_USER_VIEW_REGISTER){
			return true;
		}
		
		return false;	 
	}
	
	function _isRegistrationCompleteUrl()
	{
		return true;	 
	}
	
	function onPayplansAccessCheck()
	{
		$vars = $this->_getVars(array('option', 'view', 'task'));
		$fromPayplans = (int) JRequest::getVar('fromPayplans', 0);
		
	//	if($vars['option'] == PAYPLANS_COM_USER && $vars['view'] == PAYPLANS_COM_USER_VIEW_REGISTER && !($fromPayplans) && $vars['task'] != 'registration.register'){
	//		$this->_app->redirect(XiRoute::_("index.php?option=com_payplans&view=plan&task=subscribe", false));
	//	}
	}
}

