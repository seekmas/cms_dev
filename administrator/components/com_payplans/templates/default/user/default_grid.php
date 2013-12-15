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
if(PAYPLANS_JVERSION_25 === true){
	JHtml::_('behavior.framework');
}?>

<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm">

		<?php echo $this->loadTemplate('filter'); ?>
	<table class="table table-striped">
		<thead>
		<!-- TABLE HEADER START -->
			<tr>
			    <th class="default-grid-chkbox hidden-phone">
					<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
				</th>
				<th class="hidden-phone"><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_USER_GRID_USER_ID", 'user_id', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_USER_GRID_USER_NAME", 'realname', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_USER_GRID_USER_USERNAME",'username', $filter_order_Dir, $filter_order);?></th>
				<th class="hidden-phone"><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_USER_GRID_USER_USERTYPE", 'usertype', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo XiText::_("COM_PAYPLANS_USER_GRID_USER_SUBSCRIPTION");?></th>				
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
					<td class="hidden-phone"><?php echo $record->user_id;?></td>
					<td><?php echo PayplansHtml::link($uri.'&task=edit&id='.$record->{$record_key}, $record->realname);?></td>
					<td><?php echo $record->username;?></td>
					<td class="hidden-phone"><?php echo $record->usertype;?></td>
					<td><?php if(isset($subscriptions[$record->user_id])) : ?>
						<?php foreach($subscriptions[$record->user_id] as $sub):?>
								<div class="user-grid-subscription-values">									
									<div class="user-grid-subscription-value1">
										<?php if(isset($plans[$sub->plan_id])):?>
										<?php echo PayplansHtml::link(XiRoute::_('index.php?option=com_payplans&view=subscription&task=edit&id='.$sub->subscription_id, false), $plans[$sub->plan_id]->getTitle());?>
										<?php else:?>
										<?php echo XiText::_("COM_PAYPLANS_SUBSCRIPTION_PLAN_DOES_NOT_EXIST");?>
										<?php endif;?>
									</div>
									<div class="user-grid-subscription-value2">
										<?php echo XiText::_('COM_PAYPLANS_STATUS_'.PayplansStatus::getName($sub->status));?></div>
								</div>
								<div class='clr'></div>								
						<?php endforeach;?>
						<?php endif;?>
					</td>
				</tr>
			<?php $count++;?>
			<?php endforeach;?>
		<!-- TABLE BODY END -->
		</tbody>
		
		<tfoot>
			<tr>
				<td colspan="7">
					<?php echo $pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>

	<input type="hidden" name="filter_order" value="<?php echo $filter_order;?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $filter_order_Dir;?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="plan_id" value="" />
</form>
<?php 
