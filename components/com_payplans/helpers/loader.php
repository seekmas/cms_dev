<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

// Loader should be already loaded with framework

class PayplansHelperLoader extends XiHelperLoader
{
	//here we will try to register all MC and libs and helpers
	static function addAutoLoadFolder($folder, $type, $prefix='Payplans')
	{
		return parent::addAutoLoadFolder($folder, $type, $prefix);
	}

	/* View are stored very differently */
	static function addAutoLoadViews($baseFolders, $format, $prefix='Payplans')
	{
		return parent::addAutoLoadViews($baseFolders, $format, $prefix);
	}
}

//it will load files which we need from our library
function PayplansImport($filePath, $base=PAYPLANS_PATH_LIBRARY, $key = 'components.com_payplans.libraries.')
{
	return JLoader::import($filePath, $base, $key);
}