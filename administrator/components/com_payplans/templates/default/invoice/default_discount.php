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
<fieldset class="form-horizontal">
	<legend> <?php echo XiText::_('COM_PAYPLANS_SM_PRODISCOUNT') ?> </legend>
	<div class="control-group">		
			<span>
				<input id="app_discount_code_id" name="app_discount_code" class="input-large" placeholder="<?php echo XiText::_('COM_PAYPLANS_PRODISCOUNT_ENTER_DISCOUNT_CODE_OR_AMOUNT') ?>" value="" />
				&nbsp;&nbsp;&nbsp;<a  id="app_discount_code_submit" onClick="xi.order.discount.apply(<?php echo $invoice->getId();?>);"><?php echo XiText::_("COM_PAYPLANS_PRODISCOUNT_APPLY");?></a>
				<span id="pp-discount-spinner" style="height:12px;">&nbsp;&nbsp;</span>
			</span>
			</br></br>
			<span id="app-discount-apply-error" class="error">&nbsp;</span>
	</div>	
</fieldset>
<?php 