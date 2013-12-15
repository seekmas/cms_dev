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
if(defined('_JEXEC')===false) die();?>

<?php if(!empty($log_records)): ?>
<fieldset class="form-horizontal">
	<legend onClick="payplans.jQuery('.pp-log-details').slideToggle();">
		<span style="font-size: 0.7em;" class=" <?php echo isset($show_log_details)? 'hide' : 'show' ;?> pp-log-details">[+]</span>
		<?php echo XiText::_('COM_PAYPLANS_LOG_GRID_LEGEND'); ?>
	</legend>
	
	<div class="<?php echo isset($show_log_details)? 'show' : 'hide' ;?> pp-log-details">
	
	<table id="payplans_grid" class="table table-striped">
		
		<thead>
	<!-- TABLE HEADER START -->
		<tr>
			<th><?php echo XiText::_('COM_PAYPLANS_LOG_GRID_ID');?></th>
			<th><?php echo XiText::_('COM_PAYPLANS_LOG_GRID_MESSAGE');?></th>
			<th><?php echo XiText::_('COM_PAYPLANS_LOG_GRID_USER_ID');?></th>
			<th><?php echo XiText::_('COM_PAYPLANS_LOG_GRID_LEVEL');?></th>
			<th><?php echo XiText::_('COM_PAYPLANS_LOG_GRID_USER_IP');?></th>
			<th><?php echo XiText::_('COM_PAYPLANS_LOG_GRID_CREATED_DATE');?></th>
		</tr>
	<!-- TABLE HEADER END -->
	</thead>
		
		<tbody>
		<!-- TABLE BODY START -->
			<?php 
			$count =0;
			$cbCount = 0;
			foreach ($log_records as $record):?>
				<tr class="<?php echo "row".$count%2; ?>">
					<td> <?php echo $count+1; ?> </td>
					<td><?php echo $record->message;?>
							<a href="" 
							   onclick="payplans.url.modal('<?php echo 'index.php?option=com_payplans&view=log&task=view&tmpl=component&record='.$record->log_id;?>'); return false;"> 
							   <?php echo XiText::_('COM_PAYPLANS_LOG_DETAIL_LINK');?>
							</a>			
					</td>
					
					<td><!-- in case there is no user id present then display SYSTEM in modifier -->
						<?php if($record->user_id != 0):?>
								<?php echo  $record->user_id.' ('.PayplansHelperUser::getName($record->user_id).')';?>
				    	<?php else :?>
				    			<?php echo XiText::_('COM_PAYPLANS_LOGGER_MODIFIER_SYSTEM');?>
				    	<?php endif;?>
				    </td>
					<td><?php echo  PayplansHtml::_('loglevel.grid', '', $record->level);?></td>
					<td><?php echo $record->user_ip ;?></td>
					<td><?php echo XiDate::timeago($record->created_date); ?></td>	
				</tr>
			<?php $count++;?>
			<?php endforeach;?>
		<!-- TABLE BODY END -->
		</tbody>
		
	</table>
	</div>
</fieldset>
<?php endif;?>
<?php 