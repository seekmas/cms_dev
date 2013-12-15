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
<form action="index.php?option=com_payplans&view=invoice&task=mailInvoice&invoice_id=<?php echo $invoice->getId();?>&tmpl=component" method="post" name="admin-invoice-email-form" id="admin-invoice-email-form">
<div class="span10">
	<div class="form-vertical">
		<div class="control-group">
				<div class="control-label"><?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_EMAIL_TO'); ?></div>
				<div class="controls">
					<input type="text"  name="email-to" id="email-to" size="60" value="<?php echo $invoice->getBuyer(true)->getEmail(); ?>"  />
				</div>
		</div>
		
		<div class="control-group">
				<div class="control-label"><?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_CC'); ?></div>
				<div class="controls">
					<input type="text"  name="email-cc" id="email-cc" size="60" value=" "  />
				</div>
		</div>
		
		<div class="control-group">
				<div class="control-label"><?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_BCC'); ?></div>
				<div class="controls">
					<input type="text"  name="email-bcc" id="email-bcc" size="60" value=" "  />
				</div>
		</div>
	
		<div class="control-group">
				<div class="control-label"><?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_SUBJECT');?></div>
				<div class="controls">
					<input type="text"  name="email-subject" id="email-subject" value="" size="60"  />
				</div>
		</div>
	
		<div class="control-group">
				<div class="control-label"><?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_CONTENT');?></div>
				<div class="controls">
					<?php echo $editor->display( 'email-body',  htmlspecialchars(XiText::_('COM_PAYPLANS_INVOICE_EMAIL_LINK_BODY'), ENT_QUOTES), '100%', '200', '60', '20' ) ;?>
			
				</div>
		</div>
		
		<div class="control-group">
				<div class="control-label"><?php echo XiText::_('COM_PAYPLANS_INVOICE_EDIT_ADD_TOKEN'); ?></div>
				<div class="controls">
					<?php echo PayplansHtml::_('rewriter.edit', 'rewriter', '', 'INVOICE');?>
				</div>
				<input id="pp-submit-button" class="hide" type="submit" value="submit" />
		</div>
	</div>
</div>
	
</form>
<?php 
