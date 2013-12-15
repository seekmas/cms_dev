<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license	GNU/GPL, see LICENSE.php
* @package	PayPlans
* @subpackage	Modules
* @contact 	payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();
?>
<?php 
// Load JQuery, XI Scripts
PayplansHelperTemplate::loadAssets();
PayplansHtml::stylesheet('modules/mod_payplans_subscription/css/style.css');

if(!$subscriptions){
	return true;
}

?>

<script>
xi.jQuery(document).ready(function(){
	xi.jQuery(".subscription .timeago").timeago();
});
</script>
<?php $count=1;?>
<ul id="mod-subscription">
<?php $app_plans = ModPayplansSubscriptionHelper::isRenewAppApplicable();?>
<?php 	rsort($subscriptions);
		foreach ($subscriptions as $subscription): 
		if($count > $NumRecord)
			break;
	?>

	<li class="latestnews">
	<span class="plan"><?php echo $subscription->getTitle(); ?></span>
	<?php
	if($app_plans && in_array(ModPayplansSubscriptionHelper::APPLY_ALL, $app_plans)){
		echo ModPayplansSubscriptionHelper::showRenewLink($subscription);
	}else {
		$plan = $subscription->getPlans();
		if(array_intersect($plan, $app_plans)){
			echo ModPayplansSubscriptionHelper::showRenewLink($subscription);
		}
	}
	?>
	
	<div class="modifydate" style="height: auto;"> 
	<?php 
	if ($subscription->getStatus() == PayplansStatus::SUBSCRIPTION_ACTIVE && !$subscription->getExpirationDate()->toFormat($dateFormat) ){
		echo XiText::_('MOD_PAYPLANS_SUBSCRIPTION_EXPIRATION_DATE_LIFETIME');}
		else{
		echo XiText::_('MOD_PAYPLANS_SUBSCRIPTION_EXPIRATION_DATE_'.PayplansStatus::getName($subscription->getStatus())) ." ". $subscription->getExpirationDate()->toFormat($dateFormat);
	}?>	
	</div>

	</li> 
	<?php $count++; ?>
<?php endforeach;?>
</ul>
<?php 
	