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
<fieldset class="form-horizontal">
<legend> <?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_ORDER' ); ?> </legend>
	<div class="control-group">
	<div class="control-label"><?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_ORDER_ORDER_ID');?></div>	
	<div class="controls"><?php echo PayplansHtml::link(XiRoute::_("index.php?option=com_payplans&view=order&task=edit&id=".$order->getId(), false), $order->getId().'('.$order->getKey(). ')');?></div>
	</div>
	
	<div class="control-group">
	<div class="control-label"><?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_ORDER_TOTAL');?> </div>
	<div class="controls"><?php echo $order->getTotal();?></div>
	</div>
	
	<div class="control-group">
	<div class="control-label"><?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_ORDER_CURRENCY');?></div>
	<div class="controls"><?php echo $order->getCurrency();?></div>
	</div>
	
	<div class="control-group">
	<div class="control-label"><?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_ORDER_STATUS');?></div>    
	<div class="controls">
			<?php echo XiText::_('COM_PAYPLANS_STATUS_'.PayplansStatus::getName($order->getStatus()));?>
	</div>	
	</div>
</fieldset>	
<?php 
