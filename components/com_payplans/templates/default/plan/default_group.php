<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();?>

<div class="pp-plan <?php echo $plan_grid_class; ?> <?php echo $group->getCssClasses(); ?>">
	<span class="pp-badge"> </span>
	<div class="pp-plan-details center">
		<div class="pp-plan-border">
			<div class="pp-plan-basic">
					<div class="pp-plan-price">
						<h4><?php echo JString::ucfirst($group->getTitle()); ?></h4>
						<div class="pp-plan-teaser muted">
							&nbsp;<?php echo JString::ucfirst($group->getTeasertext());?>&nbsp;
						</div>
					</div>
			</div>
			
			<div class="pp-plan-description">
				<?php echo $group->getDescription(); ?>
			</div>
			
			<!-- Its necessary for making same height in case plans and group are being displayed together  -->
			<div class="pp-plan-title"><h4>&nbsp;</h4></div>
			
			<div class="pp-plan-subscribebutton">
					<a id="testPlan<?php echo $group->getId();?>" class="btn btn-primary" href="<?php echo XiRoute::_('index.php?option=com_payplans&view=plan&task=subscribe&group_id='.$group->getId());?>">
						&nbsp;<?php echo XiText::_('COM_PAYPLANS_GROUP_BUTTON')?>&nbsp;
					</a>
			</div>
		</div>
	</div>
</div>
<?php 