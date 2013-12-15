<?php 
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	pdfInvoice
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

//render modules that are using this position 
?>
<div class="pp-invoice-download clearfix <?php if(count($plugin_result) != 1){echo "well";} ?>" >
<?php 
        $position = 'pp-invoice-thanks-action';
        echo $this->loadTemplate('partial_position',compact('plugin_result','position'));
        ?>
</div> 
<?php 	 