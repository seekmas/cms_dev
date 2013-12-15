<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();
?>
<div>
<?php echo XiText::_('COM_PAYPLANS_ERROR_LOG_DETAILS');?>
</div>
<ul>
<li>
	<?php echo XiText::_('COM_PAYPLANS_ERROR_LOG_MESSAGE');?>
	<?php echo $message;?>
</li>
<li>
	<?php echo XiText::_('COM_PAYPLANS_ERROR_LOG_OBJECT_ID');?>
	<?php echo $object_id;?>
</li>
<li>
	<?php echo XiText::_('COM_PAYPLANS_ERROR_LOG_CLASS');?>
	<?php echo $class;?>
</li>
<?php if(is_array($content)):?>
	<?php foreach($content as $key => $value):?>
		<li>
			<?php echo $key.":";?>
			<?php echo $value;?>
		</li>
	<?php endforeach;?>
<?php else :?>
	<?php echo $content;?>
<?php endif;?>
</ul>
<?php 