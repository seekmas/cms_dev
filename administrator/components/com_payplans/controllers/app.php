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

class PayplansadminControllerApp extends XiController
{

	public function edit($itemId = null, $userId = null)
	{
		$userId = XiFactory::getUser($userId)->id;

		//set editing template
		$this->setTemplate('edit');

		//if it was a new task, simply return true
		// as we cannot checkout non-existing record
		if($this->getTask() ==='new' || $this->getTask() === 'newItem'){
			return true;
		}

		//user want to edit record
		if($this->_edit($itemId, $userId)===false){
			//XITODO : enqueue message that item is already checkedout
			$this->setRedirect(null,$this->getError());
			return false;
		}

		return true;
	}

	public function _save(array $data, $itemId=null, $type=null)
	{
		XiError::assert(isset($data['type']), XiText::_('COM_PAYPLANS_ERROR_TYPE_IS_NOT_DEFINED_FOR_APP'));
		$app = PayplansApp::getInstance($itemId,$data['type']);

		// we need to collect params, and convert them into INI
		// it should be done via app
		$data['core_params'] = $app->collectCoreParams($data);
		$data['app_params']  = $app->collectAppParams($data);

		return parent::_save($data, $itemId, $data['type']);
	}

	public function selectApp()
	{
		$this->setTemplate('selectapp');
		return true;
	}
	
	
	
	public function _copy($itemId)
	{
		$app = PayplansApp::getInstance($itemId);
		if($app === FALSE){
			$this->setError(XiText::_('COM_PAYPLANS_GRID_INVALID_APP'));
			return false;
		}
		
		$app->setId(0);
		$app->set('title', XiText::_("COM_PAYPLANS_COPY_OF").$app->getTitle());
		return $app->save();
	}
}

