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

<div class="pp-app-select">

	<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm">

	<div class="row-fluid clearfix pp-gap-top10">
		<div class="span2">
				<a class="icon-home" onClick="xi.jQuery('#filtertags').val('all').change(); return false;" href="" title="Click for Back">
				&nbsp;
				</a>
		</div>
		<div class="offset6 span4 pull-right">
			<?php echo PayplansHtml::_('apptags.edit', 'filtertags', '', array('style'=>array('style'=>'width:130px;')), array());?>
		</div>
	</div>
		<?php $count = 0;?>
		<?php foreach($apps as $key => $app) :?> 		
			<?php if($app == 'adminpay') continue; ?>
			
			<?php if($count == 0):?>
			<div class="pp-apps row-fluid  ">
			<?php endif;?>
			<?php $count++;?>
			<div class="pp-app span6 pp-cssbutton pp-app-<?php echo $app; ?> <?php echo ' f-'.implode(' f-',$appdata[$app]['tags']); ?>" onClick="payplans.admin.app.clickapp('<?php echo $app; ?>');">
					<table>
						<tr>
						<td class="hidden-phone"><img class="pp-icon" src="<?php echo PayplansHelperUtils::pathFS2URL($appdata[$app]['icon']); ?>" /></td>					
						<td><?php echo XiText::_($appdata[$app]['name']); ?></td>
						<td class="pp-appdetail pp-appdetail-<?php echo $app; ?> hide">
							<a href='#' style='line-height:2.5em;' class="pp-cssbutton orange" onClick="payplans.admin.app.clickapp('<?php echo $app; ?>');"><?php echo XiText::_('SELECT');?></a>
						</td>
					</tr>
					</table>
			</div>
			<?php if($count == 2):?>
			<?php $count = 0;?>
			</div>	
			<?php endif;?>			
		<?php endforeach;?>
	
	<div class="pp-appdetails" >
		<?php foreach($appdata as $app => $data): ?> 
			<div class="pp-appdetail pp-appdetail-<?php echo $app; ?> hide">
				<?php echo XiText::_($data['description']); ?>
			</div>
		<?php  endforeach; ?>					
	</div>
	
	<input type="hidden" id="payplans-app-new-next" type="submit" name="appnext" value="#" />
	<input type="hidden" name="task" value="new" />
	<input type="hidden" id="type" name="type" value="#" />

</form>
</div>
<?php 
