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
if(defined('_JEXEC')===false) die(); ?>

<div class="pp-invoice-details">
	<fieldset class="form-horizontal">
		<legend>
			<?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_INVOICE' ); ?>
		</legend>

		<?php if($order->getId()) :?>
                        <div class="pull-right">
				<a href="<?php echo XiRoute::_('index.php?option=com_payplans&view=order&task=createInvoice&id='.$order->getId(), false);?>" onclick="this.onclick=function(){return false;}" class="btn btn-large"><i class="icon-plus"></i><?php echo ' '.XiText::_('COM_PAYPLANS_ORDER_EDIT_INVOICE_ADD_INVOICE');?></a>
                        </div>
		<?php else:?>
			<div> 
                            <p class="center muted">
                                <i class="icon-warning"></i>
				<?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_INVOICE_NEW_INVOICE_CAN_BE_ADDED_AFTER_SAVE');?>
                           </p>
			</div>
		<?php endif; ?>

		<div class="pp-order-edit-invoice">
			<?php if(is_array($invoice_records) && !empty($invoice_records)) : ?>
				<?php echo $this->loadTemplate('partial_invoice_table', compact('invoice_records'));?>
			<?php else :?>
				<div>
					<p class="center"><big><?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_NO_INVOICE');?></big></p>
					<p class="center muted"><?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_NO_INVOICE_DESC');?></p>
				</div>
			<?php endif;?>
		</div>
	</fieldset>
</div>
