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
<?php if(isset($name)=== false) $name = $param['name']; ?>

<?php $class = $name.$param[5]; ?>
<div class="pp-row <?php echo XiHelperUtils::jsCompatibleId($class);?>">
	<?php if ($param[0] && $param[0] != '&nbsp;'): ?>
		<div class="pp-col pp-label pp-grid_3">
			<?php echo $param[0]; ?>
		</div>
		<div class="pp-col pp-input pp-grid_9">
			<?php echo $param[1]; ?>
			<?php if(isset($subparam)) echo '<br />', $subparam;?>
		</div>
	<?php else: ?>
		<div class="pp-description pp-grid_12">
			<?php echo $param[1]; ?>
		</div>
	<?php endif; ?>
</div>
<?php 
