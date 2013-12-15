<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Frontend
* @contact 		Payplans@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();

?>
<script type="text/javascript">
	payplans.jQuery(document).ready(function(){
		if(payplans.jQuery('.nav-tabs').find('li[class=active]').length){
			return ;
		}
		else{
			payplans.jQuery('.nav-tabs').find('li').attr('class','active');
			payplans.jQuery('.tab-content').find('.tab-pane').attr('class','active');
		}
	});
</script>
<?php 
echo '<ul class="nav nav-tabs">';
       
if(isset($plugin_result) && isset($plugin_result['payplans-admin-reports-tabs']) && isset($plugin_result['payplans-admin-reports-contents']))
{
	
	echo $plugin_result['payplans-admin-reports-tabs'];

	echo "</ul><div class='tab-content'>";
	
	echo $plugin_result['payplans-admin-reports-contents'];
	
}   
 echo "</div>" ;
