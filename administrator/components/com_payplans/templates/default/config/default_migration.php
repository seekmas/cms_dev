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

	if('inProcess' == $action || 'start' == $action) { 
	?>
	<div class="row-fluid text-center pp-gap-top10">
		<div class="span12 text-error"><?php echo XiText::_('COM_PAYPLANS_LOG_MIGRATION_MSG_DONOT_CLOSE');?></div>
	    <div class="progress progress-striped active span11">
	    	<div class="bar" style="width: <?php echo $progress;?>%;"></div>
	    </div>
	    <div class="span11 loading"></div>
	    <div class="span11">
		   <h5> <span id="pp-rebuild-progress-count">
		    	<?php echo XiText::sprintf('COM_PAYPLANS_LOG_MIGRATION_INPROCESS',$exeCount,$migrate_total);?>
		    </span></h5>
	    </div>
	</div>
	<?php 
	//When action is complete then creates contents for complete dialog box.
	}
	elseif('complete' == $action){?>
		<div class="text-center"><b><?php echo XiText::_('COM_PAYPLANS_LOG_MIGRATION_COMPLETED');?></b></div>
	<?php 		
	}
	?>
