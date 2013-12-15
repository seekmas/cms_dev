<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	App Manager
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansadminViewAppmanager extends XiView
{
	protected function _adminGridToolbar()
	{
		XiHelperToolbar::openPopup('credential', 'credential', 'credential.png', 'PLG_PAYPLANS_APPMANAGER_TOOLBAR_CREDENTIAL', true );
		XiHelperToolbar::openPopup('manualupdatecache', 'refresh', 'refresh.png', 'PLG_PAYPLANS_APPMANAGER_TOOLBAR_MANUALUPDATECACHE', true );
		XiHelperToolbar::openPopup('help', 'info-sign', 'info-sign.png', 'PLG_PAYPLANS_APPMANAGER_TOOLBAR_HELP', true );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see components/com_payplans/xiframework/base/abstract/XiAbstractView::_getTemplatePath()
	 */
	function _getTemplatePath($layout = 'default')
	{
		return array_merge(parent::_getTemplatePath($layout),$this->_path['template']);
	}
	
	/**
	 * Display the default screen 
	 * (non-PHPdoc)
	 * @see components/com_payplans/xiframework/base/XiView::display()
	 * @return boolean
	 */
	function display($tpl=null)
	{	
		$list 				= PayplansHelperAppmanager::getAppList();
		$installed_plugins	= PayplansHelperAppmanager::getInstalledPlugins();
		$versions			= PayplansHelperAppmanager::getReleasedAppVersion();
		
		$post = JRequest::get('post');
		if(isset($post['filter'])){
			$list = $this->_applyFilters($list, $installed_plugins, $versions, $post['filter']);			
		}
		else{
			$post['filter'] = array();
		}
		
		//Afet installation when user is redirected to app manager then show the upgaradable apps.
		// if not then show all the apps 
		$upgrade = JRequest::getVar('checkUpgradeApps',0);
		if($upgrade == 1)
		{
			$temp   = unserialize(serialize($list));
			$filter = array('plugin_state' => 'upgradable');
			$post['filter'] = $filter;
			$temp = $this->_applyFilters($temp, $installed_plugins, $versions, $post['filter']);
			if(!count($temp->items))
			{
				$list = $temp;
			}
			else {
			  $post['filter'] = array();
			}
		}

		//data assigned to extension filter		
		$extensions = array(    'plg'=>'Plugins'
							   ,'mod'=>'Modules'
							   ,'file'=>'Languages'
							   ,'lib'=>'Libraries'
							   ,'com'=>'Components');
		
		$this->assign('extensions', $extensions);
		$this->assign('filter', $post['filter']);
		$this->assign('applist', $list->items);
		$this->assign('categories', $list->categories);
		$this->assign('versions', (array) $versions);
		$this->assign('installed_plugins', $installed_plugins);		
		return true;
	}

	protected function _applyFilters($list, $installed_plugins, $versions, $filter)
	{
		$category 	  = isset($filter['app_catergory']) ? $filter['app_catergory'] : '';
		$plugin_state = isset($filter['plugin_state']) ? $filter['plugin_state'] : '';
		$keyword  	  = isset($filter['search']) ? $filter['search'] : '';
		$keyword   	  = strtolower($keyword);
		$versions	  = (array) $versions;
		$extension_type = isset($filter['extension_type']) ? $filter['extension_type'] : '';
		
		//filter for popular apps according to rating
		if($plugin_state == 'popular')
		{
			//firstly filter all rating to be sorted
			$arraytosort = array();
			foreach ($list->items as $key => $value)
			{
				foreach ($value as $innerKey => $innerValue)
				{	
					if($innerKey == 'rating')
					$arraytosort[$key] = $innerValue->value;		
				}	
	
			}
						
			//sort according to rating 
			arsort($arraytosort);

			$sortedArray = array();
			
			foreach ($arraytosort as $key => $value)
			{
				$sortedArray[] = $list->items->$key;
			}
				
			$list->items = (object)$sortedArray;
		}
		
		foreach($list->items as $name => $item){
			
			$exactAppName = $item->extension_type.'_'.$item->app_folder.'_'.$item->app_element.'_'.$item->client_id;
			
			// keyword filter
			if(!empty($keyword)){
				$found = false;
				foreach(array('title', 'teaser') as $index){
					$subject = strtolower($item->$index);
					if(JString::strpos($subject, $keyword) !== FALSE){
						$found = true;
						break;
					}
				}
				
				if($found != true){
					unset($list->items->$name);
				}
			}
			
			// category filter
			if(!empty($category) && !in_array($category, (array)$item->categories)){
				unset($list->items->$name);
			}
			
			// plugin state filter
			if(!empty($plugin_state)){
				if($plugin_state == 'installed'){
					if(!isset($installed_plugins[$exactAppName])){
						unset($list->items->$name);
					}
				}
				
				if($plugin_state == 'available'){
					if(isset($installed_plugins[$exactAppName]) || $item->app_folder == 'inbuilt'){
						unset($list->items->$name);
					}
				}
				
				if($plugin_state == 'upgradable'){
					if(!isset($installed_plugins[$exactAppName])){
						unset($list->items->$name);
					}
					
					$current_version  = isset($installed_plugins[$exactAppName]->build_version)
															? $installed_plugins[$exactAppName]->build_version
															: '0.0.0.0'; 
					$released_version = isset($versions[$item->extension_type.'_'.$item->app_folder.'_'.$item->app_element]) 
															? $versions[$item->extension_type.'_'.$item->app_folder.'_'.$item->app_element]
															: '0.0.0.0';		
															
					$released_version = explode(".", $released_version); 
					if((int)$released_version[3] <= (int)$current_version ){
						unset($list->items->$name);
					}
				}
			}
			
			// extension type filter
			if(!empty($extension_type)){
				if($item->extension_type != $extension_type)
				{
					unset($list->items->$name);
				}
			}
		}	
		
		return $list;
	}
	
	/**
	 * There is no model, so no nee to setup the form
	 * (non-PHPdoc)
	 * @see components/com_payplans/xiframework/base/abstract/XiAbstractView::_basicFormSetup()
	 * @return boolean
	 */
	function _basicFormSetup()
	{
		return true;
	}
	
	/** 
	 * un-install the extension
	 */
	function uninstall()
	{
		$eid 	 		= JRequest::getVar('eid', 0);
		$appType 		= JRequest::getVar('appType', 0);
		$appName 		= JRequest::getVar('appName', 0);
		$extension_type = JRequest::getVar('extension_type', 'plg');
		$client_id 		= JRequest::getVar('client_id', '0');
		
		$tpl = $this->getTpl();
		
		$this->_setAjaxWinTitle(XiText::_('PLG_PAYPLANS_APPMANAGER_'.JString::strtoupper($tpl).'_WINDOW_TITLE'));		
		
		if($tpl === 'uninstall_confirm'){			
			$onclick = "payplans.ajax.go('".XiRoute::_('index.php?option=com_payplans&view=appmanager&task=uninstall&confirm=1&eid='.$eid.'&appType='.$appType.'&appName='.$appName.'&extension_type='.$extension_type.'&client_id='.$client_id)."', Array()); ";
			$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_AJAX_NEXT_BUTTON'), $onclick, null, 'btn btn-primary');		
		}
		
		$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_AJAX_CLOSE_BUTTON'), 'xi.ui.dialog.close();');
		$this->_setAjaxWinAction();		
		
		return true;
	}
	
	/**
	 * install the extension
	 */
	function install()
	{	
		$app_element 	= JRequest::getVar('app_element', '');
		$app_folder 	= JRequest::getVar('app_folder', '');
		$extension_type = JRequest::getVar('extension_type','plg');
		$client_id 		= JRequest::getVar('client_id','0');

		$this->assign('app_element', $app_element);
		$this->assign('app_folder', $app_folder);
		$this->assign('extension_type', $extension_type);
		$this->assign('client_type', $client_id);
		
		return true;
	}
	
	/**
	 * set JPayplans credential
	 */
	public function credential()
	{
		
		$this->_setAjaxWinTitle(XiText::_('PLG_PAYPLANS_APPMANAGER_SET_CREDENTIAL_WINDOW_TITLE'));

		$this->_setAjaxWinAction();		
		list($username, $password) = PayplansHelperAppmanager::getCredential();
		$this->assign('username', $username);
		//$this->assign('password', $password);
		
		//set the error when installation is not done because of crendentials
		$error = JRequest::getVar('error','');
		$this->assign('error',$error);
		
		$onclick = "payplans.plg.appmanager.set_credential();";
		$this->_addAjaxWinAction(XiText::_('PLG_PAYPLANS_APPMANAGER_AJAX_VERIFY_AND_SAVE_BUTTON'), $onclick,null,'btn btn-primary');
		$this->_addAjaxWinAction(XiText::_('COM_PAYPLANS_AJAX_CLOSE_BUTTON'), 'xi.ui.dialog.close();');
		$this->_setAjaxWinAction();		
			
		return true;
	}
	
	public function _getDynamicJavaScript()
	{
		$url	=	"index.php?option=com_payplans&view={$this->getName()}";
		ob_start(); ?>

		payplansAdmin.appmanager_credential = function()
		{
			payplans.url.modal("<?php echo "$url&task=credential"; ?>");
			// do not submit form
			return false;
		}
		
		payplansAdmin.appmanager_manualupdatecache = function()
		{
			payplans.url.modal("<?php echo "$url&task=manualupdatecache"; ?>");
			xi.ui.dialog.title("<?php echo XiText::_('PLG_PAYPLANS_APPMANAGER_TOOLBAR_MANUALUPDATECACHE'); ?>");
			xi.ui.dialog.body("<?php echo XiText::_('PLG_PAYPLANS_APPMANAGER_WAITING_CLEARCACHE'); ?>");
			// do not submit form
			return false;
		}
		
		payplansAdmin.appmanager_help = function()
		{
	      var theurl = "http://pub.jpayplans.com/app-manager/help.html";
	      xi.ui.dialog.create(
	       {url:theurl, data:{iframe:true, id:'pp-appmanager-help'}},
	        '<?php echo XiText::_('PLG_PAYPLANS_APPMANAGER_TOOLBAR_HELP');?>',null,400
	     );
		}
		<?php
		$js = ob_get_contents();
		ob_end_clean();
		return $js;
	}
}

