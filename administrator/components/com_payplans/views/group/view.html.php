<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansadminViewGroup extends XiView
{
	function edit($tpl=null,$itemId=null)
	{
		$itemId = ($itemId === null) ? $this->getModel()->getState('id') : $itemId;

		$group	= PayplansGroup::getInstance($itemId); 
		
		$logRecords	= XiFactory::getInstance('log', 'model')
								->loadRecords(array('object_id'=>$itemId, 'class'=>'PayplansGroup'));
		
        $form = $group->getModelform()->getForm($group);
        $this->assign('form', $form );
		$this->assign('group', $group);
		$this->assign('log_records', $logRecords);

		return true;
	}
	
}

