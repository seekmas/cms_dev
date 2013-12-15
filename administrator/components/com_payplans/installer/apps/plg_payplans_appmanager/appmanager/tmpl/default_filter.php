<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();?>

<div class="alert alert-error fade in pp-description" >
     		<span><?php echo  XiText::_("COM_PAYPLANS_DASHBOARD_ERROR_FOR_APPS");?></span>
     </div>
     
<?php $showUpgrade = (isset(PayplansFactory::getConfig()->expert_show_upgrade_message))?
               		  PayplansFactory::getConfig()->expert_show_upgrade_message:'1';
     if($showUpgrade):?>
     <div class="alert fade in pp-description" id="error-message-body">
     	<button class="close" data-dismiss="alert"  type="button">×</button>
		<span>For using export functionality, if pdf invoice is already intalled then reinstall it and then install export plugin.</span>
     </div>
<?php endif;?>

<?php $show = ((isset(PayplansFactory::getConfig()->show_appmanager_description)?PayplansFactory::getConfig()->show_appmanager_description:'show') == 'hide') ? false : true;?>
<?php if($show):?>
     <div class="alert fade in alert-info pp-description" id="description-message-body">
     	<button class="close" data-dismiss="alert"  type="button">×</button>
		<span><?php echo XiText::_("PLG_PAYPLANS_APPMANAGER_DESCRIPTION"); ?></span>
     </div>
<?php endif;?>

<div class="text-center">
	<iframe id="appvillebanner" scrolling="no" frameborder="0" width="100%" src="http://pub.jpayplans.com/appvilletracking.html">
	</iframe>
</div>

<form action="<?php echo XiRoute::_('index.php?option=com_payplans&view=appmanager', false); ?>" method="post" name="adminForm" id="adminForm">

<div class="row-fluid well well-small">
		<div class="span5">
			<div class="row-fluid">
			    <div class="span6">
			    	<input class="span12" type="text" name="filter[search]" value="<?php echo (!empty($filter['search'])) ? $filter['search'] : '';?>" />
			    	
			    </div>
				<div class="span6">
					<button class="btn btn-info" type="submit">
						<?php echo XiText::_('PLG_PAYPLANS_APPMANAGER_BUTTON_GO');?>
					</button>
					<button class="btn"	onclick="payplansAdmin.resetFilters(this.form);">				
						<?php echo XiText::_('PLG_PAYPLANS_APPMANAGER_BUTTON_RESET');?>
					</button>
				</div>
			</div>
		</div>
		
		<div class="span7">
			<div class="row-fuild">
				
					<select class="span3" name="filter[plugin_state]">
						<option value=""><?php echo XiText::_('PLG_PAYPLANS_APPMANAGER_FILTER_PLUGIN_STATE_SELECT');?></option>		
						<option value="installed" <?php echo (!empty($filter['plugin_state']) && $filter['plugin_state'] == 'installed') ? 'selected="selected"' : '';?>>
							<?php echo XiText::_('PLG_PAYPLANS_APPMANAGER_FILTER_PLUGIN_STATE_INSTALLED');?>
						</option>
						<option value="upgradable" <?php echo (!empty($filter['plugin_state']) && $filter['plugin_state'] == 'upgradable') ? 'selected="selected"' : '';?>>
							<?php echo XiText::_('PLG_PAYPLANS_APPMANAGER_FILTER_PLUGIN_STATE_UPGRADE');?>
						</option>
						<option value="available" <?php echo (!empty($filter['plugin_state']) && $filter['plugin_state'] == 'available') ? 'selected="selected"' : '';?>>
							<?php echo XiText::_('PLG_PAYPLANS_APPMANAGER_FILTER_PLUGIN_STATE_AVAILABLE');?>
						</option>									
						<option value="popular" <?php echo (!empty($filter['plugin_state']) && $filter['plugin_state'] == 'popular') ? 'selected="selected"' : '';?>>
							<?php echo XiText::_('PLG_PAYPLANS_APPMANAGER_FILTER_PLUGIN_STATE_POPULAR');?>
						</option>									
					</select>
		
		
					<select class="span4"  name="filter[app_catergory]">
						<option value=""><?php echo XiText::_('PLG_PAYPLANS_APPMANAGER_FILTER_CATEGORY_SELECT')?></option>
						<?php foreach($categories as $name => $title):
								$selected = '';
								if(!empty($filter['app_catergory']) && $filter['app_catergory'] == $name){
									$selected = 'selected="selected"';
								}?>
								<option value="<?php echo $name;?>" <?php echo $selected;?>><?php echo $title;?></option>
						<?php endforeach;?>
					</select>
		

					<select class="span4"  name="filter[extension_type]">
						<option value=""><?php echo XiText::_('PLG_PAYPLANS_APPMANAGER_FILTER_EXTENSION_SELECT')?></option>
						<?php foreach($extensions as $name => $title):
								$selected = '';
								if(!empty($filter['extension_type']) && $filter['extension_type'] == $name){
									$selected = 'selected="selected"';
								}?>
								<option value="<?php echo $name;?>" <?php echo $selected;?>><?php echo $title;?></option>
						<?php endforeach;?>
					</select>
				
			</div>
		</div>
</div>
</form>
<?php 

