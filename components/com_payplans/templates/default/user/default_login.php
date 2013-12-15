<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();
?>
<div class="pp-login-popup">
		<div class="row-fluid">
		<form id="loging_popup" action="#" method="get">
			<div class="offset1 text-error"><span class="err-payplansLoginError">&nbsp;</span>&nbsp;</div>
			<div>&nbsp;</div>
			<div class="control-group">
			<div class="span4 offset1 control-label"><lable><?php echo XiText::_('COM_PAYPLANS_LOGIN_USERNAME');?></label></div>
			<div class="span7 controls"><input type="text" size="20" class="payplansLoginUsername required"/></div>
			</div>
			<div>&nbsp;</div>
			<div class="control-group">
			<div class="span4 offset1 control-label"><lable><?php echo XiText::_('COM_PAYPLANS_LOGIN_PASSWORD');?></label></div>
			<div class="span7 controls"><input type="password" size="20" class="payplansLoginPassword required"/></div>
			</div>
			<div>&nbsp;</div>
			<div>&nbsp;</div>
		</div>
	</form>
</div>
<?php 