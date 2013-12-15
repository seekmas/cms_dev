<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class JFormFieldXiarticle extends XiField
{
	public $type = 'Xiarticle'; 
	 function getInput()
	{			
		$articles = XiHelperJoomla::getJoomlaArticles();

		if(empty($articles)){
			return XiText::_('COM_PAYPLANS_CONTENTACL_NO_ARTICLE_AVAILABLE');	
		}
		
		return PayplansHtml::_('autocomplete.edit', $articles, $this->name, array('multiple'=>true), 'id', 'title', $this->value);
	}
}