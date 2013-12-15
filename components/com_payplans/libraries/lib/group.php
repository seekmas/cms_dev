<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

/**
 * Base class for all payplans-apps who have multiple instances
 * @author Meenal Devpura
 *
 */
class PayplansGroup extends XiLib
{
	// group should be trigger, so set to true
	public 		$_trigger   = true;
	
	//vars which should be propogates to template
	protected   $_tplVars = array();
		
	protected	$group_id		= 0 ;
	protected	$title			= '';
	protected   $parent			= 0;
	protected	$ordering		= 0;
	protected	$published		= 1;
	protected	$visible			= 1;
	protected   $description	= '';
	protected 	$_plans			= array();
	/**
	 * Params
	 * @var XiParameter
	 */
	protected $params	= 	null;
	
	/**
	 * @return PayplansGroup
	 * @param string $dummy is added just for removing warning with development mode(XiLib::getInstance is having 4 parameters)
	 * @param string $dummy1 is added just for removing warning with development mode
	 */
	static public function getInstance($id=0, $bindData=null,$dummy=null,$dummy1=null)
	{
		return parent::getInstance('group',$id, null, $bindData);
	}

	public function bind($data, $ignore=array())
	{
		if(is_object($data)){
			$data = (array) ($data);
		}

		parent::bind($data, $ignore=array());

		if(isset($data['plans'])){
			$this->_plans = $data['plans'];
		}

		return $this;
	}
	
	public function getParam($key, $default=null)
	{
		XiError::assert($this);
		return $this->getParams()->get($key,$default);
	}

	public function setParam($key, $value)
	{
		XiError::assert($this);
		$this->getParams()->set($key,$value);
		return $this;
	}

	//No child class should override it
	final public function collectParams(array $data)
	{
		return PayplansHelperParam::collectParams($data,'params');
	}
	
	public function getPlans()
	{
		return $this->_plans;
	}

	// Reset to construction time.
	public function reset(Array $config=array())
	{
		$this->group_id		= 0 ;
		$this->parent		= 0 ;
		$this->title		= '';
		$this->ordering		= 0;
		$this->published	= 1;
		$this->visible		= 1;
		$this->params		= new XiParameter();
		$this->_plans		= array();
		return $this;
	}

	public function afterBind($id = 0)
	{
		if(!$id) return $this;

		$this->_plans = XiFactory::getInstance('plangroup', 'model')
									->getGroupPlans($id);
		return $this;
	}
	
    public function getTitle()
    {
    	return $this->title;
    }
    
	public function getCssClasses()
	{
		return $this->getParams()->get('css_class','');
	}
	
	public function getTeasertext()
	{
		return $this->getParams()->get('teasertext','');
	}
	
    public function getDescription()
    {
    	return $this->description;
    }
    
    public function getPublished()
    {
    	return $this->published;
    }
    
    public function getVisible()
    {
    	return $this->visible;
    }
    
    public function getOrdering()
    {
    	return $this->ordering;
    }
    
    public function getParams()
    {
    	return $this->params;
    }
    
	private function _saveGroupPlans()
	{
		// delete all existing values of current plan id
		$model = XiFactory::getInstance('plangroup', 'model');
		$model->deleteMany(array('group_id' => $this->getId()));

		// insert new values into planapp for current plan id
		$data['group_id'] = $this->getId();
		if(is_array($this->_plans)){
			foreach($this->_plans as $plan){
				$data['plan_id'] = $plan;
				$model->save($data);
			}
		}

		return $this;
	}
	
	public function save()
	{
		parent::save();
		return $this->_saveGroupPlans();
	}
	
	public function getParent()
	{
		return $this->parent;
	}
}

class PayplansGroupFormatter extends PayplansFormatter
{
	function getIgnoredata()
	{
		$ignore = array('_component', '_errors', '_name', '_tplVars', '_trigger');
		return $ignore;
	}
	
	// get formatter to apply on vars
	function getVarFormatter()
	{
		$rules = array( '_plans'      =>array('formatter'=> 'PayplansGroupFormatter',
										       'function' => 'getChildPlans'),
						'parent'      =>array('formatter'=> 'PayplansGroupFormatter',
										       'function' => 'getParentGroup'),
						'params'      => array('formatter'=> 'PayplansFormatter',
										       'function' => 'getFormattedParams'));
		return $rules;
	}
	
	function getChildPlans($key,$value,$data)
	{
		$value = !empty($value)?(is_array($value) ? $value : array($value)):array();
		$plans = array();
		foreach ($value as $v)
		{
			$plan   = PayplansPlan::getInstance($v);
			$plans[]= PayplansHtml::link(XiRoute::_("index.php?option=com_payplans&view=plan&task=edit&id=".$plan->getId(), false), $plan->getId().'('.$plan->getTitle().')');
			
		}
		$value = $plans;
	}
	
	function getParentGroup($key,$value,$data)
	{
		if(!empty($value)){
			$group = PayplansGroup::getInstance($value);
			$value = PayplansHtml::link(XiRoute::_("index.php?option=com_payplans&view=group&task=edit&id=".$group->getId(), false), $group->getId().'('.$group->getTitle().')');
		}
	} 
	
	// get plan groups
	function getPlanGroups($key,$value,$data)
	{
		// if not array convert to array
		$value = is_array($value) ? $value : array($value);
		$key   = XiText::_('COM_PAYPLANS_LOG_KEY_GROUPS');
		$groups = array();
		foreach ($value as $v)
		{
			if(empty($v)){
				continue;
			}
			$group   = PayplansGroup::getInstance($v);
			$groups[]= PayplansHtml::link(XiRoute::_("index.php?option=com_payplans&view=group&task=edit&id=".$group->getId(), false), $group->getId().'('.$group->getTitle().')');
			
		}
		$value = $groups;
	}
	
}