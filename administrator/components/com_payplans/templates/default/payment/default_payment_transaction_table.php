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
			<th><?php echo XiText::_('COM_PAYPLANS_PAYMENT_EDIT_TRANSACTION_GRID_TRANSACTION_ID');?></th>
			<th><?php echo XiText::_('COM_PAYPLANS_PAYMENT_EDIT_TRANSACTION_GRID_INVOICE_ID');?></th>
			<th><?php echo XiText::_('COM_PAYPLANS_PAYMENT_EDIT_TRANSACTION_GRID_AMOUNT');?></th>
			<th><?php echo XiText::_('COM_PAYPLANS_PAYMENT_EDIT_TRANSACTION_GRID_GATEWAY_TXN_ID');?></th>
			<th><?php echo XiText::_('COM_PAYPLANS_PAYMENT_EDIT_TRANSACTION_GRID_CREATED_DATE');?></th>
		</tr>
	<!-- TABLE HEADER END -->
	</thead>

	<tbody>
	<!-- TABLE BODY START -->
		<?php $count = 0;?>
		<?php ksort($transaction_records);?>
		<?php foreach($transaction_records as $record) :
			$transaction->bind($record);?>
			<tr class="<?php echo "row".$count%2; ?>">
				<td><?php echo PayplansHtml::link(XiRoute::_("index.php?option=com_payplans&view=transaction&task=edit&id=".$transaction->getId(), false), $transaction->getId());?></td>
				<td><?php echo $transaction->getInvoice();?></td>
				<td><?php echo PayplansHelperFormat::price($transaction->getAmount()); ?></td>
				<td><?php echo $transaction->getGatewayTxnId();?></td>
				<td><?php echo XiDate::timeago($transaction->getCreatedDate()->toMySql());?></td>
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
