<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();
?>

<div id="adminPay">
	<?php echo $transaction_html;?>
	<input type="hidden" name="payment_id" value="<?php echo $payment_id;?>" />
</div>
<?php
