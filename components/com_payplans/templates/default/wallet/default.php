<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die(); ?>
<div class="pp-order-view">
	<h2 class="componentheading pp-primary pp-color pp-border pp-background"> 
		<?php echo XiText::_('COM_PAYPLANS_FRONT_WALLET_DETAILS');?> 
	</h2>

	<?php foreach($wallets as $wallet) :?>
		<div>
			<span><?php echo XiText::_('COM_PAYPLANS_FRONT_WALLET_DATE');?></span>
			<span><?php echo $wallet->created_date;?></span>
		</div>
		
		<?php if(isset($transaction[$wallet->transaction_id])) : ?>
			<?php $invoice_key = XiHelperUtils::getKeyFromId($transaction[$wallet->transaction_id]->invoice_id);?>
			<div>
				<span><?php echo XiText::_('COM_PAYPLANS_FRONT_WALLET_PURPOSE');?></span>
				<span><a href="<?php echo XiRoute::_('index.php?option=com_payplans&view=invoice&task=display&invoice_key='.$invoice_key);?>"><?php echo $invoice_key;?></a></span>
			</div>
		<?php endif;?>
		
		<?php if(isset($transaction[$wallet->transaction_id])) : ?>
			<div>
				<span><?php echo XiText::_('COM_PAYPLANS_FRONT_WALLET_PAYMANET_TRANSACTION_ID');?></span>
				<span><?php echo $transaction[$wallet->transaction_id]->gateway_txn_id;?></span>
			</div>
		<?php endif;?>
		
		<div>
			<span><?php echo XiText::_('COM_PAYPLANS_FRONT_WALLET_AMOUNT');?></span>
			<span><?php echo XiText::_($wallet->amount);?></span>
		</div>
		
		<div>
			<span><?php echo XiText::_('COM_PAYPLANS_FRONT_WALLET_MESSAGE');?></span>
			<span><?php echo XiText::_($wallet->message);?></span>
		</div>
		
	<?php endforeach;?>
</div>
<?php 