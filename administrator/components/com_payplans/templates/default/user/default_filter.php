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
<?php $attr = array(); ?>
<div class="container-fluid well">
	<div class="row-fluid">

		<div class="span3 hidden-phone">&nbsp;</div>
		<div class="span9">

			<div class="span1 visible-desktop"></div>
			<div class="span11">
				<div class="span1 visible-desktop">&nbsp;</div>
				<div class="span3" style="min-width: 100px;">
					<label><?php echo XiText::_('COM_PAYPLANS_USER_GRID_FILTER_USERNAME');?></label>
					<div>
						<?php $attr['style'] ='class="pp-filter-width"'; ?>
						<?php echo PayplansHtml::_('text.filter', 'username', 'user', $filters, 'filter_payplans', $attr);?>
					</div>
				</div>

				<div class="hidden-phone">&nbsp;</div>

				<div class="span2" style="min-width: 100px;">
					<?php $attr = array('style' => 'class="pp-filter-width pp-filter-gap-top"')?>
					<?php echo PayplansHtml::_('plans.filter', 'cross_subscription_plan_id', 'user', $filters, 'filter_payplans', $attr);?>
				</div>

				<div class="span2" style="min-width: 100px;">
					<?php echo PayplansHtml::_('status.filter', 'cross_subscription_status', 'user', $filters, 'subscription', 'filter_payplans', $attr);?>
				</div>

				<div class="span2" style="min-width: 100px;">
					<?php echo PayplansHtml::_('jusertype.filter', 'usertype', 'user', $filters, 'filter_payplans', $attr);?>
				</div>

				<div style="min-width: 85px;" class="span2">
					<div><input type="submit" name="filter_submit" class="btn btn-primary pp-filter-width pp-filter-gap-top" value="<?php echo XiText::_('COM_PAYPLANS_FILTERS_GO');?>" /></div>
					<div><input type="reset"  name="filter_reset"  class="btn pp-filter-width pp-filter-gap-top" value="<?php echo XiText::_('COM_PAYPLANS_FILTERS_RESET');?>" onclick="payplansAdmin.resetFilters(this.form);" /></div>
				</div>

			</div>
		</div>
		
	</div>	
</div>
<?php 