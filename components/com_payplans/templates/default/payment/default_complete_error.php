<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();
?>
<div class="row-fluid">

	<h2>
		<?php echo XiText::_('COM_PAYPLANS_PAYMENT_ERROR'); ?>
	</h2>
	<div><hr ></div>

	<div class="text-center">
		<h4>
			<?php echo XiText::_('COM_PAYPLANS_PAYMENT_ERROR_MSG'); ?>
		</h4>
		
		<div>
			<?php echo XiText::_('COM_PAYPLANS_PAYMENT_ERROR_TRY_TO')?>
			<a class="pp-button" href="<?php echo XiRoute::_('index.php?option=com_payplans&view=plan&task=subscribe'); ?>">
				<?php echo XiText::_('COM_PAYPLANS_PAYMENT_ERROR_SUBSCRIBE_AGAIN');?>
			</a>
		</div>
		<p>
			<?php echo implode("\n", $appCompleteHtml);?>
			<?php echo XiText::_('COM_PAYPLANS_PAYMENT_ERROR_CONTACT_TO_ADMIN')?>
			<?php echo PayplansHtml::_('email.link', XiText::_('COM_PAYPLANS_ELEMENT_EMAIL'));?>
		</p>
		
	</div>
</div>
<?php 
