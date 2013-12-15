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
<div>
	<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
	<!-- left float -->
	<div class="span6">	
		<fieldset class="form-horizontal">
			<legend><?php echo XiText::_('COM_PAYPLANS_PAYMENT_EDIT_DETAILS' ); ?> </legend>
			
			<div class="control-group">
				<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_PAYMENT_EDIT_TOOLTIP_PAYMENT_ID'); ?>"> 
					<?php echo XiText::_('COM_PAYPLANS_PAYMENT_EDIT_PAYMENT_ID'); ?>					
				</div>
				<div class="controls">
					<?php echo $payment->getId()." (".$payment->getkey().")"; ?>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_PAYMENT_EDIT_TOOLTIP_CREATION_DATE'); ?>"> 
					<?php echo XiText::_('COM_PAYPLANS_PAYMENT_EDIT_CREATION_DATE'); ?>					
				</div>
				<div class="controls">
					<?php echo XiDate::timeago($payment->getCreatedDate()->toMySql()); ?>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_PAYMENT_EDIT_TOOLTIP_MODIFIED_DATE'); ?>"> 
					<?php echo XiText::_('COM_PAYPLANS_PAYMENT_EDIT_MODIFIED_DATE'); ?>					
				</div>
				<div class="controls">
					<?php echo XiDate::timeago($payment->getModifiedDate()->toMySql()); ?>
				</div>
			</div>

			<?php if(!empty($gateway_params)):?>
			<div class="control-group">
				<fieldset class="form-horizontal">	
				<legend > <?php echo XiText::_('COM_PAYPLANS_PAYMENT_GATEWAY_PARAMS' ); ?> </legend>
							<?php foreach ($gateway_params as $lable=>$value): ?>
							<div class="form-horizontal">
								<div class="control-group">
									<div class="control-label"><?php echo $lable;?></div>
									<div class="controls"><?php echo $value;?></div>
								</div>
							</div>
							<?php endforeach;?>
				</fieldset>
			</div>
			<?php endif;?>
			
		</fieldset>	
		
		<?php echo $this->loadTemplate('edit_log'); ?>	
	</div>
	
	<div class="span6">
		<?php echo $this->loadTemplate('partial_user', compact('user'));?>
		
		<?php echo $this->loadTemplate('payment_transaction'); ?>
	</div>
	
	<?php echo $form->getInput('payment_id');?>
	</div>
	</form>
</div>
<?php 

