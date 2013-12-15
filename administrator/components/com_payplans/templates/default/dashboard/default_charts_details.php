<?php
/**
* @copyright		Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license			http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package			PayPlans
* @subpackage		Backend
* @contact 			payplans@readybytes.in
* website			http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();
?>

<!-- Recent Sales Chart-->
<div class="row-fluid">

<ul class="nav nav-tabs">
  <li class='active'><a href="#charts-details-sales" data-toggle="tab"><?php echo XiText::_('COM_PAYPLANS_STATISTICS_DETAILS_CHART_RECENT_SALES'); ?></a></li>
  <li><a href="#charts-details-errors" data-toggle="tab"><?php echo XiText::_('COM_PAYPLANS_ERROR_LOG'); ?></a></li>
  <li><a href="#charts-details-txns" data-toggle="tab"><?php  echo XiText::_('COM_PAYPLANS_STATISTICS_DETAILS_CHART_RECENT_TRANSACTIONS');?></a></li>
  <li><a href="#charts-details-gateways" data-toggle="tab"><?php echo XiText::_('COM_PAYPLANS_STATISTICS_DETAILS_CHART_PAYMENT_GATEWAY_INFO');?></a></li>
</ul>

<div class="tab-content">
	<!-- sales -->
	<div class="tab-pane active" id="charts-details-sales">
		<?php if(!$recentSales): ?>
 			<p class="lead well well-large">
 					<?php echo XiText::_('COM_PAYPLANS_STATISTICS_DETAILS_CHART_RECENT_SALES_EMPTY'); ?>
 			</p>
	 			
		<?php else: 
	 		$count = 1;?>
	 		<table class='table table-hover table-bordered'>
	 		<thead>
	 			<tr>
	 			<th>#</th>
	 			<th><?php echo XiText::_('Plan');?></th>
	 			<th><?php echo XiText::_('Buyer');?></th>
	 			<th><?php echo XiText::_('Total');?></th>
	 			<th><?php echo XiText::_('Date');?></th>
	 			<tr>
	 		</thead>
	 		<tbody>
	 		<?php foreach($recentSales as $sale):?>
	 			<tr>
	 				<td><?php echo $count++;?>.</td> 
	 				<td><?php echo PayplansHtml::link(XiRoute::_("index.php?option=com_payplans&view=subscription&task=edit&id=".$sale['subscription_id'], false), ucfirst($sale['title']), 'target=_blank');?></td>
					<td><?php echo ucfirst($sale['buyer']); ?></td>
					<td><?php echo number_format($sale['amount'],2); ?></td>
					<td><?php echo XiDate::timeago($sale['subscription_date']); ?></td>
				 </tr>
	 		<?php endforeach; ?>
	 		</tbody>
	 		</table>
	 	<?php endif; ?> 
  	</div>
  

	<div class="tab-pane" id="charts-details-errors">
		<?php if(empty($error_logs)): ?>
			<p id="pp-empty-error" class="lead well well-large">
				<?php echo XiText::_('COM_PAYPLANS_STATISTICS_LOGS_CHART_ERROR_LOGS_EMPTY'); ?>
			</p>
		<?php else: ?>
				<?php $count = 1;?> 
		 		<table class='table table-hover table-bordered'>
			 		<thead>
			 			<tr>
			 			<th>#</th>
			 			<th><?php echo XiText::_('Message');?></th>
			 			<th><?php echo XiText::_('Time');?></th>
			 			<th>&nbsp;</th>
			 			<tr>
			 		</thead>
			 		<tbody>
				<?php foreach ($error_logs as $record): ?>
					<tr class="pp-log-<?php echo $record->log_id;?>" title ="<?php echo $record->message;?>" >
						<td><?php echo $count++;?>.</td>   
						<td  onclick="payplans.url.modal('<?php echo 'index.php?option=com_payplans&view=log&task=view&tmpl=component&record='.$record->log_id; ?>'); return false;"><?php echo $record->message; ?> </td>
						<td><?php echo XiDate::timeago($record->created_date); ?></td>
						<td>
							<span id="<?php echo $record->log_id;?>" class="pp-err-cancel hasTip pp-icon-remove" title="<?php echo XiText::_('COM_PAYPLANS_MARK_AS_READ');?>"></span>
						</td>
					</tr>
			    <?php endforeach;?>
	        	</table>
		<?php endif; ?>
    </div>
  
	<!--  Recent Transaction details-->
	<div class="tab-pane" id="charts-details-txns">
		 <?php if(!$recentTransactions):?>
			<p class="lead well well-large">
				<?php echo XiText::_('COM_PAYPLANS_STATISTICS_DETAILS_CHART_RECENT_TRANSACTIONS_EMPTY'); ?>
			</p>
		<?php else:?>
			<?php $count = 1;?>
			<table class='table table-hover table-bordered'>
			<thead>
	 			<tr>
	 			<th>#</th>
	 			<th><?php echo XiText::_('Transaction');?></th>
	 			<th><?php echo XiText::_('Buyer');?></th>
	 			<th><?php echo XiText::_('Date');?></th>
				<th><?php echo XiText::_('Total');?></th>
	 			<tr>
	 		</thead>
	 		<tbody>
			<?php foreach($recentTransactions as $transaction):?>
				<tr title="<?php echo XiText::_($transaction['message']);?>" >
					<td><?php echo $count++;?>.</td>
					<td><?php echo PayplansHtml::link(XiRoute::_("index.php?option=com_payplans&view=transaction&task=edit&id=".$transaction['id'], false), PayplansHelperUtils::getKeyFromId($transaction['id'])); ?></td>
					<td><?php echo ucfirst($transaction['buyer']); ?></td>
					<td><?php echo XiDate::timeago($transaction['date']); ?></td>
					<td><?php echo number_format($transaction['amount'],2); ?></td>
				</tr>
			<?php endforeach;?>
			</table> 
		<?php endif;?>
	</div>
  
	<!-- Payment Gateway Details-->  
  	<div class="tab-pane" id="charts-details-gateways">
  	<?php if(!$gatewayInfo):?>
		<p class="lead well well-large">
 			<?php echo XiText::_('COM_PAYPLANS_STATISTICS_DETAILS_CHART_RECENT_SALES_EMPTY'); ?>
		</p>
	<?php else:?> 
		<?php $count = 1;?>
		<table class='table table-hover table-bordered'>
		<thead>
 			<tr>
 			<th>#</th>
 			<th><?php echo XiText::_('Gateway');?></th>
 			<th><?php echo XiText::_('Count');?></th>
			<th><?php echo XiText::_('Last Time Used');?></th>
 			<tr>
 		</thead>
 		<tbody>
		<?php foreach($gatewayInfo as $gateway):?>
			<tr>
			 	<td><?php echo $count++;?>.</td>
			 	<td><?php echo $gateway['title']; ?></td>
			 	<td><?php echo $gateway['used']; ?></td>
			 	<td><?php echo XiDate::timeago($gateway['lastused']); ?></td>
			 </tr>
		<?php endforeach;?>
		</table>
	 <?php endif;?>
  	</div>
</div>
	
</div>
<?php 
