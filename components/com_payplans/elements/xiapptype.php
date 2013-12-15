<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class JFormFieldXiApptype extends XiField
{
	public $type = 'XiApptype'; 
	
	function getInput()
	{
		$ignore = array();
		$appTypes =	PayplansHelperApp::getXmlData('name');
		
		foreach ($appTypes as $apptype=>$appName)
		{
			$app 		= PayplansApp::getInstance(0,$apptype);
			$apprecords = XiFactory::getInstance('app','model')
		                   ->loadRecords(array('type'=>$apptype,
		                                       'published'=>'1'));
			if(method_exists($app, 'renderWidgetHtml') === false || count($apprecords)== 0)
              $ignore[]=$apptype;
		}
	    return PayplansHtml::_('apptypes.edit', $this->name, $this->value, null, $ignore);
	}
}
