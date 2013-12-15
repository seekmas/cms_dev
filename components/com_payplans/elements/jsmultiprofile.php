<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class JFormFieldJsmultiprofile extends XiField
{
	public $type = 'Jsmultiprofile'; 
	
	function getInput()
	{
		if(JFolder::exists(JPATH_ROOT.DS.'components'.DS.'com_community') == false){
				return '<input type="text" class="readonly" style="border: 0 none;" readonly="readonly" value="'.XiText::_('COM_PAYPLANS_JOMSOCIAL_PROFILETYPE_NOT_AVAILABLE').'"/>';
		}
		
		$profiletype = PayplansAppJsmultiprofile::getJsprofiletype();
		if(is_array($profiletype)==false || count($profiletype)==0){
			return '<input type="text" class="readonly" readonly="readonly" value="'.XiText::_('COM_PAYPLANS_JOMSOCIAL_PROFILETYPE_NOT_AVAILABLE').'"/>';
		}
		
		return PayplansHtml::_('autocomplete.edit', $profiletype, $this->name, null, 'id', 'name', $this->value);
	}
}