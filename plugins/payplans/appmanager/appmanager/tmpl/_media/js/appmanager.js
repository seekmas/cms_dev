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
if (typeof(payplans.plg)=='undefined'){
	payplans.plg = {};
}

payplans.plg.appmanager = {
		install: function(app_folder, app_element, extension_type, client_id, action){
				
					var url = 'index.php?option=com_payplans&view=appmanager&task=install&app_folder='+app_folder+'&app_element='+app_element+'&app_action='+action+'&extension_type='+extension_type+'&client_id='+client_id;
					var args = {};		
					$('.pp-app-running-status-'+app_folder+'-'+app_element).addClass('loading').html(xi.cms.text._('PLG_PAYPLANS_APPMANAGER_JS_MANAGE_INSTALLING', 'Installing.....'));
					
					var selector = '#pp-install'+'-'+app_folder+'-'+app_element;
					if(action == 'upgrade'){
						selector = '#pp-upgrade'+'-'+app_folder+'-'+app_element;
					}
					
					$(selector).parent().html('<div class="loading" id="pp-install'+'-'+app_folder+'-'+app_element+'">'+xi.cms.text._('PLG_PAYPLANS_APPMANAGER_JS_MANAGE_INSTALLING', 'Installing.....')+'</div>');
					
					payplans.ajax.go(url, args);
					return false;
		},
		
		install_response : function(app_folder, app_element, response,url){
					response = JSON.parse(response);
					var tracking_value = -1;	
					var teaser 		   = xi.cms.text._('PLG_PAYPLANS_APPMANAGER_JS_MANAGE_RESPONSE_ERROR', 'Error');
					var teaserClass	   = "error";
					//success : change install button to uninstall
					if(response.response_code == 200){
						teaser 		   = xi.cms.text._('PLG_PAYPLANS_APPMANAGER_JS_MANAGE_RESPONSE_SUCCESS', 'SUCCESS');
						tracking_value = +1;
						teaserClass    = "success";
						var id 		   = "#pp-install-"+app_folder+"-"+app_element;
						var uninstallbutton = "<button class='btn btn-danger' id=\"pp-uninstall-"+app_folder+"-"+app_element+"\"onclick=\"payplans.url.modal ('"+url+"'); return false;\" > Un-install </button>";
											
						$('#pp-install'+'-'+app_folder+'-'+app_element).parent().html(uninstallbutton);
					}
					//error
					else{
						$('#pp-install'+'-'+app_folder+'-'+app_element).parent().html(xi.cms.text._('PLG_PAYPLANS_APPMANAGER_JS_MANAGE_ERROR_CODE_'+response.error_code, response.error_code)).removeClass('loading').addClass('pp-appmanager-error');

						//opening a popup to open credential if installation error is becasue of improper credentials
						var url = "index.php?option=com_payplans&view=appmanager&task=credential&error="+xi.cms.text._('PLG_PAYPLANS_APPMANAGER_JS_MANAGE_ERROR_CODE_'+response.error_code, response.error_code);
						payplans.url.modal(url);

					}
					//stop the wheel and show teaser msg with appropriate css class
					$('.pp-app-running-status-'+app_folder+'-'+app_element).removeClass('loading').addClass("pp-appmanager-"+teaserClass).html(teaser);
					
					//tracking of install is doen here
					_trackAppInstallation('install',app_element,tracking_value);
					
					return true;
		},
		
		set_credential : function(){
					$('.pp-appmanager-error').html('');
					$('.pp-appmanager-credential-err').html('&nbsp;');
					$('.processing-request').addClass('loading');
					
					var username = $('#jpayplansUsername').val();
					var password = $('#jpayplansPassword').val();
					var url = 'index.php?option=com_payplans&view=appmanager&task=credential&action=set';
					var args   = { 'event_args' : {'username' : username, 'password' : password} };
					payplans.ajax.go(url, args);
		},
		
		show_success_message : function(){
			var successtext = xi.cms.text._('PLG_PAYPLANS_APPMANAGER_JS_SUCCESS_MESSAGE','Your credentials are stored successfully');
			$('.pp-appmanager-credential-err').html(successtext).removeClass('invalid').addClass('text-success');	
			$('.processing-request').removeClass('loading');	
			return true;
		},
		
		//tracking of uninstall is done here
		tracking : function(appname,apptype,extension_type,client_id,response)
		{
			//success : change button from uninstall to install button
			if(response == 1)
			{
				var unid = "#pp-uninstall-"+apptype+"-"+appname;
				var id 	 = "pp-install-"+apptype+"-"+appname;
				var data = "return payplans.plg.appmanager.install('"+apptype+"', '"+appname+"','"+extension_type+"','"+client_id+"', 'install');";
				$(unid).removeClass('btn-danger').addClass("btn-success").html('Install');
				$(unid).attr('onclick',data);
				$(unid).attr('id',id);
			}
			
			_trackAppInstallation('uninstall',appname,response);
			return true;
		}
};


$(document).ready(function(){

	$('.pp-appmanager-filter select').change(function(){
		document.adminForm.submit();
	});	

	$('#error-message-body').bind('closed', function () {
		var url = 'index.php?option=com_payplans&view=appmanager&task=removeUpgradeMessage&action=hide';
		payplans.ajax.go(url);
	});
	
	$('#description-message-body').bind('closed', function () {
		var url = 'index.php?option=com_payplans&view=appmanager&task=removeUpgradeMessage&action=hide';
		payplans.ajax.go(url);
	});
	
	$("img.lazyimages").lazyload();
	
	$('.pp-app-block').hover(function() {
		$(this).children('.pp-app-block-front').stop().animate({ "top" : '-250px'}, 300);
		$(this).children('.pp-app-block-back').stop().animate({ "top" : '0px'}, 300);
		//payplans.jQuery(this).children('.pp-app-block-front').stop().animate({"opacity" : '0'}, 200);
		//payplans.jQuery(this).children('.pp-app-block-back').stop().animate({"opacity" : '100'}, 200);   
    }, function() {
    	$(this).children('.pp-app-block-front').stop().animate({ "top" : '0px'}, 300);
    	$(this).children('.pp-app-block-back').stop().animate({ "top" : '250px'}, 300);   
    	//	payplans.jQuery(this).children('.pp-app-block-front').stop().animate({"opacity" : '100'}, 200);
		//payplans.jQuery(this).children('.pp-app-block-back').stop().animate({"opacity" : '0'}, 200);    
    });

	$("select").change(function () {
		$('#adminForm').submit();
	});

});	


function _trackAppInstallation(action,label,value)
{
	var tracker = null;
	if(tracker == null){
		var base = "http://pub.jpayplans.com/appvilletracking.html";
		var url = base+"?action="+action+"&label="+label+"&value="+value;
		payplans.jQuery('#appvillebanner').attr('src',url);
	}
	return true;
}


// ENDING :
// Scoping code for easy and non-conflicting access to $.
// Should be last line, write code above this line.
})(payplans.jQuery);