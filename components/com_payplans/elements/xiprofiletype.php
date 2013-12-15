<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class JFormFieldXiprofiletype extends XiField
{
	public $type = 'Xiprofiletype'; 
	
	function getInput()
	{
		$file = JPATH_ROOT.DS.'components'.DS.'com_xipt'.DS.'api.xipt.php';
		if(!JFile::exists($file))
			return XiText::_('COM_PAYPLANS_PLEASE_INSTALL_JSPT_BEFORE_USING_THIS_APPLICATION');
			
		require_once JPATH_ROOT.DS.'components'.DS.'com_xipt'.DS.'api.xipt.php';
		
		$profiletype = XiptAPI::getProfiletypeInfo();
		if(empty($profiletype)){
			return XiText::_('COM_PAYPLANS_APP_JSPT_NO_PROFILTYPE_EXISTS');
		}
		
		return PayplansHtml::_('autocomplete.edit', $profiletype, $this->name, null, 'id', 'name', $this->value);
	}
}