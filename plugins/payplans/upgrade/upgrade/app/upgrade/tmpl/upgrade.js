/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Javascript
* @contact 		shyam@readybytes.in
*/
(function($){
// START : 	
// Scoping code for easy and non-conflicting access to $.
// Should be first line, write code below this line.
if (typeof(payplans.apps)=='undefined'){
		payplans.apps = {};
}

payplans.apps.upgrade = {
		getPlansUpgradeTo : function(upgrade_from){
			// var args = Array(upgrade_from);
			var url = "index.php?option=com_payplans&view=plan&task=trigger&event=onPayplansUpgradeToRequest";
			var args   = { 'event_args' : {'subscription_id' : upgrade_from} };
			payplans.ajax.go(url, args);
		},

  		setPlansUpgradeTo : function(upgrade_to, sub_key){
			// hide current div
			// if not selected an element
			if(upgrade_to <= 0){
				return false;
			}
			//xi.jQuery('#payplans-upgrading-from').hide();
			//XITODO: create a new div automatically
			payplans.jQuery('#payplans-upgrading-from').hide();
			payplans.jQuery('#payplans-popup-upgrade-details').show();
			//var args = Array(upgrade_to, sub_key);
			var url = "index.php?option=com_payplans&view=plan&task=trigger&event=onPayplansUpgradeDisplayConfirm";
			var args   = {'event_args':[upgrade_to,sub_key]};
            payplans.ajax.go(url, args);
	
		},

     	setPlansUpgradeToCancel: function(new_order, old_sub_key){
			payplans.jQuery('#payplans-upgrading-from').show();
			payplans.jQuery('#payplans-upgrade-'+old_sub_key+'-to').html('&nbsp;');
			payplans.jQuery('#payplans-popup-upgrade-details').hide();
			//var args = Array(new_order);
			var url = "index.php?option=com_payplans&view=plan&task=trigger&event=onPayplansUpgradeToCancel";
			payplans.apps.upgrade.hideButtons();
			var data = new Array(new_order);
			payplans.ajax.go(url, data, function(){});
        },
        
		hideButtons:function(){
        	$('#button-upgrade-now').hide();
        	$('#button-upgrade-cancel').hide();
        },
        
        displayInfoButtons:function(value){
        	$('.upgrade-options').hide();
        	
        	$('#upgrade-info-'+value).show();
        	$('#upgrade-info-back').show();
        	$('#button-upgrade-now').show();
        	$('#button-upgrade-cancel').show();
        	
        	$('#upgrade-type').val(value);
        },
        
        hideInfoButtons:function(){    
        	$('.upgrade-info').hide();
        	payplans.apps.upgrade.hideButtons();
        },
        
        showUpgradeButtons:function(){
        	$('.upgrade-options').show();
        	payplans.apps.upgrade.hideButtons();
        	payplans.apps.upgrade.hideInfoButtons();
        },
        
        upgradeOrder:function(invoiceKey)
        {
		$('#button-upgrade-now').attr('disabled','disabled');
        	var type = payplans.jQuery('#upgrade-type').val();
        	var url = "index.php?option=com_payplans&view=plan&task=trigger&event=onPayplansUpgradeFromBackend";
        	var args   = {'event_args':[type,invoiceKey]};
			payplans.ajax.go(url,args);
        	
        }
};


// ENDING :
// Scoping code for easy and non-conflicting access to $.
// Should be last line, write code above this line.
})(payplans.jQuery);
