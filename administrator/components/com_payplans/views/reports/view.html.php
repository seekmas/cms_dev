<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansadminViewReports extends XiView
{
	function display($tpl=null)
	{
		if(!empty($tpl))
		{
			$this->setTpl($tpl);	
		}
		
		return true;
	}

	function _basicFormSetup()
	{
		return true;
	}
	
	protected function _adminGridToolbar()
	{
		XiHelperToolbar::openPopup('searchRecords', 'search', 'search.png', 'COM_PAYPLANS_TOOLBAR_SEARCH', true );
	}

	function _getTemplatePath($layout = 'default')
	{
		$paths    = parent::_getTemplatePath($layout);
		$tmplPath = $this->_path['template'];
		foreach ($tmplPath as $path){
			//if(!in_array($path,$paths))
				$paths[] = $path;
		}
				
		return $paths;
	}
}

