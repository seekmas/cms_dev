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

<form action="<?php echo XiRoute::_('index.php?option=com_payplans&view=invoice', false); ?>" method="post" name="adminForm" id="adminForm">
	<?php echo $this->loadTemplate('filter'); ?>
	<table class="table table-striped">

		<thead>
		<!-- TABLE HEADER START -->
			<tr>
        		<th class="default-grid-chkbox hidden-phone">
					<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
				</th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_INVOICE_GRID_INVOICE_ID", 'invoice_id', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_INVOICE_GRID_BUYER", 'user_id', $filter_order_Dir, $filter_order);?></th>
				<th class="hidden-phone"><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_INVOICE_GRID_OBJECT_TYPE", 'object_type', $filter_order_Dir, $filter_order);?></th>
				<th class="hidden-phone"><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_INVOICE_GRID_SUBTOTAL", 'subtotal', $filter_order_Dir, $filter_order);?></th>
				<th class="hidden-phone"><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_INVOICE_GRID_TOTAL", 'total', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo PayplansHtml::_('grid.sort', "COM_PAYPLANS_INVOICE_GRID_STATUS", 'status', $filter_order_Dir, $filter_order);?></th>
				<th class="hidden-phone"><?php echo XiText::_("COM_PAYPLANS_INVOICE_GRID_ACTIONS");?></th>
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
				    <td class="hidden-phone"><?php echo $record->object_type;?></td>
				    <td class="hidden-phone"><?php echo PayplansHelperFormat::price($record->subtotal);?></td>
					<td class="hidden-phone"><?php echo PayplansHelperFormat::price($record->total);?></td>
					<td><?php $status = PayplansStatus::getStatusOf('INVOICE'); 
										echo XiText::_('COM_PAYPLANS_STATUS_'.$status[$record->status]);?></td>
					<td class="hidden-phone">
                        <div>
                            <span id="testAddPayment<?php echo $record->{$record_key}; ?>">
                                <a href="<?php echo XiRoute::_('index.php?option=com_payplans&view=invoice&task=addTransaction&invoice_id='.$record->{$record_key});?>" onclick="this.onclick=function(){return false;}"><?php echo XiText::_('COM_PAYPLANS_INVOCE_GRID_ADD_TRANSACTION');?></a>
                            </span>
                        </div>
					</td>
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
