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

<div class="pp-selected-plan">
	<fieldset class="form-horizontal">
		<legend>
			<h4><?php echo XiText::_('COM_PAYPLANS_PLAN_SELECTED_PLAN'); ?></h4>
		</legend>
		<?php $plan_grid_class = ""; echo $this->loadTemplate('plan',compact('plan','plan_grid_class'));?>
	    <input type="hidden" name="plan_id" id="payplans_subscription_plan" value="<?php echo $plan->getId();?>" />
	</fieldset>
</div>	


<?php 