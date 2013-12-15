<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
* 
*/
if(defined('_JEXEC')===false) die();

class PayplansAppUserpreferences extends PayplansApp
{
	protected $_location	= __FILE__;
	
	public function isApplicable($refObject = null, $eventName='')
	{
		if($eventName == 'onPayplansUserpreferencesSaveRequest' || $eventName == 'onPayplansRewriterDisplayTokens') 
		{	
		return true;
		}
	}
	//render Widget
	public function renderWidgetHtml()
	{   
		//get user id
		$userId     = XiFactory::getUser()->id;
		if(!$userId){
			return '';
		}
		
	   $user 		  = PayplansUser::getInstance( $userId);
	   $preference    = $user->getPreference();
	   $data          = array('preference'=>$preference->toArray());
	   if(empty($data['preference']))
	   	return "";
	   	   
	   $form          = Jform::getInstance('user.userPreference',PAYPLANS_PATH_XML.DS.'user.preference.xml',array(), true, '//config');
	   $form->bind($data);
	   
	   $this->assign('form', $form);
       $data = $this->_render('widgethtml');
       return $data;
	}	

	public function onPayplansUserPreferencesSaveRequest($fields)
	{
		if(empty($fields)){
			return true;
		}

		$userid = XiFactory::getUser()->id;
		if(!$userid){
			// show error message
			return true;
		}

		$data = array();
		foreach($fields as $field){
			if(strncmp($field['name'], 'preference',10)==0){
				$data[$field['name']]= $field['value'];
			}
		}
	    $user = PayplansUser::getInstance($userid);	
	    
	    $form          = Jform::getInstance('user.userPreference',PAYPLANS_PATH_XML.DS.'user.preference.xml',array(), true, '//config');
	    $fields        = $form->getFieldset('preference');
		foreach($fields as $field){
			$key     = $field->__get('fieldname');
			$dataKey = 'preference['.$field->__get('fieldname').']';
			if(isset($data[$field->__get('name')])){
				$user->setPreference($key, $data[$dataKey]);
			}
		}	
		$user->save();

		//XITODO : Send success or error message
		$response = XiFactory::getAjaxResponse();
		$response->sendResponse();
	}
	
	function onPayplansRewriterDisplayTokens()
	{
			$form          = Jform::getInstance('user.userPreference',PAYPLANS_PATH_XML.DS.'user.preference.xml',array(), true, '//config');
			$fields        = $form->getFieldset('preference');
			foreach($fields as $field)
			{
				$preferenceParams[$field->fieldname] = $field->value;
			}		

			if(!is_array($preferenceParams) || empty($preferenceParams)){
				continue ;
			}
			
			foreach($preferenceParams as $key => $value){
				$rewriterToken[JString::strtoupper('USER_PREFERENCE'.'_'.$key)] = 0 ;
			}
		
		
		$contentLi = '<li><a href="#User_Preference" data-toggle="tab">User Preference</a></li>';
		$contentsTab = '<div class="tab-pane" id="User_Preference">';
		foreach($rewriterToken as $key => $val){			
			$contentsTab .= '[['.$key.']]<br/>';			
		}
		$contentsTab .= "</div>"; 
		return array($contentLi, $contentsTab);
	}

}
