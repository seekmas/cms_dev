<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die(); ?>
<div>
	<?php if(isset($show_cancel_option) && $show_cancel_option) : ?>
		<div class="ui-state-default pp-float-right">
			<i class="pp-icon-remove"></i>
			<a  class="hasTip" 
				title="cancel your recurring order" 
				payplans-tipsy-gravity="s" 
				href="#" 
				onclick="payplans.url.modal('<?php echo XiRoute::_('index.php?option=com_payplans&view=order&task=terminate&order_key='.$order->getKey());?>'); return false;">
				<?php echo XiText::_('COM_PAYPLANS_ORDER_DETAIL_CANCEL_BUTTON');?>
			</a>
		</div>
	<?php endif;?>
</div>
<?php 