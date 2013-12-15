<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class JFormFieldPrice extends XiField
{
	public $type = 'Price'; 
	
	function getInput()
	{
		$size  = ((string) $this->element['size'] ? 'size="'.(string) $this->element['size'].'"' : '' );
		$class = ( (string) $this->element['class'] ? 'class="'.(string) $this->element['class'].'"' : 'class="text_area"' );
       
		$html = '';
		if($class=='class="readonly"'){
			$html = '<input type="hidden" name="'.$this->name.'" value="'.$this->value.'">';
		}
		return $html.PayplansHtml::_('price.edit', $this->name, $this->name.'_0', $this->value, $class, $size, array());	
	}
}