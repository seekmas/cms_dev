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


<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
	<!-- left float -->
	<div class="span6">	
	
		<fieldset class="form-horizontal">
			<legend><?php echo XiText::_('COM_PAYPLANS_PLAN_EDIT_PLAN_DETAILS' ); ?> </legend>
					
					<div class="control-group">
										<div class="control-label"><?php echo $form->getLabel('title'); ?> </div>
										<div class="controls"><?php echo $form->getInput('title'); ?></div>	
					</div>	
					<div class="control-group">
										<div class="control-label"><?php echo $form->getLabel('published'); ?> </div>
										<div class="controls"><?php echo $form->getInput('published'); ?></div>				
					</div>	
					<div class="control-group">
										<div class="control-label"><?php echo $form->getLabel('visible'); ?> </div>
										<div class="controls"><?php echo $form->getInput('visible'); ?></div>	
					</div>	
						
					
					
						<?php foreach ($form->getFieldset('params') as $field):?>
							<?php $class = $field->group.$field->fieldname; ?>
							<div class="control-group <?php echo $class;?>">
								<div class="control-label"><?php echo $field->label; ?> </div>
								<div class="controls"><?php echo $field->input; ?></div>								
							</div>
						<?php endforeach;?>

		</fieldset>
		
		<fieldset class="form-horizontal">
			
			<div class="control-group">
										<div class="control-label"><?php echo $form->getLabel('description'); ?> </div>
										<div class="controls"><?php echo $form->getInput('description'); ?></div>	
					</div>	
		</fieldset>
		
		<!-- LOGS -->
		<?php echo $this->loadTemplate('edit_log'); ?>
	</div>

	<!-- right floater -->
	<div class="span6">

	<fieldset class="form-horizontal">
                 <legend><?php echo XiText::_( 'COM_PAYPLANS_PLAN_EDIT_TIME_PARAMETERS' ); ?></legend>
                                             
                                                       <?php foreach ($form->getFieldset('details') as $field):?>
                                                               <?php $class = $field->group.$field->fieldname; ?>
                                                               <div class="control-group <?php echo $class;?>">
                                                                       <div class="control-label"><?php echo $field->label; ?> </div>
                                                                       <div class="controls"><?php echo $field->input; ?></div>                                                                
                                                               </div>
                                                       <?php endforeach;?>
                                                                   
                               </fieldset>
		
		<fieldset class="form-horizontal">
			<legend><?php echo XiText::_( 'COM_PAYPLANS_PLAN_EDIT_ASSOCIATION' ); ?></legend>
			<div>
				<div class="span5" title="<?php echo XiText::_('COM_PAYPLANS_PLAN_EDIT_APPS_TITLE_DESC'); ?>" >
					<?php echo XiText::_( 'COM_PAYPLANS_PLAN_EDIT_APPS_TITLE' ); ?>
				</div>
				
				<div class="span7">
					<?php $apps = $plan->getPlanapps();?>
					<?php 	echo PayplansHtml::_('apps.edit', 'Payplans_form[planapps]', $apps, '', array('multiple'=>true)); ?> 
				</div>
			</div>
			<div class="clearfix"></div>
			<!-- display all core apps if exists -->
			<?php if(!empty($core_apps)):?>
			<div class="pp-gap-top10">
				<div class="span5" title="<?php echo XiText::_('COM_PAYPLANS_PLAN_EDIT_CORE_APPS_TITLE'); ?>" >
					<?php echo XiText::_( 'COM_PAYPLANS_PLAN_EDIT_CORE_APPS_TITLE' ); ?>
				</div>
				<div class="core-app-param-value-wrapper span7">
						<?php foreach($core_apps as $coreApp):?>
							<div class="core-app-param-value"><?php echo $coreApp;?></div>
						<?php endforeach;?>
				</div>
			</div>
			<?php endif;?>
			<div class="clearfix"></div>
			<div class="pp-gap-top10">
				<div class="span5" title="<?php echo XiText::_('COM_PAYPLANS_PLAN_EDIT_GROUPS_TITLE_DESC'); ?>">
					<?php echo XiText::_( 'COM_PAYPLANS_PLAN_EDIT_GROUPS_TITLE' ); ?>
				</div>
				<div class="span7">
					<?php $groups = $plan->getGroups();?>
					<?php 	echo PayplansHtml::_('groups.edit', 'Payplans_form[groups]', $groups, array('multiple'=>true)); ?> 
				</div>
			</div>		
		</fieldset>
	
		<!-- PARENT CHILD PLUGIN RESULT -->	
		<?php 
		$position = 'payplans-admin-plan-edit-parentchild';
        echo $this->loadTemplate('partial_position',compact('plugin_result','position'));
		 ?>
		
	</div>
	
	<input type="hidden" name="task" value="" />
    <?php echo $form->getInput('plan_id'); ?>
	<input type="hidden" name="boxchecked" value="1" />
	</div>
</form>

<?php 
