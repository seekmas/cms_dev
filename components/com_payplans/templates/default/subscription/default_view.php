<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die(); ?>
<div class="row-fluid">
	<!-- Header -->
	<div class="row clearfix">
		<h2 class='page-header'>
			<?php echo $subscription->getTitle(); ?>
			<?php
				$class = '';
				switch($order->getStatus()){
					case PayplansStatus::ORDER_CONFIRMED :
						$class = 'label-info';
						break;
					case PayplansStatus::ORDER_PAID :
						break;
					case PayplansStatus::ORDER_COMPLETE :
						$class = 'label-success';
						break;
					case PayplansStatus::ORDER_HOLD :
						$class = 'label-warning';
						break;
					case PayplansStatus::ORDER_EXPIRED :
						$class = 'label-important';
						break;
					case PayplansStatus::ORDER_CANCEL:
						$class = 'label-inverse';
						break;
					
					default:
						break;
				} 
			?>
			<span class="hidden-phone pull-right label <?php echo $class; ?>"><?php echo $order->getStatusName();?></span>
		</h2>
	</div>
	
	<?php  if($order->isRecurring() && ($order->getStatus() == PayplansStatus::ORDER_COMPLETE) && ($subscription->getStatus() == PayplansStatus::SUBSCRIPTION_ACTIVE)):
		 	// if next payment is expected then display the relevant date and amount
			$paidCounter   = $order->getRecurringInvoiceCount();
			$counter 		= $order->getRecurrenceCount(); 
			 if( ($counter > $paidCounter) || ($counter == 0)):
			 	$invoice	 = $order->getLastMasterInvoice(PAYPLANS_INSTANCE_REQUIRE);
			 	$amount		 = $invoice->getTotal($paidCounter + 1);
			 	$currency    = $order->getCurrency();
			 	$amount_html = $this->loadTemplate('partial_amount', compact('currency', 'amount')) ?>
				<div class="row alert">
				    <button type="button" class="close" data-dismiss="alert">&times;</button>
				    <?php echo XiText::sprintf('COM_PAYPLANS_SUBSCRIPTION_NEXT_EXPECTED_PAYMENT', $amount_html, PayplansHelperFormat::date($subscription->getExpirationDate()))?>
			    </div>			    
		 		<?php 
		 	endif;
	endif;
	?>
	<div class="row">
		<div class="span4 well">
			<?php echo $this->loadTemplate('partial_subscription', compact('plugin_result','subscription')); ?>
			<?php echo $this->loadTemplate('subscription_action'); ?>
		</div>
		
		<div class="span8">
			<div class="row-fluid clearfix">
				<h4 class="page-header">
					<?php echo XiText::_('COM_PAYPLANS_FRONTEND_MY_ORDER_INVOICES');?>
				</h4>
					<?php
						$invoices = $all_invoices; 
						$message = XiText::_('COM_PAYPLANS_FRONT_ORDER_NO_PAID_INVOICES');
						echo $this->loadTemplate('partial_invoices', compact('invoices', 'user', 'message')); 
					?>
			</div>
			
			<div class="row-fluid clearfix">
				<h4 class="page-header">
					<?php echo XiText::_('COM_PAYPLANS_FRONTEND_MY_ORDER_TRANSACTIONS');?>
				</h4>
					<?php
						$message = XiText::_('COM_PAYPLANS_FRONT_ORDER_NO_PAID_TRANSACTION');
						echo $this->loadTemplate('partial_transaction', compact('transactions', 'user', 'message')); 
					?>
			</div>
		</div>
	</div>
</div>
<?php 