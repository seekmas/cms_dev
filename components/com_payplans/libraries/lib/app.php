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
class PayplansApp extends XiLib
{
	// app should be trigger, so set to false
	public 		$_trigger   = true;
	
	//vars which should be propogates to template
	protected   $_tplVars = array();
		
	protected	$app_id		= 0 ;
	protected	$title		= '';
	protected	$type		= '';
	protected	$ordering	= 0;
	protected	$published	= 1;
	protected   $description= '';
	/**
	 * Core parameters for every app
	 * @var XiParameter
	 */
	protected $core_params	= 	null;
	/**
	 * Apps parameters given and optional by app
	 * @var XiParameter
	 */
	protected $app_params	=	null;

	public function __construct($config = array())
	{
		//load language
		$this->loadLanguage($this->getName(), dirname($this->_location));
		
		//return $this to chain the functions
		return parent::__construct($config);
	}
	
	/**
	 * @return PayplansApp
	 * @param string $dummy is added just for removing warning with development mode(XiLib::getInstance is having 4 parameters)
	 */
	static public function getInstance($id=0, $type=null, $bindData=null,$dummy=null)
	{
		return parent::getInstance('app',$id, $type, $bindData);
	}

	/**
	 * Loads the app language file
	 *
	 * @access	public
	 * @param	string 	$app	 	The app for which a language file should be loaded
	 * @param	string 	$basePath  	The basepath to use
	 * @return	boolean	True, if the file has successfully loaded.
	 */
	function loadLanguage($app, $basePath = JPATH_BASE)
	{
		$lang = JFactory::getLanguage();
		return $lang->load( strtolower($app), $basePath);
	}
	
	function getName()
	{
		if(isset($this->type)==false || empty($this->type))
		{
			$r = null;
			if (!preg_match('/App(.*)/i', get_class($this), $r)) {
				JError::raiseError (500, "PayplansApp : Can't get or parse class name.");
			}
			$this->type = strtolower( $r[1] );
		}

		return $this->type;
	}

	/*
	 * Collect prefix auto-magically
	 */
	public function getPrefix()
	{
		if(isset($this->prefix) && empty($this->prefix)===false)
			return $this->prefix;

		$r = null;
		if (!preg_match('/(.*)App/i', get_class($this), $r)) {
			XiError::raiseError (500, "PayplansApp::getName() : Can't get or parse class name.");
		}

		$this->prefix  =  JString::strtolower($r[1]);
		return $this->prefix;
	}

	/**
	 * @return : XiModel of app
	 */
	public function getModel()
	{
		XiError::assert($this);
		return XiFactory::getInstance('app', 'Model');
	}

	public function getId()
	{
		XiError::assert($this);
		return $this->app_id;
	}

	public function getParam($key, $default=null)
	{
		XiError::assert($this);
		return $this->core_params->get($key,$default);
	}

	public function setParam($key, $value)
	{
		XiError::assert($this);
		$this->core_params->set($key,$value);
		return $this;
	}


	public function getAppParam($key, $default=null)
	{
		XiError::assert($this);
		return $this->app_params->get($key,$default);
	}

	public function setAppParam($key, $value)
	{
		XiError::assert($this);
		$this->app_params->set($key,$value);
		return $this;
	}

	//No child class should override it
	final public function collectCoreParams(array $data)
	{
		return PayplansHelperParam::collectParams($data,'core_params');
	}

	public function collectAppParams(array $data)
	{
		return PayplansHelperParam::collectParams($data,'app_params');
	}

	public function setId($id)
	{
		XiError::assert($this);
		$this->app_id = $id;
		return $this;
	}

	// IMP : app require to overload load function, as getName != 'app'
	public function load($id = 0)
	{
		if(!$id) return $this;

		$apps = XiFactory::getInstance('app', 'model')
							->loadRecords(array('id' => $id));
		$this->bind(array_shift($apps));
		return $this;
	}

	// load given id
	public function afterBind($id = 0)
	{
		if(!$id) return $this;

		$this->_appplans = XiFactory::getInstance('planapp', 'model')
									->getAppPlans($id);
		return $this;
	}

	public function bind($data, $ignore=array())
	{
		if(is_object($data)){
			$data = (array) ($data);
		}

		parent::bind($data, $ignore=array());

		if(isset($data['appplans'])){
			$this->_appplans = $data['appplans'];
		}

		return $this;
	}

	public function save()
	{
		// if error in saving, then do not save other data
		if(!parent::save()){
			return false;
		}

		return $this->_saveAppPlans();
	}

	public function getPlans()
	{
		return $this->_appplans;
	}

	private function _saveAppPlans()
	{
		// delete all existing values of current app id
		$model = XiFactory::getInstance('planapp', 'model');
		$model->deleteMany(array('app_id' => $this->getId()));

		// insert new values into planapp for current plan id
		$data['app_id'] = $this->getId();

		if(empty($this->_appplans) || !is_array($this->_appplans)){
			return $this;
		}

		foreach($this->_appplans as $plan){
			$data['plan_id'] = $plan;
			$model->save($data);
		}

		return $this;
	}

	// Reset to construction time.
	public function reset(Array $config=array())
	{
		$this->app_id		= 0 ;
		$this->title		= '';
		$this->type			= $this->getName();;
		$this->ordering		= 0;
		$this->published	= 1;
		$this->core_params	= new XiParameter();
		$this->app_params	= new XiParameter();
		$this->_appplans	= array();
		
		return $this;
	}

	/**
	 * if we need to implement if plugin is applicable or not
	 * @param unknown_type $refObject
	 */
	public function isApplicable($refObject = null, $eventName='')
	{
		// if not with reference to payment then return
		if($refObject === null || !($refObject instanceof PayplansIfaceApptriggerable)){
			return false;
		}

		//if applicable to all is false then check plan v/s apps
		if($this->getParam('applyAll',false) == false){
		 	// if object is of interest as per plans selected
			$ret = array_intersect($this->getPlans(), $refObject->getPlans());
			if(count($ret) <= 0 ){
				return false;
			}
		}
		// finally check if plugin want trigger for this or not
		return (boolean) $this->_isApplicable($refObject, $eventName);
	}

	/**
	 *
	 * @param $refObject
	 * @return boolean
	 */
	public function _isApplicable(PayplansIfaceApptriggerable $refObject, $eventName='')
	{
		return true;
	}

	/**
	 * Check the plugin purpose
	 */
	public function hasPurpose($purpose='')
	{
		// I am always as app
		if($purpose==='')
			return true;

		$type = JString::ucfirst(JString::strtolower($purpose));
		//simply check if I am instance of app type
		return is_a($this, 'PayplansApp'.$type);
	}

	protected function assign($key, $value)
	{
		$this->_tplVars[$key] = $value;
	}

	/**
	 * Render plugins template
	 */
	protected function _render($tpl, $args=null, $layout = null)
	{
		return $this->_loadTemplate($tpl, $args, $layout);
	}


	protected function _loadTemplate($tpl = null, $args = null, $layout=null)
	{
		if($args === null){
			$args= $this->_tplVars;
		}
		
		$file = isset($layout) ?  $layout.'_'.$tpl : $tpl;	
		$file = preg_replace('/[^A-Z0-9_\.-]/i', '', $file);
		
		$template = $this->_getTemplatePath($file);

		if($template == false){
        	XiError::raiseError(500, "Template file : $tpl missing for app {$this->getType()}");
		}
		
		// unset so as not to introduce into template scope
		unset($tpl);

		// Support tmpl vars
        // Extracting variables here
        unset($args['this']);
        unset($args['_tplVars']);
        extract((array)$args,  EXTR_OVERWRITE);
		
		// start capturing output into a buffer
		ob_start();
		include $template;
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
	

    protected function _getTemplatePath($layout = 'default')
    {
    	static $paths = null;

    	if($paths == null){
	        $paths[] = JPATH_THEMES.DS.XiFactory::getApplication()->getTemplate().DS
	        			.'html'.DS.PAYPLANS_COMPONENT_NAME.
	        			DS.'_app'.DS.JString::strtolower($this->getType());

	        $paths[] = PAYPLANS_PATH_TEMPLATE.DS.'default'
	        			.DS.'_app'.DS.JString::strtolower($this->getType());
	        			
	        $paths[] = $this->getLocation().DS.'tmpl';
	        $paths[] = PAYPLANS_PATH_TEMPLATE.DS.'default'.DS.'_partials';
    	}

        //Security Checks : clean paths
        $layout = preg_replace('/[^A-Z0-9_\.-]/i', '', $layout);

        //find the path and return
        return JPath::find($paths, $layout.'.php');
    }

    public function getLocation()
    {
    	return dirname($this->_location);
    }

    public function getTitle()
    {
    	return $this->title;
    }
    
  	public function setTitle($title)
    {
    	$this->title = $title;
    	return $this;
    }
    
    public function getDescription()
    {
    	return $this->description;
    }
    
    public function getType()
    {
    	return $this->type;
    }
    
    public function getPublished()
    {
    	return $this->published;
    }
    
    public function getOrdering()
    {
    	return $this->ordering;
    }
    
    public function getCoreParams()
    {
    	return $this->core_params;
    }
    
    public function getAppParams()
    {
    	return $this->app_params;
    }
    
    // Do nothing
    public static function _install()
    {
    	return true;
    }
    
    static function getResourceModel()
    {
    	static  $rmodel = null;
    	
    	if($rmodel == null){
    		$rmodel = XiFactory::getInstance('resource', 'model');	
    	}
    	
    	return $rmodel;
    }
    
    //XITODO : use Lib instance of Resource instead of model
    protected function _getResource($userid, $groupid, $resource)
	{		 
		$rmodel = PayplansApp::getResourceModel();
		$record = $rmodel->loadRecords(array(	'user_id'  => $userid,
												'title' => $resource,
												'value'	   => $groupid));
		
		$record = array_shift($record);
		if(empty($record) || !$record){
			return false;
		}
		
		// always trim the string by comma (,)
		$record->subscription_ids = JString::trim($record->subscription_ids, ',');
		
		return $record;		
	}
	
	protected function _addToResource($subId, $userid, $groupid, $resource, $count = 0)
	{
		$record 	= $this->_getResource($userid, $groupid, $resource);
		$id 		= 0;
		
		$data['subscription_ids'] 	= $subId;
		$data['value']				= $groupid;
		$data['title'] 				= $resource;
		$data['user_id']			= $userid;
		$data['count']				= $count;
		
		if($record){
			$id = $record->resource_id;
			$record->subscription_ids 	= empty($record->subscription_ids) ? array() : explode(',', $record->subscription_ids);
			$record->subscription_ids[] = $subId; 
			$data['subscription_ids'] 	= implode(',', $record->subscription_ids);			
			$data['count']				= $record->count + $count;
		}		
		 
		// each subscription id should be packed with comma (,)
		$data['subscription_ids'] = ','.$data['subscription_ids'].',';
		$rmodel = $this->getResourceModel();
		return $rmodel->save($data, $id);
	}
	
	protected function _removeFromResource($subId, $userid, $groupid, $resource, $count = 0)
	{
		$record 	= $this->_getResource($userid, $groupid, $resource);
		
		// should not remove from this group, if resource is not available
		if(!$record || empty($record)){
			return false;
		}
		
		$record->subscription_ids = explode(',', $record->subscription_ids);
		
		// do not remove from this group if user was not added by this subscription 
		if(!in_array($subId, $record->subscription_ids)){
			return false;
		}
		
		$data['value']				= $groupid;
		$data['title'] 				= $resource;
		$data['user_id']			= $userid;
		$data['count']				= $record->count - $count;

		// if count becomes negative then set it 0
		if($data['count'] < 0){
			$data['count'] = 0;
		}

		// remove the currenct sub id from ids
		$record->subscription_ids = array_diff($record->subscription_ids, array($subId));
		$data['subscription_ids'] 	= implode(',', $record->subscription_ids);
					 
		$rmodel = $this->getResourceModel();
		$remove = false;
		
		// if ids are empty then return true, and remove from group
		// and delete the resource
		if(empty($data['subscription_ids'])){
			$rmodel->delete($record->resource_id);
			$remove = true;
		}
		// each subscription id should be packed with comma (,)
		$data['subscription_ids'] 	= ','.$data['subscription_ids'].',';
		// do not remove if any ids are there
		$rmodel->save($data, $record->resource_id);
		return $remove;
	}

	public function getVars($key, $default=null)
	{
		if(isset($this->_tplVars[$key])) {
			return $this->_tplVars[$key];
		}
		return $default;
	}	
	
	public function getModelform()
	{
		if(isset($this->_modelform)){
			return $this->_modelform;
		}
		
		// setup modelform
		$this->_modelform = XiFactory::getInstance('App', 'Modelform' , 'Payplans');
		
		// set model form to pick data from this object
		$this->_modelform->setLibData($this);
		
		return $this->_modelform ;
	}
}


class PayplansAppFormatter extends PayplansFormatter
{	
	function getIgnoredata()
	{
		$ignore = array('_trigger', '_component', '_name', '_errors', '_tplVars','_location');
		return $ignore;
	}
	
	/**
	 * override parent applyFormatterRules to handle app_params
	 *  $data is passes through reference
	 */
	public function applyFormatterRules(&$data,$rules)
	{
		$new = array();

		foreach ($data['previous'] as $key => $value)
		{	
			// if there is some rule for that param then apply rule
			if(array_key_exists($key, $rules)){
				$args = array(&$key, &$value ,$data['previous']);
				$this->callFormatRule($rules[$key]['formatter'],$rules[$key]['function'], $args);	
			}
			// handling of app params 
			// display all app params in new line 
			if($key == 'app_params')
			{
				$prev_param = PayplansHelperParam::iniToArray($value);
				foreach($prev_param as $param=>$v){
					$new['previous'][$param]= $v;
				}
				unset($new['previous']['app_params']);
				continue;
			}
			$new['previous'][$key]= $value;
		}	
		
		foreach ($data['current'] as $key => $value)
		{
			// if there is some rule for that param then apply rule
			if(array_key_exists($key, $rules)){
				$args = array(&$key, &$value,$data['current']);
				$this->callFormatRule($rules[$key]['formatter'],$rules[$key]['function'], $args);
			}	
			// handling of app params 
			// display all app params in new line 
			if($key == 'app_params')
			{
				$prev_param = PayplansHelperParam::iniToArray($value);
				foreach($prev_param as $param=>$v){
					$new['current'][$param]= $v;
				}
				unset($new['current']['app_params']);
				continue;
			}
			$new['current'][$key]= $value;
		}	
		
		$data['previous'] = isset($new['previous'])? $new['previous']: '';
		$data['current'] = isset($new['current']) ? $new['current'] : '';
		unset($new);
	}
	
	// get applicable apps on plans
	function getApplicableApps($key,$value,$data)
	{
		// if not array convert to array
		$value = is_array($value) ? $value : array($value);
		$key   = XiText::_('COM_PAYPLANS_LOG_KEY_APPLICABLE_APPS');
		$apps = array();
		foreach ($value as $v)
		{   
			if(empty($v)){
				continue;
			}
			$app   = PayplansApp::getInstance($v);
			$apps[]= PayplansHtml::link(XiRoute::_("index.php?option=com_payplans&view=app&task=edit&id=".$app->getId(), false), $app->getId().'('.$app->getTitle().')');
		}
		$value = $apps;
	}
	
	// get app's plans
	function getAppPlans($key,$value,$data)
	{
		// if not array convert to array
		$value = is_array($value) ? $value : array($value);
		$key = XiText::_('COM_PAYPLANS_LOG_KEY_PLAN');
		$plans = array();
		foreach ($value as $v){
				if(empty($v)){
					continue;
				}
			$planName = PayplansHelperPlan::getName($v);
			$plans[]  = PayplansHtml::link(XiRoute::_("index.php?option=com_payplans&view=plan&task=edit&id=".$v, false), $v.'('.$planName.')');
		}
		
		$value = $plans;
	}
	// get rules
	function getVarFormatter()
	{
		$rules = array('_appplans'       => array('formatter'=> 'PayplansAppFormatter',
										       'function' => 'getAppPlans'));
		return $rules;
	}
	
	function getAppName($key,$value,$data)
	{
		$app = PayplansApp::getInstance($value);
		$value = PayplansHtml::link(XiRoute::_("index.php?option=com_payplans&view=app&task=edit&id=".$value, false), $value.'('.$app->getTitle().')');
		
	}
}