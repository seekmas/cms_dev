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
<div class="pp-filter-warp clearfix">
	<div class="pp-filters pp-grid_12 cleafix pp-alpha pp-omega">
		
		<div class="pp-action pp-filter">
			<div class="pp-label" style="border:0px;">&nbsp;</div>
			<div class="pp-input">	
				<input type="submit" name="filter_submit" value="<?php echo XiText::_('COM_PAYPLANS_FILTERS_GO');?>" />
				<input type="reset" name="filter_reset" value="<?php echo XiText::_('COM_PAYPLANS_FILTERS_RESET');?>" onclick="payplansAdmin.resetFilters(this.form);" />
			</div>
		</div>

		<div class="pp-filter">
			<div class="pp-label" style="border:0px;">&nbsp;</div>
			<div class="pp-input">
				<?php echo PayplansHtml::_('status.filter', 'status', 'order', $filters, 'order');?>
			</div>
		</div>	
		
		<div class="pp-filter">	
			<div class="pp-label">
				<?php echo XiText::_('COM_PAYPLANS_ORDER_GRID_TOTAL');?>
			</div>
			<div class="pp-input">
				<?php echo PayplansHtml::_('range.filter', 'total', 'order', $filters, 'text');?>
			</div>
		</div>
			
	</div>	
</div>
<?php 