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
<?php if(empty($upgrade_to)):?>
		<div class="error" style="margin-left:-70px; margin-top:10px;"><?php echo XiText::_('PLG_PAYPLANS_UPGRADE_NO_UPGRADES_AVAILABLE_FOR_THIS_PLAN');?></div>
<?php else :?>
	<select name="upgrade_to" id="payplans-upgrade-to" onChange="payplans.apps.upgrade.setPlansUpgradeTo(this.value, '<?php echo $sub_key;?>')">
	<option value="0"><?php echo XiText::_('PLG_PAYPLANS_UPGRADE_SELECT_NEW_PLAN');?></option>
	<?php foreach($upgrade_to as $plan) : ?>
		<option value="<?php echo $plan->getId();?>"><?php echo $plan->getTitle();?></option>
	<?php endforeach;?>
	</select>
<?php endif;?>
<?php 
