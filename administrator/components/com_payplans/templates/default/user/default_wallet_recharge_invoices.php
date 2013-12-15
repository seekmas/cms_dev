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
if(defined('_JEXEC')===false) die();?>

<?php if(is_array($wallet_Invoice) && !empty($wallet_Invoice)) :?>
	<?php foreach ($wallet_Invoice as $record):?>
		<?php $invoice_records[] = PayplansInvoice::getInstance($record->invoice_id); ?>
	<?php endforeach;?>
	<fieldset class="form-horizontal">
		<legend onClick="xi.jQuery('.pp-wallet-recharge').slideToggle();">
			<span class="show pp-wallet-recharge">[+]</span>
			<?php echo XiText::_('COM_PAYPLANS_WALLET_RECHARGE_INVOICE' );?>
		</legend>
		<div class="hide pp-wallet-recharge">
			<div class="clr"></div>
			<div>
				<?php echo $this->loadTemplate('partial_invoice_table', compact('invoice_records'));?>
			</div>
		</div> 
	</fieldset>
<?php endif;?>
<?php 
