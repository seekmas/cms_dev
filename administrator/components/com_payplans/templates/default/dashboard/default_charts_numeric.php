<?php
/**
* @copyright		Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license			http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package			PayPlans
* @subpackage		Backend
* @contact 			payplans@readybytes.in
* website			http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();
?>
<div class="row-fluid">

<!--Sales Count-->
  		<div class="span3  pp-statistics-numeric pp-sales">
  			<?php 
  					$current_image = XiHtml::image(JURI::root().'administrator/components/com_payplans/templates/default/_media/images/icons/sales.png', ' ');
  					$current = $currentSales;
  					$previous = $previousSales;
  					$percentage = $percentageSales;
  					$message = XiText::_('COM_PAYPLANS_STATISTICS_NUMERIC_SALES');
  					echo $this->loadTemplate('charts_numeric_partial', compact('current', 'previous', 'percentage', 'message', 'current_image'));?>
  		</div>
  		
  		
<!--  		Revenue Total-->
  		<div class="span3  pp-statistics-numeric pp-revenue">
  			<?php 
  					$current_image = XiHtml::image(JURI::root().'administrator/components/com_payplans/templates/default/_media/images/icons/revenue.png', ' ');
  					$current = $currentRevenue;
  					$previous = $previousRevenue;
  					$percentage = $percentageRevenue;
  					$message = XiText::_('COM_PAYPLANS_STATISTICS_NUMERIC_REVENUE');
  					echo $this->loadTemplate('charts_numeric_partial', compact('current', 'previous', 'percentage', 'message', 'current_image'));?>
		</div>
  	
 
<!-- Active Subscriptions Calculation-->
  		<div class="span3  pp-statistics-numeric pp-growth">
  			<?php 
  					$current_image = XiHtml::image(JURI::root().'administrator/components/com_payplans/templates/default/_media/images/icons/activeuser.png', ' ');
  					$current = $currentActive;
  					$previous = $previousActive;
  					$percentage = $percentageActive;
  					$message = XiText::_('COM_PAYPLANS_STATISTICS_NUMERIC_ACTICE_SUBSCRIPTION');
  					echo $this->loadTemplate('charts_numeric_partial', compact('current', 'previous', 'percentage', 'message', 'current_image'));?>
		</div>
		
		
<!--Unutilized Invoices		-->
  		<div class="span3  pp-statistics-numeric pp-unpaid">
  			<?php 
  					$current_image = XiHtml::image(JURI::root().'administrator/components/com_payplans/templates/default/_media/images/icons/pendinginvoices.png', ' ');
  					$current = $currentUnutilized;
  					$previous = $previousUnutilized;
  					$percentage = $percentageUnutilized;
  					$message = XiText::_('COM_PAYPLANS_STATISTICS_NUMERIC_ABANDONED_CART');
  					echo $this->loadTemplate('charts_numeric_partial', compact('current', 'previous', 'percentage', 'message', 'current_image'));?>
		</div>
		
		
<!--Sales Count-->
  		<div class="span3  pp-statistics-numeric pp-upgrade">
  			<?php 
  					$current_image = XiHtml::image(JURI::root().'administrator/components/com_payplans/templates/default/_media/images/icons/ppupgrade.png', ' ');
  					$current = $currentUpgrade;
  					$previous = $previousUpgrade;
  					$percentage = $percentageUpgrade;
  					$message =  XiText::_('COM_PAYPLANS_STATISTICS_COMPARISON_CHART_UPGRADE_INFO');
  					echo $this->loadTemplate('charts_numeric_partial', compact('current', 'previous', 'percentage', 'message', 'current_image'));?>
  		</div>
  		
  		
<!--  		Revenue Total-->
  		<div class="span3  pp-statistics-numeric pp-renewal">
  			<?php 
  					$current_image = XiHtml::image(JURI::root().'administrator/components/com_payplans/templates/default/_media/images/icons/pprenewal.png', ' ');
  					$current = $currentRenewal;
  					$previous = $previousRenewal;
  					$percentage = $percentageRenewal;
  					$message = XiText::_('COM_PAYPLANS_STATISTICS_COMPARISON_CHART_RENEWAL_INFO');
  					echo $this->loadTemplate('charts_numeric_partial', compact('current', 'previous', 'percentage', 'message', 'current_image'));?>
		</div>
  	
 
<!-- Active Subscriptions Calculation-->
  		<div class="span3  pp-statistics-numeric pp-discount">
  			<?php 
  					$current_image = XiHtml::image(JURI::root().'administrator/components/com_payplans/templates/default/_media/images/icons/ppdiscount.png', ' ');
  					$current = $currentDiscount;
  					$previous = $previousDiscount;
  					$percentage = $percentageDiscount;
  					$message = XiText::_('COM_PAYPLANS_STATISTICS_COMPARISON_CHART_DISCOUNTS_INFO');
  					echo $this->loadTemplate('charts_numeric_partial', compact('current', 'previous', 'percentage', 'message', 'current_image'));?>
		</div>
		
		
<!--Unutilized Invoices		-->
  		<div class="span3  pp-statistics-numeric pp-coupon ">
  			<?php 
  					$current_image = XiHtml::image(JURI::root().'administrator/components/com_payplans/templates/default/_media/images/icons/ppcoupon.png', ' ');
  					$current = "$currentConsumption";// [$currentUsage]";
  					$previous = "$previousConsumption";// [$previousUsage]";
  					$percentage = $percentageConsumption;
  					$message =  XiText::_('COM_PAYPLANS_STATISTICS_COMPARISON_CHART_COUPON_CODES_INFO');
  					echo $this->loadTemplate('charts_numeric_partial', compact('current', 'previous', 'percentage', 'message', 'current_image'));?>
		</div>
</div>
 
