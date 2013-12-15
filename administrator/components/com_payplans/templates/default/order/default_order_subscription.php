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

<script type="text/javascript">
(function($){
	$(document).ready(function(){
		$('.pp-order-edit-subscribe').hide();
		$('#<?php echo 'pp_order_edit_add_subscription_'.$order->getId(); ?> ')
			.change(function(){
				var planid = $(this).val();
				var orderid = '<?php echo $order->getId();?>';
				var url =  '<?php echo JURI::base().'index.php?option=com_payplans&view=subscription&task=edit&order_id=';?>';
				payplans.url.redirect(url+ orderid +'&plan_id='+planid);
			});
	});
})(payplans.jQuery);
</script>

<div>
	<fieldset class="form-horizontal">
		<legend><?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_SUBSCRIPTION' );?></legend>

		<?php if(!$order->getId()) :?>
			<div> 
                             <p class="center muted">
                                <i class="icon-warning"></i>
				<?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_SUBSCRIPTION_NEW_SUBSCRIPTION_CAN_BE_ADDED_AFTER_SAVE');?>
                            </p>
			</div>
		<?php elseif(!empty($subscr_record)) : ?>
			<?php echo $this->loadTemplate('partial_subscription', compact('subscr_record'));?>
		<?php else :?>
			<div>
                                <div class="pull-right" onClick="payplans.jQuery('.pp-order-edit-subscribe').show();">
                                    <a href="<?php echo XiRoute::_('index.php?option=com_payplans&view=order&task=createInvoice&id='.$order->getId(), false);?>" onclick="this.onclick=function(){return false;}" class="btn btn-large"><i class="icon-plus"></i><?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_SUBSCRIPTION_ADD_SUBSCRIPTION'); ?></a>
                                </div>
				
				<div class="pp-order-edit-subscribe pull-right" style="clear:both;">
					<br />
					<?php echo PayplansHtml::_('plans.edit','pp_order_edit_add_subscription_'.$order->getId(),0,array('none'=>true)); ?>
				</div>
			</div>
			<div>			
                            <p class="center"><big><?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_NO_SUBSCRIPTION');?></big></p>				
				<p class="center muted">
					<?php echo XiText::_('COM_PAYPLANS_ORDER_EDIT_NO_SUBSCRIPTION_DESC');?>
				</p>
			</div>
		<?php endif;?>
	</fieldset>
</div>