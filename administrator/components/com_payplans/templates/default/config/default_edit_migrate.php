<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();?>

<div class="row-fluid">
 <div class="span6">
 <?php $url = XiRoute::_('index.php?option=com_payplans&view=config&task=doLogMigration&arg1='.json_encode(array()));?>
  <a href="" class="btn btn-primary" onClick="payplans.url.modal('<?php echo $url; ?>');return false;">
		<?php echo XiText::_('COM_PAYPLANS_LOG_MIGRATION_BUTTON');?>
	</a>
 </div>

</div>