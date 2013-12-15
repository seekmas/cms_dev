<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansModelGroup extends XiModel
{
	public $filterMatchOpeartor = array(
										'title'		=> array('LIKE'),
										'parent'	=> array('='),
										'published'	=> array('='),
										'visible'	=> array('=')			
										);
										
	//XITODO : Apply validation when it is applied all over
	function validate(&$data, $pk=null,array $filter = array(),array $ignore = array())
	{
		return true;
	}
	
	
	public function delete($pk=null)
	{
	  if(!parent::delete($pk))
		{
			$db = JFactory::getDBO();
			XiError::raiseError(500, $db->getErrorMsg());
		}
		// delete entry from plangroup table
		return XiFactory::getInstance('plangroup', 'model')
			 	 ->deleteMany(array('group_id' => $pk));
	}
	
}

class PayplansModelformGroup extends XiModelform {}