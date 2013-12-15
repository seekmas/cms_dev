<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();
?>

<div class="control-group">
	<div class="control-label">
		<?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_SUBSCRIPTION_GRID_SUBSCRIPTION_ID');?>
	</div>
	<div class="controls">
		<?php echo PayplansHtml::link(XiRoute::_("index.php?option=com_payplans&view=subscription&task=edit&id=".$subscr_record->getId(), false), $subscr_record->getId().'('.$subscr_record->getKey().')');?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_SUBSCRIPTION_GRID_PLAN');?>
	</div>
	<div class="controls">
		<?php echo PayplansHelperPlan::getName(array_shift($subscr_record->getPlans()));?>
	</div>	
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_SUBSCRIPTION_GRID_STATUS');?>
	</div>
	<div class="controls">
		<?php echo XiText::_('COM_PAYPLANS_STATUS_'.PayplansStatus::getName($subscr_record->getStatus()));?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_SUBSCRIPTION_GRID_SUBSCRIPTION_DATE');?>
	</div>	
	<div class="controls">
		<?php echo XiDate::timeago($subscr_record->getSubscriptionDate()->toMysql());?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">	    
		<?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_SUBSCRIPTION_GRID_EXPIRATION_DATE');?>
	</div>
	<div class="controls">		
		<?php echo XiDate::timeago($subscr_record->getExpirationDate()->toMysql());?>
	</div>
</div>
<?php 