<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHtmlStatus
{
	/**
	 * @return select box html of all available status
	 * @param $name - name for the html element
	 * @param $value- selected value of status
	 * @param $entity : for which entity id, status are asked
	 * @param $attr - other attributes of select box html
	 */
	static function grid($name, $value, $entity, $attr = null, $class="", $attributeId="", $recordKey)
	{
		$status = PayplansStatus::getStatusOf($entity);
		
		$options = array();
		$stvalues = array();
		
		if(isset($attr['none'])){
			$stvalue = new stdClass();
			$stvalue->name =  XiText::_('COM_PAYPLANS_SELECT_STATUS');
			$stvalue->value = 0;
			$stvalues[0] = $stvalue; 
			$options[] = JHTML::_('select.option', '', XiText::_('COM_PAYPLANS_SELECT_STATUS'));
		}
		
		foreach($status as $key => $val){
			$stvalue = new stdClass();
			$stvalue->name =  XiText::_('COM_PAYPLANS_STATUS_'.$val);
			$stvalue->value = $key;
			$stvalues[$key] = $stvalue; 
    		$options[] = JHTML::_('select.option', $key, XiText::_('COM_PAYPLANS_STATUS_'.$val));
		}	
		
    	$style = (isset($attr['style'])) ? $attr['style'] : "class=$class entity-id=$attributeId record-key=$recordKey";
    	return JHTML::_('select.genericlist', $options, $name, $style, 'value', 'text', $value);
	}
	
	/**
	 * @return select box html of all available status
	 * @param $name - name for the html element
	 * @param $value- selected value of status
	 * @param $entity : for which entity id, status are asked
	 * @param $attr - other attributes of select box html
	 */
	static function edit($name, $value, $entity,$exclude='', $attr = null,$class="", $useAutoComplete=true)
	{
		$status = PayplansStatus::getStatusOf($entity);
		if($exclude){
			foreach($status as $key => $val){
				if(preg_match("/^{$exclude}_/i", $val))
					unset($status[$key]);
			}
		}
		$options = array();
		$stvalues = array();
		
		if(isset($attr['none'])){
			$stvalue = new stdClass();
			$stvalue->name =  XiText::_('COM_PAYPLANS_SELECT_STATUS');
			$stvalue->value = 0;
			$stvalues[0] = $stvalue; 
			$options[] = JHTML::_('select.option', '', XiText::_('COM_PAYPLANS_SELECT_STATUS'));
		}
		
		foreach($status as $key => $val){
			$stvalue = new stdClass();
			$stvalue->name =  XiText::_('COM_PAYPLANS_STATUS_'.$val);
			$stvalue->value = $key;
			$stvalues[$key] = $stvalue; 
    		$options[] = JHTML::_('select.option', $key, XiText::_('COM_PAYPLANS_STATUS_'.$val));
		}
				
		if(isset($attr['multiple']) && $useAutoComplete){
			return  PayplansHtml::_('autocomplete.edit', $stvalues, $name, $attr, 'value', 'name', $value);
		}
    	
    	if(isset($attr['multiple'])){
    		return JHTML::_('select.genericlist', $options, $name, $attr, 'value', 'text', $value);
    	}
   		
		$style = (isset($attr['style'])) ? $attr['style'] : "class=$class";
    	return JHTML::_('select.genericlist', $options, $name, $style, 'value', 'text', $value);
	}
	
	static function filter($name, $view, Array $filters = array(), $entity, $prefix='filter_payplans', $attr = "")
	{
		$elementName  = $prefix.'_'.$view.'_'.$name;
		$elementValue = @array_shift($filters[$name]);
		
		$attr['none']  = true;
		$attr['style'] = (isset($attr['style']) ? $attr['style'] : '').' onchange="document.adminForm.submit();"';
		return PayplansHtml::_('status.edit', $elementName.'[]', $elementValue, $entity,'', $attr);
	}
}