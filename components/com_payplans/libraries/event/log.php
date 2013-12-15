<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Loggers
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansEventLog
{
	static public function onPayplansOrderAfterSave($previous, $current)
	{
		return self::_save($previous, $current, 'ORDER', 'PayplansOrderFormatter');
	}
	
	static public function onPayplansSubscriptionAfterSave($previous, $current)
	{
		return self::_save($previous, $current, 'SUBSCRIPTION', 'PayplansSubscriptionFormatter');
	}
	
	static public function onPayplansPaymentAfterSave($previous, $current)
	{
		return self::_save($previous, $current, 'PAYMENT', 'PayplansPaymentFormatter');
	}
	
	static public function _save($previous, $current, $type='ORDER', $formatter='PayplansFormatter')
	{
		//XITODO : ignore during migration
		$str = JString::strtoupper($type).'_'.($previous?'UPDATED':'CREATED');
		$message = XiText::_('COM_PAYPLANS_LOGGER_'.$str);
		
		$content = PayplansFormatter::writer($previous, $current);
		$prev = $content['previous'];
		$curr = $content['current'];

		//IMP::Don't required log when Migration is running. 
		if((defined('PAYPLANS_MIGRATION_START') && !defined('PAYPLANS_MIGRATION_END'))
				|| self::_isEqual($prev, $curr, $previous) == false){
			return true;
		}

		//log the update in status/amount
		PayplansHelperLogger::log(XiLogger::LEVEL_INFO, $message, $current, $content, $formatter);
		return true;
	}
	
	static public function onPayplansPlanAfterSave($previous, $current)
	{
		return self::_save($previous, $current, 'PLAN', 'PayplansPlanFormatter');
	}
	
	static public function onPayplansAppAfterSave($previous, $current)
	{
		return self::_save($previous, $current, 'APP', 'PayplansAppFormatter');
	}
	

	
	static public function onPayplansPlanAfterDelete($itemId)
	{
		return self::_delete($itemId,'PLAN', 'PayplansPlanFormatter');
		
	}
	
	static public function onPayplansOrderAfterDelete($itemId)
	{
		return self::_delete($itemId, 'ORDER', 'PayplansOrderFormatter');
	}
	
	static public function onPayplansSubscriptionAfterDelete($itemId)
	{
		return self::_delete($itemId,'SUBSCRIPTION', 'PayplansSubscriptionFormatter');
	}
	
	static public function onPayplansPaymentAfterDelete($itemId)
	{
		return self::_delete($itemId,'PAYMENT', 'PayplansPaymentFormatter');
	}
	
	static public function onPayplansAppAfterDelete($itemId)
	{
		return self::_delete($itemId,'APP', 'PayplansAppFormatter');
	}

	static public function _delete($itemId, $type='PLAN', $formatter='PayplansFormatter')
	{
		$str 		= JString::strtoupper($type).'_'.'DELETED';
		$message 	= XiText::_('COM_PAYPLANS_LOGGER_'.$str);
		
		$object 	= XiFactory::getSession()->get('OBJECT_TO_BE_DELETED_'.$itemId.'_'.$type,null);
		$content['previous'] = $object ? $object->toArray() : array();
	

		PayplansHelperLogger::log(XiLogger::LEVEL_INFO, $message, $object, $content, $formatter);
		
		XiFactory::getSession()->clear('OBJECT_TO_BE_DELETED_'.$itemId.'_'.$type);
		return true;
	}
	
	static public $prevConfig = null;
	static public function onPayplansControllerBeforeExecute($controller, $task)
	{
		$view = JRequest::getVar('view', null);
		if($task == 'apply' && $view=='config' && XiFactory::getApplication()->isAdmin()==True){
			self::$prevConfig = XiFactory::getConfig();
		}

		return true;
	}

	static public function onPayplansControllerAfterExecute($controller, $task)
	{		
		$view = JRequest::getVar('view', null);

		if($task != 'apply' || $view !='config' 
				 || XiFactory::getApplication()->isAdmin() == false){
				 	
			return true;
		}
			
		XiFactory::cleanStaticCache(true);
		$currConfig = XiFactory::getConfig();
		$message = XiText::_('COM_PAYPLANS_LOGGER_CONFIG_UPDATED');
		
		$prev = (array)self::$prevConfig;
		$curr = (array)$currConfig;
		
		if(self::_isEqual($prev, $curr, self::$prevConfig) == false){
			return true;
		}
		
		$content['previous']= $prev ;
		$content['current'] = $curr;
		
		//log the update in status/amount
		PayplansHelperLogger::log(XiLogger::LEVEL_INFO, $message, null, $content, 'PayplansConfigFormatter', 'PayplansConfig');
		return true;	
	}
	
	public static function _isEqual($previous, $current, $prevObject)
	{
		//only record log if there is a real change.
		$params = array('_appplans', '_planapps', '_groups','_plans');
		foreach($current as $key => $val){
			if(preg_match('/^_/',$key) && !in_array($key, $params)){
				continue;
			}
			// if empty params in both previous and current
			// there are some cases where previous is empty array and current is unintialized
			if(in_array($key, $params)){
				if(empty($current[$key]) && empty($previous[$key]))
				{
					continue;
				}
			}
			if(!$prevObject || ($current[$key] != $previous[$key])){
				return true;
			}
		}

		return false;
	}
	
	
	// during post migration add log and also delete previous logs of OPS , plan and App
	static function onPayplansStartMigration()
	{
		// XiTODO:: delete invoice and transaction  logs
		$model = XiFactory::getInstance('log','model');
		
        // For joomla3.0 compatibility
		$conditions = array('class'=>'"PayplansSubscription"');
		$model->deleteMany($conditions, 'OR');

		$conditions = array('class'=>'"PayplansPayment"');
		$model->deleteMany($conditions, 'OR');	
		
		$conditions = array('class'=>'"PayplansOrder"');
		$model->deleteMany($conditions, 'OR');
		
		$conditions = array('class'=>'"PayplansPlan"');
		$model->deleteMany($conditions, 'OR');
		
		$conditions = array('class'=> '"PayplansApp%"');
		$model->deleteMany($conditions, 'OR', 'LIKE');

		return true;
	}

	public static function onPayplansPostMigration($pluginKey)
	{	
		$message = XiText::_('COM_PAYPLANS_MIGRATION_SUCCESS_'.JString::strtoupper($pluginKey));
		PayplansHelperLogger::log(XiLogger::LEVEL_INFO, $message, null, $message);
		return true;
	}
	
	static public function onPayplansGroupAfterSave($previous, $current)
	{
		return self::_save($previous, $current, 'GROUP', 'PayplansGroupFormatter');
	}

	static public function onPayplansGroupAfterDelete($itemId)
	{
		return self::_delete($itemId,'GROUP', 'PayplansGroupFormatter');
	}
	
	static public function onPayplansUserAfterSave($previous, $current)
	{
		return self::_save($previous, $current, 'USER', 'PayplansUserFormatter');
	}
	
	static public function onPayplansInvoiceAfterSave($previous, $current)
	{
		return self::_save($previous, $current, 'INVOICE', 'PayplansInvoiceFormatter');
	}

	static public function onPayplansInvoiceAfterDelete($itemId)
	{
		return self::_delete($itemId,'INVOICE', 'PayplansInvoiceFormatter');
	}
	
}