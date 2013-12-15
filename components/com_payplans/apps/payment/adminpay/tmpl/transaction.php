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

	<?php foreach($transaction_html as $key => $value) :?>
			<div class="row-fluid">
				 <div class="span3"> <?php echo XiText::_('COM_PAYPLANS_APP_ADMINPAY_TRANSACTION_RECORD_'.JString::strtoupper($key)); ?></div>
				 <div class="offset1 span8"><?php echo $value;?></div>
				<input type="hidden" name="transaction[<?php echo $key;?>]" value="<?php echo $value;?>" />
			</div>
	<?php endforeach; ?>
<?php 