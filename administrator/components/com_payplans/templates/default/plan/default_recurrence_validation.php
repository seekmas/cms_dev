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
if(defined('_JEXEC')===false) die();
?>
<div class="pp-recurr-validation row-fluid">
			<div class="pp-bold pp-primary pp-color clearfix">
				<div class="span2">
					<?php echo XiText::_('COM_PAYPLANS_PLAN_EDIT_RECURRENCE_APP_NAME');?>
				</div>
				<div class="span2">
					<?php echo XiText::_('COM_PAYPLANS_PLAN_EDIT_RECURRENCE_UNIT');?>
				</div>
				<div class="span2">
					<?php echo XiText::_('COM_PAYPLANS_PLANS_EDIT_RECURRENCE_PERIOD');?>
				</div>
				<div class="span2">
					<?php echo XiText::_('COM_PAYPLANS_PLANS_EDIT_RECURRENCE_COUNT');?>
				</div>
				<div class="span4">
					<?php echo XiText::_('COM_PAYPLANS_PLANS_EDIT_RECURRING_MESSAGE');?>
				</div>
			</div>
		<?php foreach($time as $app => $recurringTime) : ?>
			<div class="pp-recurr-validation-value pp-secondary pp-border pp-color  row-fluid clearfix">
				<div class="span2">
					<?php echo $app."\t"; ?>
				</div>
				<div class="span2">
					<?php 	echo $recurringTime['period']."\t"; ?>
				</div>
				<div class="span2">
					<?php echo $recurringTime['unit']."\t";	?> 
				</div>
				<div class="span2">
					<?php echo $recurringTime['frequency']."\t";	?> 
				</div>
				<div class="span4">
					<?php if(isset($recurringTime['message'])){echo $recurringTime['message']."\t";} ?>
				</div>
			</div>
		<?php endforeach;?>
		<div class="pp-recurr-validation-value">
				<?php echo XiText::_('COM_PAYPLANS_PLANS_EDIT_RECURRENCE_COUNT_MSG');?>
		</div>
</div>
<?php 

