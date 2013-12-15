<?php
/**
*                       _oo0oo_
*                      o8888888o
*                      88" . "88
*                      (| -_- |)
*                      0\  =  /0
*                    ___/`---'\___
*                  .' \\|     |// '.
*                 / \\|||  :  |||// \
*                / _||||| -:- |||||- \
*               |   | \\\  -  /// |   |
*               | \_|  ''\---/''  |_/ |
*               \  .-\__  '-'  ___/-. /
*             ___'. .'  /--.--\  `. .'___
*          ."" '<  `.___\_<|>_/___.' >' "".
*         | | :  `- \`.;`\ _ /`;.`/ - ` : | |
*         \  \ `_.   \_ __\ /__ _/   .-` /  /
*     =====`-.____`.___ \_____/___.-`___.-'=====
*                       `=---='
*     ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
*
*               佛祖保佑         mot代码永无BUG
* 
* @author 		mot
* @package     	Joomla.Site
* @subpackage  	com_catalogue
* @since 		3.0
* @copyright   	Copyright (C) 2013 onlinelean, Inc. All rights reserved.
* @license     	GNU General Public License version 2 or later; see LICENSE.txt
*/
defined('_JEXEC') or die;

class LessonViewCatalogue extends JViewLegacy
{

	/**
	* @access public
	* @return null
	*/
	function display( $tpl=null)
	{

		$catalogue = $this->getModel('catalogue')->getCatalogue();	

		$user		= JFactory::getUser();

		$this->assignRef( 'catalogue' , $catalogue );

		parent::display( $tpl);
	}
}