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
<div class="pp-user-selectplan">
	<div class="pp-user-selectplan-message center">
		<?php echo XiText::_('COM_PAYPLANS_ADMIN_WALLET_RECHARGE_DETAILS_MESSAGE');?>
	</div>
	<br/>
		<div class="control-group center">
			<div class="controls">
				<input type="text" name="recharge_amount" id="wallet_recharge_amount" value="" /><?php echo XiFactory::getConfig()->currency;?>
				<div class="hide recharge-amount-error"><?php echo XiText::_('COM_PAYPLANS_WALLET_RECHARGE_INVALID_AMOUNT');?></div>
			</div>
		</div>
</div>
<?php 