<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class JElementDocmangroups extends XiElement
{
	var	$_name = 'Docmangroups';

	static function fetchElement($name, $value, &$node, $control_name)
	{
		if(!JFolder::exists(JPATH_SITE .DS.'components'.DS.'com_docman')){
			return XiText::_('COM_PAYPLANS_PLEASE_INSTALL_DOCMAN_BEFORE_USING_THIS_APPLICATION');
		}
			
		$groups = PayplansAppDocman::getGroups();
		
		//add None option
		$none = new stdClass();
		$none->groups_id = 0;
		$none->groups_name = XiText::_('NONE');
		$groups[0] = $none;
		
		$multiple = self::getAttrib($node, 'multiple');
		if($multiple == true){
			return PayplansHtml::_('autocomplete.edit', $groups, $control_name.'['.$name.']', array('multiple' => true), 'groups_id', 'groups_name', $value);
		}
		
		return PayplansHtml::_('select.genericlist', $groups, $control_name.'['.$name.']', null, 'groups_id', 'groups_name', $value);
	}
}