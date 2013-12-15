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

<form action="<?php echo XiRoute::_('index.php?option=com_payplans&view=subscription', false); ?>" method="post" name="adminForm" id="adminForm">
	
		<?php echo $this->loadTemplate('filter'); ?>
	
	<table class="table table-striped">
		<thead>
		<!-- TABLE HEADER START -->
			<tr>		
			    <th class="default-grid-chkbox hidden-phone">
					<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
				</th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_SUBSCRIPTION_GRID_ID", 'subscription_id', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_SUBSCRIPTION_GRID_USER", 'user_id', $filter_order_Dir, $filter_order);?></th>
				<th class="hidden-phone"><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_SUBSCRIPTION_GRID_PLAN", 'plan_id', $filter_order_Dir, $filter_order);?></th>
				<th class="hidden-phone"><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_SUBSCRIPTION_GRID_AMOUNT", 'total', $filter_order_Dir, $filter_order);?></th>    
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_SUBSCRIPTION_GRID_STATUS", 'status', $filter_order_Dir, $filter_order);?></th>
				<th class="hidden-phone"><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_SUBSCRIPTION_GRID_SUBSCRIPTION_DATE", 'subscription_date', $filter_order_Dir, $filter_order);?></th>
				<th class="hidden-phone"><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_SUBSCRIPTION_GRID_EXPIRATION_DATE", 'expiration_date', $filter_order_Dir, $filter_order);?></th>    
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
				    <td><?php echo PayplansHtml::link($uri.'&task=edit&id='.$record->{$record_key}, $record->{$record_key}. '<span class="hidden-phone">('.XiHelperUtils::getKeyFromId($record->{$record_key}).')</span>'); ?></td>
				    <td><?php echo "<span class='hidden-phone'>#".$record->user_id.": ".$users[$record->user_id]->realname."</span>";?>
				    	<?php echo '('.$users[$record->user_id]->username.')';?>
				    </td>	
					<td class="hidden-phone"><?php $subscription = PayplansSubscription::getInstance( $record->subscription_id, null, $record);
							  echo $subscription->getTitle();?></td>
					<td class="hidden-phone"><?php echo PayplansHelperFormat::price($record->total); ?></td>
					<td><?php echo PayplansHtml::_('status.grid' ,'status'.$record->{$record_key}, $record->status, 'SUBSCRIPTION', '', 'gridupdatestatus', $record->{$record_key}, $record_key);?></td>
					<td class="hidden-phone"><?php echo XiDate::timeago($record->subscription_date); ?></td>
					<td class="hidden-phone"><?php echo XiDate::timeago($record->expiration_date); ?></td>
				</tr>
			<?php $count++;?>
			<?php endforeach;?>
		<!-- TABLE BODY END -->
		</tbody>
		
		<tfoot>
			<tr>
				<td colspan="9">
					<?php echo $pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>
	
	<input type="hidden" name="filter_order" value="<?php echo $filter_order;?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $filter_order_Dir;?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
</form>
<?php 
