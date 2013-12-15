<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class JFormFieldRegistrationtype extends XiField
{
	public $type = 'Registrationtype'; 
	
	function getInput()
	{
		$path = JPATH_ROOT.DS.'plugins'.DS.'payplansregistration';

		$html   = '<select id="'.$this->id.'" name="'.$this->name.'" title="' . XiText::_('COM_PAYPLANS_REGISTRATION_TYPE') . '">';
		
		$files = JFolder::folders($path);
		 
        foreach($files  as $file ){
        	$file = JFile::stripExt($file);
        	
        	if(XiHelperPlugin::getStatus($file, 'payplansregistration') == false){
        		continue;
        	}
        	
            $selected   = ( JString::trim($file) == $this->value ) ? ' selected="true"' : '';
            $html   .= '<option value="' . $file . '"' . $selected . '>' . XiText::_('COM_PAYPLANS_REGISTRATION_TYPE_'.JString::strtoupper($file)) . '</option>';
        }

        $html   .= '</select>';
        return $html;
	}
}