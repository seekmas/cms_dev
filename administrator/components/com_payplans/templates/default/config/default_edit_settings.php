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
			<fieldset class="form-horizontal">
				<legend><?php echo XiText::_('COM_PAYPLANS_CONFIG_CUSTOMIZATION_FEATURES');?> </legend>
				<?php foreach($form->getFieldset('features') as $field) : ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $field->label; ?>
						</div>
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</fieldset>
			
			<fieldset class="form-horizontal">
				<legend><?php echo XiText::_('COM_PAYPLANS_CONFIG_LOCALIZATION');?> </legend>
				<?php foreach($form->getFieldset('localization') as $field) : ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $field->label; ?>
						</div>
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</fieldset>
			<fieldset class="form-horizontal">
				<?php echo $this->loadTemplate('edit_log');?>	
			</fieldset>
		</div>

		<div class="span6">
			<fieldset class="form-horizontal">
				<legend><?php echo XiText::_('COM_PAYPLANS_CONFIG_SETUP_CHECKLIST');?>
				</legend>
				<div><?php
				$position = 'payplans-admin-config-checklist';
	            echo $this->loadTemplate('partial_position',compact('plugin_result','position'));
				?>
				</div>
	   		</fieldset>
	   
	   		<fieldset class="form-horizontal">
				<legend onClick="xi.jQuery('.pp-configuration-advance').slideToggle();">
					<span class="pp-configuration-advance">[+]</span>
					<?php echo XiText::_('COM_PAYPLANS_ADVANCE'); ?>
				</legend>
				<div class="hide pp-configuration-advance">
				<?php foreach($form->getFieldset('advance') as $field) : ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $field->label; ?>
						</div>
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php endforeach; ?>
				</div>
			</fieldset>
			
			<fieldset class="form-horizontal">
				<legend onClick="xi.jQuery('.pp-configuration-expert').slideToggle();">
				<span class="pp-configuration-expert">[+]</span>
				<?php echo XiText::_('COM_PAYPLANS_CONFIG_SETTINGS_EXPERT'); ?>
			</legend>
			<div class="hide pp-configuration-expert">
				<?php foreach($form->getFieldset('expert') as $field) : ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $field->label; ?>
						</div>
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php endforeach; ?>
				</div>
			</fieldset>
		</div>
</div>
<?php 
