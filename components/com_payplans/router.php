<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


function PayplansBuildRoute( &$query )
{
       if(class_exists('PayplansRouter',true)){
          return PayplansRouter::getInstance()->build($query);
       }
          return array();
}

/**
 * @param	array	A named array
 * @param	array
 *
 * Formats:
 */
function PayplansParseRoute($segments)
{
  if(class_exists('PayplansRouter',true)){
   return PayplansRouter::getInstance()->parse($segments);
}
    return array();
}
