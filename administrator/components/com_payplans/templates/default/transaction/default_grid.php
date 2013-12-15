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
// get the java script for add new subscription link
//echo $this->loadTemplate('script');
?>

<form action="<?php echo XiRoute::_('index.php?option=com_payplans&view=transaction', false); ?>" method="post" name="adminForm" id="adminForm">
	<?php echo $this->loadTemplate('filter'); ?>
	<table class="table table-striped">

		<thead>
		<!-- TABLE HEADER START -->
			<tr>
        		<th class="default-grid-chkbox hidden-phone">
					<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
				</th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_TRANSACTION_GRID_TRANSACTION_ID", 'transaction_id', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_TRANSACTION_GRID_BUYER", 'user_id', $filter_order_Dir, $filter_order);?></th>
				<th class="hidden-phone"><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_TRANSACTION_GRID_INVOICE_ID", 'invoice_id', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_TRANSACTION_GRID_AMOUNT", 'amount', $filter_order_Dir, $filter_order);?></th>
				<th class="hidden-phone"><?php echo XiText::_("COM_PAYPLANS_TRANSACTION_GRID_GATEWAY_TYPE");?></th>
				<th class="hidden-phone"><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_TRANSACTION_GRID_MESSAGE", 'message', $filter_order_Dir, $filter_order);?></th>
				<th class="hidden-phone"><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_TRANSACTION_GRID_CREATED_DATE", 'created_date', $filter_order_Dir, $filter_order);?></th>
			</tr>
		<!-- TABLE HEADER END -->
		</thead>

		<tbody>
		<!-- TABLE BODY START -->
			<?php $count= $limitstart;
			$cbCount = 0;
			foreach ($records as $record):?>
				<tr class="<?php echo "row".$count%2; ?>">
					<th class="default-grid-chkbox hidden-phone">
    					<?php echo PayplansHtml::_('grid.id', $cbCount++, $record->{$record_key} ); ?>
    				</th>
    				<td><?php echo PayplansHtml::link($uri.'&task=edit&id='.$record->{$record_key}, $record->{$record_key}); ?></td>
    				<td><?php echo "<span class='hidden-phone'>#".$record->user_id.": ".$users[$record->user_id]->realname."</span>" ;?>
				    	<?php echo '('.$users[$record->user_id]->username.')';?>
				    </td>	
				    <td class="hidden-phone">
				    	<?php if(!empty($record->invoice_id)):?>
				    		<?php echo PayplansHtml::link(XiRoute::_('index.php?option=com_payplans&view=invoice&task=edit&id='.$record->invoice_id), $record->invoice_id.'('.XiHelperUtils::getKeyFromId($record->invoice_id).')');?></td>
				    	<?php else :?>
				    		<?php echo XiText::_('COM_PAYPLANS_TRANSACTION_UNUTILIZED');?>
				    	<?php endif;?>
				    <td><?php echo PayplansHelperFormat::price($record->amount);?></td>
				    <td class="hidden-phone"><?php $payment = PayplansPayment::getInstance($record->payment_id);
				    		  echo (!empty($record->payment_id) && ($payment instanceof PayplansPayment))? $payment->getAppName() : XiText::_('COM_PAYPLANS_TRANSACTION_PAYMENT_GATEWAY_NONE') ;?></td>
				    <td class="hidden-phone"><?php echo XiText::_($record->message);?></td>
				    <td class="hidden-phone"><?php echo XiDate::timeago($record->created_date); ?></td>
				</tr>
			<?php $count++;?>
			<?php endforeach;?>
		<!-- TABLE BODY END -->
		</tbody>

		<tfoot>
		<!-- TABLE FOOTER START -->
			<tr>
				<td colspan="9">
					<?php echo $pagination->getListFooter(); ?>
				</td>
			</tr>
		<!-- TABLE BODY END -->
		</tfoot>
	</table>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $filter_order;?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $filter_order_Dir;?>" />
</form>
<?php 
