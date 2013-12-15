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
<div class='span12 clearfix'>
	<div class="input-append pull-left">
	    <?php echo PayplansHtml::_('daterange.edit', 'statistics-date', 'statistics-date', $currentFirstDate->toFormat('%Y-%m-%d').':'.$currentLastDate->toFormat('%Y-%m-%d'));?>
		<button id='update-statistics-date' class='btn' type="button" onClick='payplans.admin.dashboard.customDateStatistics(); return false;'>Go</button>
	</div>
	
</div>

<?php 