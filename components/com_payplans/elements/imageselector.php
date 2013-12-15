<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class JFormFieldImageselector extends XiField
{
	public $type = 'imageselector'; 
	
	function getInput()
	{
		jimport( 'joomla.filesystem.folder' );
		jimport( 'joomla.filesystem.file' );

		// path to images directory
		$path		= JPATH_ROOT.DS.(string)$this->element['directory'];
		$filter = '\.png$|\.gif$|\.jpg$|\.bmp$|\.ico$';
		$files		= JFolder::files($path, $filter, true, true);

		$options = array ();

		if (!(string)$this->element['hide_none'])
		{
			$options[] = JHTML::_('select.option', '-1', '- '.XiText::_('COM_PAYPLANS_CONFIG_CUSTOMIZATION_INVOICE_LOGO_DO_NOT_USE').' -');
		}


		if ( is_array($files) )
		{
			foreach ($files as $file)
			{
				$refPath = XiHelperUtils::str_ireplace(JPATH_ROOT.DS,'',$file);
				$options[] = JHTML::_('select.option', $refPath, $refPath);
			}
		}

		return JHTML::_('select.genericlist',  $options, $this->name, 'class="inputbox"', 'value', 'text', $this->value, $this->group.$this->fieldname);
		
	}
}