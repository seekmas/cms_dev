<?php
/**
 * @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package		PayPlans
 * @subpackage	Frontend
 * @contact 		shyam@readybytes.in


 */
if(defined('_JEXEC')===false) die();
?>

<div class="payplans-app-widget-docman">
	<ul>
		<?php foreach($docman_groups as $group):?>
			<li><a href="<?php echo XiRoute::_('index.php?option=com_docman');?>"><?php echo $group ;?></a></li>
		<?php endforeach;?>
	</ul>
</div>
