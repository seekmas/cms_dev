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
$invoiceStatus = $invoice->getStatus();
$condition = ($invoiceStatus == PayplansStatus::INVOICE_CONFIRMED || $invoiceStatus == PayplansStatus::NONE)?true:false;
?>

<fieldset class="form-horizontal">
		<legend> <?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_MODIFIER' ); ?> </legend>
			<?php if($invoice->getId() && (is_array($modifiers) && !empty($modifiers))) :?>
				<table class="table table-striped">
						<thead>
						<!-- TABLE HEADER START -->
							<tr>
								<th><?php echo XiText::_('COM_PAYPLANS_MODIFIER_GRID_AMOUNT');?></th>			
								<th><?php echo XiText::_('COM_PAYPLANS_MODIFIER_GRID_TYPE');?></th>
								<th><?php echo XiText::_('COM_PAYPLANS_MODIFIER_GRID_REFERENCE');?></th>
								<th><?php echo XiText::_('COM_PAYPLANS_MODIFIER_GRID_MESSAGE');?></th>    
								<th><?php echo XiText::_('COM_PAYPLANS_MODIFIER_GRID_PERCENTAGE');?></th>
								<th><?php echo XiText::_('COM_PAYPLANS_MODIFIER_GRID_FREQUENCY');?></th>
							<?php if($condition):?>
								<th>&nbsp;</th>
							<?php endif;?>
							</tr>
						<!-- TABLE HEADER END -->
						</thead>
						
						<tbody>
						<!-- TABLE BODY START -->
							<?php $count = 0; ?> 
										<?php foreach($modifiers as $modifier) : ?>
											<tr class="<?php echo "row".$count%2; ?>">
												<td><?php echo PayplansHelperFormat::price($modifier->getAmount());?></td>
												<td><?php echo $modifier->getType();?></td>
												<td><?php echo $modifier->getReference();?></td>
												<td><?php echo XiText::_($modifier->getMessage());?></td>
												<td><?php echo ($modifier->isPercentage()) ? XiText::_('COM_PAYPLANS_MODIFIER_PERCENTAGE_YES') : XiText::_('COM_PAYPLANS_MODIFIER_PERCENTAGE_NO');?></td>
												<td><?php echo $modifier->getFrequency();?></td>	
											   <?php if($condition):?>
												<td>
													<?php $url = 'index.php?option=com_payplans&view=invoice&task=deleteModifier&modifierId='.$modifier->get('modifier_id') ?>
													<i title="<?php echo XiText::_('COM_PAYPLANS_MODIFIER_DELETE_DESC');?>" class="hasTip pp-icon-remove" payplans-tipsy-gravity="s" onclick="payplansAdmin.invoice_deleteModifier('<?php echo $url;?>')">
													</i>
												</td>
											   <?php endif;?>
											</tr>		
										<?php $count++;?> 
							<?php endforeach;?>		
						<!-- TABLE BODY END -->
						</tbody>
						
						<tfoot>
							<tr>
								<td colspan="7">
					
								</td>
							</tr>
						</tfoot>
					</table>
		<?php else :?>
			<div>
					<p class="center"><big><?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_NO_MODIFIER');?></big></p>
					<p class="center muted"><?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_NO_MODIFIER_DESC');?></p>
			</div>
		<?php endif;?>
		
</fieldset>
<?php 
