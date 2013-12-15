<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansHtml extends XiHtml
{
	public static function _($key)
	{		
		$parts = explode('.', $key);
		$prefix 	= 'PayplansHtml';
		$className	= $prefix.ucfirst($parts[0]);
		$extraArgs 	= func_get_args();
		
		if (class_exists( $className , true ))
		{
			$extraArgs[0] = isset($parts[1]) ? $prefix.'.'.$parts[0].'.'.$parts[1] : $prefix.'.'.$parts[0];
			return call_user_func_array( array( 'JHTML', '_' ), $extraArgs );
		}

		$extraArgs[0] = isset($parts[1]) ? $parts[0].'.'.$parts[1] : $parts[0];
		return call_user_func_array( array( 'XiHtml', '_' ), $extraArgs );
	}
}