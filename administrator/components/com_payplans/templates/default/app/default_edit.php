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
<div id="pp-app-edit">
<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<div class="span6">
			<fieldset class="form-horizontal">
				<legend> <?php echo XiText::_($appData['name'])." - ".XiText::_('COM_PAYPLANS_APP_EDIT_APP_DETAILS' ); ?> </legend>
		
				<div class="control-group">
										<div class="control-label"><?php echo $form->getLabel('title'); ?> </div>
										<div class="controls"><?php echo $form->getInput('title'); ?></div>	
					</div>	
					
				<div class="control-group">
										<div class="control-label"><?php echo $form->getLabel('published'); ?> </div>
										<div class="controls"><?php echo $form->getInput('published'); ?></div>	
					</div>	
					
				<?php foreach ($form->getFieldset('core_params') as $field):?>
					<?php $class = $field->group.$field->fieldname; ?>
					<div class="control-group <?php echo $class;?>">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
				<?php endforeach;?>
				 
				<div class="control-group core_paramsappplans">
					<div class="control-label" title="<?php echo XiText::_('COM_PAYPLANS_APP_EDIT_APPS_PLAN_TITLE'); ?>::<?php echo XiText::_('COM_PAYPLANS_APP_EDIT_APPS_PLAN_TITLE_DESC'); ?>" >
						<?php echo XiText::_( 'COM_PAYPLANS_APP_EDIT_APPS_PLAN_TITLE' ); ?>
					</div>
					<div class="controls">
						<?php $plans = $app->getPlans();
						echo PayplansHtml::_('plans.edit', 'Payplans_form[appplans]', $plans, array('multiple'=>true,'usexifbselect' => true, 'style' => 'class="required"'));?>
					</div>
				</div>
				
				<div class="control-group">
										<div class="control-label"><?php echo $form->getLabel('description'); ?> </div>
										<div class="controls"><?php echo $form->getInput('description'); ?></div>	
				</div>
					
						
			</fieldset>
	
			<!-- Logs -->
			<?php echo $this->loadTemplate('edit_log');?>
		</div>
		
		<div class="span6">
			<fieldset class="form-horizontal">
				<legend>
					<?php echo XiText::_( 'COM_PAYPLANS_APP_EDIT_APP_PARAMETERS' ); ?>
				</legend>
	 				
				<?php foreach ($form->getFieldset('app_params') as $field):?>
					<?php $class = $field->group.$field->fieldname; ?>
					<div class="control-group <?php echo $class;?>">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
				<?php endforeach;?>
		
			</fieldset>
			<?php if(isset($appData['help'])):?>
			<?php $help = preg_replace('/\s+/', '', $appData['help']);?>
			<?php if(!empty($help)):?>
				<fieldset class="adminform">
					<legend onClick="xi.jQuery('.pp-app-help').slideToggle();">
						<span class="show pp-app-help">[+]</span>
						 <?php echo XiText::_( 'COM_PAYPLANS_APP_EDIT_APP_HELP' ); ?>
					</legend>
					<div class="hide pp-app-help"><?php echo XiText::_($appData['help']); ?></div>
				</fieldset>
			<?php endif;?>
			<?php endif;?>
		</div>
		
		<?php echo $form->getInput('app_id'); ?>
		<?php echo $form->getInput('type'); ?>
		<input type="hidden" name="task" value="save" />
		<input type="hidden" name="boxchecked" value="1" />
		</div>
	</form>
	
</div>
</div>
<?php 
