<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHtmlApptags
{
	static function edit($name, $value, $attr=null, $ignore=array())
	{
		// all tags
		$tags     = PayplansHelperApp::getTags(true);
		// "all option:- should come at the top so it is unset first then
		// sorting is done and again it is push into array"

		$key  	 		= array_search('all', $tags);
		$result[$key]   = $tags[$key];
		unset($tags[$key]);
		natcasesort($tags);
		array_unshift($tags,$result[$key]);
		$options   = array();
		$options[] = JHTML::_('select.option', 'all', XiText::_('COM_PAYPLANS_APP_SELECT_TAG'));
		
		foreach($tags as $tag){
			if(in_array($tag, $ignore)){
				continue;
			}
			$options[] = JHTML::_('select.option', XiHelperUtils::jsCompatibleId($tag), JString::ucfirst($tag));	
		}

		$style = isset($attr['style']) ? $attr['style'] : '';
		return JHTML::_('select.genericlist', $options, $name, $style, 'value', 'text', $value);
	}
}