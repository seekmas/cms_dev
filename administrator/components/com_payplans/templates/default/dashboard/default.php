<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Backend
* @contact 		payplans@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();
?>
<form action="<?php echo XiRoute::_('index.php?option=com_payplans&view=dashboard', false); ?>" method="post" name="adminForm" id="adminForm" style="overflow:hidden;">
<div class="pp-dashboard-display">

	<!-- show error logs -->
	<?php if(!empty($error_logs)):	?>
		<div class="alert alert-error">
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		  <strong>Errors!</strong> Please scroll down and check the error list.
		  <a class='btn btn-link' href='#charts-details-sales'>Check Now.</a>
		</div>
	<?php endif;?>
	
	<!-- show checklist is not clean -->
	<?php if(!$clean_checklist):?>
		<div class="alert alert-warning">
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		  <?php echo XiTEXT::_('COM_PAYPLANS_DASHBOARD_SETUP_CHECKLIST_NOT_CLEAN'); ?>
		  <a class='btn btn-link' href='<?php echo XiRoute::_('index.php?option=com_payplans&view=config');?>'>Check Now.</a>		  		
		</div>
	<?php endif;?>
	
	<div class="row-fluid">
		<div class="span7">
			<div id="pp-dashboard-toolbar" class='row-fluid'>
			<?php echo $this->loadTemplate('toolbar'); ?>
			</div>
			<div id="pp-dashboard-statistics" class='row-fluid'>
				<?php  echo $this->loadTemplate('charts'); ?>
			</div>
		</div>
		<div class="span5">
            <?php $position = 'payplans-admin-dashboard-userinfo';?>
			<?php echo $this->loadTemplate('partial_position',compact('plugin_result','position'));?>
			
			<?php $position = 'payplans-admin-dashboard-useraction';?>
			<?php echo $this->loadTemplate('partial_position',compact('plugin_result','position'));?>
		</div>
	</div>
	
</div>

<input type="hidden" name="boxchecked" value="1" />
<input type="hidden" name="task" value="" />
</form>
<?php 
