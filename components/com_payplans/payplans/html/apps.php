<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHtmlApps
{
	static function grid($app_id)
	{
		$apps = PayplansHelperApp::getAvailableApps();
		if(!isset($apps[$app_id])) return XiText::_('COM_PAYPLANS_INVALID_APP_TYPE');
		return $apps[$app_id]->getTitle();
	}
	
	/**
	 * @return select box html of all available apps
	 * @param $name - name for the html element
	 * @param $value- selected value of app
	 * @param $type - type of app needed
	 * @param $attr - other attributes of select box html
	 */
	static function edit($name, $value, $types ='', $attr=null , $ignoreType=array())
	{
		$purposeApps = PayplansHelperApp::getPurposeApps($types);

		$queryFilter = isset($attr['queryFilter']) ? $attr['queryFilter'] : array();
		$apps = XiFactory::getInstance('app', 'model')->loadRecords($queryFilter);

		$readonly = isset($attr['readonly']);
		if($readonly){
			$html = '<lable>'.$apps[$value]->title.'</label>';
			$html.=	'<input type="hidden" name="'.$name.'" value="'.$value.'" />';
			return $html;
		}
		
		if(isset($attr['multiple']))
			return  PayplansHtml::_('autocomplete.edit',$apps, $name, $attr, 'app_id', 'title', $value);

		$options = array();
		if(isset($attr['none']))
			$options[] = JHTML::_('select.option', '', XiText::_('COM_PAYPLANS_SELECT_APP'));
		
		foreach($apps as $app){
			if(!($app->published))
				continue;

			if(!in_array($app->type, $purposeApps) || (in_array($app->type, $ignoreType)))
				continue;

			$options[] = JHTML::_('select.option', $app->app_id, PayplansHelperFormat::app($app));
		}
		// XITODO : clean attr concept
		$style = isset($attr['style']) ? $attr['style'] : '';
		return JHTML::_('select.genericlist', $options, $name, $style, 'value', 'text', $value);
	}
	
	static function filter($name, $view, Array $filters = array(), $type = '', $prefix='filter_payplans', $attr = "")
	{
		$elementName  = $prefix.'_'.$view.'_'.$name;
		$elementValue = @array_shift($filters[$name]);
		
		$attr['none']  = true;
		$attr['style'] = (isset($attr['style']) ? $attr['style'] : '').' onchange="document.adminForm.submit();"';

		return PayplansHtml::_('apps.edit', $elementName.'[]', $elementValue, $type, $attr);
	}
}