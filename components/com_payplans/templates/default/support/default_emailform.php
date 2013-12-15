<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();
?>
<div class="row-fluid">
	<form action="<?php echo $uri; ?>" method="post" name="site-support-email-form" id="site-support-email-form">
	

	<div class="control-group">
		<div class="control-label span6">
			<?php echo XiText::_('COM_PAYPLANS_SUPPORT_EMAILFORM_SUBJECT');?>
		</div>
		<div class="controls span6">
			<input type="text" class="required" name="email-form-subject" id="email-form-subject" value="" size="42" onblur="payplans.validate.notempty(this.id);" />
			<span id="err-email-form-subject"></span>
		</div>
	</div>
	
	<div class="control-group">
		<div class="control-label span6">
			<?php echo XiText::_('COM_PAYPLANS_SUPPORT_EMAILFORM_FROM');?></div>
		<div class="controls span6">
			<input type="text" name="email-form-from" id="email-form-from" size="42" value="<?php echo $from;?>" onblur="payplans.validate.notempty(this.id);"/>
			<span id="err-email-form-from"></span>
		</div>
	</div>
	
	<div class="control-group">
		<div class="control-label span6">
			<?php echo XiText::_('COM_PAYPLANS_SUPPORT_EMAILFORM_BODY');?></div>
		<div class="controls span6">
			<textarea rows="5" cols="42" name="email-form-body" id="email-form-body" onblur="payplans.validate.notempty(this.id);"></textarea>
		</div>
	</div>

	</form>
</div>
<?php 
