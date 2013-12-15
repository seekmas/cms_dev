<?php

/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license	GNU/GPL, see LICENSE.php
* @package	Payplans
* @subpackage	Payplans App Manager
* @contact	payplans@readybytes.in
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Payplans Apps Manager Plugin
 *
 * @author payplans
 */
class plgPayplansAppmanager extends XiPlugin
{
	public $_name = 'Appmanager';
	
	/**
	 * load plugin's view,controller,model,table and lib
	 */
	public function onPayplansSystemStart()
	{
		//autoload view,controller,model and table
        $dir = dirname(__FILE__).DS.'appmanager';
        PayplansHelperLoader::addAutoLoadFile($dir.DS.'view.php', 'PayplansadminViewAppmanager');
		PayplansHelperLoader::addAutoLoadFile($dir.DS.'controller.php', 'PayplansadminControllerAppmanager');
		PayplansHelperLoader::addAutoLoadFile($dir.DS.'helper.php', 'PayplansHelperAppmanager');
		
        return true;
	}
	
	/**
	 * set plugin template for backend and front end
	 */
	public function onPayplansViewBeforeRender(XiView $view, $task)
	{
		//add admin-submenu when plugin in enabled
		if(XiFactory::getApplication()->isAdmin()){
			XiAbstractView::addSubmenus('appmanager');
		}
		
		//for admin side
		if($view instanceof PayplansadminViewAppmanager){
			// we need to add the normal template overriding paths
			$templatePaths = $this->_getTemplatePath($this->_name);
			$view->addPathToView($templatePaths);
		}

		return true;
	}

	public function onPayplansCron()
    {
        if(PayplansHelperAppmanager::checkForClearCache())
        {
        	PayplansHelperAppmanager::clearCache();
        	PayplansHelperAppmanager::updateCache();
        }
        
        return true;
    }
}

