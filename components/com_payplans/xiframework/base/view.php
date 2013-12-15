<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();
JHtml::_('behavior.tooltip');

abstract class XiView extends XiAbstractView
{
	public function getDynamicJavaScript()
	{
		// get valid actions for validation submission
		$validActions = $this->getJSValidActions();
		if(!is_array($validActions)){
			$validActions = (array)$validActions;
		}
		
		//common js code to trigger
		ob_start(); ?>

		// current view
		var view = '<?php echo $this->getName();?>' ;
        var validActions = '<?php echo json_encode($validActions);?>' ;

		Joomla.submitbutton = function(action) {
			payplansAdmin.submit(view, action, validActions);
		}

		<?php
		$js = ob_get_contents();
		ob_end_clean();

		return $this->_getDynamicJavaScript().$js;
	}

    public function getJSValidActions()
    {
    	return array('apply', 'save', 'edit', 'delete', 'savenew');
    }

	public function _getDynamicJavaScript()
	{
		return '';
	}

	//Available Task for views, these should only
	//we will later override this
	function display($tpl=null)
	{
		//IMP : If load records is already done before rendering the page
		// then it will not add pagination into it
		// so always clean the query for displaying it on grid views
		$model = $this->getModel();
		$model->clearQuery();

		// IMP : this is required for the pagination issue
		// we should load records after pagination is set, so that it can work well
		$model->getPagination();
		
		$records = $model->loadRecords(array(), array());

		// if total of records is more than 0
		if($model->getTotal() > 0)
			return $this->_displayGrid($records);

		return $this->_displayBlank();
	}

	function _displayBlank()
	{
		$model = $this->getModel();
		$heading = "COM_PAYPLANS_ADMIN_BLANK_".JString::strtoupper($this->getName());
		$msg = "COM_PAYPLANS_ADMIN_BLANK_".JString::strtoupper($this->getName())."_MSG";
		
		$this->assign('heading', XiText::_($heading));
		$this->assign('msg', XiText::_($msg));
		$this->assign('filters', $model->getState(XiHelperContext::getObjectContext($model)));
		
		$this->setTpl('blank');
		
		return true;
	}

	function _displayGrid($records)
	{
		$this->setTpl('grid');

		//do processing for default display page
		$model = $this->getModel();
		$recordKey =  $model->getTable()->getKeyName();
		$this->assign('records', $records);
		$this->assign('record_key', $recordKey);
		$this->assign('pagination', $model->getPagination());
		$this->assign('filter_order', $model->getState('filter_order'));
		$this->assign('filter_order_Dir', $model->getState('filter_order_Dir'));
		$this->assign('limitstart', $model->getState('limitstart'));
		$this->assign('filters', $model->getState(XiHelperContext::getObjectContext($model)));
		return true;
	}



	function view($tpl=null)
	{
		//do processing for default disply page
	}

	function edit($tpl=null)
	{
		$this->setTpl('edit');
		return true;
	}

    //this will set popup window title
    function _setAjaxWinTitle($title){
    	XiFactory::getAjaxResponse()->addScriptCall('xi.ui.dialog.title',$title);
    }

    //this will set action/submit button on bottom of popup window
	function _addAjaxWinAction($text, $onButtonClick=null, $id = null, $classes = 'btn' , $attr = ''){
		static $actions = array();

		if($onButtonClick !== null){
			$object 		 = new stdClass();
			$object->click   = $onButtonClick;
			$object->text 	 = $text;
			$object->classes = $classes;
			$object->attr	 = $attr;
			
			if($id){
				$object->attr .= " id='".$id."' ";
			}
			
			$actions[]=$object;
		}
    	return $actions;
    }

	function _setAjaxWinAction(){
    	$actions = $this->_addAjaxWinAction('',null);

    	if(count($actions)===0){
    		return false;
    	}

    	XiFactory::getAjaxResponse()->addScriptCall('xi.ui.dialog.button',$actions);
    	return true;
    }

    function _setAjaxWinHeight($height){
    	XiFactory::getAjaxResponse()->addScriptCall('xi.ui.dialog.height',$height);
    }
    
	function _setAjaxWinWidth($width){
    	XiFactory::getAjaxResponse()->addScriptCall('xi.ui.dialog.width',$width);
    }
    
    function _setAjaxWinAutoclose($time){
    	XiFactory::getAjaxResponse()->addScriptCall('xi.ui.dialog.autoclose',$time);
    }
    
    function searchRecords()
    {
    	$this->_setAjaxWinTitle(XiText::_('COM_PAYPLANS_SEARCH_RECORDS_TITLE'));
    	$this->setTpl('partial_search');
		return true;	
    }
}
