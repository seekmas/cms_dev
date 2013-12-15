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

<form action="<?php echo XiRoute::_('index.php?option=com_payplans&view=order', false); ?>" method="post" name="adminForm" id="adminForm">
	<?php echo $this->loadTemplate('filter'); ?>
	<table class="table table-striped">

		<thead>
		<!-- TABLE HEADER START -->
			<tr>
        		<th class="default-grid-chkbox">
					<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
				</th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_ORDER_GRID_ORDER_ID", 'order_id', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_ORDER_GRID_SUBSCRIPTION_ID", 'subscription_id', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_ORDER_GRID_BUYER", 'buyer_id', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_ORDER_GRID_TOTAL", 'total', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_ORDER_GRID_STATUS", 'status', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_ORDER_GRID_CREATED_DATE", 'created_date', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_ORDER_GRID_MODIFIED_DATE", 'modified_date', $filter_order_Dir, $filter_order);?></th>
			</tr>
		<!-- TABLE HEADER END -->
		</thead>

		<tbody>
		<!-- TABLE BODY START -->
			<?php $count= $limitstart;
			$cbCount = 0;
			foreach ($records as $record):?>
				<tr class="<?php echo "row".$count%2; ?>">
					
					<th class="default-grid-chkbox"><?php echo PayplansHtml::_('grid.id', $cbCount++, $record->{$record_key} ); ?></th>
    				<td><?php echo PayplansHtml::link($uri.'&task=edit&id='.$record->{$record_key}, $record->{$record_key}. '('.XiHelperUtils::getKeyFromId($record->{$record_key}).')'); ?></td>
					
					<td><?php echo (empty($subscriptions[$record->order_id]->subscription_id)) ? XiText::_('COM_PAYPLANS_ORDER_GRID_NO_SUBSCRIPTION_ADDED') : PayplansHtml::link(XiRoute::_('index.php?option=com_payplans&view=subscription&task=edit&id='.$subscriptions[$record->order_id]->subscription_id), 
																																											$subscriptions[$record->order_id]->subscription_id . '('.XiHelperUtils::getKeyFromId($subscriptions[$record->order_id]->subscription_id).')'); ?></td>
    				
    				<td><?php echo  $users[$record->buyer_id]->realname . '('.$users[$record->buyer_id]->username.')';?></td>
					<td><?php echo PayplansHelperFormat::price($record->total);?></td>
					
					<td><?php $status = PayplansStatus::getStatusOf('ORDER'); 
										echo XiText::_('COM_PAYPLANS_STATUS_'.$status[$record->status]);?></td>
					
					<td><?php echo XiDate::timeago($record->created_date);?></td>
					<td><?php echo XiDate::timeago($record->modified_date);?></td>
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

	<input type="hidden" name="filter_order" value="<?php echo $filter_order;?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $filter_order_Dir;?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
</form>
<?php
