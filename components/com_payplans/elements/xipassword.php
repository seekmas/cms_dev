<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @contact 		payplans@readybytes.in
*/	


// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

class JElementXiPassword extends XiElement
{
	/**
	 * Element name
	 *
	 * @var    string
	 */
	protected $_name = 'XiPassword';

	
	static public function fetchElement($name, $value, &$node, $control_name)
	{
		$size = ($node->attributes('size') ? 'size="' . $node->attributes('size') . '"' : '');
		$class = ($node->attributes('class') ? 'class="' . $node->attributes('class') . '"' : 'class="text_area"');

		return '<input type="password" name="' . $control_name . '[' . $name . ']" id="' . $control_name . $name . '" value="' . $value . '" '
			. $class . ' ' . $size . ' />';
	}
}

class JFormFieldXiPassword extends XiField
{
	public $type = 'XiPassword'; 
}
