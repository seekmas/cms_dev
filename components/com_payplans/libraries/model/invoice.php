<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansModelInvoice extends XiModel
{
	public $filterMatchOpeartor = array(
										'status'						=> array('='),
										'total'							=> array('>=', '<='),
										'cross_users_username' 			=> array('LIKE'),
										'cross_subscription_plan_id' 	=> array('='),
										'cross_wallet_created_date'		=> array('>=', '<=')
	);
								
	public $crossTableNetwork 	= array(
									"users" => array('users'),
									"subscription" => array('subscription'),
									"usergroups" => array('user_usergroup_map','usergroups'),
									"wallet"=>array('wallet')	
	);
	
	public $innerJoinCondition = array(
						"tbl-subscription"				=> " `#__payplans_subscription` as cross_subscription on cross_subscription.order_id = tbl.object_id ",
						"tbl-users"						=> " `#__users` 				as cross_users 		  on tbl.user_id 				 = cross_users.id ",
						"tbl-wallet"					=> " `#__payplans_wallet`		as cross_wallet		  on tbl.invoice_id 			= cross_wallet.invoice_id "					 					
	);

    public function getUnUtilizedInvoices($status = array(PayplansStatus::INVOICE_CONFIRMED), XiDate $firstDate, XiDate $endDate)
    {
    	$query = new XiQuery();

    	$status = is_array($status) ? $status : array($status);
		$query->select('count(`tbl`.invoice_id)')
				 ->where('`tbl`.status IN ('.implode(',', $status).')')
				 ->where("`tbl`.created_date>='". $firstDate->toMySQL()."'")
				 ->where("`tbl`.created_date<='".$endDate->toMySQL()."'")
				 ->from('#__payplans_invoice as tbl');

		return $query->dbLoadQuery()->loadResult();
    }
    
}

class PayplansModelformInvoice extends XiModelform {}

