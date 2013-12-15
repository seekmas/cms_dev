<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHtmlUsers
{
	/**
	 * @return select box html of all available users
	 * @param $name - name for the html element
	 * @param $value- selected value of user which is user id
	 * @param $attr - other attributes of select box html
	 */
	static function edit( $name, $value, $attr=null )
	{
		if( $value !== 0 && is_array($value)==false){
			$selected = array($value);
		}
		$options = array();
		$usersForFBSelect = array();
		if(!empty($selected)){
			$users               = XiHelperJoomla::getJoomlaUsers($selected);
			foreach($selected as $u){
				$tmpUser            = new stdClass();
    			$tmpUser->id 		= $users[$u]->id;
    			$tmpUser->name		= $users[$u]->name.' ('.$users[$u]->username.')';
				$usersForFBSelect[$tmpUser->id] = $tmpUser;
				$options[] = JHTML::_('select.option', $tmpUser->id, $tmpUser->name);

			}
		}
		$url = 'index.php?option=com_payplans&view=user&task=search&isJSON=true';
		if(isset($attr['multiple']) || isset($attr['usexifbselect'])){
			return  PayplansHtml::_('autocomplete.edit',$usersForFBSelect, $name, $attr, 'id', 'name', $value,$url,true);
		}
		$style = (isset($attr['style'])) ? $attr['style'] : '';
		return JHTML::_('select.genericlist', $options, $name, $style, 'value', 'text', $value);
	}
}