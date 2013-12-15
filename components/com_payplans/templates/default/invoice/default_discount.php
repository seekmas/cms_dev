<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

?>
<div>
	<span class="span6">
		<?php echo XiText::_("COM_PAYPLANS_ENTER_DISCOUNT_CODE"); ?>
	</span>
	<span class="span6">			
		<div class="input-append">
		    	<input class="span9" id="app_discount_code_id" type="text" name="app_discount_code" size="9" value=""/>
		    	<button type="button" id="app_discount_code_submit" class="btn" data-loading-text="wait..." title = "<?php  echo XiText::_("COM_PAYPLANS_PRODISCOUNT_APPLY_TOOLTIP"); ?>"  onClick="payplans.discount.apply(<?php echo $invoice->getId();?>);"><?php  echo XiText::_("COM_PAYPLANS_APP_DISCOUNT_APPLY"); ?></button>
		</div>
		<div id="app-discount-apply-error" class="text-error">&nbsp;</div>
	</span>
</div>
<?php 