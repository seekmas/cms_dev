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
<div class="pp-invoice-thanks">
<h2 class="componentheading pp-primary pp-color pp-border pp-background">
	<?php echo XiText::_('COM_PAYPLANS_PAYMENT_SUCCESS'); ?>	
</h2>

<?php if(!empty($redirecturl)):?>
     <script type="text/javascript">
        window.onload = function(){
            setTimeout("payplans.url.redirect('<?php echo XiRoute::_($redirecturl); ?>')", 3000);
        }
    </script>
<?php endif;?>

	<div>
		<?php echo $this->loadTemplate('partial_invoice', compact('invoice', 'user')); ?>
	</div>
</div>
<?php 
