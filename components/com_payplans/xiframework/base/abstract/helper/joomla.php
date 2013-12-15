<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class XiAbstractHelperJoomlaBase
{
	public static function changePluginState($element, $folder = 'system', $state=parent::ENABLE)
	{
		$db		= XiFactory::getDBO();
		$query	= 'UPDATE '. $db->quoteName( '#__extensions' )
				. ' SET   '. $db->quoteName('enabled').'='.$db->Quote($state)
				. ' WHERE '. $db->quoteName('element').'='.$db->Quote($element)
				. ' AND ' . $db->quoteName('folder').'='.$db->Quote($folder) 
				. " AND `type`='plugin' ";
		
		$db->setQuery($query);
		return $db->query();
	}
	
	public static function getPluginPath($plugin)
	{
		return  JPATH_PLUGINS.DS.$plugin->get('_type').DS.$plugin->get('_name').DS.$plugin->get('_name');
	}
	
	public static function isMenuExist($link, $cid, $published=null, $alias=null)
	{
		$strQuery	= "SELECT `alias` FROM `#__menu` "
					  ." WHERE `link` LIKE '$link' AND "
					  ."`client_id` = 0 AND"
					  ." `component_id`={$cid}"
					  . ( ($published !==null) ? " AND `published`= $published " : " ")
					  . ( ($alias !==null) ? " AND `alias`= '$alias' " : " ") 
					  ;

		$db = XiFactory::getDBO();
		$db->setQuery($strQuery);
		return $db->loadResult() ? true : false;
	}
	
	public static function addMenu($title, $alias, $link, $menu, $cid)
	{
		if(self::isMenuExist($link, $cid, null, $alias)){
			return true;
		}
		
		jimport('joomla.application.application');
		$defaultMenuType	= JApplication::getInstance('site')->getMenu()->getDefault('workaround_joomla_bug')->menutype;
	
		$db = XiFactory::getDBO();
	
		// Update the existing menu items.
		$row		= JTable::getInstance ( 'menu', 'JTable' );
		
		$row->id = null; 
		$row->menutype 		= $defaultMenuType;
		$row->title 		= $title;
		$row->alias 		= $alias;
		$row->link 			= $link;
		$row->type 			= 'component';
        $row->language   	= '*';
		$row->published 	= '1';
		$row->component_id 	= $cid;
//		$row->ordering 		= $order;
//		$row->parent_id		= 1; // gives segmentation fault
		
				
		if(!$row->check() || !$row->store()){
			return false;
		}

		//update parent id
		$query =   ' UPDATE '. $db->quoteName( '#__menu' ) 
				 . ' SET `parent_id` = '.$db->quote(1).', `level` = ' . $db->quote(1) 
				 . ' WHERE `id` = '.$db->quote($row->id) ;
		$db->setQuery( $query );
		return $db->query();

		return true;
	} 
	
	public static function getUsertype()
	{
		$db	 =  JFactory::getDBO();
		$sql = ' SELECT `title` FROM '.$db->quoteName('#__usergroups')
				.' WHERE '.$db->quoteName('title').' NOT LIKE "%Public%"';
		$db->setQuery($sql);
		return $db->loadColumn();
	}
	
	public static function isAdmin($userId)
	{
		if(!$userId || !XiFactory::getUser($userId)->authorise('core.admin')){
			return false;
		}
		
		return true;
	}
	
	public static function getJoomlaGroups()
	{
		$db  = JFactory::getDBO();

		$sql = 'SELECT a.id AS value, a.title AS name, COUNT(DISTINCT b.id) AS level' .
			' FROM #__usergroups AS a' .
			' LEFT JOIN `#__usergroups` AS b ON a.lft > b.lft AND a.rgt < b.rgt' .
			' GROUP BY a.id' .
			' ORDER BY a.lft ASC';
		$db->setQuery($sql);
		$groups =  $db->loadObjectList('value');
		
		// filter groups
		// unset groups which are core.admin
		$cloneGroups = $groups;
		foreach($cloneGroups as $group){
			if(!class_exists('JAccessRules')){
				jimport('joomla.access.rules');
			}
			// if its admin group
			if(JAccess::getAssetRules(1)->allow('core.admin', $group->value)){
				unset($groups[$group->value]);
			}
		}
		
		return $groups;
	}
	
	public static function addUserToGroup($userId, $group)
	{
		jimport('joomla.user.helper');
		return JUserHelper::addUserToGroup($userId, $group);
	}
	
	public static function setUserGroups($userId, $group)
	{
		jimport('joomla.user.helper');
		
		if(!is_array($group)){
			$group = (array)$group;
		}
		
		// if user has any core.admin user group
		// then core.admin groups also be set, remove others
		$usergroups = JUserHelper::getUserGroups($userId);
		foreach($usergroups as $usergroup){
			// if its admin group
			if(JAccess::getAssetRules(1)->allow('core.admin', $usergroup)){
				$group[]= $usergroup;
			}
		}
		
		return JUserHelper::setUserGroups($userId, $group);
	} 
	
	public static function removeUserFromGroup($userId, $group)
	{
		jimport('joomla.user.helper');
		return JUserHelper::removeUserFromGroup($userId, $group);
	}
	
	//	XiTODO: Use joomla's article element : J30
	public static function getArticleElementHtml($control_name, $name, $value)
	{
		// Load the modal behavior script.
		PayplansHtml::_('behavior.modal', 'a.modal');

		// Build the script.
		$script = array();
		$script[] = '	function jSelectArticle_'.$control_name.'_'.$name.'(id, title, catid, object) {';
		$script[] = '		document.id("'.$control_name.'_'.$name.'_id").value = id;';
		$script[] = '		document.id("'.$control_name.'_'.$name.'_name").value = title;';
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Add the script to the document head.
		XiFactory::getDocument()->addScriptDeclaration(implode("\n", $script));


		// Setup variables for display.
		$html	= array();
		$link	= 'index.php?option=com_content&amp;view=articles&amp;layout=modal&amp;tmpl=component&amp;function=jSelectArticle_'.$control_name.'_'.$name;

		$db	= XiFactory::getDBO();
		$db->setQuery(
			'SELECT title' .
			' FROM #__content' .
			' WHERE id = '.(int) $value
		);
		$title = $db->loadResult();

		if ($error = $db->getErrorMsg()) {
			XiError::assert(false, $error, XiError::ERROR);
		}

		if (empty($title)) {
			$title = XiText::_('COM_PAYPLANS_APP_CONTENT_JOOMLA_SELECT_ARTICLE');
		}
		$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

		// The current user display field.
		$html[] = '<div class="fltlft">';
		$html[] = '  <input type="text" id="'.$control_name.'_'.$name.'_name" value="'.$title.'" disabled="disabled" size="35" />';

		// The user select button.
		$html[] = '	<a class="modal" title="'.XiText::_('COM_PAYPLANS_APP_CONTENT_JOOMLA_SELECT_ARTICLE').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 800, y: 450}}">'.JText::_('COM_PAYPLANS_APP_CONTENT_JOOMLA_SELECT_ARTICLE_BUTTON').'</a>';
		$html[] = '  </div>';

		// The active article id field.
		if (0 == (int)$value) {
			$value = '';
		} else {
			$value = (int)$value;
		}

		// class='required' for client side validation
		$class = '';

		$html[] = '<input type="hidden" id="'.$control_name.'_'.$name.'_id"'.$class.' name= "Payplans_form['.$control_name.']['.$name.']" value="'.$value.'" />';

		return implode("\n", $html);
	}
	
	public static function getUserTimeZone($config = null, $user = null)
	{
		//$user and $config is for testing purpose only
		$config = ($config==null) ? XiFactory::getConfig() 	: $config;
		$my		= ($user==null)   ? XiFactory::getUser() 	: $user;
		
		//default offset
		$timezone = $config->offset;
		
		//if user is logged in, then do it as per him
		if($my->id){
			$timezone = $my->getParam('timezone', $timezone);
		}
		
		$zoneObject = new DateTimeZone($timezone);
		$offset = $zoneObject->getOffset(new DateTime("now")); // Offset in seconds
		return round($offset/3600, 2); // prints "+1100"
	}
	
	public static function getJoomlaUserGroups($userid)
	{
	  jimport('joomla.user.helper');
	  $usergroups = JUserHelper::getUserGroups($userid);
	  if(PAYPLANS_JVERSION_25 || PAYPLANS_JVERSION_30)
	  {
	  	$db      = XiFactory::getDBO();
	  	$groups  = implode(',', $usergroups);
		$db->setQuery( 'SELECT `title`'
				. ' FROM #__usergroups'
				. ' WHERE `id` IN (' . $groups . ')');
		return $db->loadColumn();	
	  }

	  $joomlagroups = array_keys($usergroups);
	  return $joomlagroups;
	}

	public static function getUserEditLink($user)
	{
		return XiRoute::_("index.php?option=com_users&task=user.edit&id=".$user->getId(), false);
	}
	
	// in j1.7+ doesn't have sections
	public static function getJoomlaSections()
	{
		return false;
	}
	
	public static function getUsersToSendSystemEmail()
	{
		if(!class_exists('JAccessRules')){
			jimport('joomla.access.rules');
		}
		
		$rules 	= JAccess::getAssetRules(1);
		$groups = $rules->getData();
		$adminGroup = array_keys($groups['core.admin']->getData());
				
		$db = XiFactory::getDBO();
		//get all super administrator
		$query = "SELECT *
				FROM #__users
				WHERE block = 0
				AND sendEmail = 1
				AND id IN(
						SELECT user_id FROM #__user_usergroup_map WHERE group_id IN (".implode(",", $adminGroup).")
				)";
		
		$db->setQuery( $query );
		return $db->loadObjectList();
	}
	
	/**
	 * Returns the plugins	 
	 * @param string $type
	 * @param string $name
	 * @param boolean $published
	 * @since 2.2
	 */
	public static function getPlugin($type, $name = null, $published = null)
	{
		static $plugins = null;
		
		if(isset($plugins[$type])){
        	return $plugins[$type];
		}        
        
        $query = new XiQuery();
        $query->select('extension_id as eid, folder AS type, element AS name, params, manifest_cache as xml_params')
              ->from('#__extensions')                  
                  ->where('type = "plugin"')
                  ->where(' folder = "'.$type.'"')             
                  ->order('ordering');

        if($published !== null){
			$query->where(' enabled = '.$published);
		}

		if($name !== null){
			$query->where(' element = "'.$name.'"');
		}	

		$plugins[$type] = $query->dbLoadQuery()
				   	 			->loadObjectList('name');			
		
		return $plugins[$type];
	}
		
}

// Include the Joomla Version Specific class, which will ad XiAbstractHelperToolbar class automatically
XiError::assert(class_exists('XiAbstractJ'.PAYPLANS_JVERSION_FAMILY.'HelperJoomla',true), XiError::ERROR);

