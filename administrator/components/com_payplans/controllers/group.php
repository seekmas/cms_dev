<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();

class PayplansadminControllerGroup extends XiController
{
	public function _copy($itemId)
	{
		$group = PayplansGroup::getInstance($itemId);
		$group->setId(0);
		$group->set('title', XiText::_("COM_PAYPLANS_COPY_OF").$group->getTitle());
		return $group->save();
	}
	
	public function _save(array $data, $itemId=null, $type=null)
	{
		//XITODO : SECURITY : Should we html_encode the data ?		
		$data['description'] = isset($data['description']) ? $data['description'] : '';	
		$data['description']= JRequest::getVar( 'description', $data['description'], 'post', 'string', JREQUEST_ALLOWRAW );
		return parent::_save($data, $itemId);
	}
}