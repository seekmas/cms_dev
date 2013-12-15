<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

$invoice_key = $invoice->getKey();
$created_date = PayplansHelperFormat::date($invoice->getCreatedDate());
//get paid-on date from wallet entry
$walletRecord      = PayplansHelperInvoice::getWallet($invoice->getId());
$modification_date = null;
if(!empty($walletRecord)){
	$paid_date 		   = array_pop($walletRecord)->created_date;
	$new_date          = new XiDate($paid_date);
	$modification_date = PayplansHelperFormat::date($new_date);
}

$payment = $invoice->getPayment();
$currency = $invoice->getCurrency();

// status of invoice
$class = '';
switch($invoice->getStatus()){
	case PayplansStatus::INVOICE_CONFIRMED :
		$class = 'label-important';
		break;
	case PayplansStatus::INVOICE_PAID :
	case PayplansStatus::INVOICE_WALLET_RECHARGE :
		$class = 'label-success';
		break;
		
	case PayplansStatus::INVOICE_REFUNDED :
		$class = 'label-inverse';
		break;
		
	default:
		$pending_text = XiText::_('COM_PAYPLANS_STATUS_INVOICE_PENDING');
		$class = 'label-warning';
		break;
}

?>

<div id='invoice' class="thumbnail pp-gap-top10">
<div class='row-fluid'>	
	<!--  Section 1 starts -->
	<div class='clearfix'>
		<div class="span6">
			<img style="max-width:300px; max-height:100px;" src="<?php echo JURI::base().XiFactory::getConfig()->companyLogo; ?>" />&nbsp;
		</div>
		<div class="span6 pull-right pp-right">
				<h3><?php echo XiFactory::getConfig()->companyName; ?></h3>
				<p class='small'>
					<?php echo XiFactory::getConfig()->companyAddress;?><br />
					<?php echo XiFactory::getConfig()->companyCityCountry; ?><br />
					 <?php if(!empty(XiFactory::getConfig()->companyPhone)):?>
					<?php echo XiText::_('COM_PAYPLANS_INVOICE_PHONE_NUMBER'); 
					      echo XiFactory::getConfig()->companyPhone; ?>
 					<?php endif;?> 
				</p>
		</div>
	</div>
	<hr class='pp-gap-top10'>
	<!--  Section 1 ends -->
	

	<!--  Section 2 start -->
	<div class="pp-gap-top10 clearfix">
		<div class="span4">
			<h4 class="center">
				<?php echo XiText::_('COM_PAYPLANS_INVOICE_CREATED_DATE'); ?>
				<?php echo ' ', $created_date; ?>
			</h4>
			<div class='label' style="width:96%">	
				<h4 class='center'>
					<?php $amount   = $invoice->getTotal(); 
					echo $this->loadTemplate('partial_amount', compact('currency', 'amount'));?>
				</h4>
			</div>
		</div>
		
		<div class="span4">&nbsp;</div>
		
		<div class="span4">
			<h4 class='center'> 
			<?php if($invoice->getStatus() == PayplansStatus::INVOICE_PAID):?>
				<?php echo XiText::_('COM_PAYPLANS_INVOICE_PAID_ON'), ' ' ,$modification_date; ?>
			<?php else:?>
				&nbsp;
			<?php endif;?>
			 </h4>
			
			<div class='label <?php echo $class;?>' style="width:96%">
				<h4 class='center'><?php echo ($invoice->getStatus()==PayplansStatus::NONE) ? $pending_text : $invoice->getStatusName();?></h4>
			</div>
		</div>
		
	</div>	
	<!--  Section 2 end -->
	
	<hr />
	<!---------------THREE BLOCKS FOR INVOICE DETAILS------------------------>
	<div class='clearfix'>
		<div class="span8">
			<h4><?php echo XiText::_('COM_PAYPLANS_INVOICE_BILL_TO'); ?></h4>
				<!----BILL TO DETAILS-------------->
			<p>
				<?php echo $user->getRealname(); ?><br />
				<?php echo $user->getEmail(); ?><br />
				<!-- ADDITIONAL DETAILS PARTIAL -->
				<?php echo $this->loadTemplate('partial_extra_details', compact('invoice')); ?>
			</p>
					
			

		</div>
		
		<div class="span4">
			<h4><?php echo XiText::_('COM_PAYPLANS_FRONT_INVOICE_DETAILS'); ?></h4>
		
			
			<span>
				<?php echo XiText::_('COM_PAYPLANS_INVOICE_PURCHASED_PLAN');?>:&nbsp;
				<strong><?php echo $invoice->getTitle(); ?></strong>
			</span><br />
			
<!-- Not required 
			<span>
				<?php echo XiText::_('COM_PAYPLANS_INVOICE_PRICE');?>:&nbsp;
				<strong><?php $amount   = $invoice->getSubtotal();
					echo $this->loadTemplate('partial_amount', compact('currency', 'amount'));?>
				</strong>
			</span><br />
 -->			
			<span>
				<?php echo XiText::_('COM_PAYPLANS_INVOICE_KEY'); ?>:&nbsp;
				<strong><?php echo $invoice_key; ?></strong>
			</span><br />
			
			<?php  
			 if(($invoice->getStatus() == PayplansStatus::INVOICE_PAID)):?>
				<span>
					<?php echo XiText::_('COM_PAYPLANS_INVOICE_PAYMENT_METHOD'); ?>:&nbsp;
					<strong>
						<?php 
						if(isset($payment) && ($payment instanceof PayplansPayment) && $payment->getId()){	
						 	echo $payment->getAppName();
						}else{
						 	echo XiText::_('COM_PAYPLANS_TRANSACTION_PAYMENT_GATEWAY_NONE');
						}
						?>
					</strong>
			</span>
			<?php endif;?>
			
		</div>
		
		
	
	</div>
	
	
	<!-- 
			--------------------------------------------------------------------------------------------------------
						DISPLAY MODIFIRES			
			--------------------------------------------------------------------------------------------------------
	 -->

	 		<table class='table pp-gap-top30' <?php echo (XiFactory::getConfig()->rtl_support) ? 'dir=rtl': '';?>>
			 			<thead>
					 			<tr>
				 					<th class='span10'><?php echo XiText::_('COM_PAYPLANS_INVOICE_DESCRIPTION'); ?></th>
				 					<th class='span2'><?php echo XiText::_('COM_PAYPLANS_INVOICE_AMOUNT'); ?></th>
					 			</tr>
			 			</thead>
			 			
			 			<tbody>							
							<tr>
									<td><?php echo XiText::_('COM_PAYPLANS_INVOICE_PRICE'); ?></td>
									<td>
											<?php  $currency = $invoice->getCurrency();
														$amount   = $invoice->getSubtotal();
														echo $this->loadTemplate('partial_amount', compact('currency', 'amount'));?>
									</td>
							</tr>
							
					 		<?php 
									  $discountables = $invoice->getModifiers(array('serial'=>array(PayplansModifier::FIXED_DISCOUNTABLE,PayplansModifier::PERCENT_DISCOUNTABLE), 'invoice_id'=>$invoice->getId()));
									  $discountables = PayplansHelperModifier::_rearrange($discountables);
									  foreach ($discountables as $discountable): ?>
				 						<tr>
										 	  <td>
							                   		<?php echo XiText::_($discountable->get('message'));?>
							                   </td>
							                   <td>
										                   <?php $amount = $discountable->_modificationOf;
													 	  		echo $this->loadTemplate('partial_amount', compact('currency', 'amount'));?>
												</td>
											</tr>
										 	  <?php endforeach;?>

						 			<tr>
						 					<td><?php echo XiText::_('COM_PAYPLANS_INVOICE_DISCOUNT'); ?></td>
						 					<td><?php $amount = (-1)* $invoice->getDiscount();
													echo $this->loadTemplate('partial_amount', compact('currency', 'amount'));?>
											</td>
						 			</tr>
						 			
						 			<tr>
						 					<td><?php echo XiText::_('COM_PAYPLANS_INVOICE_TAX'); ?></td>
						 					<td>
						 							<?php  $amount = $invoice->getTaxAmount();
							        							echo $this->loadTemplate('partial_amount', compact('currency', 'amount'));?>
											</td>
						 			</tr>
						 			
						 			<?php $nonTaxables = $invoice->getModifiers(array('serial'=>array(PayplansModifier::FIXED_NON_TAXABLE,PayplansModifier::PERCENT_NON_TAXABLE), 'invoice_id'=>$invoice->getId()));
									 	  $nonTaxables = PayplansHelperModifier::_rearrange($nonTaxables);
									 	  foreach ($nonTaxables as $nonTaxable):
									 	  ?> 
										 	  <tr>
												 	<td><?php echo $nonTaxable->get('message')?></td> 
						 							<td>												 	  
												 			<?php $amount = $nonTaxable->_modificationOf;
													 	  				echo $this->loadTemplate('partial_amount', compact('currency', 'amount'));?>
								 	  				</td>
											 	</tr>
								 	  <?php endforeach;?>
								 	  
								 	
								 	<tr>
							 			<td class='pp-right'>
							 				<strong><?php echo XiText::_('COM_PAYPLANS_INVOICE_TOTAL'); ?></strong>
							 			</td>
							 			<td>
											<?php $amount   = $invoice->getTotal(); 
									  		echo $this->loadTemplate('partial_amount', compact('currency', 'amount'));
									  	?>
										</td>
							 		</tr>		 	  
			 			</tbody>
			 </table>
	 
	
		<!--  Notes Section -->
		<?php if(1 || !empty(XiFactory::getConfig()->note)):?>
		<div class="alert alert-block">
			<p><?php echo XiFactory::getConfig()->note;?></p>
		</div>	
		<?php endif;?>
</div>
</div>

<div class='clearfix'>
	<?php echo $this->loadTemplate('invoice_action');?> 
</div>
<?php 
