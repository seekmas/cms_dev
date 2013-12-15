<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in


*/
if(defined('_JEXEC')===false) die();
?>
<div id="payplans" class="payplans">
		
	   <div class="row-fluid upgrade-options">
				
				<div class="span6 form-horizontal">
						<?php $oldplans = $old_sub->getPlans(PAYPLANS_INSTANCE_REQUIRE);
                              $old_plan = array_shift($oldplans);?>
							<div class="row-fluid">
								<div class="span6 muted text-center text-center">
									<?php echo XiText::_('COM_PAYPLANS_UPGRADES_DETAILS_PREVIOUS_PLAN');?>
								</div>
								<div class="span6">
									<?php echo $old_plan->getTitle(); ?>
								</div>
							</div>

							<div class="row-fluid">
								<div class="span6 muted text-center text-center">
									<?php echo XiText::_('COM_PAYPLANS_UPGRADES_DETAILS_PREVIOUS_PAYMENT');?>
								</div>
								<div  class="span6">
									<?php 	$currency = $old_order->getCurrency();
											$amount	  = $paid_amount;
											echo $this->_render('partial_amount', compact('currency', 'amount'), 'default');
									?>
								</div>
							</div>
							
							<div class="row-fluid">
							<div class="span6 muted text-center text-center">
								<?php echo XiText::_('COM_PAYPLANS_UPGRADES_DETAILS_NOT_UTILIZED_PAYMENT');?>
							</div>
							<div class="span6">
								<?php 	$amount = $unutilized_amount;
										echo $this->_render('partial_amount', compact('currency', 'amount'), 'default');
								?>
							</div>
							</div>
				</div>
				
				<div class="span6 form-horizontal">
						<?php $plans    = $new_sub->getPlans(true);
                              $new_plan = array_shift($plans);?>
						<div class="row-fluid">
							<div class="span6 muted text-center ">
								<?php echo XiText::_('COM_PAYPLANS_UPGRADES_DETAILS_NEW_PLAN');?>
							</div>
							<div  class="span6">
								<?php echo $new_plan->getTitle(); ?>
							</div>
						</div>
						<div class="row-fluid">
							<div class=" span6 muted text-center ">
								<?php echo XiText::_('COM_PAYPLANS_UPGRADES_DETAILS_NEW_PRICE');?>
							</div>
							<div  class="span6"><?php 	$currency = $new_order->getCurrency();
											$amount   = $new_plan->getPrice();
											echo $this->_render('partial_amount', compact('currency', 'amount'), 'default');
									?></div>
						</div>
						<?php $amount = $new_invoice->getDiscount();
							  if($amount != 0):
								?>
								<div class="row-fluid">
									<div class="span6 muted text-center ">
										<?php echo XiText::_('COM_PAYPLANS_UPGRADES_DETAILS_NEW_CURRENT_DISCOUNT');?>
									</div>
									<div>
										<?php echo $this->_render('partial_amount', compact('currency', 'amount'), 'default');?>
									</div>
								</div>
						<?php endif;?>
						<div class="row-fluid">
							<div class="span6 muted text-center ">
								<?php echo XiText::_('COM_PAYPLANS_UPGRADES_DETAILS_NEW_CURRENT_PAYABLE_AMOUNT');?>
							</div>
							<div  class="span6">
								<?php 	$amount = $new_invoice->getTotal();
										echo $this->_render('partial_amount', compact('currency', 'amount'), 'default');
								?>
							</div>
						</div>
						<div class="row-fluid">						
							<div class=" span6 muted text-center ">
								<?php echo XiText::_('COM_PAYPLANS_UPGRADES_DETAILS_NEW_REGULAR_PAYMENT');?>
							</div>

							<div class="span6">
								<?php 	$amount = $new_invoice->getPrice();
										echo $this->_render('partial_amount', compact('currency', 'amount'), 'default'); 
								?>
							</div>
						</div>
				</div>
			</div>
		<?php if(XiFactory::getApplication()->isAdmin()):?>
		<div class="clearfix">
			<script type="text/javascript"> 
				payplans.jQuery(document).ready(function($){
						payplans.apps.upgrade.hideInfoButtons();  
					});
			</script>
			<div>
				<div class="upgrade-info pp-upgrade-success-msg" id="upgrade-info-free">
					<?php echo XiText::_('COM_PAYPLANS_UPGRADES_FROM_BACKEND_FREE_UPGARDE_DETAILS');?>
				</div>
				<div class="upgrade-info pp-upgrade-success-msg" id="upgrade-info-offline">
					<?php echo XiText::_('COM_PAYPLANS_UPGRADES_FROM_BACKEND_OFFLINE_UPGARDE_DETAILS');?>
				</div>
				<div class="upgrade-info pp-upgrade-success-msg" id="upgrade-info-partial">
					<?php echo XiText::_('COM_PAYPLANS_UPGRADES_FROM_BACKEND_PARTIAL_UPGARDE_DETAILS');?>
				</div>
				<div class="upgrade-info pp-upgrade-success-msg" id="upgrade-info-wallet">
					<?php echo XiText::sprintf('COM_PAYPLANS_UPGRADES_FROM_BACKEND_WALLET_UPGRADE_DETAILS', $currency, $user->getWalletBalance())?>
				</div>
				
			</div>			
			<input type="hidden" id="upgrade-type" value="" />
		</div> 
		<?php endif;?>
</div>
<?php 