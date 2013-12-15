<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();?>

<?php  if(empty($subscription_records)):?>
	<div class="hero-unit">
	    <h2><?php echo XiText::_('COM_PAYPLANS_DASHBOARD_ORDER_WIDGET_NO_SUBSCRIPTIONS');?></h2>
	    <p><a  href="<?php echo XiRoute::_('index.php?option=com_payplans&view=plan&task=subscribe'); ?>">
				<?php echo XiText::_('COM_PAYPLANS_DASHBOARD_ORDER_WIDGET_ACTION_SUBSCRIBE_PLAN');?>
			</a> 
		</p>
	</div>
<?php elseif(is_array($subscription_records)):?>
		<?php krsort($subscription_records); ?>
	    <table class="table table-hover">
	    	<thead>
                <tr>
                  <th>#</th>
                  <th><?php echo XiText::_('COM_PAYPLANS_DASHBOARD_PLAN_TITLE'); ?></th>
                  <!-- <th><?php echo XiText::_('COM_PAYPLANS_ORDER_DISPLAY_STATUS'); ?></th> -->
                  <th><?php echo XiText::_('COM_PAYPLANS_DASHBOARD_SUBSCRIPTION_PERIOD'); ?></th>
                  <th class='hidden-phone'><?php echo XiText::_('COM_PAYPLANS_ORDER_DISPLAY_TOTAL'); ?></th>
               </tr>
            </thead>
            <tbody>
            <?php $counter =1; ?>
		<?php foreach($subscription_records as $record): ?>
			<?php 
				$subscription 		= PayplansSubscription::getInstance($record->subscription_id,null, $record);
				$subscription_key 	= XiHelperUtils::getKeyFromId($record->subscription_id);
				$class='';
				switch($subscription->getStatus()){ 
					case PayplansStatus::SUBSCRIPTION_ACTIVE:
						$class='success';
						break;
					case PayplansStatus::SUBSCRIPTION_EXPIRED:
						$class='important';
						break;
					case PayplansStatus::SUBSCRIPTION_HOLD:
						$class='warning';
					default :
						break;
				}
			?>
			<tr>
				<td><?php echo $counter++;?>.</td>
				<td><?php echo PayplansHtml::link('index.php?option=com_payplans&view=subscription&task=display&subscription_key='.$subscription_key, $subscription->getTitle());?>
				<!-- </td><td> -->
				<?php echo '<br /> <span class="small label label-'.$class.'">'.$subscription->getStatusName().'</span>';?></td>
				<td>
					<!-- display life time as subscription period when plan expiration time is forever -->
					<?php if($subscription->getExpirationDate()->toString()!=null):?>					
				  		<?php echo PayplansHelperFormat::date($subscription->getSubscriptionDate()).
				  					 ' <span class="separator">'.XiText::_('COM_PAYPLANS_ORDER_SUBSCRIPTIONS_TO').'</span><br/>'.
				  					 PayplansHelperFormat::date($subscription->getExpirationDate()); ?>
				  <?php elseif($subscription->getSubscriptionDate()->toString()==null) :?>
				  		<?php echo XiText::_('COM_PAYPLANS_ORDER_SUBSCRIPTION_NOT_ACTIVATED'); ?>
				  <?php else :?>
			  	  		<?php echo XiText::_('COM_PAYPLANS_ORDER_SUBSCRIPTION_TIME_LIFETIME'); ?>
		  		  <?php endif;?>
				</td>
				<td class='hidden-phone'>
					<?php 	$amount   = $subscription->getTotal();
							$currency = $subscription->getOrder(PAYPLANS_INSTANCE_REQUIRE)->getCurrency(); 
							echo $this->loadTemplate('partial_amount', compact('currency', 'amount'));
					?>
				</td>
			</tr>
		<?php endforeach;?>
		</tbody>
		</table> 
<?php endif; ?>

<?php 