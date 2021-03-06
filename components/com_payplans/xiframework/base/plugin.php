<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

jimport('joomla.plugin.plugin');

/**
 * VERY IMP :
 * While adding functions into plugin, we should keep in mind
 * that all function not starting with _ (under-score), will be
 * added into plugins event functions. So while adding supportive
 * function, always start them with underscore
 */


class XiPlugin extends JPlugin
{
	protected $_tplVars = array();

	function __construct(& $subject, $config = array())
	{
		parent::__construct($subject, $config);

		//init the plugin
		$this->_initalize();
		
		//load language file
		$path = XiHelperJoomla::getPluginPath($this);
		$this->loadLanguage('', $path);
	}

	/**
	 * Check the plugin type
	 */
	public function _hasType($type="Unknown")
	{
		$type = JString::ucfirst(JString::strtolower($type));

		//simply check if I am instance of plugin type
		return is_a($this, 'XiPlugin'.$type);
	}

	protected function _initalize(Array $options= array())
	{}

	/**
	 * Plugin is available :
	 * If current plugin can be used ir-respective
	 * of conditions
	 */
	public function _isAvailable(Array $options= array())
	{}

	/**
	 * Plugin is available but check if
	 * It should be used for given conditions
	 */
	public function _isApplicable(Array $conditions= array())
	{}


	/**
	 * Render plugins template
	 */
	protected function _render($tpl, $args=null, $layout=null)
	{
		return $this->_loadTemplate($tpl, $args, $this->_type, $layout);
	}

	protected function _assign($key, $value)
	{
		$this->_tplVars[$key] = $value;
	}

	protected function _loadTemplate( $tpl = null, $args = null, $type = 'payplans', $layout=null)
	{
		if($args === null){
			$args= (array)$this->_tplVars;
		}
		
    	//create the template file name based on the layout
		$file = isset($layout) ?  $layout.'_'.$tpl : $tpl;	
		$file = preg_replace('/[^A-Z0-9_\.-]/i', '', $file);
		
		$paths = $this->_getTemplatePath($this->_name, $type);
		$template = JPath::find($paths, $file.'.php');
		if($template == false){
			XiError::raiseError(500, "Template file : $tpl missing for app $type");
		}
		
		// Support tmpl vars
        // Extracting variables here
        if(isset($args['this'])){
        	unset($args['this']);
        } 
        
        if(isset($args['_tplVars'])){
        	unset($args['_tplVars']);
        }
        
        extract((array)$args,  EXTR_OVERWRITE);
		
		// start capturing output into a buffer
		ob_start();
		include $template;
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
	

    protected function _getTemplatePath($plugin=null, $type='payplans')
    {

        $app = XiFactory::getApplication();

        //Security Checks : clean paths
        $plugin = preg_replace('/[^A-Z0-9_\.-]/i', '', $plugin);

        //XITODO : Move paths to addPath function, so that it can be extended.
        // get the template and default paths for the layout
        $paths[] = JPATH_THEMES.DS.$app->getTemplate().DS.'html'.DS.PAYPLANS_COMPONENT_NAME.DS
        		 .'_plg'.DS.$type.DS.$plugin;

        $paths[] = XiHelperJoomla::getPluginPath($this).DS.'tmpl';
        $paths[] = PAYPLANS_PATH_TEMPLATE.DS.'default'.DS.'_partials';

        //find the path and return
        return $paths;
    }
}
