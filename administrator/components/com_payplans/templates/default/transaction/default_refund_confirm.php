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

<div>
	<div>
		<?php echo XiText::_('COM_PAYPLANS_TRANSACTION_REFUND_AMOUNT'); ?>
		<input type=hidden value= <?php echo $transactionAmt;?> id='refund_amount'>
		<?php echo $transactionAmt;?>
	</div>
	<div>&nbsp;</div>
	<div>
		<?php echo XiText::_('COM_PAYPLANS_TRANSACTION_REFUND_CONFIRM_WINDOW_MSG');	?>
	</div>
</div>

<?php 