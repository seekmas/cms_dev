<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @contact 		payplans@readybytes.in
*/	

defined('JPATH_PLATFORM') or die;

class JElementXiCalendar extends XiElement
{
	/**
	 * Element name
	 */
	protected $_name = 'XiCalendar';

	/**
	 * Fetch a calendar element
	 *
	 * @param   string       $name          Element name
	 * @param   string       $value         Element value
	 * @param   JXMLElement  &$node         JXMLElement node object containing the settings for the element
	 * @param   string       $control_name  Control name
	 *
	 * @return  string   HTML string for a calendar
	 */
	static public function fetchElement($name, $value, &$node, $control_name)
	{
		// Load the calendar behavior
		JHtml::_('behavior.calendar');

		$format = ($node->attributes('format') ? $node->attributes('format') : '%Y-%m-%d');
		$class = $node->attributes('class') ? $node->attributes('class') : 'inputbox';
		$id = $control_name . $name;
		$name = $control_name . '[' . $name . ']';

		return PayplansHtml::_('calendar', $value, $name, $id, $format, array('class' => $class));
	}
}

class JFormFieldXiCalendar extends XiField
{
	public $type = 'XiCalendar'; 
	
	function getInput()
	{
		JHtml::_('behavior.calendar');

		$format = ((string)$this->element['format'] ? (string)$this->element['format'] : '%Y-%m-%d');
		$class = (string)$this->element['class'] ? (string)$this->element['class'] : 'inputbox';

		return PayplansHtml::_('calendar', $this->value, $this->name, $this->id, $format, array('class' => $class));
	}
}