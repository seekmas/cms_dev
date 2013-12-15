<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansadminViewWallet extends XiView
{
	protected function _adminGridToolbar()
	{
		XiHelperToolbar::openPopup('searchRecords', 'search', 'search.png', 'COM_PAYPLANS_TOOLBAR_SEARCH', true );
		XiHelperToolbar::divider();
		//XiHelperToolbar::customX( 'copy', 'copy.png', 'copy_f2.png', 'Copy', true );
//		XiHelperToolbar::addNewX('new');
	}
	
	protected function _adminEditToolbar()
	{   
        $model = $this->getModel();
		XiHelperToolbar::save();
		XiHelperToolbar::apply();
		XiHelperToolbar::cancel();
		XiHelperToolbar::divider();
	}
	
	function edit($tpl=null, $itemId=null)
	{	
		return true;
	}
	
	function _displayGrid($records)
	{
		$uesrids = array();
		foreach($records as $record){
			$userids[] = $record->user_id;
		}
		
		$users = PayplansHelperUser::get($userids);
		$this->assign('users', $users);
		
		return parent::_displayGrid($records);
	}
}

