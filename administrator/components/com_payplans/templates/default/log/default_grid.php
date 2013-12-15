<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Log
* @contact 		shyam@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();
if(PAYPLANS_JVERSION_25 === true){
	JHtml::_('behavior.framework');
}
?>

<form action="<?php echo $uri; ?>" method="post" id="adminForm" name="adminForm">
	
	<?php echo $this->loadTemplate('filter'); ?>
	<table class="table table-striped">
		
		<thead>
		<!-- TABLE HEADER START -->
			<tr>			
			    <th class="default-grid-chkbox">
					<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
				</th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_LOG_GRID_MESSAGE", 'message', $filter_order_Dir, $filter_order);?></th>
				<th class="hidden-phone"><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_LOG_GRID_OWNER_ID", 'owner_id', $filter_order_Dir, $filter_order);?></th>
				<th class="hidden-phone"><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_LOG_GRID_CLASS", 'class', $filter_order_Dir, $filter_order);?></th>
				<th class="hidden-phone"><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_LOG_GRID_USER_ID", 'user_id', $filter_order_Dir, $filter_order);?></th>
				<th class="hidden-phone"><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_LOG_GRID_LEVEL", 'level', $filter_order_Dir, $filter_order);?></th>
				<th class="hidden-phone"><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_LOG_GRID_USER_IP", 'user_ip', $filter_order_Dir, $filter_order);?></th>
				<th class="hidden-phone"><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_LOG_GRID_CREATED_DATE", 'created_date', $filter_order_Dir, $filter_order);?></th>
				
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
					<td>
						<div onclick="payplans.url.modal('<?php echo 'index.php?option=com_payplans&view=log&task=view&tmpl=component&record='.$record->{$record_key};?>', true); return false;">
						<i class="icon-zoom-in"></i><?php echo $record->message."(". $record->log_id.")";?>
						</div>					
					</td>
					<td class="hidden-phone"><!-- in case there is no user id present then display SYSTEM in modifier -->
						<?php if($record->owner_id != 0):?>
								<?php echo  $record->owner_id.' ('.PayplansHelperUser::getName($record->owner_id).')';?>
				    	<?php else :?>
				    			<?php echo XiText::_('COM_PAYPLANS_LOGGER_MODIFIER_SYSTEM');?>
				    	<?php endif;?>
				    </td>
					
					
					<td class="hidden-phone"><?php echo $record->class."(".$record->object_id.")" ;?></td>
					<td class="hidden-phone"><!-- in case there is no user id present then display SYSTEM in modifier -->
						<?php if($record->user_id != 0):?>
								<?php echo  $record->user_id.' ('.PayplansHelperUser::getName($record->user_id).')';?>
				    	<?php else :?>
				    			<?php echo XiText::_('COM_PAYPLANS_LOGGER_MODIFIER_SYSTEM');?>
				    	<?php endif;?>
				    </td>
					<td class="hidden-phone"><?php echo  PayplansHtml::_('loglevel.grid', '', $record->level);?></td>
					<td class="hidden-phone"><?php echo $record->user_ip ;?></td>
					<td class="hidden-phone"><?php echo XiDate::timeago($record->created_date); ?></td>
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

