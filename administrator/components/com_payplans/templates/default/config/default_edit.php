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
<div class="pp-config-edit">

<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form-horizontal">
	<fieldset class="form-horizontal">
		<ul class="nav nav-tabs">
		<li class="active"><a href="#settings" data-toggle="tab"><?php echo XiText::_('COM_PAYPLANS_CONFIG_SETTINGS'); ?> </a></li>
		<li><a href="#customization" data-toggle="tab"><?php echo XiText::_('COM_PAYPLANS_CONFIG_CUSTOMIZATION'); ?> </a></li>
		<li><a href="#importdata" data-toggle="tab"><?php echo XiText::_('COM_PAYPLANS_CONFIG_IMPORT_DATA'); ?> </a></li>
		<li><a href="#log_migration" data-toggle="tab"><?php echo XiText::_('COM_PAYPLANS_CONFIG_LOG_MIGRATION'); ?> </a></li>
		</ul>
		
		<div class="tab-content">
				<div class="tab-pane active" id="settings">
					<?php echo $this->loadTemplate('edit_settings'); ?>
				</div>
				
				<div class="tab-pane" id="customization">
					<?php echo $this->loadTemplate('edit_customization');?>
				</div>
				
				<div class="tab-pane" id="importdata">
					<?php $position = 'payplans-admin-config-importdata'; ?>
					<?php echo $this->loadTemplate('partial_position',compact('plugin_result','position')); ?>
				</div>
				
				<div class="tab-pane" id="log_migration">
					<?php  echo $this->loadTemplate('edit_migrate');?>
				</div>
		</div>
	</fieldset>

	<input type="hidden" name="task" value="save" />
</form>
</div>
<?php 
