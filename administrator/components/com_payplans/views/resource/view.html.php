<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansadminViewResource extends XiView
{
	protected function _adminGridToolbar()
	{
		XiHelperToolbar::openPopup('searchRecords', 'search', 'search.png', 'COM_PAYPLANS_TOOLBAR_SEARCH', true );
		XiHelperToolbar::divider();
		XiHelperToolbar::deleteList();
	}
	
	function edit($tpl=null, $itemId=null)
	{
		$itemId = $this->getModel()->getId();
		
		$resource = PayplansResource::getInstance($itemId);
		
		$this->assign('resource', $resource);
		return true;
	}
}