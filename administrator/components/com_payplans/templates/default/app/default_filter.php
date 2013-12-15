<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();?>
<?php $attr = array();?>
<div class="container-fluid well">
	<div class="row-fluid">

		<div class="span4 hidden-phone">&nbsp;</div>
		<div class=" span8 row-fluid">

			<div class="span1 hidden-phone"></div>
			<div class="span11">

				<div class="span3" style="min-width: 100px;">
					<label><?php echo XiText::_('COM_PAYPLANS_PLAN_GRID_FILTER_TITLE');?></label>
						<?php $attr['style'] = 'class="pp-filter-width"';?>
						<?php echo PayplansHtml::_('text.filter', 'title', 'app', $filters, 'filter_payplans', $attr);?>
				</div>
				
				<div class="hidden-phone">&nbsp;</div>

				<div class="span3 hidden-phone pp-filter-gap-top" style="min-width: 100px;">
					<?php echo PayplansHtml::_('apptypes.filter', 'type', 'app', $filters, 'filter_payplans', $attr);?>
				</div>
				<div class="span3 hidden-phone pp-filter-gap-top" style="min-width: 100px;">
					<?php echo PayplansHtml::_('boolean.filter', 'published', 'app', $filters, 'filter_payplans', $attr);?>
				</div>
				<div class="span3 pp-filter-gap-top">
					<div><input type="submit" name="filter_submit" class="btn btn-primary pp-filter-width" value="<?php echo XiText::_('COM_PAYPLANS_FILTERS_GO');?>" /></div>
					<div><input type="reset"  name="filter_reset"  class="btn pp-filter-width pp-filter-gap-top" value="<?php echo XiText::_('COM_PAYPLANS_FILTERS_RESET');?>" onclick="payplansAdmin.resetFilters(this.form);" /></div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php 
