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

<div class="pp-group-edit">
<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm">
<div class="row-fluid">
	<!-- left float -->
	<div class="span6">	
            <fieldset class="form-horizontal">
            <legend><?php echo XiText::_('COM_PAYPLANS_GROUP_EDIT_GROUP_DETAILS' ); ?> </legend>
			<?php $fieldSets = $form->getFieldset('basic'); ?>
                                <?php foreach ($fieldSets as $field) : ?>
                                                <?php $class = $field->group.$field->fieldname; ?>
                                                <div class="control-group <?php echo $class;?>">
                                                        <div class="control-label"><?php echo $field->label; ?> </div>
                                                        <div class="controls"><?php echo $field->input; ?></div>								
                                                </div>
			<?php endforeach;?>
            </fieldset>
            
			<fieldset class="form-horizontal">
				<legend><?php echo XiText::_('COM_PAYPLANS_GROUP_EDIT_GROUP_DESCRIPTION') ?></legend>
		                    <?php $fieldSets = $form->getFieldset('description'); ?>
		                            <?php foreach ($fieldSets as $field) : ?>
		                                            <?php $class = $field->group.$field->fieldname; ?>
		                                            <div class="control-group <?php echo $class;?>">
		                                                    <div class="control-label"><?php echo $field->label; ?> </div>
		                                                    <div class="controls"><?php echo $field->input; ?></div>								
		                                            </div>
			<?php endforeach;?>
			
			</fieldset>
	</div>
	<!-- left float -->
	<div class="span6">	
		<fieldset class="form-horizontal">
			<legend><?php echo XiText::_( 'COM_PAYPLANS_GROUP_EDIT_GROUP_PARAMETERS' ); ?></legend>
			<div class="control-group">
				<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_GROUP_EDIT_GROUP_PLAN_TITLE_DESCRIPTION'); ?>" >
					<?php echo XiText::_( 'COM_PAYPLANS_GROUP_EDIT_GROUP_CHILD_PLANS' ); ?>
				</div>
				<!-- We can't add this in xml becuase it won't be saved in same table i.e. group -->
				<div class="controls">
					<?php $plans = $group->getPlans();
					echo PayplansHtml::_('plans.edit', 'Payplans_form[plans]', $plans, array('multiple'=>true));?>
				</div>
                        </div>
			
			<?php $fieldSets = $form->getFieldset('params'); ?>
                        <?php foreach ($fieldSets as $field) : ?>
                                <?php $class = $field->group.$field->fieldname; ?>
                                <div class="control-group <?php echo $class;?>">
                                        <div class="control-label"><?php echo $field->label; ?> </div>
                                        <div class="controls"><?php echo $field->input; ?></div>								
                                </div>
                        <?php endforeach;?>
                        </fieldset>
		
		<!-- Logs -->
		<?php echo $this->loadTemplate('edit_log');?>
		
	</div>

	<?php echo $form->getInput('group_id');?>
	<input type="hidden" name="task" value="save" />
</div>
</form>
</div>
<?php
