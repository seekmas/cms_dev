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
			<th><?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_TRANSACTION_GRID_TRANSACTION_ID');?></th>			
			<th><?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_TRANSACTION_GRID_AMOUNT');?></th>
			<th><?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_TRANSACTION_GRID_MESSAGE');?></th>
			<th><?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_TRANSACTION_GRID_CREATED_DATE');?></th>    
		</tr>
	<!-- TABLE HEADER END -->
	</thead>
	
	<tbody>
	<!-- TABLE BODY START -->		 
		<?php $count = 0;?> 
	
					<?php ksort($txn_records);?>
					<?php foreach($txn_records as $transaction) : ?>
						<tr class="<?php echo "row".$count%2; ?>">
							<td><?php echo PayplansHtml::link(XiRoute::_("index.php?option=com_payplans&view=transaction&task=edit&id=".$transaction->transaction_id, false), $transaction->transaction_id);?></td>
							<td><?php echo PayplansHelperFormat::price($transaction->amount);?></td>
							<td><?php echo XiText::_($transaction->message);?></td>
							<td><?php echo XiDate::timeago($transaction->created_date);?></td>	
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
