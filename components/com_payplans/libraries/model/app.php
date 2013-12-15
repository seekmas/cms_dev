<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansModelApp extends XiModel
{
	public $filterMatchOpeartor = array(
										'title'		=> array('LIKE'),
										'type' 		=> array('='),
										'published' => array('=')
										);
										
	//XITODO : Apply validation when it is applied all over
	function validate(&$data, $pk=null,array $filter = array(),array $ignore = array())
	{
		return true;
	}
	
	protected function _hasType($pk, $type)
	{
		//load the table row
		$table = $this->getTable();
		if(!$table){
			$this->setError(XiText::_('COM_PAYPLANS_TABLE_DOES_NOT_EXIST'));
			return false;
		}
		
		//if we have itemid then we MUST load the record
		// else this is a new record
		if($pk && $table->load($pk)===false){
			return false;
		}
		
		return JString::strtolower($table->type) == JString::strtolower($type);
	}
	
	public function save($data, $pk=null, $new=false)
	{
		if($this->_hasType($pk, 'adminpay') == true
		   || (isset($data['type']) && $data['type'] == 'adminpay')){
			$this->setError(XiText::_('COM_PAYPLANS_APP_CAN_NOT_CHANGE_ADMINPAY'));
			return false;
		}
		
		return parent::save($data, $pk);		
	}
	
	public function boolean($pk, $column, $value, $switch)
	{
		if($this->_hasType($pk, 'adminpay') == true){
			XiError::assert(0, XiText::_('COM_PAYPLANS_APP_CAN_NOT_CHANGE_ADMINPAY'), XiError::WARNING);
			return false;
		}
		
		return parent::boolean($pk, $column, $value, $switch);
	}
	
	public function delete($pk=null)
	{
		//can not delete payment app if payment exists corresponding to that app
		$payment = XiFactory::getInstance('payment','model')
								->loadRecords(array('app_id'=>$pk));

		//can not delete adminpay application
		if($this->_hasType($pk, 'adminpay') == true){
			$this->setError(XiText::_('COM_PAYPLANS_APP_CAN_NOT_DELETE_ADMINPAY'));
			return false;
		}

		if(!empty($payment))
		{
			$this->setError(XiText::_('COM_PAYPLANS_APP_CAN_NOT_DELETE_PAYMENT_EXISTS'));
			return false;
		}	
		
		if(!parent::delete($pk))
		{
			$db = JFactory::getDBO();
			XiError::raiseError(500, $db->getErrorMsg());
		}
		// delete plans from planapp table
		return XiFactory::getInstance('planapp', 'model')
			 	 ->deleteMany(array('app_id' => $pk));
	}
}


class PayplansModelformApp extends XiModelform {
	
	function preprocessForm(JForm $form, $data, $group = 'content')
		{		
			if(isset($data['type'])){	
				$appObj = PayplansApp::getInstance($data['app_id'],$data['type']);
				 $xml = $appObj->getLocation() . DS . $appObj->getName() . '.xml';
				$form->loadFile($xml, false, '//config');

			}
			
			return parent::preprocessForm($form, $data);
		}
	
}
