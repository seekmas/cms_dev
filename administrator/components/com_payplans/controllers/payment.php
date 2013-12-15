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


class PayplansadminControllerPayment extends XiController
{
	protected	$_defaultOrderingDirection = 'DESC';
	public function newPayment()
	{
		$this->setTemplate('newpayment');
		return true;
	}

	public function _save(array $data, $itemId=null, $type=null)
	{
		if(!$itemId){
			// asert if order_id and app_id is not in post data
			XiError::assert(isset($data['app_id']) && $data['app_id'], XiText::_('COM_PAYPLANS_ERROR_INVALID_APPLICATION_ID'));
			XiError::assert(isset($data['order_id']) && $data['order_id'], XiText::_('COM_PAYPLANS_ERROR_INVALID_ORDER_ID'));
		}
		

		if(!isset($data['transaction'])){
			$data['transaction'] = array();
		}
		
		if(!is_array($data['transaction'])){
			 $data['transaction'] = (array)$data['transaction'];
		}
		
		// load the old transaction if itemid is there
		// so that they will not get lost
		if($itemId){
			$transaction = PayplansHelperParam::iniToArray(PayplansPayment::getInstance($itemId)->getTransaction());
			// merge the transaction records
			if(!$transaction){
				$transaction = array();
			}
			$data['transaction'] = array_merge($transaction, $data['transaction']);
		}	

		$data['transaction'] = PayplansHelperParam::arrayToIni($data, 'transaction');
		
		parent::_save($data, $itemId, 'payment');
		return true;
	}
     public function statusHelp()
	{
		$this->setTemplate('help');
		return true;
	}
}

