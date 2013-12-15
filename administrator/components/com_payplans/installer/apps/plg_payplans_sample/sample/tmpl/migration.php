<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Modules
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die(); ?>

<div class="pp-migrate-started">
	<?php echo XiText::_('PLG_PAYPLANS_SAMPLE_MIGRATION_STARTED');?>
</div>
<div class="pp-migrate-progress-message"> <span id="pp-migrate-progress-message"> &nbsp; </span></div>

<div class="progress-bar"> 
	<div id="pp-migrate-progress-bar" class="progress-bar-inner orange"></div>
</div>


<div class="pp-migrate-progress-count"> <?php echo XiText::_('PLG_PAYPLANS_SAMPLE_IMPORT_DATA_PROCESSED')?><span id="pp-migrate-progress-count">&nbsp; </span> of <?php echo $record_count; ?> </div>