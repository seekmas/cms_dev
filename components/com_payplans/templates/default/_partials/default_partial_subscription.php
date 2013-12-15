<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();
?>
<?php if(isset($subscription)):?>
<div class="row-fluid">
		<?php
			$class='';
			switch($subscription->getStatus()){
				case PayplansStatus::SUBSCRIPTION_ACTIVE:
					$class='success';
					break;
				case PayplansStatus::SUBSCRIPTION_EXPIRED:
					$class='important';
					break;
				case PayplansStatus::SUBSCRIPTION_HOLD:
					$class='warning';
				default :
					break;
			} 
		?>
		<div class="label label-<?php echo $class;?> disabled" style="width:96%">
			<h6 class='center'><?php echo $subscription->getStatusName(); ?></h6>
		</div><br />
		
		<div class="text-center"> 
				<!-- display life time as subscription period when plan expiration time is forever -->
					<?php if($subscription->getExpirationDate()->toString()!=null):?>
					  		<?php echo PayplansHelperFormat::date($subscription->getSubscriptionDate()).
					  					 ' <span class="small">'.XiText::_('COM_PAYPLANS_ORDER_SUBSCRIPTIONS_TO').'</span> '.
					  					 PayplansHelperFormat::date($subscription->getExpirationDate()); ?>
					  <?php elseif($subscription->getSubscriptionDate()->toString()==null) :?>
					  		<?php echo XiText::_('COM_PAYPLANS_ORDER_SUBSCRIPTION_NOT_ACTIVATED'); ?>
					  <?php else :?>
				  	  		<?php echo XiText::_('COM_PAYPLANS_ORDER_SUBSCRIPTION_TIME_LIFETIME'); ?>
			  		  <?php endif;?>
		</div>
<!-- Not required to display 					
		<table class="table">
		  <tbody>
		  
	  	
		  	<tr>
			  	<td><?php echo XiText::_('COM_PAYPLANS_ORDER_SUBSCRIPTIONS_NAME');?></td>
				<td><?php echo $subscription->getTitle(); ?>
					<span class="small"><?php echo $subscription->getKey();?></span>
				</td>
			</tr>
		
		  
		  	<tr>
			 	<td><?php echo XiText::_('COM_PAYPLANS_ORDER_SUBSCRIPTION_TIME'); ?></td>
				<td>
					<!-- display life time as subscription period when plan expiration time is forever - ->
					<?php if($subscription->getExpirationDate()->toString()!=null):?>
					  		<?php echo PayplansHelperFormat::date($subscription->getSubscriptionDate()).
					  					 ' <span class="separator">'.XiText::_('COM_PAYPLANS_ORDER_SUBSCRIPTIONS_TO').'</span><br/>'.
					  					 PayplansHelperFormat::date($subscription->getExpirationDate()); ?>
					  <?php elseif($subscription->getSubscriptionDate()->toString()==null) :?>
					  		<?php echo XiText::_('COM_PAYPLANS_ORDER_SUBSCRIPTION_NOT_ACTIVATED'); ?>
					  <?php else :?>
				  	  		<?php echo XiText::_('COM_PAYPLANS_ORDER_SUBSCRIPTION_TIME_LIFETIME'); ?>
			  		  <?php endif;?>
				</td>
			</tr>
 		  
		  	<tr>
			<td><?php echo XiText::_('COM_PAYPLANS_ORDER_SUBSCRIPTIONS_AMOUNT');?></td>
			<td><?php 
						$currency = $subscription->getCurrency();
						$amount   = $subscription->getPrice(); 
						echo $this->loadTemplate('partial_amount', compact('currency', 'amount'));
				?>
			</td>
			</tr>
 	
		  </tbody>
		</table>
-->			
			<!-- for subscription-detail app -->		
		<div class="row-fluid">
				<?php 
				 $position = 'pp-subscription-details';
                 echo $this->loadTemplate('partial_position',compact('plugin_result','position'));
                 ?>
		</div>

</div>
<?php endif;?>
<?php 
