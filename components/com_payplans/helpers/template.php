<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHelperTemplate extends XiHelperTemplate
{
	static $_assetsLoaded = false;
	public static function loadAssets()
	{
		if(self::$_assetsLoaded === true){
			return true;
		}
		
		// setup env and scripts
		parent::loadSetupEnv();
		parent::loadSetupScripts(XiFactory::getConfig()->expert_use_jquery, 
								 XiFactory::getConfig()->expert_use_bootstrap_jquery,
								 XiFactory::getConfig()->rtl_support,
								 XiFactory::getConfig()->expert_use_bootstrap_css
								);

		// load payplans core js code
		PayplansHtml::script(PAYPLANS_PATH_MEDIA.DS.'js/payplans.js');
		
		return self::$_assetsLoaded = true;
	}
}