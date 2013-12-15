<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHtmlLogclass
{
	static function edit($name, $value, $attr = null)
	{
		$options = array();
		$options[] = JHTML::_('select.option', '', XiText::_('COM_PAYPLANS_LOGGER_SELECT_LOGCLASS'));
		$classes = array('SYSTEM', 'PayplansSubscription', 'PayplansOrder', 'PayplansConfig', 'PayplansPayment',
						 'PayplansUser', 'PayplansPlan','PayplansInvoice','PayPlans_Cron', 'PayplansAppAssignplan', 
						 'PayplansAppEmail', 'PayplansApp2checkout', 'PayplansAppAuthorize', 'PayplansAppPaypal',
						 'PayplansAppOfflinepay', 'PayplansAppContent', 'PayplansAppContentacl', 'PayplansAppDocman',
						 'PayplansAppJsmultiprofile', 'PayplansAppJusertype', 'PayplansAppXiprofiletype', 'PayplansAppDiscount',
						 'PayplansAppGanalytics', 'PayplansAppJuga', 'PayplansAppK2category', 'PayplansAppK2', 'PayplansAppMtree','PayplansProdiscount');
		foreach($classes as $key)
    		$options[] = JHTML::_('select.option', $key, $key);

    	$style = (isset($attr['style'])) ? $attr['style'] : '';
    	return JHTML::_('select.genericlist', $options, $name, $style, 'value', 'text', $value);
	}
	
	static function filter($name, $view, Array $filters = array(), $prefix='filter_payplans', $attr = "")
	{
		$elementName  = $prefix.'_'.$view.'_'.$name;
		$elementValue = @array_shift($filters[$name]);
		
		$attr['none']  = true;
		$attr['style'] = (isset($attr['style']) ? $attr['style'] : '').' onchange="document.adminForm.submit();"';
		return PayplansHtml::_('logclass.edit', $elementName.'[]', $elementValue, $attr);
	}
}