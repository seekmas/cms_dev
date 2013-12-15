<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

$regular_counter = $invoice->getCounter();
if ($recurring === PAYPLANS_RECURRING_TRIAL_1) :
	$regular_counter = $invoice->getCounter() + 1;
elseif($recurring === PAYPLANS_RECURRING_TRIAL_2):
	$regular_counter = $invoice->getCounter() + 2;
endif;

$class = (!in_array($recurring, array(PAYPLANS_RECURRING_TRIAL_1, PAYPLANS_RECURRING_TRIAL_2)))? " hide " : " show ";?>
<div>
<span class="<?php echo $class; ?>">
	<!-- for recurring plans -->
	<?php if ($recurring) :?>
		<!-- plan have trials -->	
		<?php if(in_array($recurring, array(PAYPLANS_RECURRING_TRIAL_1, PAYPLANS_RECURRING_TRIAL_2))) :?>
				<span>
					<?php $amount = $invoice->getPrice();?>
					<?php echo $this->loadTemplate('partial_amount', compact('currency', 'amount'));?>	
				</span>
				<span>
					<?php echo XiText::sprintf('COM_PAYPLANS_ORDER_CONFIRM_FIRST_CHARGABLE_AMOUNT', PayplansHelperFormat::planTime($invoice->getExpiration(PAYPLANS_RECURRING_TRIAL_1)));?>
				</span>
			<!-- plan have 2 trials -->	
			<?php if($recurring === PAYPLANS_RECURRING_TRIAL_2) :?>
				<span>
					<?php $amount = $invoice->getPrice($invoice->getCounter() + 1);?>
					<?php echo $this->loadTemplate('partial_amount', compact('currency', 'amount'));?>	
				</span>
				<span> 
					<?php echo XiText::sprintf('COM_PAYPLANS_ORDER_CONFIRM_SECOND_CHARGABLE_AMOUNT', PayplansHelperFormat::planTime($invoice->getExpiration(PAYPLANS_RECURRING_TRIAL_2)));?>
				</span>
			<?php endif;?>
			
		<?php else :?>
			<!-- plan do not have trials -->
			<span><?php echo XiText::sprintf('COM_PAYPLANS_ORDER_CONFIRM_FIRST_TRIAL_AMOUNT', PayplansHelperFormat::planTime($invoice->getExpiration()));?></span>	
		<?php endif;?>
	<?php endif;?>	
</span>
<?php if($recurring):?>
	<?php $recurrence_count = $invoice->getRecurrenceCount();?>
	<?php $amount = $invoice->getPrice($regular_counter);?>
	<?php $amountHtml =  $this->loadTemplate('partial_amount', compact('currency', 'amount'));?>	
					
	<?php if($recurrence_count <= 0 ) :?>
		<span><?php echo XiText::sprintf('COM_PAYPLANS_ORDER_CONFIRM_FIRST_RECURRENCE_COUNT_ZERO_RECURRENCE_COUNT',$amountHtml,PayplansHelperFormat::planTime($invoice->getExpiration()));?></span>
	<?php else:?>
		<span><?php echo XiText::sprintf('COM_PAYPLANS_ORDER_CONFIRM_FIRST_RECURRENCE_COUNT',$amountHtml,PayplansHelperFormat::planTime($invoice->getExpiration()), $recurrence_count);?></span>
	<?php endif;?>
<?php endif; ?>
</div>