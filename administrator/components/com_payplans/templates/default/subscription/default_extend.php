<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();?>
<div class="pp-subscription-extend">
<form action="<?php echo $uri; ?>" method="post" name="select-extend-time" id="select-extend-time" class="form-horizontal">
	<div class="muted">
		<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_GRID_EXTEND_MESSAGE')?>
	</div>
	
	<div class="control-group">
		<div class="control-label">
			<?php echo XiText::_('COM_PAYPLANS_AJAX_SUBSCRIPTION_SELECT_EXTEND_TIME')?>
		</div>
		<div class="controls">
			<?php echo PayplansHtml::_('timer.edit', 'extend_time', '000000000000' ,'extend_time'); ?>
		</div>
	</div>
	<div class="pp-time">
		
	</div>
</form>
</div>
<?php 