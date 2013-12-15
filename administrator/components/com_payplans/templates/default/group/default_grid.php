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

<form action="<?php echo XiRoute::_('index.php?option=com_payplans&view=group', false); ?>" method="post" name="adminForm" id="adminForm">
	<?php echo $this->loadTemplate('filter'); ?>
	
	<table class="table table-striped">
		<thead>
		<!-- ROW HEADER START -->
			<tr>
		        <th class="default-grid-chkbox">
					<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
				</th>
				<th class="hidden-phone"><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_GROUP_GRID_GROUP_ID", 'app_id', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_GROUP_GRID_GROUP_TITLE", 'title', $filter_order_Dir, $filter_order);?></th>
				<th class="hidden-phone"><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_GROUP_GRID_GROUP_VISIBLE", 'visible', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_GROUP_GRID_GROUP_PUBLISHED", 'published', $filter_order_Dir, $filter_order);?></th>
				<th class="hidden-phone"><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_GROUP_GRID_GROUP_ORDERING", 'ordering', $filter_order_Dir, $filter_order);?></th>
			</tr>
		<!-- ROW HEADER END -->
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
		    				<td class="hidden-phone"><?php echo $record->group_id;?></td>
							<td style="width:40%;" class="hidden-phone">
								<div><?php echo PayplansHtml::link($uri.'&task=edit&id='.$record->{$record_key}, $record->title);?></div>
								<div><?php echo $record->description;?></div>
							</td>   					
							<td class="hidden-phone"><?php echo PayplansHtml::_("boolean.grid", $record, 'visible', $count);?></td>
							<td><?php echo PayplansHtml::_("boolean.grid", $record, 'published', $count);?></td>
							<td class="hidden-phone">
								<span><?php echo $pagination->orderUpIcon( $count , true, 'orderup', 'Move Up'); ?></span>
								<span><?php echo $pagination->orderDownIcon( $count , count($records), true , 'orderdown', 'Move Down', true ); ?></span>
							</td>				
					</tr>
			<?php $count++;?>
			<?php endforeach; ?>
		<!-- TABLE BODY END -->
		</tbody>

		<tfoot>
		<!-- TABLE FOOTER START -->
			<tr>
				<td colspan="7">
					<?php echo $pagination->getListFooter(); ?>
				</td>
			</tr>
		<!-- TABLE FOOTER END -->
		</tfoot>
	</table>

	<input type="hidden" name="filter_order" value="<?php echo $filter_order;?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $filter_order_Dir;?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
</form>