/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	UI
* @contact 		payplans@readybytes.in
*/	

/*-----------------------------------------------------------
  Javascript writing standards - 
  - Pack you code in 
  		(function($){
  				// Your code here
  				// Your code here
  		})(xi.jQuery); 
  		
  	and use $ as usually.
-----------------------------------------------------------*/
if (typeof(xi)=='undefined')
{
	var xi = {
		jQuery: window.jQuery,
		extend: function(obj){
			this.jQuery.extend(this, obj);
		}
	}
}


(function($){
// START : 	
// Scoping code for easy and non-conflicting access to $.
// Should be first line, write code below this line.	
	

/*--------------------------------------------------------------
  UI related works
  xi.ui.dialog.create  = create a dialog, fill with ajax data
  xi.ui.dialog.button  = add buttons on dialog
  xi.ui.dialog.title   = set title
  xi.ui.dialog.height  = set height
  xi.ui.dialog.close   = close dialog  
--------------------------------------------------------------*/
xi.ui = {};
xi.ui.dialog = { 
	create : function(call, winTitle, winContentWidth, winContentHeight){
		
		//close dialog if any other has also been opened in backgroud
	 	xi.ui.dialog.close();
		// create a empty-div & show a dialog
		$('#xiWindowContent').remove();

		//XITODO : loading class required or not
		$('<div id="xiWindowContent" class="modal hide fade loading" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>').addClass('new').appendTo('.payplans-wrap');

		// add title, body and footer block so that sequence can be maintained
		
		$('<div id="xiWindowTitle" class="modal-header"></div>').appendTo('#xiWindowContent');
		$('<div id="xiWindowBody"  class="modal-body"></div>').appendTo('#xiWindowContent');
		$('<div id="xiWindowFooter" class="modal-footer"></div>').appendTo('#xiWindowContent');

		if(winTitle == null) winTitle = 'Title';
		if(winContentWidth != null) this.width(winContentWidth);
		if(winContentHeight != null) this.height(winContentHeight);
		
		// set the modal attributes
		this.title(winTitle);
		//this.height(winContentHeight);
		//this.width(winContentWidth);

		// show the modal
		$('#xiWindowContent').modal('show');

		// on hiding the popup, remove the div#xiWindowContent also 
		$('#xiWindowContent').on('hidden', function(){
			$('#xiWindowContent').remove();
		});

		// call ajax
		xi.ajax.go(call.url, call.data);
	},

	button : function(actions){
		// empty previous action buttons
		$('#xiWindowFooter').html('');

		for(var i=0;i<actions.length;i++) {

			if(typeof actions[i].classes === "undefined"){ 
				actions[i].classes = '';
			} 
			
			if(typeof actions[i].id === "undefined"){ 
				actions[i].id = '';
			}
			
			if(typeof actions[i].attr === "undefined"){ 
				actions[i].attr = '';
			}
				
			actions[i].click = eval("(function(){" + actions[i].click + ";})" );
			var button = '<button class="'+actions[i].classes+'" id="'+actions[i].id+'" '+actions[i].attr+'>'+actions[i].text+'</button>';
			$(button).bind('click', actions[i].click).appendTo('#xiWindowFooter');			
		}		
	},

	body : function(body){
		// empty previous body content
		$('#xiWindowBody').html('');
		if(body != null && body.length > 0){
			$('<p>'+body+'</p>').appendTo('#xiWindowBody');	
		}
	},

	title : function(title){
		// empty previous title
		$('#xiWindowTitle').html('');

		
		// show the header in case of title is not empty
		if(title != null && title.length > 0){
			$('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="myModalLabel">'+title+'</h3>')
				.appendTo('#xiWindowTitle');	
		}
	},

	close : function(title){
		$('#xiWindowContent').modal('hide');
	},

	height : function(height){
		$('#xiWindowBody').css("height", height);
	},

	width : function(width){
		$('#xiWindowContent').css("width", width);
	},

	autoclose : function($time){
		setTimeout(function(){
			$('#xiWindowContent').modal('hide')
		}, $time);
	}
};

/*--------------------------------------------------------------
  URL related work
--------------------------------------------------------------*/
xi.route = {
	url : function(url){
				// already a complete URL
				if(url.indexOf('http://') === -1){
						// is it already routed URL without http ?
					  var base2_url_index = url.indexOf(xi_vars['url']['base_without_scheme']);
					  // only add if, its not routed URL
					  if(base2_url_index === -1 ){
						  url = xi_vars['url']['base'] + url;
					  }
				}
				
				return url;
	}
};

/*--------------------------------------------------------------
  Ajax related works
--------------------------------------------------------------*/

xi.ajax = {	
		
	//XITODO : replace via jQuery code
	create : function(sParentId, sTag, sId){
		var objParent = this.$(sParentId);
		objElement = document.createElement(sTag);
		objElement.setAttribute('id',sId);
		if(objParent){
			objParent.appendChild(objElement);
		}
	},

	remove : function(sId){
		$(sId).remove();
	},

	default_error_callback : function (error){
		//XIFW_TODO : log to console
		alert("An error has occured\n"+error);
	},

	default_success_callback : function (result){

		//XITODO : log to console

		// we now have an array, that contains an array.
		for(var i=0; i<result.length;i++){

			var cmd 		= result[i][0];
			var id			= result[i][1];
			var property 	= result[i][2];
			var data 		= result[i][3];

			switch(cmd){
			case 'as': 	// assign or clear
				var objElement = $(id);
				if(objElement){
					if(property == 'innerHtml' || property == 'innerHTML'){
						$('#'+id).html(data);
					}else if(property == 'replaceWith'){
						$('#'+id).replaceWith(data);
					}else{
						eval("objElement."+property+"=  data \; ");
					}
				}

				break;

			case 'al':	// alert
				if(data){
					alert(data);}
				break;

			case 'ce':
				xi.ajax.create(id,property, data);
				break;

			case 'rm':
				xi.ajax.remove(id);
				break;

			case 'cs':	// call script
				var scr = id + '(';
				if($.isArray(data)){
					scr += '(data[0])';
					for (var l=1; l<data.length; l++) {
						scr += ',(data['+l+'])';
					}
				} else {
					scr += 'data';
				}
				scr += ');';
				eval(scr);
				break;

			default:
				alert("Unknow command: " + cmd);
			}
		}
	},

	error : function(Request, textStatus, errorThrown, errorCallback) {
		var message = '<strong>AJAX Loading Error</strong><br/>HTTP Status: '+Request.status+' ('+Request.statusText+')<br/>';
		message = message + 'Internal status: '+textStatus+'<br/>';
		message = message + 'XHR ReadyState: ' + Request.readyState + '<br/>';
		message = message + 'Raw server response:<br/>'+Request.responseText;
		errorCallback(message);	
	},
	
	success : function(msg, successCallback, errorCallback) {
		// Initialize
		var junk = null;
		var message = "";
		
		// Get rid of junk before the data
		var valid_pos = msg.indexOf('###');
		var valid_last_pos = msg.lastIndexOf('###');
		if( valid_pos == -1 ) {
			// Valid data not found in the response
			msg = 'Invalid AJAX data: ' + msg;
			errorCallback(msg);
			return;
		}
		
		// get message between ###<----->### second argument is length
		message = msg.substr(valid_pos+3, valid_last_pos-(valid_pos+3)); 
		
		try {
			var data = JSON.parse(message);
		}catch(err) {
			var msg = err.message + "\n<br/>\n<pre>\n" + message + "\n</pre>";
			errorCallback(msg);
			return;
		}
		
		// Call the callback function
		successCallback(data);
	},

	/*
	 * url : URL to call
	 * data : array / json / string / object
	 * */
	go : function (url, data, successCallback, errorCallback, timeout){
		
		if(data != null && data.iframe == true){
			var call = {data:data, url:url}; 
			return xi.iframe.show(call);	
		}
		// timeout 60 seconds
		if(timeout == null) timeout = 600000;
		if(errorCallback == null) errorCallback = xi.ajax.default_error_callback;
		if(successCallback == null) successCallback = xi.ajax.default_success_callback;

		// properly oute the url
		ajax_url = xi.route.url(url) + '&isAjax=true';
	
		//execute ajax
		// in jQ1.5+ first argument is url
		$.ajax(ajax_url, {
			type	: "POST",
			cache	: false,
			data	: data,
			timeout	: timeout,
			success	: function(msg){ xi.ajax.success(msg,successCallback,errorCallback); },
			error	: function(Request, textStatus, errorThrown){xi.ajax.error(Request, textStatus, errorThrown, errorCallback);}
		});
	}
};



xi.iframe = {

	show:function (call, appendTo,onLoadCallback){
		
		if(onLoadCallback == null) onLoadCallback = this.process;
		if(appendTo == null) appendTo = '#xiWindowBody';
		
		if(typeof call.data.classes === "undefined"){ 
			call.data.classes = '';
		} 
		
		if(typeof call.data.id === "undefined"){ 
			call.data.id = '';
		}
		
		$iframe = $('<iframe id="'+call.data.id+'" class="span12 '+call.data.classes+'" frameborder="0" scrolling="auto" height="90%">');
		$iframe.load(onLoadCallback).appendTo(appendTo);

		// properly output the url
		url = xi.route.url(call.url);
		
		//url += '&' + $.param(call.data);
		$iframe.attr('src',url);
		return $iframe;
	},
	
	process : function(){
		
	}
};



/*---------------------------------------------------------
Joomla function available through xi.cms framework 
---------------------------------------------------------*/
xi.joomla = {};

xi.joomla.text = {
	// string holder
	strings: {
	},
	
	// translate
	"_": function(key, def) {
		return typeof this.strings[key.toUpperCase()] !== "undefined" ? this.strings[key.toUpperCase()] : def;
	},
	
	// add all keys
	load: function(object) {
		for (var key in object) {
			this.strings[key.toUpperCase()] = object[key];
		}
		return this;
	}
};

/*---------------------------------------------------------
	Javascript interface to underline framework 
---------------------------------------------------------*/
xi.cms = xi.joomla;


//Document ready
$(document).ready(function(){

	// load translation
	xi.cms.text.load(xi_strings);
});

//ENDING :
//Scoping code for easy and non-conflicting access to $.
//Should be last line, write code above this line.
})(xi.jQuery);

