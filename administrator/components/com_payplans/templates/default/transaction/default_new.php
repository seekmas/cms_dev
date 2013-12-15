<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();?>
<div>
<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm">
<div class="row-fluid">
	<div class="span6">
		<fieldset class="form-horizontal">
			<legend> <?php echo XiText::_('COM_PAYPLANS_TRANSACTION_EDIT_DETAILS' ); ?> </legend>
			
			<?php foreach ($form->getFieldset('details') as $field):?>
			<?php $class = $field->group.$field->fieldname; ?>
				<div class="control-group <?php echo $class;?>">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
			<?php endforeach;?>
			
			<div class="control-group">
				<div class="control-label">
					<?php echo $form->getLabel('created_date'); ?>
				</div>
				<div class="controls"><?php echo XiDate::timeago($form->getValue('created_date')); ?>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label">
					<?php echo $form->getLabel('message'); ?>
				</div>
				<div class="controls">
					<?php echo $form->getInput('message'); ?>
				</div>
			</div>
		
			<div>
				<?php echo $this->loadTemplate('partial_user', compact('user'));?>
			</div>
		</fieldset>
	</div>
	
	<div class="span6">
		<fieldset class="form-horizontal">	
		<legend > <?php echo XiText::_('COM_PAYPLANS_TRANSACTION_EDIT_PARAMS' ); ?> </legend>
					<?php echo $transaction_html; ?>
		</fieldset>
	</div>	
	
	
	<?php echo $form->getInput('transaction_id');?>
	<?php echo $form->getInput('user_id');?>
	<?php echo $form->getInput('payment_id');?>
	<input type="hidden" name="task" value="save" />
</div>
</form>
</div>
<?php
