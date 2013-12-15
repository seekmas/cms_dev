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

<div class="pp-subscription-edit">
<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div class="row-fluid">
	<div class="span6">			   
		<fieldset class="form-horizontal">
			<legend><?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_DETAILS' ); ?> 
			</legend>
			
			<div class="control-group">
				<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_TOOLTIP_SUBSCRIPTION_ID') ?>"> 
					<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_SUBSCRIPTION_ID') ?>  	
				</div>
				<div class="controls"><?php echo $subscription->getId()." (".$subscription->getKey().")"; ?>				
					
				</div>
			</div>
				
			<div class="control-group">
				<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_TOOLTIP_USER') ?>"> 
					<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_USER') ?>  
				</div>
				<div class="controls" style="width:60%;">
					<?php if($subscription->getBuyer()) :?>
						<?php echo PayplansHtml::link(XiRoute::_("index.php?option=com_payplans&view=user&task=edit&id=".$subscription->getBuyer(), false),PayplansHelperUser::getName($subscription->getBuyer())); ?>
						<?php echo '('.$subscription->getBuyer(PAYPLANS_INSTANCE_REQUIRE)->getUsername().')'; ?>			
						<?php echo $form->getInput('user_id');?>	
					<?php else : ?>
						<?php echo PayplansHtml::_('users.edit', 'Payplans_form[user_id]', $subscription->getBuyer(), array('usexifbselect' => true));?>
					<?php endif;?>
				</div>	
			</div>

			<div class="control-group">
				<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_TOOLTIP_PLAN') ?>">
					<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_PLAN') ?>  
				</div>				
				<div class="controls">
					<?php $plans = $subscription->getPlans();
					      if(array_shift($plans)) :?>
						<?php echo $subscription->getTitle();?>
					<?php else : ?>
						<?php $plans = $subscription->getPlans();
                              echo PayplansHtml::_('plans.edit','Payplans_form[plan_id]', array_shift($plans), array('none' => true, 'style' => 'class="required"')); ?>
					<?php endif;?>
				</div>	
			</div>
			
			<div class="control-group">
				<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_TOOLTIP_ORDER_TOTAL') ?>"> 
					<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_ORDER_TOTAL') ?>  	
				</div>
				<div class="controls">
					<?php 
		                           $amount   = $order->getTotal();
					   $currency = $order->getCurrency(); 
					   echo $this->loadTemplate('partial_amount', compact('currency', 'amount'));
					
					?>
				</div>	
			</div>
			
			<div class="control-group">
				<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_TOOLTIP_STATUS') ?>"> 
						<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_STATUS') ?>  
				</div>
				<div class="controls">
					<?php echo PayplansHtml::_('status.edit', 'Payplans_form[status]', $subscription->getStatus(), 'SUBSCRIPTION','', '', 'editupdatestatus');?>
				</div>			
			</div>
			
			
			<div class="control-group">
				<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_TOOLTIP_DATE') ?>">
					<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_SUBSCRIPTION_DATE') ?>  
				</div>
				<div class="controls">
					<?php if($subscription->getSubscriptionDate()->toMySql() != null) : 
						echo PayplansHtml::_('datetime.edit', 'Payplans_form[subscription_date]', 'subscription_date', $subscription->getSubscriptionDate()->toMySql());
					else :
						echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_EXPIRATION_DATE_NEVER');
					endif;?>
				</div>		
			</div>
			

			<div class="control-group">
				<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_TOOLTIP_EXPIRATION_DATE') ?>"> 
					<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_EXPIRATION_DATE') ?>  
				</div>
				<div class="controls">
					<?php if($subscription->getExpirationDate()->toMySql() != null) : 
						echo PayplansHtml::_('datetime.edit', 'Payplans_form[expiration_date]', 'expiration_date', $subscription->getExpirationDate()->toMySql());
					else :
						echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_EXPIRATION_DATE_NEVER');
					endif;?>
				</div>
			</div>					
		</fieldset>
	
		<fieldset class="form-horizontal">
			<legend><?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_PARAMETERS'); ?> </legend>
			<div>
					<?php foreach ($form->getFieldset('params') as $field):?>
						<?php if(strtolower($field->type) === 'hidden') : ?>
							<?php echo $field->input; ?>
							<?php continue;?>
						<?php endif;?>							
						<?php $class = $field->group.$field->fieldname; ?>
						<div class="control-group <?php echo $class;?>">
							<div class="control-label"><?php echo $field->label; ?> </div>
							<div class="controls"><?php echo $field->input; ?></div>								
						</div>
					<?php endforeach;?>
			
 			     <!-- Position for subscription-detail app output -->
			     <?php 
			     $position = 'pp-subscription-details';
                	    echo $this->loadTemplate('partial_position',compact('plugin_result','position'));
                 ?>
			     
			     <?php 	$upgradedFrom = $order->getParam('upgrading_from', 0);
			     		if($upgradedFrom): ?>
			     			<div class="control-group">
							<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_TOOLTIP_PARAM_UPGRADED_FROM') ?>"> 
								<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_PARAM_UPGRADED_FROM') ?>  	
							</div>
							<div class="controls">
								<?php echo PayplansHtml::link(XiRoute::_("index.php?option=com_payplans&view=subscription&task=edit&id=".$upgradedFrom, false),XiHelperUtils::getKeyFromId($upgradedFrom)); ?>
							</div>	
							</div>
			     		
			     		<?php endif;?>
			     		
			     <?php 	$upgradedTo = $order->getParam('upgraded_to', 0);
			     		if($upgradedTo): ?>
			     			<div class="control-group">
							<div class="control-label hasTip" title="<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_TOOLTIP_PARAM_UPGRADED_TO') ?>"> 
								<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_PARAM_UPGRADED_TO') ?>  	
							</div>
							<div class="controls">
								<?php echo PayplansHtml::link(XiRoute::_("index.php?option=com_payplans&view=subscription&task=edit&id=".$upgradedTo, false),XiHelperUtils::getKeyFromId($upgradedTo)); ?>
							</div>	
							</div>
			     		
			     		<?php endif;?>
             </div>
		</fieldset>
		
		<?php echo $this->loadTemplate('edit_log'); ?>
		<?php echo $this->loadTemplate('partial_resource_table'); ?>
		
	</div>
	
	<div class="span6">
		<?php if($order->getId()) :?>
				<?php echo $this->loadTemplate('edit_invoice'); ?>
				<?php echo $this->loadTemplate('edit_transaction'); ?>

				<?php if(isset($show_cancel_option) && $show_cancel_option) : ?>
					<?php if($subscription->isRecurring() && $subscription->getOrder(PAYPLANS_INSTANCE_REQUIRE)->getStatus() == PayplansStatus::ORDER_CANCEL):?>
				<fieldset>
				   <legend>
						<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_CANCEL_SUBSCRIPTION' ); ?> 
					</legend>			
							  <?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_CANCELLED_SUBSCRIPTION_MSG');?>
							  <?php else :?>
								 <div class="pull-right">
									<a href="" class="btn" onclick="payplans.url.modal('<?php echo XiRoute::_('index.php?option=com_payplans&view=order&task=terminate&order_id='.$order->getId());?>'); return false;"><?php echo XiText::_('COM_PAYPLANS_ORDER_DETAIL_CANCEL_BUTTON');?></a>
								</div>
				</fieldset>
					<?php endif;?>
				<?php endif;?>
		<?php endif;?>
	</div>
	
	<!-- Hidden values -->
	<?php echo $form->getInput('subscription_id');?>
	<?php echo $form->getInput('order_id');?>
	<input type="hidden" name="task" value="save" />
	<input type="hidden" name="boxchecked" value="1" />
</div>
</form>
</div>
<?php 
