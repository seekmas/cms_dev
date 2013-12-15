<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();
?>
<div class="payplans">
<div class="pp-admin-search">
	<form id="pp-search-box-form" action="" method="post" name="searchBox" >
		<div class="pp-inner">
			<div class="pp-row">
				<div class="pp-col pp-input pp-prefix_3">
					<input id="pp-search-box-form-search-text" class="inputbox" type="text" autocomplete="off" 
						onkeyup="return payplansAdmin.searchBoxKeyUp(event)" 
						onkeypress="return payplansAdmin.searchBoxKeyDown(event)" 
						title="<?php echo XiText::_('COM_PAYPLANS_MOD_SEARCH_TOOLTIP');?>" 
						name="payplans_search_text"  alt="payplans_search_text" size="30" 
					/>
				</div>
			</div>
		</div>
	</form>
	<div id="payplans-search-results" class="pp-search-results"></div>		
</div>
</div>
<?php 