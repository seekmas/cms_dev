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
if(defined('_JEXEC')===false) die();
?>

<table class="table table-striped">
	<thead>
	<!-- TABLE HEADER START -->
		<tr>
			<th><?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_INVOICE_GRID_INVOICE_ID');?></th>			
			<th><?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_INVOICE_GRID_BUYER_ID');?></th>
			<th><?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_INVOICE_GRID_OBJECT_TYPE');?></th>
			<th><?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_INVOICE_GRID_SUBTOTAL');?></th>
			<th><?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_INVOICE_GRID_TOTAL');?></th>
			<th><?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_INVOICE_GRID_STATUS');?></th>    
		</tr>
	<!-- TABLE HEADER END -->
	</thead>
	
	<tbody>
	<!-- TABLE BODY START -->		 
		<?php $count = 0;?> 
		<?php ksort($invoice_records);?>
		<?php foreach($invoice_records as $invoice) : ?>
			<tr class="<?php echo "row".$count%2; ?>">
				<td><?php echo PayplansHtml::link(XiRoute::_("index.php?option=com_payplans&view=invoice&task=edit&id=".$invoice->getId(), false), $invoice->getId().'('.XiHelperUtils::getKeyFromId($invoice->getId()).')');?></td>
				<td><?php echo $invoice->getBuyer();?></td>
				<td><?php echo $invoice->getObjectType();?></td>
				<td><?php echo $invoice->getSubtotal();?></td>	
				<td><?php echo $invoice->getTotal();?></td>
				<td><?php echo XiText::_('COM_PAYPLANS_STATUS_'.PayplansStatus::getName($invoice->getStatus()));?></td>
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
<?php 
