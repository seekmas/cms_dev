<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
* Website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();
?>
<?php if(count($results) <=0) : ?> 
		<div class="pp-message">
			<?php echo XiText::_('COM_PAYPLANS_SEARCH_NO_RESULTS_FOUND'); ?>
		</div>
<?php else: ?>
	<?php foreach($results as $record) :?>
		<div class="pp-result">
			<?php echo $record;?>
		</div> 
	<?php endforeach;?>
<?php endif; ?>
<?php 