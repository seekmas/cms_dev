<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansFormatter
{	
	
	public static function writer($previous, $current)
	{	
		$content['previous'] = $previous ? (method_exists($previous, 'toArray') ? $previous->toArray() : (array)$previous ) : array();
		$content['current'] = method_exists($current, 'toArray') ? $current->toArray() : (array)$current;
		
		return $content;
	}
	
	public function formatter($content,$type=null)
	{    
		// if content is not array convert it in array
		if(!is_array($content))
		{
			$content = array($content);
		}
		// if content has previous or current set
		if(array_key_exists('previous', $content) || array_key_exists('current', $content)){
			$data = $this->_formatter($content);
		}
		// if content doesn't have previous and current set
		// for email logs and error logs and cron logs
		else {
			$data['previous'] = $content;
			$data['current']  = array();
		}
	   return $data; 		
	}
	
	public function callFormatRule($formatter,$functionName,$args)
	{
		//XiToDo:: call function on instance or use call_user_func_array
		if($formatter){
			$instance = PayplansFactory::getFormatter($formatter,null);
			$call = array($instance, $functionName);
		}else{
			$call = $functionName;
		}
		return call_user_func_array($call,$args);
	}
	
	/**
	 * 
	 * apply rules on data 
	 *  $data is passes through reference
	 */
	public function applyFormatterRules(&$data,$rules)
	{
		$new = array();

		foreach ($data['previous'] as $key => $value){
			if(array_key_exists($key, $rules)){
				$args = array(&$key, &$value ,$data['previous']);
				$this->callFormatRule($rules[$key]['formatter'],$rules[$key]['function'], $args);	
			}
			$new['previous'][$key]= $value;
		}	
		
		foreach ($data['current'] as $key => $value){
			if(array_key_exists($key, $rules)){
				$args = array(&$key, &$value,$data['current']);
				$this->callFormatRule($rules[$key]['formatter'],$rules[$key]['function'], $args);	
			}
			$new['current'][$key]= $value;
		}	
		
		$data['previous'] = isset($new['previous'])? $new['previous']: '';
		$data['current']  = isset($new['current']) ? $new['current'] : '';
		unset($new);
	}
	
	public function _formatter($content)
	{
		$data['previous'] = array_key_exists('previous', $content)  ?  $content['previous']  : array();
		$data['current'] = array_key_exists('current', $content)   ?  $content['current']   : array();

		$data['previous'] = array_key_exists('previous', $data['previous'])? array_pop($data['previous']): $data['previous'];
		if(method_exists($this, 'getIgnoredata')){
			$ignore = $this->getIgnoredata();
		
			foreach($ignore as $key)
			{
				 unset($data['previous'][$key]);
				 unset($data['current'][$key]);
			}
		}
		
		if(method_exists($this, 'getVarFormatter')){
			$rules = $this->getVarFormatter();
			$this->applyFormatterRules($data,$rules);
		}
		return $data;
	}
	
	// format params in all logs
	function getFormattedParams($key,$value,$data)
	{
 		$key= XiText::_('COM_PAYPLANS_LOG_KEY_PARAMS');
    
		$params = "";
		foreach($value as $index => $val)
		{
			$params .= $index.' = '.$val.',';
		}
    
		$params = explode(",", $params);
 		$value  = implode("<br/>", $params);
	}
}