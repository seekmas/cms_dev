xi.extend({
	preferences:{
		
		save:function(form){
			var data = xi.jQuery('form#preferences').serializeArray();
			var url = "index.php?option=com_payplans&view=user&task=trigger&event=onPayplansUserpreferencesSaveRequest";
			payplans.ajax.go(url,{'event_args': {'preference' : data}});
			xi.form.prepare('preferences');
		}
	}
});
xi.jQuery(document).ready(function(){
		xi.form.handleForm('preferences');
	});