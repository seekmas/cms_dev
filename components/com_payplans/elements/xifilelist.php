<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @contact 		payplans@readybytes.in
*/	


// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

class JElementXiFilelist extends XiElement
{
	/**
	 * Element name
	 *
	 * @var    string
	 */
	protected $_name = 'XiFilelist';

	
	static public function fetchElement($name, $value, &$node, $control_name)
	{
		
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');

		// path to images directory
		$path = JPATH_ROOT . '/' . $node->attributes('directory');
		$filter = $node->attributes('filter');
		$exclude = $node->attributes('exclude');
		$stripExt = $node->attributes('stripext');
		$files = JFolder::files($path, $filter);

		$options = array();

		if (!$node->attributes('hide_none'))
		{
			$options[] = JHtml::_('select.option', '-1', JText::_('JOPTION_DO_NOT_USE'));
		}

		if (!$node->attributes('hide_default'))
		{
			$options[] = JHtml::_('select.option', '', JText::_('JOPTION_USE_DEFAULT'));
		}

		if (is_array($files))
		{
			foreach ($files as $file)
			{
				if ($exclude)
				{
					if (preg_match(chr(1) . $exclude . chr(1), $file))
					{
						continue;
					}
				}
				if ($stripExt)
				{
					$file = JFile::stripExt($file);
				}
				$options[] = JHtml::_('select.option', $file, $file);
			}
		}

		return JHtml::_(
			'select.genericlist',
			$options,
			$control_name . '[' . $name . ']',
			array('id' => 'param' . $name, 'list.attr' => 'class="inputbox"', 'list.select' => $value)
		);
	}
}	
class JFormFieldXiFilelist extends XiField
{
	public $type = 'XiFilelist'; 
}

