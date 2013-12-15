<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();?>

<div class="pp-user-selectplan">
<form action="<?php echo $uri; ?>" method="post" name="selectPlanForm" id="selectPlanForm">
	<div class="pp-user-selectplan-message center">
		<?php echo XiText::_('COM_PAYPLANS_USER_APPLY_PLAN_HELP_MESSAGE');?>
	</div>
	<div class="center pp-gap-top20">
		<?php echo PayplansHtml::_('plans.edit', 'plan_id', '', array('none'=>true));?>
	</div>
</form>
</div>
<?php 
