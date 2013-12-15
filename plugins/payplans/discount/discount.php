<?php

/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		Payplans
* @subpackage	Discount
* @contact		shyam@joomlaxi.com
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Payplans Discount Plugin
 *
 * @author shyam
 */
class plgPayplansDiscount extends XiPlugin
{

	public function onPayplansSystemStart()
	{
		//add discount app path to app loader
		$appPath = dirname(__FILE__).DS.'discount'.DS.'app';
		PayplansHelperApp::addAppsPath($appPath);

		return true;
	}

}
