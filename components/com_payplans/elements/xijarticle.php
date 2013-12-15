<?php
/**
 * @version		$Id: article.php 21097 2011-04-07 15:38:03Z dextercowley $
 * @package		Joomla.Administrator
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;
	
class JFormFieldXijarticle extends XiField
{
	public $type = 'Xijarticle'; 
	
	function getInput()
	{
		return XiHelperJoomla::getArticleElementHtml($this->group, $this->fieldname, $this->value);
	}
}
