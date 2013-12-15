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
if(defined('_JEXEC')===false) die();

?>
<?php if(!empty($resources)): ?>
<fieldset class="form-horizontal">
	<legend onClick="xi.jQuery('.pp-resource-details').slideToggle();">
		<span style="font-size: 0.7em;" class=" <?php echo isset($show_resource_details)? 'hide' : 'show' ;?> pp-resource-details">[+]</span>
		<?php echo XiText::_('COM_PAYPLANS_RESOURCE_GRID_LEGEND'); ?>
	</legend>
	
	<div class="<?php echo isset($show_resource_details)? 'show' : 'hide' ;?> pp-resource-details">
	
	<table id="payplans_grid" class="payplans_grid table table-striped order-grid">

	<thead>
	<!-- TABLE HEADER START -->
		<tr>
        	<th><?php echo XiText::_("COM_PAYPLANS_RESOURCE_GRID_ID");?></th>
			<th><?php echo XiText::_("COM_PAYPLANS_RESOURCE_GRID_TITLE");?></th>
			<th><?php echo XiText::_("COM_PAYPLANS_RESOURCE_GRID_USER_ID");?></th>
			<th><?php echo XiText::_("COM_PAYPLANS_RESOURCE_GRID_VALUE");?></th>
			<th><?php echo XiText::_("COM_PAYPLANS_RESOURCE_GRID_SUBSCRIPTION_IDS");?></th>
			<th><?php echo XiText::_("COM_PAYPLANS_RESOURCE_GRID_COUNT");?></th>
		</tr>
	<!-- TABLE HEADER END -->
	</thead>

	<tbody>
	<!-- TABLE BODY START -->
		<?php 
		$count = 0;
		foreach ($resources as $resource):?>
			<tr class="<?php echo "row".$count%2; ?>">
				<td> <?php echo $resource->resource_id; ?> </td>
    			<td><?php echo PayplansHtml::link(XiRoute::_('index.php?option=com_payplans&view=resource&tas&task=edit&id='.$resource->resource_id), $resource->title);?></td>
				<td><?php echo $resource->user_id;?></td>
				<td><?php echo $resource->value;?></td>
				<td class="pp-resource-subscription"><?php echo trim($resource->subscription_ids, ',');?></td>
				<td><?php echo $resource->count;?></td>
			</tr>
		<?php $count++;?>
		<?php endforeach;?>
	<!-- TABLE BODY END -->
	</tbody>

	<tfoot>
	<!-- TABLE FOOTER START -->
		<tr>
			<td colspan="8">
			</td>
		</tr>
	<!-- TABLE BODY END -->
	</tfoot>
</table>
</div>
</fieldset>
<?php endif;?>
<?php 
