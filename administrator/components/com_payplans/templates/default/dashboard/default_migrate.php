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
if(defined('_JEXEC')===false) die();?>

<div class="pp-migrate">
	<?php foreach($results as $record) :?>
		<?php if($record !== true && $record !== false):?>
			<?php echo $record;?>
		<?php endif;?> 
	<?php endforeach;?>
</div>