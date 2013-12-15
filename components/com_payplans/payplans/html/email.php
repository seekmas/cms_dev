<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHtmlEmail
{
	static function link($text = '', $attr=array())
	{
		$text = !empty($text) ? $text : XiText::_('COM_PAYPLANS_ELEMENT_EMAIL');
		$url = "index.php?option=com_payplans&view=support&task=emailform";
		$extra = '';
		foreach($attr as $key => $value){
			$extra .= ' '.$key.'="'.$value.'"';
		}
		return '<a href="" onclick="payplans.url.modal(\''.$url.'\');return false;" '.$extra .' >'
				.$text
				.'</a>';
	}
}