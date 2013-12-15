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
if(defined('_JEXEC')===false) die(); ?>

<div>
	<fieldset class="form-horizontal">
		<legend> <?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_TRANSACTION' ); ?> </legend>
		<!-- display add transaction option in edit screen -->
		<?php if ($invoice->getId()):?>
			<div class="pull-right">
				<a href="<?php echo XiRoute::_('index.php?option=com_payplans&view=invoice&task=addTransaction&invoice_id='.$invoice->getId());?>" onclick="this.onclick=function(){return false;}" class="btn btn-large"><i class="icon-plus"></i><strong><?php echo ' '.XiText::_('COM_PAYPLANS_INVOICE_EDIT_ADD_TRANSACTION');?></strong></a>
			</div>
		<?php endif;?>
		<div class="pp-invoice-edit-transaction">
		<?php if(is_array($txn_records) && !empty($txn_records)) : ?>
				<?php echo $this->loadTemplate('partial_transaction_table', compact('txn_records'));?>
		<?php else :?>
			<div>
				<p class="center"><big><?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_NO_TRANSACTION');?></big></p>
				<p class="center muted"><?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_NO_TRANSACTION_DESC');?></p>
			</div>
		<?php endif;?>
		</div>
	</fieldset>
</div>
<?php 