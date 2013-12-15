<?php
/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package		PayPlans
 * @subpackage	Frontend
 * @contact 	payplans@readybytes.in


 */
if(defined('_JEXEC')===false) die();
?>
<!-- display renew link -->
<?php if(	(($subscription->isRecurring()) 
				&& 
				in_array($subscription->getStatus(), array(PayplansStatus::SUBSCRIPTION_EXPIRED)))):?>
				<a href="<?php echo XiRoute::_('index.php?option=com_payplans&view=order&task=trigger&event=onPayplansOrderRenewalRequest&subscription_key='.$subscription->getKey());?>">
					<div class="btn btn-large">
						<i class="pp-icon-repeat"></i>&nbsp;<?php echo XiText::_("COM_PAYPLANS_ORDER_RENEW_LINK");?>
					</div>
				</a>
<?php 
		elseif($subscription->getExpirationType() == 'forever'):	?>
			
	<?php elseif(($subscription->getExpirationType() == 'fixed') 
						&& in_array($subscription->getStatus(), array(PayplansStatus::SUBSCRIPTION_EXPIRED, PayplansStatus::SUBSCRIPTION_ACTIVE))): ?>
			
				<a href="<?php echo XiRoute::_('index.php?option=com_payplans&view=order&task=trigger&event=onPayplansOrderRenewalRequest&subscription_key='.$subscription->getKey());?>">
					<div class="btn btn-large">
					<i class="pp-icon-repeat"></i>&nbsp;<?php echo XiText::_("COM_PAYPLANS_ORDER_RENEW_LINK");?>
					</div>
				</a>
			
			<?php endif;?>
<?php 
