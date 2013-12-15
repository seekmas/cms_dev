<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class JFormFieldXifbselect extends XiField
{
	public $type = 'Xifbselect'; 
	
	function getInput()
	{
		$class = ((string)$this->element['class'] ? 'class="'.(string)$this->element['class'].'"' : 'class="inputbox"' );
		$size  = ( (string)$this->element['size'] ? 'size="'.(string)$this->element['size'].'"' : 'size="5"' );
		$attribs['multiple'] = ((string)$this->element['multiple'] ? (string)$this->element['multiple'] : NULL);
		$options = array ();
		
		foreach ($this->element->children() as $option)
		{
			$val	= (string)$option['value'];
			$options[$val] = JHTML::_('select.option', $val, JText::_((string)$option));
		}

		return PayplansHtml::_('autocomplete.edit',  $options, ''.$this->name, $attribs , 'value', 'text', $this->value);	
	}
}