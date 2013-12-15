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
if(defined('_JEXEC')===false) die();

?>
<div class="pp-invoice-edit">
	<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm">
	<!-- left float -->
	<div class="row-fluid">
		<div class="span6">	
		
		   <!-- ================== Detail section ====================== -->
		   
			<fieldset class="form-horizontal">
				<legend> <?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_DETAILS' ); ?> </legend>
			
				<div class="control-group">
					<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_TOOLTIP_INVOICE_ID') ?>" >
			 		<?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_INVOICE_ID') ?> 
			 		</div>
					<div class="controls"><?php echo $invoice->getId()."(".$invoice->getKey().")"; ?>
					</div>
				</div>

				<div class="control-group">
					<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_TOOLTIP_OBJECT_TYPE') ?>" >
						<?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_OBJECT_TYPE') ?>
					</div>  
					<div class="controls"><?php echo $invoice->getReferenceObject(PAYPLANS_INSTANCE_REQUIRE)->getObjectLink(); ?>
					</div>
				</div>
		
				<div class="control-group">
					<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_TOOLTIP_BUYER') ?>" >
						<?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_BUYER') ?>
					</div>  
					<div class="controls"><?php echo PayplansHtml::link(XiRoute::_("index.php?option=com_payplans&view=user&task=edit&id=".$user->getId(), false), $user->getRealname()); ?>
					<?php echo '('.$user->getUsername().')'; ?></div>
				</div>

				<div class="control-group">
					<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_TOOLTIP_SUBTOTAL') ?>">
			 			<?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_SUBTOTAL') ?> 
			 		</div>
					<?php $amount = $invoice->getSubtotal();
			 		$currency = $invoice->getCurrency();?>
					<div class="controls" name="subtotal">
						<?php echo $this->loadTemplate('partial_amount', compact('currency', 'amount'));?>
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_TOOLTIP_DISCOUNTABLE') ?>" >
			 			<?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_DISCOUNTABLE') ?> 
			 		</div>
			 		<?php $amount =  $invoice->getDiscountable();?>
					<div class="controls" name="discountable"><?php 
								echo $this->loadTemplate('partial_amount', compact('currency', 'amount')); ?>
					</div>
				</div>
			
				<div class="control-group">
					<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_TOOLTIP_DISCOUNT') ?>" >
				 		<?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_DISCOUNT') ?> 
				 	</div>
				 	<?php $amount =  $invoice->getDiscount();?>
					<div class="controls" name="discount"><?php 
					echo $this->loadTemplate('partial_amount', compact('currency', 'amount')); ?></div>
				</div>
			
				<div class="control-group">
					<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_TOOLTIP_TAX') ?>" >
				 		<?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_TAX') ?> 
				 	</div>
				 	<?php $amount = $invoice->getTaxAmount();?>
					<div class="controls" name="taxamount"><?php 
						echo $this->loadTemplate('partial_amount', compact('currency', 'amount'));?></div>
				</div>
							
				<div class="control-group">
					<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_TOOLTIP_NON_TAXABLE') ?>" >
				 		<?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_NON_TAXABLE') ?> 
				 	</div>
				 	<?php $amount = $invoice->getNontaxableAmount();?>
					<div class="controls"  name="nontaxableamount"><?php echo $this->loadTemplate('partial_amount', compact('currency', 'amount'));?></div>
				</div>
			
				<div class="control-group">
					<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_TOOLTIP_TOTAL') ?>" >
				 		<?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_TOTAL') ?> 
				 	</div>
					<?php $amount =  $invoice->getTotal();?>
					<div class="controls" name="total">
							<?php echo $this->loadTemplate('partial_amount', compact('currency', 'amount'));?>
					</div>
				</div>

				<div class="control-group">
					<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_TOOLTIP_STATUS') ?>" >
						<?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_STATUS') ?> 
					</div>
					<div class="controls"><?php echo PayplansHtml::_('status.edit', 'status', $invoice->getStatus(), 'INVOICE');?>
						<div onclick="xi.url.openInModal('<?php echo 'index.php?option=com_payplans&view=invoice&task=statusHelp';?>'); return false;" class="pp-invoice-status pp-float-left theme-preview-link"> </div>	
					</div>
				</div>
			
				<div class="control-group">
					<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_TOOLTIP_CREATED_DATE') ?>" >
				 		<?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_CREATED_DATE') ?> 
				 	</div>
					<div class="controls"><?php echo XiDate::timeago($invoice->getCreatedDate()->toMySql()); ?>
					</div>
				</div>
			
		</fieldset>
		
		<!-- ================== parameter section ====================== -->
		
		<fieldset class="form-horizontal">
			<legend><?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_PARAMETERS'); ?> </legend>
				<?php foreach ($form->getFieldset('params') as $field):?>
					<?php $class = $field->group.$field->fieldname; ?>
					<div class="control-group <?php echo $class;?>">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
				<?php endforeach;?>
		</fieldset>

		<!-- LOGS -->
		<?php echo $this->loadTemplate('edit_log'); ?>
	</div>
	
	<!-- ================== Other paramters In Right ====================== -->
	
	<div class="span6">
		<div class="pp-position clearfix">
		<?php if(XiFactory::getConfig()->enableDiscount): ?>
			<?php echo $this->loadTemplate('discount'); ?>
		<?php endif; ?>

		<?php $position = 'payplans_invoice_edit_modifier'; ?>
		<?php echo $this->loadTemplate('partial_position', compact('plugin_result','position'));?>
		</div>
	
		<div name="ppmodifiers">
			<?php echo $this->loadTemplate('partial_modifier_table', compact('modifiers','invoice')); ?>
		</div>
		
		<?php echo $this->loadTemplate('partial_wallet_table'); ?>
		<?php echo $this->loadTemplate('invoice_transaction');?>
	</div>
	
    <?php echo $form->getInput('invoice_id');?>
	<input type="hidden" name="task" value="save" />
	<input type="hidden" name="boxchecked" value="1" />
</div>
</form>
</div>
<?php 
