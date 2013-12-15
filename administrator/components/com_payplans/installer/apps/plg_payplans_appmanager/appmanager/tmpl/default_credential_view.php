<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Backend
* @contact 		payplans@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();
?>

<div class="container-fluid appmanager">
	<div class="row-fluid">
		<div class="span12">&nbsp;</div>
	</div>
	<div class="row-fluid">
		<div class="span6"><?php echo XiText::_('PLG_PAYPLANS_APPMANAGER_JPAYPLANS_USERNAME');?></div>
		<div class="span4"><input class="span12" type="text" size="20" id="jpayplansUsername" class="required" value="<?php echo $username;?>"/></div>
		<div class="span2">&nbsp;</div>
	</div>
	<div class="row-fluid">
		<div class="span12">&nbsp;</div>
	</div>
	<div class="row-fluid">
		<div class="span6"><?php echo XiText::_('PLG_PAYPLANS_APPMANAGER_JPAYPLANS_PASSWORD');?></div>
		<div class="span4"><input  class="span12" type="password" size="20" id="jpayplansPassword" class="required" value="<?php //echo $password;?>"/></div>
		<div class="span2 processing-request ">&nbsp;</div>
	</div>
	<div class="row-fluid pp-appmanager-credential-err text-center text-error">&nbsp;</div>
	<div class="row-fluid">
		<div class="pp-appmanager-error text-error text-center"><?php if(isset($error)){ echo $error;}; ?>&nbsp;</div>
	</div>
	<div class="row-fluid">
		<div class="text-center"><span class="err-payplansLoginError pp-center"></span>&nbsp;</div>
	</div>
	
</div>
<?php 