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

<div>
	<fieldset class="form-horizontal">
		<legend><?php echo XiText::_('COM_PAYPLANS_PAYMENT_EDIT_TRANSACTION' ); ?></legend>
		
		<div>
		<!-- TRANSACTION RECORDS -->
		<?php if(is_array($transaction_records) && !empty($transaction_records)) : ?>
			<?php echo $this->loadTemplate('payment_transaction_table');?>
		<?php else :?>
			<div>
				<p><?php echo XiText::_('COM_PAYPLANS_PAYMENT_EDIT_NO_TRANSACTION');?></p>
				<p><?php echo XiText::_('COM_PAYPLANS_PAYMENT_EDIT_NO_TRANSACTION_DESC');?></p>
			</div>
		<?php endif;?>
		</div>
		
	</fieldset>
</div>
<?php 
