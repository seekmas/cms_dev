<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansModelPayment extends XiModel
{
	public $filterMatchOpeartor = array(
										'app_id'		=> array('='),
										'created_date'	=> array('>=', '<='),
										'modified_date'	=> array('>=', '<=')
										);
}

class PayplansModelformPayment extends XiModelform {}