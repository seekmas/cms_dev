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
?>
<fieldset class="form-horizontal">
	<legend> <?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_WALLET' ); ?> 
		<?php if(isset($wallet_balance)):?>
		<div class="pull-right">
			<?php echo XiText::_('COM_PAYPLANS_WALLET_USER_BALANCE' ); 
						echo PayplansHelperFormat::currency(XiFactory::getCurrency(XiFactory::getConfig()->currency)).' '.$wallet_balance;?>
		</div>
		<?php endif;?>
	</legend>
	<?php if(empty($wallet_records)):?>
		<div>
			<p class="center"><big><?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_WALLET_NO_WALLET_ENTRY');?></big></p>
			<p class="center muted"><?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_WALLET_NO_WALET_ENTRY_DESC');?></p>
		</div>
	<?php else :?>
	
		<table class="table table-striped">
			<thead>
			<!-- TABLE HEADER START -->
				<tr>
					<th><?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_WALLET_GRID_WALLET_ID');?></th>			
					<th><?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_WALLET_GRID_WALLET_INVOICE_ID');?></th>		
					<th><?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_WALLET_GRID_WALLET_TRANSACTION_ID');?></th>			
					<th><?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_WALLET_GRID_AMOUNT');?></th>
					<th><?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_WALLET_GRID_MESSAGE');?></th>    
					<th><?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_WALLET_GRID_CREATED_DATE');?></th>
				</tr>
			<!-- TABLE HEADER END -->
			</thead>
			
			<tbody>
			<!-- TABLE BODY START -->
				<?php $count = 0;?> 
				<?php ksort($wallet_records);?>
				<?php foreach($wallet_records as $record) : ?>
					<tr class="<?php echo "row".$count%2; ?>">
						<td><?php echo $record->wallet_id;?></td>
					  <td>	<?php if($record->invoice_id):?>
									<?php echo PayplansHtml::link(XiRoute::_('index.php?option=com_payplans&view=invoice&task=edit&id='.$record->invoice_id), $record->invoice_id.'('.XiHelperUtils::getKeyFromId($record->invoice_id).')');?>
							<?php endif;?>
                      </td>

						<td> <?php if($record->transaction_id ):?>
									<?php  echo PayplansHtml::link(XiRoute::_('index.php?option=com_payplans&view=transaction&task=edit&id='.$record->transaction_id),$record->transaction_id) ; ?>
							<?php endif;?>
           			   </td>
						
						<td><?php echo PayplansHelperFormat::price($record->amount);?></td>	
						<td><?php echo XiText::_($record->message);?></td>
						<td><?php echo XiDate::timeago($record->created_date);?></td>			
					</tr>		
				<?php $count++;?> 
				<?php endforeach;?>		
			<!-- TABLE BODY END -->
			</tbody>
			
			<tfoot>
				<tr>
					<td colspan="7">
		
					</td>
				</tr>
			</tfoot>
		</table>
<?php endif;?>
</fieldset>
<?php 