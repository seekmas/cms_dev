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
<script src="<?php echo PayplansHelperUtils::pathFS2URL(dirname(__FILE__).DS.'registration.js');?>" type="text/javascript"></script>

<div id="auto-register">
	<fieldset class="form-vertical">
		<legend><?php echo XiText::_('COM_PAYPLANS_PLAN_AUTO_REGISTERATION');?></legend>
	
		<div class="control-group">
			<div class="control-label">
				<?php echo XiText::_('COM_PAYPLANS_PLAN_REGISTERATION_USERNAME');?>
			</div>
			<div class="controls">
				<input type="text" size="20" id="payplansRegisterAutoUsername" name="payplansRegisterAutoUsername" class="placeholder required"/>
				<span class="payplansRegisterAutoUsername">
					<span class="badge badge-success hide"><i class="icon-ok-sign icon-white"></i></span>
					<span class="badge badge-warning"><i class="icon-remove-sign icon-white"></i></span>
					<span class="badge badge-info hide"><i class=" icon-refresh icon-white"></i></span>
				</span>
				<div class="text-warning pp-gap-bottom05" id="err-payplansRegisterAutoUsername"></div>
			</div>	
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo XiText::_('COM_PAYPLANS_PLAN_REGISTERATION_EMAIL');?>		
			</div>
			<div class="controls">
				<input type="text" size="20" id="payplansRegisterAutoEmail" name="payplansRegisterAutoEmail" class="placeholder required" />
				<span class="payplansRegisterAutoEmail">
					<span class="badge badge-success hide"><i class="icon-ok-sign icon-white"></i></span>
					<span class="badge badge-warning"><i class="icon-remove-sign icon-white"></i></span>
					<span class="badge badge-info hide"><i class=" icon-refresh icon-white"></i></span>
				</span>
				<div class="text-warning pp-gap-bottom05" id="err-payplansRegisterAutoEmail"></div>
			</div>
		</div>
		
		<div class="control-group">
			<div class="control-label">
				<?php echo XiText::_('COM_PAYPLANS_PLAN_REGISTERATION_PASSWORD');?>		
			</div>
			<div class="controls">
				<input type="password" size="20" id="payplansRegisterAutoPassword" name="payplansRegisterAutoPassword" class="required" />
				<span class="payplansRegisterAutoPassword">
					<span class="badge badge-success hide"><i class="icon-ok-sign icon-white"></i></span>
					<span class="badge badge-warning"><i class="icon-remove-sign icon-white"></i></span>
					<span class="badge badge-info hide"><i class=" icon-refresh icon-white"></i></span>
				</span>	
				<div class="text-warning pp-gap-bottom05" id="err-payplansRegisterAutoPassword"></div>
			</div>
		</div>
		
		<?php
        	if($this->params->get('show_captcha', 0)){
				JPluginHelper::importPlugin('captcha');
				$dispatcher = JDispatcher::getInstance();
				$dispatcher->trigger('onInit','payplans_dynamic_recaptcha');
				echo array_shift($dispatcher->trigger('onDisplay',array('payplans_dynamic_recaptcha', 'payplans_dynamic_recaptcha','payplans_dynamic_recaptcha')));	
	       } ?>
      
		
		<div class="control-group">
			<div class="offset8">
				<button type="submit" class="btn" id="payplansRegisterAuto" name="payplansRegisterAuto"><i class="icon-user"></i>&nbsp;<?php echo XiText::_('COM_PAYPLANS_PLAN_REGISTER_AUTO')?></button>
			</div>
		</div>
	</fieldset>
</div>
<?php 
