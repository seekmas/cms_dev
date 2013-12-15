<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
* website		http://www.jpayplans.com
*/
if(defined('_JEXEC')===false) die();
?>
<div class="pp-recurr-validation pp-alpha pp-omega pp-grid_12">
			<div class="pp-bold pp-primary pp-color clearfix">
				<div class="pp-grid_3">
					<?php echo XiText::_('COM_PAYPLANS_INVOICE_STATUS_NAME');?>
				</div>
				<div class="pp-grid_9">
					<?php echo XiText::_('COM_PAYPLANS_INVOICE_STATUS_DESCRIPTION');?>
				</div>
			</div>
	
	<?php $status = PayplansStatus::getStatusOf('invoice');?>
	<?php foreach ($status as $invoice): ?>
		<div class="pp-recurr-validation-value pp-secondary pp-border pp-color clearfix">
			<div class="pp-grid_3">
				<?php echo XiText::_('COM_PAYPLANS_STATUS_'.$invoice); ?>
			</div>
			<div class="pp-grid_9">
				<?php echo XiText::_('COM_PAYPLANS_STATUS_'.$invoice.'_DESC'); ?>
			</div>
		</div>
	<?php endforeach;?>
</div>
<?php 