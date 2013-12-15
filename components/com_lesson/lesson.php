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
 * @package     Joomla.Site
 * @subpackage  com_lesson
 *
 * @copyright   Copyright (C) 2013 onlinelean, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

$controller = JControllerLegacy::getInstance('Lesson');
$controller->execute(JFactory::getApplication()->input->get('task', 'display'));
$controller->redirect();

/**
 * @description here list table column for lesson component
 * @table(name='lessons')
 * @column( id(integer_10) sort(medium_int_3) name(char_255) description(text) cover(char_255) timeupdated(int_10) timecreated(int_10) )
 * @table(name="lessons_unit")
 * @column( id          (integer_10) 
 *          parent_id   (integer_10) 
 *          description (text) 
 *          cover       (char_255) 
 *          path        (char_255) 
 *          file        (char_255) 
 *          caption_a   (char_255) 
 *          caption_b   (char_255) 
 *          time        (int_10) 
 *          voice       (tinyint_1) 
 *          timecreated (int_10) 
 *          timeupdated (int_10) )
 *
 */