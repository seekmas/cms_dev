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
<?php if(!empty($transaction_html)):?>
	<?php foreach($transaction_html as $key => $value) :?>
	
		<div class="row-fluid">
			<div class="span6"><?php echo $key;?></div>
 	     	<div class="span6"><?php echo $value;?></div>
 	     </div>
	
      	<?php endforeach;?>
<?php endif;?>
<?php
      
	      