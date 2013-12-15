<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

/**
 * Apptriggerable interface
 * All lib classes which are related to plans, and want to triiger apps
 * should implement this interface
 * @author shyam
 *
 */
interface PayplansIfaceApptriggerable
{
	function getPlans();
}