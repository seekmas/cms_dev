<?php
/**
 * @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package		PayPlans
 * @subpackage	Frontend
 * @contact 		shyam@readybytes.in


 */
if(defined('_JEXEC')===false) die();
$location = $this->getLocation();
?>
<script src="<?php echo PayplansHelperUtils::pathFS2URL($location.DS.'tmpl'.DS.'upgrade.js');?>" type="text/javascript"></script>

<div class="row-fluid text-center">
	<?php $url = XiRoute::_('index.php?option=com_payplans&view=plan&task=trigger&event=onPayplansUpgradeFromRequest&arg1='.json_encode(array()));?>
	<a href="" onClick="payplans.url.modal('<?php echo $url; ?>');return false;" class="btn btn-primary"> 
		<?php echo XiText::_('COM_PAYPLANS_APP_UPGRADE_BUTTON');?>
	</a>
	
</div>
