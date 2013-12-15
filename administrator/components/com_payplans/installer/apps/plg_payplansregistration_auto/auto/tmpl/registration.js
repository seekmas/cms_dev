xi.extend({
	registration : {		
		check : function(fieldId, funcName) {
			var $field = payplans.jQuery('#'+fieldId);
	        var url = 'index.php?option=com_payplans&view=user&task='+funcName;
	        
	    	payplans.jQuery('.'+fieldId+' .badge-success').hide();
	    	payplans.jQuery('.'+fieldId+' .badge-info').show();
	    	payplans.jQuery('.'+fieldId+' .badge-warning').hide();
			  
	    	
			var args   = {'event_args':[fieldId,$field.val()]};

            payplans.ajax.go(url, args);
		},

		validate : function(fieldId, result, msg) {
			var $field = payplans.jQuery('#'+fieldId);

			payplans.jQuery('#err-'+fieldId).html(msg);
					
        	if(result == true){
        		
    	    	payplans.jQuery('.'+fieldId+' .badge-success').show();
    	    	payplans.jQuery('.'+fieldId+' .badge-info').hide();
    	    	payplans.jQuery('.'+fieldId+' .badge-warning').hide();
        		
	        	return true;
	        }
	       
	    	payplans.jQuery('.'+fieldId+' .badge-success').hide();
	    	payplans.jQuery('.'+fieldId+' .badge-info').hide();
	    	payplans.jQuery('.'+fieldId+' .badge-warning').show();
        	
		}
	}
});



payplans.jQuery(document).ready(function (){	

	
	payplans.jQuery('#payplansRegisterAutoUsername').blur(function(){
		
    	payplans.jQuery('.'+this.id+' .badge-success').hide();
    	payplans.jQuery('.'+this.id+' .badge-info').hide();
    	payplans.jQuery('.'+this.id+' .badge-warning').show();
		
		payplans.jQuery(this).trigger("submit.validation").trigger("validationLostFocus.validation");
		
		if (!payplans.jQuery(this).jqBootstrapValidation("hasErrors")) {
				xi.registration.check(this.id, 'checkusername');
		}
		
	});

	payplans.jQuery('#payplansRegisterAutoEmail').blur(function(){
		
    	payplans.jQuery('.'+this.id+' .badge-success').hide();
    	payplans.jQuery('.'+this.id+' .badge-info').hide();
    	payplans.jQuery('.'+this.id+' .badge-warning').show();
		
		payplans.jQuery(this).trigger("submit.validation").trigger("validationLostFocus.validation");
		
		if (!payplans.jQuery(this).jqBootstrapValidation("hasErrors")) {
			xi.registration.check(this.id, 'checkemail');
		}
	});
	
	payplans.jQuery('#payplansRegisterAutoPassword').blur(function(){
		
    	payplans.jQuery('.'+this.id+' .badge-success').hide();
    	payplans.jQuery('.'+this.id+' .badge-info').hide();
    	payplans.jQuery('.'+this.id+' .badge-warning').show();
    	
    	if (!payplans.jQuery(this).jqBootstrapValidation("hasErrors")) {
    		
        	payplans.jQuery('.'+this.id+' .badge-success').show();
        	payplans.jQuery('.'+this.id+' .badge-info').hide();
        	payplans.jQuery('.'+this.id+' .badge-warning').hide();			
    	}
	});
});