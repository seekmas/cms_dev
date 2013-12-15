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
<?php //XITODO: clean javascript?>
<script type="text/javascript">
function offlineChangeAction()
{
	document.getElementById('payplans_payment_action').value = 'cancel';
	return true;
}
</script>

<div id="offline-pay" class="form-horizontal">
<form action="<?php echo $posturl ?>" method="post" name="site<?php echo $this->getName(); ?>Form">
	<?php	foreach ($transaction_html->getFieldset('gateway_params') as $field):?>
                                                               <?php $class = $field->group.$field->fieldname; ?>
                                                               <div class="control-group <?php echo $class;?>">
                                                                       <div class="control-label"><?php echo $field->label; ?> </div>
                                                                       <div class="controls"><?php echo $field->input; ?></div>                                                                
                                                               </div>
                                                       <?php endforeach;?>
                                                       
	<input type="hidden" name="payment_key" value="<?php echo $payment_key;?>" />
	
	<div class="row-fluid control-group "> 
		    	<div class="span3 control-label"><?php echo XiText::_('COM_PAYPLANS_APP_OFFLINE_BANK_NAME_LABEL');?></div>
		
		    <div class="span9 controls">
		       	<?php echo $this->getAppParam('bankname', false);?>
		    </div>
    </div>
    
	    <div class="row-fluid control-group">
	    		<div class="span3 control-label"><?php echo XiText::_('COM_PAYPLANS_APP_OFFLINE_ACCOUNT_NUBMER_LABEL')?>
	    	</div>
		    <div class="controls span9">
	       		<?php echo $this->getAppParam('account_number', false);?>
	       	</div>
	       	</div>

    
	<div class="offset3 span9">
		<button type="submit" id="payplans-payment" class="btn btn-primary btn-large" name="payplans_payment_btn" onclick="this.onclick=function(){return false;}"><?php echo XiText::_('COM_PAYPLANS_PAYMENT')?></button>
		<button type="submit" id="payplans-payment-cancel" class="btn btn-primary pp-button-color btn-large " name="payplans_payment_cancel_btn" onClick="offlineChangeAction()"><?php echo XiText::_('COM_PAYPLANS_PAYMENT_CANCEL_BUTTON');?></button>
		<input type="hidden" id="payplans_payment_action" name="action" value="success" />
	</div>
	
</form>
</div>
<?php
