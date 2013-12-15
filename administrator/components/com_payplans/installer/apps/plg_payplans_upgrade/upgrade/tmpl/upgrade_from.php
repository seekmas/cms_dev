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
<div id="payplans" class="payplans">

	<div id="payplans-upgrading-from">
		<!-- Header -->
		
	<?php $count = 1;?>
	<?php if(is_array($subscriptions)):?>
	<?php foreach($subscriptions as $subscription): ?>
	
		<div class="row-fluid" style="margin-top: 7px;">
		
		 <div class="offset1 span7">
			
					<?php echo "#".$count . ' : ' . $subscription->getTitle(); ?>
					<h3>
						<!-- when plan expiration time includes hours only then display hours -->
						<?php 
							$plans = $subscription->getPlans(PAYPLANS_INSTANCE_REQUIRE);
							$plan = array_shift($plans);
							$exp_time = $plan->getExpiration();
							$format = ($exp_time['hour']=="00") ? XiDate::SUBSCRIPTION_PAYMENT_FORMAT : XiDate::SUBSCRIPTION_PAYMENT_FORMAT_HOUR; ;
			
							// <!-- display life time as subscription period when plan expiration time is forever -->
							if($subscription->getExpirationDate()->toString()!=null):
								echo $subscription->getSubscriptionDate()->toFormat($format)
											." ".XiText::_('COM_PAYPLANS_ORDER_SUBSCRIPTIONS_TO')
									        ." ".$subscription->getExpirationDate()->toFormat($format); 
							elseif($subscription->getSubscriptionDate()->toString()==null):
								echo XiText::_('COM_PAYPLANS_ORDER_SUBSCRIPTION_NOT_ACTIVATED');
							else :
								echo XiText::_('COM_PAYPLANS_ORDER_SUBSCRIPTION_TIME_LIFETIME');
							endif;
						?>
				</h3>
			</div>
			<div class="span4" >
				<a href="#" onClick="payplans.apps.upgrade.getPlansUpgradeTo('<?php echo $subscription->getKey();?>'); return false;" class="btn btn-primary" ><?php echo XiText::_('PLG_PAYPLANS_UPGRADE_THIS_SUBSCRIPTION'); ?></a>
				<div  id='payplans-upgrade-<?php echo $subscription->getKey();?>-to'>&nbsp;</div>
			</div>
			
	</div>		
	<?php
			$count++;
			endforeach;
	// XITODO else case, show message you can only upgrade your active plans  
	endif;
	
?>
 	</div>
 
	<div id="payplans-popup-upgrade-details">
	</div>

</div>
<?php 