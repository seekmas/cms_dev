<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die(); ?>

<div class="row-fluid">


   <?php
   // display for all widgets  
   $position  = 'payplans-dashboard-right';
   echo $this->loadTemplate('partial_position',compact('plugin_result','position'));
   ?>
   
   
</div>