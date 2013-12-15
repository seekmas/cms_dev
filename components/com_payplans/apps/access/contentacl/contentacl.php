<?php

/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		Payplans
* @subpackage	Discount
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansAppContentacl extends PayplansApp
{
	protected $_location	= __FILE__;
	
	public function isApplicable($refObject = null, $eventName='')
	{
		$option =JRequest::getVar('option');
		if(($eventName === 'onPrepareContent' || $eventName === 'onContentPrepare') && $option == 'com_content'){
			return true;
		}
		return parent::isApplicable($refObject, $eventName);
	}

	function onPrepareContent($item, $params, $limitstart)
	{
		if(! $item instanceof stdClass){
			return true;
		}
		
		$categoryId = (isset($item->category) && is_object($item->category)) ? $item->category->id : $item->catid;
		$sectionId = (isset($item->section) && is_object($item->section)) ? $item->section->id : $item->sectionid;
		$articleId = (isset($item->id)) ? $item->id : null;
		
		$block = $this->getAppParam('blockselection', 'none');
		$url = XiRoute::_('index.php?option=com_payplans&view=plan');

		if($block == 'joomla_category' || $block == 'joomla_section' || $block == 'joomla_article'){
			if($this->_isUserAllowed() === true){
				return true;
			}

			if($block == 'joomla_category'){
				$allowedCat = $this->getAppParam('joomla_category', 0);
				$allowedCat = is_array($allowedCat) ? $allowedCat : array($allowedCat);
				
				//get all parent category of current cat
				$allCat		= $this->_getParentCategories($categoryId);
				
				$tempArray = array_intersect($allCat, $allowedCat);
				if(empty($tempArray)){
					return true;
				}
				$item->text = $item->introtext.'<a id="pp_contentacl_joomla_category" href="'.$url.'">'.XiText::_('COM_PAYPLANS_CONTENTACL_SUBSCRIBE_PLAN').'</a>';

			}

			if($block == 'joomla_section'){
				$allowedSection = $this->getAppParam('joomla_section', 0);
				$allowedSection = is_array($allowedSection) ? $allowedSection : array($allowedSection);
				
				if(!in_array($sectionId, $allowedSection)){
					return true;
				}
				$item->text = $item->introtext.'<a id="pp_contentacl_joomla_section" href="'.$url.'">'.XiText::_('COM_PAYPLANS_CONTENTACL_SUBSCRIBE_PLAN').'</a>';
	
			}
	
			if($block == 'joomla_article'){
				$allowedArticle = $this->getAppParam('joomla_article', 0);
				$allowedArticle = is_array($allowedArticle) ? $allowedArticle : array($allowedArticle);
							
				if(!in_array($articleId, $allowedArticle)){
					return true;
				}
				$item->text = $item->introtext.'<a id="pp_contentacl_joomla_article" href="'.$url.'">'.XiText::_('COM_PAYPLANS_CONTENTACL_SUBSCRIBE_PLAN').'</a>';
			}
				$item->readmore = 0;
				$item->introtext = '';
				$item->fulltext = '';
				$item->link = $url;
		}

		return true;	
	}
	
	//for Joomla 1.6++
	function onContentPrepare($context, &$row, &$params, $page = 0)
	{
		$block	  = $this->getAppParam('block_j17', 'none');
		$url   	  = XiRoute::_('index.php?option=com_payplans&view=plan');
		$view	  = JRequest::getVar('view');
		
		if($view != 'article')
			return true;
			
		if($block == 'none')
			return true;
		
		if($this->_isUserAllowed() === true)
			return true;

		if($block == 'joomla_category'){
			$allowedCat = $this->getAppParam('joomla_category', 0);
			$allowedCat = is_array($allowedCat) ? $allowedCat : array($allowedCat);
			$catId 		= (isset($row->catid)) ? $row->catid : null;
			
			//get all parent category of current cat
			$allCat		= $this->_getParentCategories($catId);
			
			$tempArray = array_intersect($allCat, $allowedCat);
			if(empty($tempArray)){
				return true;
			}
			$row->text = $row->introtext.'<a id="pp_contentacl_joomla_category" href="'.$url.'">'.XiText::_('COM_PAYPLANS_CONTENTACL_SUBSCRIBE_PLAN').'</a>';
		}
		
		if($block == 'joomla_article'){
			$allowedArticle = $this->getAppParam('joomla_article', 0);
			$allowedArticle = is_array($allowedArticle) ? $allowedArticle : array($allowedArticle);
			$articleId 		= (isset($row->id)) ? $row->id : null;
			
			if(!in_array($articleId, $allowedArticle)){
				return true;
			}	
			$row->text = $row->introtext.'<a id="pp_contentacl_joomla_article" href="'.$url.'">'.XiText::_('COM_PAYPLANS_CONTENTACL_SUBSCRIBE_PLAN').'</a>';
		}

		return true;
	}
	
	function _isUserAllowed()
	{
		$userid 	 = XiFactory::getUser()->id;
		if(!$userid){
			return false;
		}
		
		$user = PayplansUser::getInstance($userid);
		
		$userSubs  = $user->getPlans();
		$plans 	   = $this->getPlans();
		
		// return false when user is non-subscriber
		if(empty($userSubs)){
			return false;
		}
		
		// return true when app is core app,
		// no need to check whether plan is attached with this app or not
		if($this->getParam('applyAll',false) != false){
			return true;
		}

		// if user have an active subscription of the plan attached with the app then return true
		foreach($userSubs as $sub){
			if(in_array($sub, $plans)){
				return true;
			}
		}

		return false;
	}

	protected function _getParentCategories($catId)
	{
		$allCat   = array();
		while($catId)
		{
			$allCat[] = $catId;
			
			$db 	= PayplansFactory::getDBO();
			$query = 'SELECT `parent_id`'
				 	. ' FROM #__categories'
				 	. ' WHERE `published` = 1 AND `id` = '.$catId;
				 	;
		 	$db->setQuery( $query );
		 	$catId = $db->loadResult();
		}
		return $allCat;
	}
}

class PayplansAppContentaclFormatter extends PayplansAppFormatter
{
	// get rules
	function getVarFormatter()
	{
		$rules = array('_appplans'       => array('formatter'=> 'PayplansAppFormatter',
										       'function' => 'getAppPlans'),
					   'app_params'      => array('formatter'=> 'PayplansAppContentaclFormatter',
										       'function' => 'getFormattedParams'));
		return $rules;
	}
	
	// format app param
	function getFormattedParams($key, $value, $data)
	{
		$params     = PayplansHelperParam::iniToArray($value);
		$categories = XiHelperJoomla::getJoomlaCategories();
		$sections   = XiHelperJoomla::getJoomlaSections();
		$articles   = XiHelperJoomla::getJoomlaArticles();
		foreach ($params as $param=>$val)
		{
			if($param == 'joomla_category'){
				if(is_array($val)){
					foreach($val as $v){
						$cat[] = $categories[$v]->title;
					}
					$params[$param] = $cat;
				}
				else{
					$params[$param] = $categories[$val]->title;
				}
			}
			if($param == 'joomla_section'){
				if(is_array($val)){
					foreach($val as $v){
						$allSections[] = $sections[$v]->title;
					}
					$params[$param] = $allSections;
				}
				else{
					$params[$param] = $sections[$val]->title;
				}
			}
			if($param == 'joomla_article'){
				if(is_array($val)){
					foreach($val as $v){
						$allArticles[] = $articles[$v]->title;
					}
					$params[$param] = $allArticles;
				}
				else{
					$params[$param] = $articles[$val]->title;
				}
			}
		}
		$value = PayplansHelperParam::arrayToIni($params);
	}
}
