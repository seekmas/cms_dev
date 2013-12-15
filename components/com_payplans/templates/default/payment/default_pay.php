<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();
?>
<div class="row-fluid">
	<h2>
		<?php echo XiText::_('COM_PAYPLANS_PAYMENT_PAY_HEADING');?>
	</h2>
	<div><hr ></div>
	
<?php foreach($result as $html):
	if(is_bool($html)==false):
		echo $html;
	endif;
endforeach;
?>

</div>