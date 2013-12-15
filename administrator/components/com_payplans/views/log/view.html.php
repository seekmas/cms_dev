<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansadminViewLog extends XiView
{
	protected function _adminGridToolbar()
	{
		XiHelperToolbar::delete();
		XiHelperToolbar::divider();
		XiHelperToolbar::openPopup('searchRecords', 'search', 'search.png', 'COM_PAYPLANS_TOOLBAR_SEARCH', true );
	}
	
	protected function _adminEditToolbar()
	{
		XiHelperToolbar::cancel();
	}
	
	function view($tpl=null, $itemId=null)
	{
		$this->setTpl('view');
		
		$itemId = JRequest::getVar('record');
		//IMP : we need to clean where because of filters
		$logRecords 	= XiFactory::getInstance('log', 'model')
									->loadRecords(array('log_id'=>$itemId), array('where'));

		$log = array_shift($logRecords);
		
		if ($log->content){
			list($classname, $content) = PayplansHelperLogger::readBaseEncodeLog($log);
		}
		else {
			list($classname, $content) = PayplansHelperLogger::readJsonEncodeLog($log);
		}
 		
		$this->_setAjaxWinTitle(XiText::_('COM_PAYPLANS_LOG_DISPLAY_DETAIL'));
		$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_AJAX_CLOSE_BUTTON'),'xi.ui.dialog.close();');

		$this->_setAjaxWinAction();

		$instance = PayplansFactory::getFormatter($classname, $log->class);
		if(!$instance)
		{
			$data = XiText::_('COM_PAYPLANS_LOG_FORMATTER_NOT_EXISTS');
			$this->assign('data', $data);
			return true;
		}
		
		$data = '';	
		if($content!= false){
			$data = $instance->formatter($content, $log->class);
		}
		$this->assign('data', $data);
		return true;
	}
}
