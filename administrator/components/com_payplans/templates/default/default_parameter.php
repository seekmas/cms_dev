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
<?php  $class = isset($class) ? $class : ''; ?>
<?php  $tooltip = isset($tooltip) ? $tooltip : ''; ?>
<div class="control-group <?php echo $class;?>">
		<div class="control-label hasTip" title="<?php echo $tooltip;?>">
			<?php echo $label; ?>
		</div>
		<div class="controls">
			<?php echo $input; ?>
		</div>
</div>
<?php 
