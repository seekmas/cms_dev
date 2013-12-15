<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();?>
<div class="pp-transaction-edit">
<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm">
<div class="row-fluid">
	<div class="span6">
		<fieldset class="form-horizontal">
			<legend> <?php echo XiText::_('COM_PAYPLANS_TRANSACTION_EDIT_DETAILS' ); ?> </legend>
			<div class="control-group">
				<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_TRANSACTION_EDIT_TOOLTIP_TRANSACTION_ID') ?>" >
				 		<?php echo XiText::_('COM_PAYPLANS_TRANSACTION_EDIT_TRANSACTION_ID') ?> 
				</div>
				<div class="controls"><?php echo $transaction->getId(); ?></div>
			</div>
			
			
			<div class="control-group">
				<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_TRANSACTION_EDIT_TOOLTIP_INVOICE_ID') ?>" >
				 		<?php echo XiText::_('COM_PAYPLANS_TRANSACTION_EDIT_INVOICE_ID') ?> 
				 </div>
				 <div class="controls">
				 <?php $invoice_id = $transaction->getInvoice();
				 	   echo PayplansHtml::link(XiRoute::_("index.php?option=com_payplans&view=invoice&task=edit&id=".$invoice_id, false),$invoice_id.'('.XiHelperUtils::getKeyFromId($invoice_id).')'); ?>
				 </div>
			</div>
			
			<div class="control-group">
				<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_TRANSACTION_EDIT_TOOLTIP_PAYMENT_ID') ?>" >
				 		<?php echo XiText::_('COM_PAYPLANS_TRANSACTION_EDIT_PAYMENT_ID') ?> 
				 </div>
				 <div class="controls">
				 <?php $payment_id = $transaction->getPayment();
				 	   echo $payment_id.'('.XiHelperUtils::getKeyFromId($payment_id).')'; ?>
                 </div>
             </div>
			
			<div class="control-group">
				<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_TRANSACTION_EDIT_TOOLTIP_AMOUNT') ?>" >
				 		<?php echo XiText::_('COM_PAYPLANS_TRANSACTION_EDIT_AMOUNT') ?> 
				</div>
				<div class="controls"><?php $amount   = $transaction->getAmount();
					           $currency = $transaction->getCurrency();
					           echo $this->loadTemplate('partial_amount', compact('currency', 'amount')); ?>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_TRANSACTION_EDIT_TOOLTIP_GATEWAY_TYPE') ?>" >
				 		<?php echo XiText::_('COM_PAYPLANS_TRANSACTION_EDIT_GATEWAY_TYPE') ?> 
				</div>
				 <?php $pament_id = $transaction->getPayment();
				 	   $payment = PayplansPayment::getInstance($pament_id);?>
				<div class="controls"><?php echo (!empty($pament_id) && ($payment instanceof PayplansPayment))? $payment->getAppName() : XiText::_('COM_PAYPLANS_TRANSACTION_PAYMENT_GATEWAY_NONE'); ?></div>
			</div>

			<div class="control-group">
				<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_TRANSACTION_EDIT_TOOLTIP_GATEWAY_TRANSACTION_ID') ?>" >
				 		<?php echo XiText::_('COM_PAYPLANS_TRANSACTION_EDIT_GATEWAY_TRANSACTION_ID') ?> 
				</div>
				<div class="controls"><?php echo $transaction->getGatewayTxnId(); ?></div>
			</div>
			
			<div class="control-group">
				<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_TRANSACTION_EDIT_TOOLTIP_GATEWAY_PARENT_TRANSACTION') ?>" >
				 		<?php echo XiText::_('COM_PAYPLANS_TRANSACTION_EDIT_GATEWAY_PARENT_TRANSACTION') ?> 
				 </div>
				<div class="controls"><?php echo $transaction->getGatewayParentTxn(); ?></div>
			</div>
			
			<div class="control-group">
				<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_TRANSACTION_EDIT_TOOLTIP_GATEWAY_SUBSCRIPTION_ID') ?>" >
				 		<?php echo XiText::_('COM_PAYPLANS_TRANSACTION_EDIT_GATEWAY_SUBSCRIPTION_ID') ?> 
				 </div>
				<div class="controls"><?php echo $transaction->getGatewaySubscriptionId(); ?></div>
			</div>
			
			<div class="control-group">
				<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_TRANSACTION_EDIT_TOOLTIP_CREATED_DATE') ?>" >
				 		<?php echo XiText::_('COM_PAYPLANS_TRANSACTION_EDIT_CREATED_DATE') ?> 
				 </div>
				<div class="controls"><?php echo XiDate::timeago($transaction->getCreatedDate()->toMySql()); ?>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_TRANSACTION_EDIT_TOOLTIP_MESSAGE') ?>" >
				 		<?php echo XiText::_('COM_PAYPLANS_TRANSACTION_EDIT_MESSAGE') ?> 
				 </div>
				<div class="controls"><?php echo XiText::_($transaction->getMessage()); ?></div>
			</div>
		
			<div>
				<?php echo $this->loadTemplate('partial_user', compact('user'));?>
			</div>
		</fieldset>		
	</div>

	<div class="span6">
			<fieldset class="form-horizontal">	
			<legend > <?php echo XiText::_('COM_PAYPLANS_TRANSACTION_EDIT_PARAMS' ); ?> </legend>
						<?php echo $transaction_html; ?>
			</fieldset>
	
			<?php if(isset($show_refund_option) && $show_refund_option) : ?>
			<fieldset class="form-horizontal">	
					<legend>
						<?php echo XiText::_('COM_PAYPLANS_TRANSACTION_DETAIL_REFUND' ); ?> 
					</legend>			
					<div class="pp-float-left ui-button ui-button-primary ui-widget ui-corner-all pp-button-text-only">
						<a href="" onclick="payplans.url.modal('<?php echo XiRoute::_('index.php?option=com_payplans&view=transaction&task=refund&transaction_id='.$transaction->getId());?>'); return false;"><?php echo XiText::_('COM_PAYPLANS_TRANSACTION_DETAIL_REFUND');?></a>
					</div>
			</fieldset>
			<?php endif;?>
	</div>

	<input type="hidden" name="task" value="save" />
</div>
</form>
</div>
<?php

