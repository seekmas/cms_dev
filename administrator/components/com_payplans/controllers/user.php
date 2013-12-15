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

 class PayplansadminControllerUser extends XiController
{
	
	public function _save(array $data, $itemId=null, $type=null)
	{
		// we need to collect params, and convert them into INI
		$data['preference']= PayplansHelperParam::collectParams($data,'preference');
		$data['params'] = PayplansHelperParam::collectParams($data,'params');
		return parent::_save($data, $itemId);
	}
	
	/*
	 * apply the selected subscription plan to the selected users
	 */
	public function applyPlan($planId=null, $users = array())
	{
		$url = 'index.php?option=com_payplans&view=user&task=display';
		$planId = $planId ? $planId : JRequest::getInt('plan_id',$planId);
		$users = JRequest::getVar('cid', $users, 'request', 'array');

		// XITODO : proper handling needs to be implemented when plan is applied to large number of users
		foreach($users as $userId){
			if(!$planId){
				return true;
			}
			$plan = PayplansPlan::getInstance( $planId);
			$order = $plan->subscribe($userId)
						  ->save();
			
			$invoice = $order->createInvoice();

			//apply 100% discount
			$modifier = PayplansModifier::getInstance();
			$modifier->set('message','COM_PAYPLANS_APPLY_PLAN_ON_USER_MESSAGE' )
				 ->set('invoice_id', $invoice->getId())
				 ->set('user_id', $invoice->getBuyer())
				 ->set('type', 'apply_plan')
				 ->set('amount', -100) // 100 percent Discount, discount must be negative
				 ->set('percentage', true) 
				 ->set('frequency', PayplansModifier::FREQUENCY_ONE_TIME)
				 ->set('serial', PayplansModifier::FIXED_DISCOUNT)
				 ->save();
				  
			$invoice->refresh()->save();
			
			// create a transaction with 0 amount 
			$transaction = PayplansTransaction::getInstance();
			$transaction->set('user_id', $invoice->getBuyer())
						->set('invoice_id', $invoice->getId())
						->set('message', 'COM_PAYPLANS_TRANSACTION_CREATED_FOR_APPLY_PLAN_TO_USER')
						->save();

			//trigger the event
			$args = array($transaction, 0);
			PayplansHelperEvent::trigger('onPayplansWalletUpdate', $args);

		}
		$this->setRedirect($url);
		return false;

	}
	
	public function selectPlan()
	{
		$this->setTemplate('selectplan');
		return true;
	} 
	
	public function rechargeWallet()
	{
		$this->setTemplate('rechargewallet');
		return true;
	} 
	
	public function recharge($amount = null, $userId = 0)
	{
		$userId = ($userId == null) ? JRequest::getVar('user_id', null) : $userId;
		$url = 'index.php?option=com_payplans&view=user&task=edit&id='.$userId;
		
		//if no user id exists then do nothing
		if(!$userId){
			return true;
		}
		
		$rechargeAmount  = JRequest::getVar('recharge_amount', 0);
		$amount 		 = ($amount == null) ? $rechargeAmount : $amount;

		XiError::assert($amount,XiText::_('COM_PAYPLANS_WALLET_INVALID_AMOUNT'));
		
		$invoice = PayplansHelperWallet::createInvoice($userId, $amount);
		
		$params     = new stdClass();
		$params->transaction_amount     = $invoice->getTotal();
		$params->transaction_message    = 'COM_PAYPLANS_TRANSACTION_CREATED_FOR_WALLET_RECHARGE';
		
		$invoice->addTransaction($params);
		
		PayplansFactory::redirect($url, true);
		return false;
	}
	
	public function search($text='')
	{
		// do search
		$text = JRequest::getVar('text', $text);
		$user  = array();
		$model = XiFactory::getInstance('user', 'model');

		// XITODO : use load records function
		$query = $model->getQuery();
		$tmpQuery = $query->getClone();
		$tmpQuery->clear('where')
				->where("`tbl`.`username` LIKE '%".$text."%' ","OR")
				->where("`tbl`.`name` LIKE '%".$text."%' ","OR"); // Xi: ticket #1789
			
		$users = $tmpQuery->dbLoadQuery()->loadObjectList($model->getTable()->getKeyName());

		$result= array();
		foreach($users as $user){
			//$result[] = PayplansUser::getInstance($user->user_id)->getUsername().'('.PayplansUser::getInstance($user->user_id)->getRealname().')';
			$result = new stdClass();
			$result->id = $user->user_id;
			$result->name =  $user->username.'('.$user->realname.')';
			$results[] = $result;     
		}
		$this->setTemplate('search');
		$this->getView()->assign('results', $results);
		return true;
	}



}

