<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();

class PayplansadminControllerResource extends XiController
{
	protected	$_defaultOrderingDirection = 'DESC';
	public function _save(array $data, $itemId=null, $type=null)
	{
		if(isset($data['subscription_ids']) && !empty($data['subscription_ids'])){
			$data['subscription_ids'] 	= ','.implode(',', $data['subscription_ids']).',';
		}
		
		return parent::_save($data, $itemId, $type);
	}
}
