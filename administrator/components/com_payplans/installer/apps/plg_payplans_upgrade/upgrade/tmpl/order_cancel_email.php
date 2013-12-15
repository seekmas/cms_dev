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
<div><?php echo $message;?>
<?php foreach($parameters as $parameter=>$value):?>
<?php echo $parameter.' : '. $value.'<br>';?>
<?php endforeach;?>
</div>
<?php 