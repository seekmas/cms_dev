/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Javascript
* @contact 		payplans@readybytes.in
*/
(function($){
// START : 	
// Scoping code for easy and non-conflicting access to $.
// Should be first line, write code below this line.
if (typeof(payplans.apps)=='undefined'){
	payplans.apps = {};
}

payplans.apps.email = {
	preview:function(){
	
		if(payplans.jQuery("#Payplans_form_app_id").val() == 0)
		{
			alert("To see the preview, first save the app.");
			return true;
		}
		
		var selectedtemplate = payplans.jQuery("#Payplans_form_app_params_choose_template option:selected").val();
		
		if(selectedtemplate == -1 || selectedtemplate == ''){
			return true;
		}
		var theurl = 'index.php?option=com_payplans&view=app&task=trigger&event=getTemplatedata&template='+selectedtemplate;
		
		var windowtitle = 'Preview for '+selectedtemplate;
		
		xi.ui.dialog.create(
			{url:theurl, data:{iframe:true}},windowtitle,750, 550);
		
		xi.ui.dialog.button(
			[
				{
					click : 'xi.ui.dialog.close();',  
					text  : 'close',
					classes : 'btn'
				}
			]
		);
		
	}
}

payplans.jQuery(document).ready(function (){
	payplans.jQuery("#Payplans_form_app_params_choose_template").change(function(){

		payplans.apps.email.preview();
	});
});

// ENDING :
// Scoping code for easy and non-conflicting access to $.
// Should be last line, write code above this line.
})(payplans.jQuery);