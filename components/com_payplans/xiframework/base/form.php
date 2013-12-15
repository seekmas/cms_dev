<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class XiForm extends JRegistry
{
	static protected $__formCache = array();
	
	function __construct($data='', $path = '')
	{		
		// find form xml path
		$this->formKey  = ($path !== '') ? md5($path) : null ;
		
		// get form, if required
		if($this->formKey){
			self::$__formCache[$this->formKey] = JForm::getInstance($this->formKey, $path, array(),true,'//config');
			// Set base path, this way other paths can be added automatically			
		}

		//call parent constructor
		parent::__construct($data);

		// bind data
		if($data !== ''){
			$this->bind($data);
		}
	}
	
	function render($name = 'params', $group = '_default')
	{			
		$parameter = $this;
		return XiHelperTemplate::partial('default_partial_parameters',compact('parameter', 'name', 'group'));
	}
	
	function renderToArray($name = 'params', $group = '_default')
	{
		$results = array();
		$params  = self::getParams($name, $group);
		foreach($params as $result) {
			$result[2] = XiText::_($result[2]);
			$result[3] = XiText::_($result[3]);
			$result['name']  = $name;
			$result['group'] = $group;
			
			$results[$result[5]] = $result;
		}
		return $results;
	}
	
	public function getParams($name = 'params', $group ='params')
	{
		$form    = self::$__formCache[$this->formKey];
		$newData = array($name => $this->toArray());
		$form->bind($newData);
		$fields  = $form->getFieldset();
		$count   = 0;
		$result  = array();
		
		if($fields){
			foreach($fields as $field){			
				$result[$count][0]   = $field->label;
				$result[$count][1]   = $field->input;
				$result[$count][2]   = $field->description;
				$result[$count][3]   = $field->fieldname;
				$result[$count][4]   = $field->value;
				$result[$count++][5] = $field->fieldname;
			}
		}
		
		return $result;
	}
}
