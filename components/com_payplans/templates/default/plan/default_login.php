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
<div class="row-fluid">
	<fieldset>
		<legend>
			<h2><?php echo XiText::_('COM_PAYPLANS_PLAN_LOGIN_TO_SUBSCRIBE_THIS_PLAN');?></h2>
		</legend>
	</fieldset>

	<div class="span6">
		<?php echo $this->loadTemplate('login_plan');?>
	</div>
	<div class="span5 pull-right">
		<div class="pp-login">
			<form action="<?php echo $uri; ?>" method="post" name="site<?php echo $this->getName(); ?>Form">
				<fieldset class="form-vertical">
					<legend><h4><?php echo XiText::_('COM_PAYPLANS_LOGIN_HEADING'); ?></h4></legend>
					<div class="control-group">
						<div class="control-label"><?php echo XiText::_('COM_PAYPLANS_LOGIN_USERNAME');?></div>
						<div class="controls"><input type="text" size="20" name="payplansLoginUsername" class="placeholder required"/>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label"><?php echo XiText::_('COM_PAYPLANS_LOGIN_PASSWORD');?></div>
						<div class="controls"><input type="password" size="20" name="payplansLoginPassword" class="placeholder required"/></div>
					</div>
					
					<div class="control-group">		
						<div class="controls offset8">
							<button type="submit" class="btn" id="payplansLoginSubmit" name="payplansLoginSubmit" value="1"><i class="icon-user"></i>&nbsp;<?php echo XiText::_('COM_PAYPLANS_LOGIN_BUTTON'); ?></button>
						</div>
					</div>
								
					<input type="hidden" name="boxchecked" value="0" />
					<input type="hidden" name="task" value="login"/>
					<input type="hidden" name="plan_id" id="payplans_subscription_plan" value="<?php echo $plan->getId();?>" />
				</fieldset>
			</form>
		</div>

	<div class="pp-registration">
		<form action="<?php echo $uri; ?>" method="post" name="siteRegistrationForm">
			<?php $position= 'pp_plan_login_registration_position'; ?>
			<?php echo $this->loadTemplate('partial_position', compact('plugin_result','position'));?>
			<input type="hidden" name="plan_id" id="payplans_subscription_plan" value="<?php echo $plan->getId();?>" />
		</form>
	</div>
	
</div>

</div>
<?php 