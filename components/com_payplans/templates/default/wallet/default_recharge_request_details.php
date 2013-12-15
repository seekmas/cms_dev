<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();
?>
<div class="pp-grid_12 pp-recharge-popup">
	<div class="pp-recharge-msg">
		<?php echo XiText::_('COM_PAYPLANS_WALLET_RECHARGE_DETAILS_MESSAGE');?>
	</div>

	<div class="pp-parameter">
		<div class="pp-row">
			<div class="pp-col pp-label">
				<?php echo XiText::_('COM_PAYPLANS_WALLET_RECHARGE_AMOUNT');?>
			</div>
			<div class="pp-col pp-input">
				<input type="text" name="recharge_amount" id="wallet_recharge_amount" value="" /><?php echo XiFactory::getConfig()->currency;?>
				<div class="hide recharge-amount-error"><?php echo XiText::_('COM_PAYPLANS_WALLET_RECHARGE_INVALID_AMOUNT');?></div>
			</div>
		</div>
	</div>
	
	<div class="pp-parameter">
		<div class="pp-row">
			<div class="pp-col pp-label">
				<?php echo XiText::_('COM_PAYPLANS_WALLET_RECHARGE_PAYMENT_METHOD');?>
			</div>
			<div class="pp-col pp-input">
				<?php echo PayplansHtml::_('apps.edit', 'payment_method_id', '' , 'payment', '', array('adminpay', 'paybywallet')); ?>
			</div>
		</div>
	</div>
</div>
<?php 