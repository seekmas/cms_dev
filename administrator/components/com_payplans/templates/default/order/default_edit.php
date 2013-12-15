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
<div class="pp-order-edit">
<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm">
<div class="row-fluid">
	<div class="span6">
		<fieldset class="form-horizontal">
			<legend> <?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_DETAILS' ); ?> </legend>
			<div class="control-group">
				<div class="control-label">
					<span class="hasTip" title="<?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_TOOLTIP_ORDER_ID') ?>" >
				 		<?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_ORDER_ID') ?> 
				 	</span>
				 </div>
				<div class="controls"><?php echo $order->getId()." (".$order->getKey().")"; ?></div>
			</div>

			<div class="control-group">
				<div class="control-label required">  
					<span class="hasTip" title="<?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_TOOLTIP_BUYER') ?>" >
					<?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_BUYER') ?>
					</span>  
				</div>
				<div class="controls" style="width:60%;">
					<?php echo PayplansHtml::_('users.edit', 'buyer_id', $order->getBuyer(), array('usexifbselect' => true));?>
				</div>
			</div>

			<div class="control-group">
				<div class="control-label"> 
					<span class="hasTip" title="<?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_TOOLTIP_TOTAL') ?>" >
				 		<?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_TOTAL') ?> 
				 	</span>
				 </div>
			<div class="controls"><input type="text" class="readonly" name="total" value="<?php echo $order->getTotal(); ?>" /></div>
			</div>

			<div class="control-group">
				<div class="control-label"> 
					<span class="hasTip" title="<?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_TOOLTIP_CURRENCY') ?>" >
				 		<?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_CURRENCY') ?> 
				 	</span>
				 </div>
			<div class="controls"><input type="text" class="readonly" name="currency" value="<?php echo $order->getCurrency(); ?>" /></div>
			</div>
			
			<div class="control-group">
				<div class="control-label">
					<span class="hasTip" title="<?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_TOOLTIP_STATUS') ?>" >
						<?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_STATUS') ?> 
					</span>
				</div>
				<div class="controls">
					<input type="text" class="readonly" name="status" value="<?php echo $order->getStatusName(); ?>" />
				</div>
			</div>
		</fieldset>
		<?php echo $this->loadTemplate('partial_user', compact('user'));?>
		<?php echo $this->loadTemplate('edit_log'); ?>	
	</div>
	
	<div class="span6">
		<?php echo $this->loadTemplate('order_subscription'); ?>
		<?php echo $this->loadTemplate('order_invoice'); ?>
		<?php echo $this->loadTemplate('order_transaction'); ?>

		<?php //XITODO : Add to toolbar ?>
		<?php if(isset($show_cancel_option) && $show_cancel_option) : ?>
			<fieldset class="form-horizontal">
				<legend><?php echo XiText::_('COM_PAYPLANS_CANCEL_RECURRING_ORDER')?></legend>
                                <div class="center">
                                    <a class="btn btn-large" href="" onclick="payplans.url.modal('<?php echo XiRoute::_('index.php?option=com_payplans&view=order&task=terminate&order_id='.$order->getId());?>'); return false;"><?php echo XiText::_('COM_PAYPLANS_ORDER_DETAIL_CANCEL_BUTTON');?></a>
                                </div>
                                <br>
			</fieldset>
		<?php endif;?>
	</div>
	
	<?php foreach ($form->getFieldset('hidden') as $field):?>
		<?php $class = $field->group.$field->fieldname; ?>
		<div class="control-group <?php echo $class;?>">
			<div class="control-label"><?php echo $field->label; ?> </div>
			<div class="controls"><?php echo $field->input; ?></div>								
		</div>
	<?php endforeach;?>
	
	<input type="hidden" name="task" value="save" />
	</div>
</form>
</div>
<?php
