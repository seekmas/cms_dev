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
class PayplansConfig extends XiLib
{
	protected	$config_id	 = 0 ;
	protected	$value		 = '';
	protected	$key		 = '';

	/**
	 * @return PayplansConfig
	 * @param string $dummy is added just for removing warning with development mode(XiLib::getInstance is having 4 parameters)
	 */
	static public function getInstance($id=0, $type=null, $bindData=null,$dummy=null)
	{
		return parent::getInstance('config',$id, $type, $bindData);
	}
	
	public function bind($data, $ignore=array())
	{
		if(is_object($data)){
			$data = (array) ($data);
		}
		return parent::bind($data, $ignore);
	}

	// Reset to construction time.
	public function reset(Array $config=array())
	{
		$this->config_id	 = 0 ;
		$this->key		 	 = '';
		$this->value		 = '';

		return $this;
	}
	
	public function getKey()
	{
		return $this->key;
	}
	
	public function getValue()
	{
		return $this->value;
	}
}

class PayplansConfigFormatter extends PayplansFormatter
{

	function getIgnoredata()
	{
		$ignore = array('dbtype', 'host', 'user', 'password', 'db', 'dbprefix', 'ftp_host', 'ftp_port',
						 'ftp_user', 'ftp_pass', 'ftp_root', 'ftp_enable', 'tmp_path', 'log_path', 'mailer', 
						 'mailfrom', 'fromname', 'sendmail', 'smtpauth', 'smtpsecure', 'smtpport', 'smtppass', 
						 'smtpuser', 'smtphost', 'debug', 'caching', 'cachetime', 'language', 'secret', 'editor',
						 'offset', 'lifetime', 'offline', 'offline_message', 'sitename', 'list_limit', 'legacy', 
						 'debug_lang', 'live_site', 'gzip', 'error_reporting', 'helpurl', 'xmlrpc_server', 'force_ssl',
						 'offset_user', 'cache_handler', 'MetaDesc', 'MetaTitle', 'MetaKeys' ,'MetaAuthor', 'sef', 
						 'sef_rewrite', 'sef_suffix', 'feed_limit', 'feed_email', 'session_handler', 'memcache_settings','jpayplansPassword' );
		return $ignore;
	}
	
	// get rules to apply
	function getVarFormatter()
	{
		$rules = array( 'order_status'      => array('formatter'=> 'PayplansOrderFormatter',
										       	   'function' => 'getOrderStatusName')
						);
		return $rules;
	}
}
