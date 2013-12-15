<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
* @ref 			https://www.x.com/docs/DOC-1332#id08A6HI00JQU
*/
if(defined('_JEXEC')===false) die();?>

<script type="text/javascript">
payplans.jQuery(document).ready(function(){
         setTimeout(function paypalSubmit(){
                       document.forms["site_app_<?php echo $this->getName(); ?>_form"].submit();
               }, 1000);
});
</script>


<form action="<?php echo $post_url ?>"
	  method="post" name="site_app_<?php echo $this->getName(); ?>_form" >


	<!--ORDER INFO-->
	<input TYPE="hidden" name="charset"     value="utf-8">
    <input type='hidden' name='app_id'	    value='<?php echo $this->getId();?>' />
	<input type='hidden' name='order_id' 	value='<?php echo $order_id;?>' />
	<input type='hidden' name='invoice' 	value='<?php echo $invoice; ?>'>
	<input type='hidden' name='item_name' 	value='<?php echo $item_name;?>'>
	<input type='hidden' name='item_number' value='<?php echo $item_number; ?>'>

	<input type='hidden' name='cmd' 			value='<?php echo $cmd;?>'>
	<input type='hidden' name='business' 		value='<?php echo $merchant_email; ?>' />
	<input type='hidden' name='return' 			value='<?php echo $return_url; ?>'>
	<input type='hidden' name='cancel_return' 	value='<?php echo $cancel_url; ?>'>
	<input type="hidden" name="notify_url" 		value="<?php echo $notify_url; ?>" />
	<input type='hidden' name='currency_code' 	value='<?php echo $currency; ?>'>
	<input type='hidden' name='no_note' 		value='1'>

	<!-- TRIAL SUBSCRIPTION -->
	<?php 
	if(in_array($recurring, array(PAYPLANS_RECURRING_TRIAL_1, PAYPLANS_RECURRING_TRIAL_2))) : ?>
		<input type='hidden' name='a1' 			value='<?php echo $a1; ?>'>
		<input type='hidden' name='p1' 			value='<?php echo $p1;?>'>
		<input type='hidden' name='t1' 			value='<?php echo $t1;?>'>
		
		<?php if($recurring == PAYPLANS_RECURRING_TRIAL_2) :?>
			<input type='hidden' name='a2' 			value='<?php echo $a2; ?>'>
			<input type='hidden' name='p2' 			value='<?php echo $p2;?>'>
			<input type='hidden' name='t2' 			value='<?php echo $t2;?>'>
		<?php endif;?>
	<?php endif;
	?>
	<!-- Some variables for Recurring Payment   -->
	<input type='hidden' name='a3' 			value='<?php echo $a3; ?>'>
	<input type='hidden' name='p3' 			value='<?php echo $p3;?>'>
	<input type='hidden' name='t3' 			value='<?php echo $t3;?>'>
	<input type='hidden' name='src' 		value='1'>
	<input type='hidden' name='sra' 		value='1'>
	<input type='hidden' name='srt' 		value='<?php echo $srt;?>'>
	
	<!--	METHOD in which data to be post from paypal 0-get,1,2-post -->
	<input type='hidden' name='rm' 		value='2'>
 
	
	<div id="payment-paypal" class="pp-payment-pay-process">		
		<div id="payment-redirection">
			<h4>
				<?php echo XiText::_('COM_PAYPLANS_APP_PAYPAL_PAYMENT_REDIRECTION'); ?>
			</h4>
			<div class="loading"></div>
		</div>
	
		<div id="payment-submit">
			<button type="submit" class="btn btn-primary btn-large"
					name="payplans_payment_btn"><?php echo XiText::_('COM_PAYPLANS_PAYPAL_PAYMENT')?></button>
		</div>
	</div>
</form>
<?php 
