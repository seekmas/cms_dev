<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansModelWallet extends XiModel
{
	public $filterMatchOpeartor = array(
										'amount'	     => array('>=', '<='),
										'user_id'	     => array('='),
										'invoice_id'     => array('='),
										'transaction_id' => array('='),
										'created_date'   => array('>=', '<=') 	);
}

class PayplansModelformWallet extends XiModelform {}