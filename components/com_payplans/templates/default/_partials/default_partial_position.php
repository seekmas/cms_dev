<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();?>

<div class="pp-position">
       <?php if(isset($position)==false) $position = 'default'; ?>
       
       <?php $attribs = isset($attribs) ? $attribs : array(); ?>
   
       <?php if(isset($plugin_result) && isset($plugin_result[$position])):?>
               <div class="<?php echo $position;?>">
                        <?php echo $plugin_result[$position]; ?>
                </div>
       <?php endif;?>
       
        <?php $modules = PayplansHelperTemplate::_renderModules($position, $attribs); ?>
               <?php foreach($modules as $html):?>
                               <?php echo $html; ?>
               <?php endforeach;?>
</div>