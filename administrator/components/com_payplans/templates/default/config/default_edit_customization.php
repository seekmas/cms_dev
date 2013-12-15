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
				<legend><?php echo XiText::_('COM_PAYPLANS_CONFIG_CUSTOMIZATION_INVOICE');?> </legend>
				<?php //$fields = $form->getFieldset('invoice'); ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $form->getLabel('companyAddress'); ?>
						</div>
						<div class="controls">
							<?php echo $form->getInput('companyAddress'); ?>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label">
							<?php echo $form->getLabel('companyName'); ?>
						</div>
						<div class="controls">
							<?php echo $form->getInput('companyName'); ?>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label">
							<?php echo $form->getLabel('companyCityCountry'); ?>
						</div>
						<div class="controls">
							<?php echo $form->getInput('companyCityCountry'); ?>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label">
							<?php echo $form->getLabel('companyPhone'); ?>
						</div>
						<div class="controls">
							<?php echo $form->getInput('companyPhone'); ?>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label">
							<?php echo $form->getLabel('add_token'); ?>
						</div>
						<div class="controls">
							<?php echo $form->getInput('add_token'); ?><br/>
						</div>
					</div>
					
					<div class="control-group">
						<div class="controls">
								<?php echo $form->getInput('rewriter'); ?>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label">
							<?php echo $form->getLabel('companyLogo'); ?>
						</div>
						<div class="controls">
							<?php echo $form->getInput('companyLogo'); ?>
							<?php $logoValue = $form->getValue('companyLogo');?>
							<?php $subparam = '';?>
						</div>
					</div>
					
					<div class="control-group">
						<div class="controls">
							<?php if(!empty($logoValue)):?>
								<?php 	
										ob_start();
										?>
											<br><a onclick="xi.jQuery.apprise('Are you sure to delete?', 
																			{'verify':true}, 
																			function(r){
																				if(r){
																					payplans.url.redirect('<?php echo XiRoute::_('index.php?option=com_payplans&view=config&task=removecompanylogo'); ?>');
																				} 
																				else{
																					return false;
																				}
																			}
																			);" href="#">
																					
												Delete
											</a>
											<p><img style="max-width: 250px;" src="<?php echo PayplansHelperTemplate::mediaURI(XiHelperJoomla::getRootPath().DS.$logoValue, false) ?>" /></p>
										<?php 
										$subparam = ob_get_contents();
										ob_end_clean();
							endif;?>
							<?php echo $subparam;?>
						</div>
					</div>
					
					
					
					<div class="control-group">
						<div class="control-label">
							<?php echo $form->getLabel('note'); ?>
						</div>
						<div class="controls">
							<?php echo $form->getInput('note'); ?>
						</div>
					</div>
					
			</fieldset>
			
			<fieldset class="form-horizontal">
				<legend><?php echo XiText::_('COM_PAYPLANS_CONFIG_CUSTOMIZATION_DASHBOARD');?> </legend>
				<?php foreach($form->getFieldset('dashboard') as $field) : ?>
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
		</div>
		
		<div class="span6">
			<fieldset class="form-horizontal">
				<legend><?php echo XiText::_('COM_PAYPLANS_CONFIG_CUSTOMIZATION_TEMPLATE');?> </legend>
					<div class="control-group">
						<div class="control-label">
							<?php echo $form->getLabel('rtl_support'); ?>
						</div>
						<div class="controls">
							<?php echo $form->getInput('rtl_support'); ?>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label">
							<?php echo $form->getLabel('layout'); ?>
						</div>
						<div class="controls">
							<?php echo $form->getInput('layout'); ?>
						</div>
					</div>
					
					<div class="control-group layoutrow_plan_counter">
						<div class="control-label">
							<?php echo $form->getLabel('row_plan_counter'); ?>
						</div>
						<div class="controls">
							<?php echo $form->getInput('row_plan_counter'); ?>
						</div>
					</div>
			</fieldset>
		</div>
	</div>
	
	<!-- Right ends -->	
<?php 

