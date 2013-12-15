<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class XiHtmlBoolean extends XiHtml
{
	static function grid( $row,$what , $i, $imgY = 'tick.png', $imgX = 'publish_x.png', $prefix='' )
	{
		$img 	= $row->$what ? $imgY : $imgX;
		$task 	= $row->$what ? 'switchOff'.$what : 'switchOn'.$what;
		$alt 	= $row->$what ? XiText::_( 'COM_PAYPLANS_SWITCH_ON_'.$what ) : XiText::_( 'COM_PAYPLANS_SWITCH_OFF_'.$what);
		$action = $row->$what ? XiText::_( 'COM_PAYPLANS_SWITCH_OFF_'.$what.'_ITEM' ) : XiText::_( 'COM_PAYPLANS_SWITCh_ON_'.$what.'_ITEM' );

		$href = '
		<a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $prefix.$task .'\')" title="'. $action .'">'.
		JHtml::_('image','admin/'.$img, $alt, NULL, true);
		;

		return $href;
	}
	
	static function filter($name, $view, Array $filters = array(), $prefix='filter_payplans', $attr = "")
	{
		$elementName  = $prefix.'_'.$view.'_'.$name;
		$elementValue = @array_shift($filters[$name]);
		
		$data[] = array('value' => '', 
		  				'text'  => XiText::_( 'COM_PAYPLANS_FILTERS_SELECT_'.JString::strtoupper($name).'_STATE'));
		$data[] = array('value' => 0, 
		  				'text'  => XiText::_( 'COM_PAYPLANS_FILTERS_OFF_'.JString::strtoupper($name)));
		$data[] = array('value' => 1, 
		  				'text'  => XiText::_( 'COM_PAYPLANS_FILTERS_ON_'.JString::strtoupper($name)));
		
		foreach($data as $d)
    		$options[] = JHTML::_('select.option', $d['value'], $d['text']);
    		
		$attr['style'] = (isset($attr['style']) ? $attr['style'] : '').' onchange="document.adminForm.submit();"';

    	return JHTML::_('select.genericlist', $options, $elementName.'[]', $attr['style'], 'value', 'text', $elementValue);

	}
}