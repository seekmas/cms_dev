
if(typeof(xi)=='undefined')
{var xi={jQuery:window.jQuery,extend:function(obj){this.jQuery.extend(this,obj);}}}
var JSON;if(!JSON){JSON={};}
(function(){'use strict';function f(n){return n<10?'0'+n:n;}
if(typeof Date.prototype.toJSON!=='function'){Date.prototype.toJSON=function(key){return isFinite(this.valueOf())?this.getUTCFullYear()+'-'+
f(this.getUTCMonth()+1)+'-'+
f(this.getUTCDate())+'T'+
f(this.getUTCHours())+':'+
f(this.getUTCMinutes())+':'+
f(this.getUTCSeconds())+'Z':null;};String.prototype.toJSON=Number.prototype.toJSON=Boolean.prototype.toJSON=function(key){return this.valueOf();};}
var cx=/[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,escapable=/[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,gap,indent,meta={'\b':'\\b','\t':'\\t','\n':'\\n','\f':'\\f','\r':'\\r','"':'\\"','\\':'\\\\'},rep;function quote(string){escapable.lastIndex=0;return escapable.test(string)?'"'+string.replace(escapable,function(a){var c=meta[a];return typeof c==='string'?c:'\\u'+('0000'+a.charCodeAt(0).toString(16)).slice(-4);})+'"':'"'+string+'"';}
function str(key,holder){var i,k,v,length,mind=gap,partial,value=holder[key];if(value&&typeof value==='object'&&typeof value.toJSON==='function'){value=value.toJSON(key);}
if(typeof rep==='function'){value=rep.call(holder,key,value);}
switch(typeof value){case'string':return quote(value);case'number':return isFinite(value)?String(value):'null';case'boolean':case'null':return String(value);case'object':if(!value){return'null';}
gap+=indent;partial=[];if(Object.prototype.toString.apply(value)==='[object Array]'){length=value.length;for(i=0;i<length;i+=1){partial[i]=str(i,value)||'null';}
v=partial.length===0?'[]':gap?'[\n'+gap+partial.join(',\n'+gap)+'\n'+mind+']':'['+partial.join(',')+']';gap=mind;return v;}
if(rep&&typeof rep==='object'){length=rep.length;for(i=0;i<length;i+=1){if(typeof rep[i]==='string'){k=rep[i];v=str(k,value);if(v){partial.push(quote(k)+(gap?': ':':')+v);}}}}else{for(k in value){if(Object.prototype.hasOwnProperty.call(value,k)){v=str(k,value);if(v){partial.push(quote(k)+(gap?': ':':')+v);}}}}
v=partial.length===0?'{}':gap?'{\n'+gap+partial.join(',\n'+gap)+'\n'+mind+'}':'{'+partial.join(',')+'}';gap=mind;return v;}}
if(typeof JSON.stringify!=='function'){JSON.stringify=function(value,replacer,space){var i;gap='';indent='';if(typeof space==='number'){for(i=0;i<space;i+=1){indent+=' ';}}else if(typeof space==='string'){indent=space;}
rep=replacer;if(replacer&&typeof replacer!=='function'&&(typeof replacer!=='object'||typeof replacer.length!=='number')){throw new Error('JSON.stringify');}
return str('',{'':value});};}
if(typeof JSON.parse!=='function'){JSON.parse=function(text,reviver){var j;function walk(holder,key){var k,v,value=holder[key];if(value&&typeof value==='object'){for(k in value){if(Object.prototype.hasOwnProperty.call(value,k)){v=walk(value,k);if(v!==undefined){value[k]=v;}else{delete value[k];}}}}
return reviver.call(holder,key,value);}
text=String(text);cx.lastIndex=0;if(cx.test(text)){text=text.replace(cx,function(a){return'\\u'+
('0000'+a.charCodeAt(0).toString(16)).slice(-4);});}
if(/^[\],:{}\s]*$/.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,'@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,']').replace(/(?:^|:|,)(?:\s*\[)+/g,''))){j=eval('('+text+')');return typeof reviver==='function'?walk({'':j},''):j;}
throw new SyntaxError('JSON.parse');};}}());(function($){$.timeago=function(timestamp){if(timestamp instanceof Date){return inWords(timestamp);}else if(typeof timestamp==="string"){return inWords($.timeago.parse(timestamp));}else{return inWords($.timeago.datetime(timestamp));}};var $t=$.timeago;$.extend($.timeago,{settings:{refreshMillis:60000,allowFuture:true,strings:{prefixAgo:null,prefixFromNow:null,suffixAgo:"ago",suffixFromNow:"from now",seconds:"less than a minute",minute:"about a minute",minutes:"%d minutes",hour:"about an hour",hours:"about %d hours",day:"a day",days:"%d days",month:"about a month",months:"%d months",year:"about a year",years:"%d years",numbers:[]}},inWords:function(distanceMillis){var $l=this.settings.strings;var prefix=$l.prefixAgo;var suffix=$l.suffixAgo;if(this.settings.allowFuture){if(distanceMillis<0){prefix=$l.prefixFromNow;suffix=$l.suffixFromNow;}
distanceMillis=Math.abs(distanceMillis);}
var seconds=distanceMillis/1000;var minutes=seconds/60;var hours=minutes/60;var days=hours/24;var years=days/365;function substitute(stringOrFunction,number){var string=$.isFunction(stringOrFunction)?stringOrFunction(number,distanceMillis):stringOrFunction;var value=($l.numbers&&$l.numbers[number])||number;return string.replace(/%d/i,value);}
var words=seconds<45&&substitute($l.seconds,Math.round(seconds))||seconds<90&&substitute($l.minute,1)||minutes<45&&substitute($l.minutes,Math.round(minutes))||minutes<90&&substitute($l.hour,1)||hours<24&&substitute($l.hours,Math.round(hours))||hours<48&&substitute($l.day,1)||days<30&&substitute($l.days,Math.floor(days))||days<60&&substitute($l.month,1)||days<365&&substitute($l.months,Math.floor(days/30))||years<2&&substitute($l.year,1)||substitute($l.years,Math.floor(years));return $.trim([prefix,words,suffix].join(" "));},parse:function(iso8601){var s=$.trim(iso8601);s=s.replace(/\.\d\d\d+/,"");s=s.replace(/-/,"/").replace(/-/,"/");s=s.replace(/T/," ").replace(/Z/," UTC");s=s.replace(/([\+\-]\d\d)\:?(\d\d)/," $1$2");return new Date(s);},datetime:function(elem){var isTime=$(elem).get(0).tagName.toLowerCase()==="time";var iso8601=isTime?$(elem).attr("datetime"):$(elem).attr("title");return $t.parse(iso8601);}});$.fn.timeago=function(){var self=this;self.each(refresh);var $s=$t.settings;if($s.refreshMillis>0){setInterval(function(){self.each(refresh);},$s.refreshMillis);}
return self;};function refresh(){var data=prepareData(this);if(!isNaN(data.datetime)){$(this).text(inWords(data.datetime));}
return this;}
function prepareData(element){element=$(element);if(!element.data("timeago")){element.data("timeago",{datetime:$t.datetime(element)});var text=$.trim(element.text());if(text.length>0){element.attr("title",text+' UTC');}}
return element.data("timeago");}
function inWords(date){return $t.inWords(distance(date));}
function distance(date){return(new Date().getTime()-date.getTime());}
document.createElement("abbr");document.createElement("time");}(xi.jQuery));(function($){var instances=[];$.fn.editableSelect=function(options){var defaults={bg_iframe:false,onSelect:false,items_then_scroll:10,case_sensitive:false};var settings=$.extend(defaults,options);if(settings.bg_iframe&&!$.browser.msie){settings.bg_iframe=false;};var instance=false;$(this).each(function(){var i=instances.length;if($(this).data('editable-selecter')!==null){instances[i]=new EditableSelect(this,settings);$(this).data('editable-selecter',i);};});return $(this);};$.fn.editableSelectInstances=function(){var ret=[];$(this).each(function(){if($(this).data('editable-selecter')!==null){ret[ret.length]=instances[$(this).data('editable-selecter')];};});return ret;};var EditableSelect=function(select,settings){this.init(select,settings);};EditableSelect.prototype={settings:false,text:false,select:false,select_width:0,wrapper:false,list_item_height:20,list_height:0,list_is_visible:false,hide_on_blur_timeout:false,bg_iframe:false,current_value:'',init:function(select,settings){this.settings=settings;this.wrapper=$(document.createElement('div'));this.wrapper.addClass('xi-editable-select-options');this.select=$(select);this.text=$('<input type="text">');this.text.attr('name',this.select.attr('name'));this.text.data('editable-selecter',this.select.data('editable-selecter'));this.select.attr('disabled','disabled');this.text[0].className=this.select[0].className;var id=this.select.attr('id');if(!id){id='editable-select'+instances.length;};this.text.attr('id',id);this.text.attr('autocomplete','off');this.text.addClass('xi-editable-select');this.select.attr('id',id+'_hidden_select');this.select.after(this.text);if(this.select.css('display')=='none'){this.text.css('display','none');}
if(this.select.css('visibility')=='hidden'){this.text.css('visibility','visibility');}
this.select.css('visibility','hidden');this.select.hide();this.initInputEvents(this.text);this.duplicateOptions();this.setWidths();$(document.body).append(this.wrapper);if(this.settings.bg_iframe){this.createBackgroundIframe();};},duplicateOptions:function(){var context=this;var option_list=$(document.createElement('ul'));this.wrapper.append(option_list);var options=this.select.find('option');options.each(function(){if($(this).attr('selected')){context.text.val($(this).val());context.current_value=$(this).val();};var li=$('<li>'+$(this).val()+'</li>');context.initListItemEvents(li);option_list.append(li);});this.checkScroll();},checkScroll:function(){var options=this.wrapper.find('li');if(options.length>this.settings.items_then_scroll){this.list_height=this.list_item_height*this.settings.items_then_scroll;this.wrapper.css('height',this.list_height+'px');this.wrapper.css('overflow','auto');}else{this.wrapper.css('height','auto');this.wrapper.css('overflow','visible');};},addOption:function(value){var li=$('<li>'+value+'</li>');var option=$('<option>'+value+'</option>');this.select.append(option);this.initListItemEvents(li);this.wrapper.find('ul').append(li);this.setWidths();this.checkScroll();},initInputEvents:function(text){var context=this;var timer=false;$(document.body).click(function(){context.clearSelectedListItem();context.hideList();});text.focus(function(){context.showList();context.highlightSelected();}).click(function(e){e.stopPropagation();context.showList();context.highlightSelected();}).keydown(function(e){switch(e.keyCode){case 40:if(!context.listIsVisible()){context.showList();context.highlightSelected();}else{e.preventDefault();context.selectNewListItem('down');};break;case 38:e.preventDefault();context.selectNewListItem('up');break;case 9:context.pickListItem(context.selectedListItem());break;case 27:e.preventDefault();context.hideList();return false;break;case 13:e.preventDefault();return false;};}).keyup(function(e){if(timer!==false){clearTimeout(timer);timer=false;};timer=setTimeout(function(){if(context.text.val()!=context.current_value){context.current_value=context.text.val();context.highlightSelected();};},200);}).keypress(function(e){if(e.keyCode==13){e.preventDefault();return false;};});},initListItemEvents:function(list_item){var context=this;list_item.mouseover(function(){context.clearSelectedListItem();context.selectListItem(list_item);}).mousedown(function(e){e.stopPropagation();context.pickListItem(context.selectedListItem());});},selectNewListItem:function(direction){var li=this.selectedListItem();if(!li.length){li=this.selectFirstListItem();};if(direction=='down'){var sib=li.next();}else{var sib=li.prev();};if(sib.length){this.selectListItem(sib);this.scrollToListItem(sib);this.unselectListItem(li);};},selectListItem:function(list_item){this.clearSelectedListItem();list_item.addClass('selected');},selectFirstListItem:function(){this.clearSelectedListItem();var first=this.wrapper.find('li:first');first.addClass('selected');return first;},unselectListItem:function(list_item){list_item.removeClass('selected');},selectedListItem:function(){return this.wrapper.find('li.selected');},clearSelectedListItem:function(){this.wrapper.find('li.selected').removeClass('selected');},pickListItem:function(list_item){if(list_item.length){this.text.val(list_item.text());this.current_value=this.text.val();};if(typeof this.settings.onSelect=='function'){this.settings.onSelect.call(this,list_item);};this.hideList();},listIsVisible:function(){return this.list_is_visible;},showList:function(){this.positionElements();this.setWidths();this.wrapper.show();this.hideOtherLists();this.list_is_visible=true;if(this.settings.bg_iframe){this.bg_iframe.show();};},highlightSelected:function(){var context=this;var current_value=this.text.val();if(current_value.length<0){if(highlight_first){this.selectFirstListItem();};return;};if(!context.settings.case_sensitive){current_value=current_value.toLowerCase();};var best_candiate=false;var value_found=false;var list_items=this.wrapper.find('li');list_items.each(function(){if(!value_found){var text=$(this).text();if(!context.settings.case_sensitive){text=text.toLowerCase();};if(text==current_value){value_found=true;context.clearSelectedListItem();context.selectListItem($(this));context.scrollToListItem($(this));return false;}else if(text.indexOf(current_value)===0&&!best_candiate){best_candiate=$(this);};};});if(best_candiate&&!value_found){context.clearSelectedListItem();context.selectListItem(best_candiate);context.scrollToListItem(best_candiate);}else if(!best_candiate&&!value_found){this.selectFirstListItem();};},scrollToListItem:function(list_item){if(this.list_height){this.wrapper.scrollTop(list_item[0].offsetTop-(this.list_height/2));};},hideList:function(){this.wrapper.hide();this.list_is_visible=false;if(this.settings.bg_iframe){this.bg_iframe.hide();};},hideOtherLists:function(){for(var i=0;i<instances.length;i++){if(i!=this.select.data('editable-selecter')){instances[i].hideList();};};},positionElements:function(){var offset=this.text.offset();offset={top:offset.top,left:offset.left};offset.top+=this.text[0].offsetHeight;this.wrapper.css({top:offset.top+'px',left:offset.left+'px'});this.wrapper.css('visibility','hidden');this.wrapper.show();this.list_item_height=this.wrapper.find('li')[0].offsetHeight;this.wrapper.css('visibility','visible');this.wrapper.hide();},setWidths:function(){this.select.show();var width=this.select.width()+2;this.select.hide();var padding_right=parseInt(this.text.css('padding-right').replace(/px/,''),10);this.text.width(width-padding_right);this.wrapper.width(width+2);if(this.bg_iframe){this.bg_iframe.width(width+4);};},createBackgroundIframe:function(){var bg_iframe=$('<iframe frameborder="0" class="xi-editable-select-iframe" src="about:blank;"></iframe>');$(document.body).append(bg_iframe);bg_iframe.width(this.select.width()+2);bg_iframe.height(this.wrapper.height());bg_iframe.css({top:this.wrapper.css('top'),left:this.wrapper.css('left')});this.bg_iframe=bg_iframe;}};})(xi.jQuery);(function($){$.apprise=function(string,args,callback)
{var default_args={'confirm':false,'verify':false,'input':false,'animate':false,'textOk':'Ok','textCancel':'Cancel','textYes':'Yes','textNo':'No'}
if(args)
{for(var index in default_args)
{if(typeof args[index]=="undefined")args[index]=default_args[index];}}
var aHeight=$(document).height();var aWidth=$(document).width();$('body').append('<div class="appriseOverlay" id="aOverlay"></div>');$('.appriseOverlay').css('height',aHeight).css('width',aWidth).fadeIn(100);$('body').append('<div class="appriseOuter"></div>');$('.appriseOuter').append('<div class="appriseInner"></div>');$('.appriseInner').append(string);$('.appriseOuter').css("left",($(window).width()-$('.appriseOuter').width())/2+$(window).scrollLeft()+"px");if(args)
{if(args['animate'])
{var aniSpeed=args['animate'];if(isNaN(aniSpeed)){aniSpeed=400;}
$('.appriseOuter').css('top','-200px').show().animate({top:"100px"},aniSpeed);}
else
{$('.appriseOuter').css('top','100px').fadeIn(200);}}
else
{$('.appriseOuter').css('top','100px').fadeIn(200);}
if(args)
{if(args['input'])
{if(typeof(args['input'])=='string')
{$('.appriseInner').append('<div class="aInput"><input type="text" class="aTextbox" t="aTextbox" value="'+args['input']+'" /></div>');}
else
{$('.appriseInner').append('<div class="aInput"><input type="text" class="aTextbox" t="aTextbox" /></div>');}
$('.aTextbox').focus();}}
$('.appriseInner').append('<div class="aButtons"></div>');if(args)
{if(args['confirm']||args['input'])
{$('.aButtons').append('<button value="ok">'+args['textOk']+'</button>');$('.aButtons').append('<button value="cancel">'+args['textCancel']+'</button>');}
else if(args['verify'])
{$('.aButtons').append('<button value="ok">'+args['textYes']+'</button>');$('.aButtons').append('<button value="cancel">'+args['textNo']+'</button>');}
else
{$('.aButtons').append('<button value="ok">'+args['textOk']+'</button>');}}
else
{$('.aButtons').append('<button value="ok">Ok</button>');}
$(document).keydown(function(e)
{if($('.appriseOverlay').is(':visible'))
{if(e.keyCode==13)
{$('.aButtons > button[value="ok"]').click();}
if(e.keyCode==27)
{$('.aButtons > button[value="cancel"]').click();}}});var aText=$('.aTextbox').val();if(!aText){aText=false;}
$('.aTextbox').keyup(function()
{aText=$(this).val();});$('.aButtons > button').click(function()
{$('.appriseOverlay').remove();$('.appriseOuter').remove();if(callback)
{var wButton=$(this).attr("value");if(wButton=='ok')
{if(args)
{if(args['input'])
{callback(aText);}
else
{callback(true);}}
else
{callback(true);}}
else if(wButton=='cancel')
{callback(false);}}});}})(xi.jQuery);