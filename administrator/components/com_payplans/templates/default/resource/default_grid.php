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

<form action="<?php echo XiRoute::_('index.php?option=com_payplans&view=resource', false); ?>" method="post" name="adminForm" id="adminForm">
	<?php echo $this->loadTemplate('filter'); ?>
	<table class="table table-striped">

		<thead>
		<!-- TABLE HEADER START -->
			<tr>
        		<th class="default-grid-chkbox">
					<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
				</th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_RESOURCE_GRID_TITLE", 'resource', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_RESOURCE_GRID_USER_ID", 'user_id', $filter_order_Dir, $filter_order);?></th>
<!--			<th><?php //echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_RESOURCE_GRID_VALUE", 'value', $filter_order_Dir, $filter_order);?></th>-->
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_RESOURCE_GRID_SUBSCRIPTION_IDS", 'subscription_ids', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_RESOURCE_GRID_COUNT", 'count', $filter_order_Dir, $filter_order);?></th>
			</tr>
		<!-- TABLE HEADER END -->
		</thead>

		<tbody>
		<!-- TABLE BODY START -->
			<?php $count= $limitstart;
			$cbCount = 0;
			foreach ($records as $record):?>
				<tr class="<?php echo "row".$count%2; ?>">
					<th class="default-grid-chkbox">
    					<?php echo PayplansHtml::_('grid.id', $cbCount++, $record->{$record_key} ); ?>
    				</th>
    				<td><?php echo PayplansHtml::link($uri.'&task=edit&id='.$record->{$record_key}, $record->title);?></td>
					<td><?php echo $record->user_id;?></td>
<!--				<td><?php //echo $record->value;?></td>-->
					<td><?php echo $record->subscription_ids;?></td>
					<td><?php echo $record->count;?></td>
				</tr>
			<?php $count++;?>
			<?php endforeach;?>
		<!-- TABLE BODY END -->
		</tbody>

		<tfoot>
		<!-- TABLE FOOTER START -->
			<tr>
				<td colspan="8">
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
