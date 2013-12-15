<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();?>

<div class="pp-plan <?php echo $plan_grid_class;?> <?php echo $plan->getCssClasses(); ?> ">
	<span class="pp-badge"> </span>
	
	<div class="pp-plan-details center">
		<div class="pp-plan-border">
		<!-- =========================================
			Basic Detail section (Plan name and pricing)
		========================================= -->
			<div class="pp-plan-basic">
				<div class="pp-plan-title">
					<h4><?php echo JString::ucfirst($plan->getTitle()); ?></h4>
					<div class="pp-plan-teaser muted">
						&nbsp;<?php echo JString::ucfirst($plan->getTeasertext());?>&nbsp;
					</div>
				</div>
				
				<div class="pp-plan-price">		
						<?php $currency = $plan->getCurrency();
							  $amount   = $plan->getPrice();?>
						<h1>
							<?php if(floatval(0) == floatval($plan->getPrice())):?>
									<?php echo XiText::_('COM_PAYPLANS_PLAN_PRICE_FREE');?>
							<?php else : 
									echo $this->loadTemplate('partial_amount', compact('currency', 'amount'));?>
							<?php endif;?>
						</h1>
						
						<div class="pp-plan-time muted">
							&nbsp;<?php echo($plan->isRecurring() !== false)? XiText::_('COM_PAYPLANS_PLAN_PRICE_TIME_SEPERATOR') : XiText::_('COM_PAYPLANS_PLAN_PRICE_TIME_SEPERATOR_FOR') ;?>&nbsp;
							<?php echo PayplansHelperFormat::planTime($plan->getExpiration()); ?>
						</div>
				</div>
			 </div>  
			 
			 
			 <!-- =========================================
				Plan Description
			 ========================================== --> 
			 
			<div class="pp-plan-description ">
				<?php echo $plan->getDescription(true); ?>
			</div>
			
			
			<!-- =========================================
				Subscribe Button
			 ========================================== --> 
		
			<div class="pp-plan-subscribebutton">
				<?php 
				$position = 'plan-block-bottom_'.$plan->getId();
				echo $this->loadTemplate('partial_position',compact('plugin_result','position'));?>
				<a id="testPlan<?php echo $plan->getId();?>" class="btn btn-primary" href="<?php echo XiRoute::_('index.php?option=com_payplans&view=plan&task=subscribe&plan_id='.$plan->getId());?>" onclick="this.onclick=function(){return false;}">
					&nbsp;<?php echo XiText::_('COM_PAYPLANS_PLAN_SUBSCRIBE_BUTTON')?>&nbsp;
				</a>
			</div>
	   </div>
	</div>
	
	<div class="visible-phone">&nbsp;</div>
</div>
<?php 