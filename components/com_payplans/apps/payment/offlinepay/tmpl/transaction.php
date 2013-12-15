<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();?>
<?php if(!empty($transaction_html)):?>
	<div class="span12"><?php echo XiText::_('COM_PAYPLANS_APP_OFFLINE_TRANSACTION_HELP_MESSAGE');?></div><br>
	<?php	foreach ($transaction_html as $key =>$value ):?>
                                                            
                                  <div class="row-fluid">
                                           <div class="span3"><?php echo $key; ?></div>
                                           <div class="offset1 span8"><?php echo $value; ?></div>  
							     </div>                                                                
                                                       
                                                       <?php endforeach;?>
<?php endif;