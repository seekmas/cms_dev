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


<form action="<?php echo XiRoute::_('index.php?option=com_payplans&view=wallet', false); ?>" method="post" name="adminForm" id="adminForm">
	
	<?php echo $this->loadTemplate('filter'); ?>
	
	<table class="table table-striped" >
		<thead>
		<!-- TABLE HEADER START -->
			<tr>
        		<th class="default-grid-chkbox"">
					<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
				</th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_WALLET_GRID_USERNAME", 'user_id', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_WALLET_GRID_TRANSACTION_ID", 'transaction_id', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_WALLET_GRID_AMOUNT", 'amount', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_WALLET_GRID_MESSAGE", 'message', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_WALLET_GRID_INVOICE_ID", 'invoice_id', $filter_order_Dir, $filter_order);?></th>				
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_WALLET_GRID_CREATED_DATE", 'created_date', $filter_order_Dir, $filter_order);?></th>
			</tr>
		<!-- TABLE HEADER END-->
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
    				<td><?php echo $users[$record->user_id]->realname;?>
				    	<?php echo '('.$users[$record->user_id]->username.' : #'.$record->user_id.')';?>
				    </td>
				    <td><?php echo $record->transaction_id;?></td>
				    <td><?php echo PayplansHelperFormat::price($record->amount);?></td>
				    <td><?php echo XiText::_($record->message);?></td>
				    <td><?php echo $record->invoice_id;?></td>
				    <td><?php echo XiDate::timeago($record->created_date); ?></td>
				</tr>				
			<?php $count++;?>
			<?php endforeach;?>	
		<!-- TABLE BODY START -->	
		</tbody>
		<tfoot>
			<tr>
				<td colspan="<?php echo 9+2; ?>">
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
