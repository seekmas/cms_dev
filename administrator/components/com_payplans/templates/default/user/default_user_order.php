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
<?php $count = 0;?> 
<?php if(is_array($order_records) && !empty($order_records)) :?>
<?php foreach($order_records as $record) :?>
	<?php $order->bind($record);?> 
	<?php $order_sub = $order->getSubscription();?>
	<?php if(!($order_sub instanceof PayplansSubscription)):?>
		<?php continue;?>
	<?php endif;?>
	<?php $sub_id  = $order_sub->getId();?>
	<fieldset class="form-horizontal">
		<legend onClick="xi.jQuery('.pp-order-details-<?php echo $sub_id?>').slideToggle();">
			<span class="show pp-order-details-<?php echo $sub_id?>">[+]</span>
			<?php $invoice_records = $order->getInvoices();?>			
			<?php echo "#".$sub_id." : ".$order_sub->getTitle()." (".$order_sub->getStatusName().")"; ?>
		</legend>
		
		<div class="hide pp-order-details-<?php echo $sub_id?>">
			<div>
				<?php echo XiText::_('COM_PAYPLANS_USER_EDIT_ORDER_SUBSCRIPTION_SUBSCRIPTION_DATE')." ";?> 
				<strong><?php echo XiDate::timeago($order_sub->getSubscriptionDate()->toMysql())." ";?></strong>
				<?php echo XiText::_('COM_PAYPLANS_AND')." ";?>
				<?php echo XiText::_('COM_PAYPLANS_USER_EDIT_ORDER_SUBSCRIPTION_SUBSCRIPTION_EXPIRATION_DATE')." ";?>
				<strong><?php echo XiDate::timeago($order_sub->getExpirationDate()->toMysql()).".";?></strong>
			</div>
			<div class="clr"></div>
			<div class="offset1">
				<fieldset class="form-horizontal">
				<legend><?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_INVOICE' ); ?></legend>
				</fieldset>
				<?php echo $this->loadTemplate('partial_invoice_table', compact('invoice_records'));?>
			</div>
		</div> 
	</fieldset>
<?php endforeach;?>
<?php endif;?>
<?php 
