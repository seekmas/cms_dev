<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die(); ?>

<div class="pp-order-action pp-prefix_1 pp-grid_10 pp-suffix_1 pp-alpha pp-omega">
		<?php 
		$position = 'pp-subscription-display-action';
		echo $this->loadTemplate('partial_position',compact('plugin_result','position'));
		?>
</div>
<?php 