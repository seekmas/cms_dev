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
<div class="container-fluid well">
	<div class="row-fluid">
		<div class="span3 hidden-phone">&nbsp;</div>
		
		<div class="span9">
			<div class="span2 visible-desktop">&nbsp;</div>
		
			<div class="span2" style="min-width: 90px;">
				<label><?php echo XiText::_('COM_PAYPLANS_GROUP_GRID_FILTER_TITLE');?></label>	
				<div>
					<?php $attr['style'] = 'class="pp-filter-width"';?>
					<?php echo PayplansHtml::_('text.filter', 'title', 'group', $filters, 'filter_payplans', $attr);?>
				</div>
			</div>
			
			<div class="hidden-phone">&nbsp;</div>
			
			<?php $attr['style'] = 'class="pp-filter-width pp-filter-gap-top"';?>
			<div class="span2 hidden-phone" style="min-width: 100px;">
				<?php echo PayplansHtml::_('boolean.filter', 'visible', 'group', $filters, 'filter_payplans', $attr);?>
			</div>

			<div class="span2 hidden-phone" style="min-width: 100px;">
				<?php echo PayplansHtml::_('boolean.filter', 'published', 'group', $filters, 'filter_payplans', $attr);?>
			</div>
	
			<div class="span2 hidden-phone" style="min-width: 100px;">
				<?php echo PayplansHtml::_('groups.filter', 'parent', 'group', $filters, $attr, array('none'=> 'COM_PAYPLANS_GROUP_SELECT_PARENT'));?>
			</div>
			
			<div style="min-width: 85px;" class="span2">
				<div><input type="submit" name="filter_submit" class="btn btn-primary pp-filter-width pp-filter-gap-top" value="<?php echo XiText::_('COM_PAYPLANS_FILTERS_GO');?>" /></div>
				<div><input type="reset"  name="filter_reset"  class="btn pp-filter-width pp-filter-gap-top" value="<?php echo XiText::_('COM_PAYPLANS_FILTERS_RESET');?>" onclick="payplansAdmin.resetFilters(this.form);" /></div>
			</div>
		</div>
				
	</div>	
</div>
<?php  