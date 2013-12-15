<?php
/**
 * @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package		PayPlans
 * @subpackage	Frontend
 * @contact 		shyam@readybytes.in
 */
if(defined('_JEXEC')===false) die();
?>

<div class="row-fuild">
	<table class="table table-borderless">
		<tr>
			<td><?php echo XiText::_('COM_PAYPLANS_ORDER_CONFIRM_REGULAR_TOTAL');?></td>
			<td class="text-right pp-payment-header-price"><?php echo $subtotal;?></td>
		</tr>
		<tr class="discount-amount">
			<td><?php echo XiText::_('COM_PAYPLANS_ORDER_CONFIRM_DISCOUNT');?>&nbsp;(-)</td>
			<td class="text-right pp-payment-header-price2 pp-amount"><?php echo $discount;?></td>
		</tr>
		<tr class="tax-amount">
			<td><?php echo XiText::_('COM_PAYPLANS_ORDER_CONFIRM_TAX');?>&nbsp;(+)</td>
			<td class="text-right pp-payment-header-price2 pp-amount"><?php echo $tax_amount;?></td>
		</tr>
		<tr class="table-row-border">
			<td><?php echo XiText::_('COM_PAYPLANS_ORDER_CONFIRM_AMOUNT_PAYABLE');?></td>
			<td class="text-right pp-payment-header-price payable first-amount">
				<?php $amount = $total;?>
				<?php echo $this->loadTemplate('partial_amount', compact('currency', 'amount'));?>
			</td>
		</tr>

	</table>
	
	<div class="pp-gap-top10 pp-gap-bottom05 row-fluid">
		<?php if(XiFactory::getConfig()->enableDiscount): ?>
				<?php echo $this->loadTemplate('discount'); ?>
	    <?php endif;?>
		
		<?php $position = 'payplans_order_confirm_payment'; ?>
		<?php echo $this->loadTemplate('partial_position', compact('plugin_result','position'));?>
	</div>
	
	<div class="pp-gap-top10 pp-gap-bottom05 row-fluid">
			<div class="span6">
					<?php echo XiText::_('COM_PAYPLANS_ORDER_MAKE_PAYMENT_FROM');?>
			</div>
			<div class="span6">
				<span class="pp-payment-method">
					<?php echo (!($invoice->isRecurring()) && (floatval(0) == floatval($total))) ? XiText::_('COM_PAYPLANS_ORDER_NO_PAYMENT_METHOD_NEEDED') : PayplansHtml::_('select.genericlist', $payment_apps, 'app_id', 'class="span12"' , 'id', 'title');?>
				</span>	
			</div>
	</div>	
	
	
</div><?php 
