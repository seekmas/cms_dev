<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	
* @contact 		payplans@readybytes.in
* Website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
	if(defined('_JEXEC')===false) die();
	$action = JRequest::getVar('action', false); 

	//When action is start then creates content for information dialog box
	if('start' == $action) {
	?>
		<div  class="text-center">	
				<div><?php echo XiText::_('COM_PAYPLANS_DASHBOARD_REBUILD_START_MSG');?></div>
				<div><?php echo XiText::_('COM_PAYPLANS_DASHBOARD_REBUILD_START_MSG_NOTE')?></div><br>
				<div><?php echo XiText::sprintf('COM_PAYPLANS_DASHBOARD_REBUILD_BEFORE_START_MSG_NUMBER_OF_DAYS_STRING', $rebuild_total);?></div>
		</div> 
	<?php 
	//When action is do then creates content for progress dialog box such as progress bar, how much days are processed,etc.
	}
	elseif('do' == $action) { 
	?>
	<div class="row-fluid text-center pp-gap-top10">
		<div class="span12 text-warning"><?php echo XiText::_('COM_PAYPLANS_DASHBOARD_REBUILD_MSG_DONOT_CLOSE');?></div>
	    <div class="progress progress-striped active span11">
	    	<div class="bar" style="width: <?php echo $progress;?>%;"></div>
	    </div>
	    <div class="span11">
		    <span id="pp-rebuild-progress-count">
		    	<?php echo XiText::sprintf('COM_PAYPLANS_DASHBOARD_REBUILD_PROGRESS',$exeCount,$rebuild_total);?>
		    </span>
	    </div>
	</div>
	<?php 
	//When action is complete then creates contents for complete dialog box.
	}
	elseif('complete' == $action){?>
		<div class="text-center"><b><?php echo XiText::_('COM_PAYPLANS_DASHBOARD_REBUILD_COMPLETED');?></b></div>
	<?php 		
	}
	?>
