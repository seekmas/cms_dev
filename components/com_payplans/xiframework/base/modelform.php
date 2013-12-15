<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		RB Framework
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

jimport('joomla.application.component.modelform');

class XiModelform extends JModelForm
{
	public	$_component			= 'Payplans';	
	protected $_forms_path 		= PAYPLANS_PATH_XML;
	protected $_fields_path 	= PAYPLANS_PATH_ELEMENTS;
	
	public function __construct($config = array())
	{
		parent::__construct($config);
		
		// Setup path for forms	
		XiError::assert(isset($this->_forms_path));
		JForm::addFormPath($this->_forms_path);
		
		XiError::assert(isset($this->_fields_path));
		JForm::addFieldPath($this->_fields_path);
	}
	
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$name = 'payplans'.'.'.$this->getName();
		$form = $this->loadForm($name, $this->getName(), array('control' => $this->_component.'_form', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}
	
	protected function loadFormData()
	{
		// when form load then we get processor's data into params, to bind this data into processor_config we assing params into this
		if(isset($this->_lib_data)){
			return $this->_lib_data->toArray();
		}
		
		return array();
	}
	
	public function getName()
	{
		if (empty($this->name))
		{
			$r = null;
			if (!preg_match('/Modelform(.*)/i', get_class($this), $r))
			{
				JError::raiseError(500, JText::_('JLIB_APPLICATION_ERROR_MODEL_GET_NAME'));
			}
			$this->name = strtolower($r[1]);
		}

		return $this->name;
	}
	
	public function setLibData($object)
	{
		$this->_lib_data = $object;
		return $this;
	}
}