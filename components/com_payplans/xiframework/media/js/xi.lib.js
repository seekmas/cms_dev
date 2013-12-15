/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		XiFramework
* @subpackage	Javascript
* @contact 		shyam@readybytes.in
*/

/*-----------------------------------------------------------
  Javascript writing standards - 
  - Pack you code in (function($){})(xi.jQuery); and use $ as usually.
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


/*
http://www.JSON.org/json2.js
2011-10-19

Public Domain.

NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.

See http://www.JSON.org/js.html


This code should be minified before deployment.
See http://javascript.crockford.com/jsmin.html

USE YOUR OWN COPY. IT IS EXTREMELY UNWISE TO LOAD CODE FROM SERVERS YOU DO
NOT CONTROL.


This file creates a global JSON object containing two methods: stringify
and parse.

    JSON.stringify(value, replacer, space)
        value       any JavaScript value, usually an object or array.

        replacer    an optional parameter that determines how object
                    values are stringified for objects. It can be a
                    function or an array of strings.

        space       an optional parameter that specifies the indentation
                    of nested structures. If it is omitted, the text will
                    be packed without extra whitespace. If it is a number,
                    it will specify the number of spaces to indent at each
                    level. If it is a string (such as '\t' or '&nbsp;'),
                    it contains the characters used to indent at each level.

        This method produces a JSON text from a JavaScript value.

        When an object value is found, if the object contains a toJSON
        method, its toJSON method will be called and the result will be
        stringified. A toJSON method does not serialize: it returns the
        value represented by the name/value pair that should be serialized,
        or undefined if nothing should be serialized. The toJSON method
        will be passed the key associated with the value, and this will be
        bound to the value

        For example, this would serialize Dates as ISO strings.

            Date.prototype.toJSON = function (key) {
                function f(n) {
                    // Format integers to have at least two digits.
                    return n < 10 ? '0' + n : n;
                }

                return this.getUTCFullYear()   + '-' +
                     f(this.getUTCMonth() + 1) + '-' +
                     f(this.getUTCDate())      + 'T' +
                     f(this.getUTCHours())     + ':' +
                     f(this.getUTCMinutes())   + ':' +
                     f(this.getUTCSeconds())   + 'Z';
            };

        You can provide an optional replacer method. It will be passed the
        key and value of each member, with this bound to the containing
        object. The value that is returned from your method will be
        serialized. If your method returns undefined, then the member will
        be excluded from the serialization.

        If the replacer parameter is an array of strings, then it will be
        used to select the members to be serialized. It filters the results
        such that only members with keys listed in the replacer array are
        stringified.

        Values that do not have JSON representations, such as undefined or
        functions, will not be serialized. Such values in objects will be
        dropped; in arrays they will be replaced with null. You can use
        a replacer function to replace those with JSON values.
        JSON.stringify(undefined) returns undefined.

        The optional space parameter produces a stringification of the
        value that is filled with line breaks and indentation to make it
        easier to read.

        If the space parameter is a non-empty string, then that string will
        be used for indentation. If the space parameter is a number, then
        the indentation will be that many spaces.

        Example:

        text = JSON.stringify(['e', {pluribus: 'unum'}]);
        // text is '["e",{"pluribus":"unum"}]'


        text = JSON.stringify(['e', {pluribus: 'unum'}], null, '\t');
        // text is '[\n\t"e",\n\t{\n\t\t"pluribus": "unum"\n\t}\n]'

        text = JSON.stringify([new Date()], function (key, value) {
            return this[key] instanceof Date ?
                'Date(' + this[key] + ')' : value;
        });
        // text is '["Date(---current time---)"]'


    JSON.parse(text, reviver)
        This method parses a JSON text to produce an object or array.
        It can throw a SyntaxError exception.

        The optional reviver parameter is a function that can filter and
        transform the results. It receives each of the keys and values,
        and its return value is used instead of the original value.
        If it returns what it received, then the structure is not modified.
        If it returns undefined then the member is deleted.

        Example:

        // Parse the text. Values that look like ISO date strings will
        // be converted to Date objects.

        myData = JSON.parse(text, function (key, value) {
            var a;
            if (typeof value === 'string') {
                a =
/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2}(?:\.\d*)?)Z$/.exec(value);
                if (a) {
                    return new Date(Date.UTC(+a[1], +a[2] - 1, +a[3], +a[4],
                        +a[5], +a[6]));
                }
            }
            return value;
        });

        myData = JSON.parse('["Date(09/09/2001)"]', function (key, value) {
            var d;
            if (typeof value === 'string' &&
                    value.slice(0, 5) === 'Date(' &&
                    value.slice(-1) === ')') {
                d = new Date(value.slice(5, -1));
                if (d) {
                    return d;
                }
            }
            return value;
        });


This is a reference implementation. You are free to copy, modify, or
redistribute.
*/

/*jslint evil: true, regexp: true */

/*members "", "\b", "\t", "\n", "\f", "\r", "\"", JSON, "\\", apply,
call, charCodeAt, getUTCDate, getUTCFullYear, getUTCHours,
getUTCMinutes, getUTCMonth, getUTCSeconds, hasOwnProperty, join,
lastIndex, length, parse, prototype, push, replace, slice, stringify,
test, toJSON, toString, valueOf
*/


//Create a JSON object only if one does not already exist. We create the
//methods in a closure to avoid creating global variables.
var JSON;
if (!JSON) {
JSON = {};
}

(function () {
'use strict';

function f(n) {
    // Format integers to have at least two digits.
    return n < 10 ? '0' + n : n;
}

if (typeof Date.prototype.toJSON !== 'function') {

    Date.prototype.toJSON = function (key) {

        return isFinite(this.valueOf())
            ? this.getUTCFullYear()     + '-' +
                f(this.getUTCMonth() + 1) + '-' +
                f(this.getUTCDate())      + 'T' +
                f(this.getUTCHours())     + ':' +
                f(this.getUTCMinutes())   + ':' +
                f(this.getUTCSeconds())   + 'Z'
            : null;
    };

    String.prototype.toJSON      =
        Number.prototype.toJSON  =
        Boolean.prototype.toJSON = function (key) {
            return this.valueOf();
        };
}

var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
    escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
    gap,
    indent,
    meta = {    // table of character substitutions
        '\b': '\\b',
        '\t': '\\t',
        '\n': '\\n',
        '\f': '\\f',
        '\r': '\\r',
        '"' : '\\"',
        '\\': '\\\\'
    },
    rep;


function quote(string) {

//If the string contains no control characters, no quote characters, and no
//backslash characters, then we can safely slap some quotes around it.
//Otherwise we must also replace the offending characters with safe escape
//sequences.

    escapable.lastIndex = 0;
    return escapable.test(string) ? '"' + string.replace(escapable, function (a) {
        var c = meta[a];
        return typeof c === 'string'
            ? c
            : '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
    }) + '"' : '"' + string + '"';
}


function str(key, holder) {

//Produce a string from holder[key].

    var i,          // The loop counter.
        k,          // The member key.
        v,          // The member value.
        length,
        mind = gap,
        partial,
        value = holder[key];

//If the value has a toJSON method, call it to obtain a replacement value.

    if (value && typeof value === 'object' &&
            typeof value.toJSON === 'function') {
        value = value.toJSON(key);
    }

//If we were called with a replacer function, then call the replacer to
//obtain a replacement value.

    if (typeof rep === 'function') {
        value = rep.call(holder, key, value);
    }

//What happens next depends on the value's type.

    switch (typeof value) {
    case 'string':
        return quote(value);

    case 'number':

//JSON numbers must be finite. Encode non-finite numbers as null.

        return isFinite(value) ? String(value) : 'null';

    case 'boolean':
    case 'null':

//If the value is a boolean or null, convert it to a string. Note:
//typeof null does not produce 'null'. The case is included here in
//the remote chance that this gets fixed someday.

        return String(value);

//If the type is 'object', we might be dealing with an object or an array or
//null.

    case 'object':

//Due to a specification blunder in ECMAScript, typeof null is 'object',
//so watch out for that case.

        if (!value) {
            return 'null';
        }

//Make an array to hold the partial results of stringifying this object value.

        gap += indent;
        partial = [];

//Is the value an array?

        if (Object.prototype.toString.apply(value) === '[object Array]') {

//The value is an array. Stringify every element. Use null as a placeholder
//for non-JSON values.

            length = value.length;
            for (i = 0; i < length; i += 1) {
                partial[i] = str(i, value) || 'null';
            }

//Join all of the elements together, separated with commas, and wrap them in
//brackets.

            v = partial.length === 0
                ? '[]'
                : gap
                ? '[\n' + gap + partial.join(',\n' + gap) + '\n' + mind + ']'
                : '[' + partial.join(',') + ']';
            gap = mind;
            return v;
        }

//If the replacer is an array, use it to select the members to be stringified.

        if (rep && typeof rep === 'object') {
            length = rep.length;
            for (i = 0; i < length; i += 1) {
                if (typeof rep[i] === 'string') {
                    k = rep[i];
                    v = str(k, value);
                    if (v) {
                        partial.push(quote(k) + (gap ? ': ' : ':') + v);
                    }
                }
            }
        } else {

//Otherwise, iterate through all of the keys in the object.

            for (k in value) {
                if (Object.prototype.hasOwnProperty.call(value, k)) {
                    v = str(k, value);
                    if (v) {
                        partial.push(quote(k) + (gap ? ': ' : ':') + v);
                    }
                }
            }
        }

//Join all of the member texts together, separated with commas,
//and wrap them in braces.

        v = partial.length === 0
            ? '{}'
            : gap
            ? '{\n' + gap + partial.join(',\n' + gap) + '\n' + mind + '}'
            : '{' + partial.join(',') + '}';
        gap = mind;
        return v;
    }
}

//If the JSON object does not yet have a stringify method, give it one.

if (typeof JSON.stringify !== 'function') {
    JSON.stringify = function (value, replacer, space) {

//The stringify method takes a value and an optional replacer, and an optional
//space parameter, and returns a JSON text. The replacer can be a function
//that can replace values, or an array of strings that will select the keys.
//A default replacer method can be provided. Use of the space parameter can
//produce text that is more easily readable.

        var i;
        gap = '';
        indent = '';

//If the space parameter is a number, make an indent string containing that
//many spaces.

        if (typeof space === 'number') {
            for (i = 0; i < space; i += 1) {
                indent += ' ';
            }

//If the space parameter is a string, it will be used as the indent string.

        } else if (typeof space === 'string') {
            indent = space;
        }

//If there is a replacer, it must be a function or an array.
//Otherwise, throw an error.

        rep = replacer;
        if (replacer && typeof replacer !== 'function' &&
                (typeof replacer !== 'object' ||
                typeof replacer.length !== 'number')) {
            throw new Error('JSON.stringify');
        }

//Make a fake root object containing our value under the key of ''.
//Return the result of stringifying the value.

        return str('', {'': value});
    };
}


//If the JSON object does not yet have a parse method, give it one.

if (typeof JSON.parse !== 'function') {
    JSON.parse = function (text, reviver) {

//The parse method takes a text and an optional reviver function, and returns
//a JavaScript value if the text is a valid JSON text.

        var j;

        function walk(holder, key) {

//The walk method is used to recursively walk the resulting structure so
//that modifications can be made.

            var k, v, value = holder[key];
            if (value && typeof value === 'object') {
                for (k in value) {
                    if (Object.prototype.hasOwnProperty.call(value, k)) {
                        v = walk(value, k);
                        if (v !== undefined) {
                            value[k] = v;
                        } else {
                            delete value[k];
                        }
                    }
                }
            }
            return reviver.call(holder, key, value);
        }


//Parsing happens in four stages. In the first stage, we replace certain
//Unicode characters with escape sequences. JavaScript handles many characters
//incorrectly, either silently deleting them, or treating them as line endings.

        text = String(text);
        cx.lastIndex = 0;
        if (cx.test(text)) {
            text = text.replace(cx, function (a) {
                return '\\u' +
                    ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
            });
        }

//In the second stage, we run the text against regular expressions that look
//for non-JSON patterns. We are especially concerned with '()' and 'new'
//because they can cause invocation, and '=' because it can cause mutation.
//But just to be safe, we want to reject all unexpected forms.

//We split the second stage into 4 regexp operations in order to work around
//crippling inefficiencies in IE's and Safari's regexp engines. First we
//replace the JSON backslash pairs with '@' (a non-JSON character). Second, we
//replace all simple value tokens with ']' characters. Third, we delete all
//open brackets that follow a colon or comma or that begin the text. Finally,
//we look to see that the remaining characters are only whitespace or ']' or
//',' or ':' or '{' or '}'. If that is so, then the text is safe for eval.

        if (/^[\],:{}\s]*$/
                .test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, '@')
                    .replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']')
                    .replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

//In the third stage we use the eval function to compile the text into a
//JavaScript structure. The '{' operator is subject to a syntactic ambiguity
//in JavaScript: it can begin a block or an object literal. We wrap the text
//in parens to eliminate the ambiguity.

            j = eval('(' + text + ')');

//In the optional fourth stage, we recursively walk the new structure, passing
//each name/value pair to a reviver function for possible transformation.

            return typeof reviver === 'function'
                ? walk({'': j}, '')
                : j;
        }

//If the text is not JSON parseable, then a SyntaxError is thrown.

        throw new SyntaxError('JSON.parse');
    };
}
}());


/*
 * timeago: a jQuery plugin, version: 0.9.3 (2011-01-21)
 * @requires jQuery v1.2.3 or later
 *
 * Timeago is a jQuery plugin that makes it easy to support automatically
 * updating fuzzy timestamps (e.g. "4 minutes ago" or "about 1 day ago").
 *
 * For usage and examples, visit:
 * http://timeago.yarp.com/
 *
 * Licensed under the MIT:
 * http://www.opensource.org/licenses/mit-license.php
 *
 * Copyright (c) 2008-2011, Ryan McGeary (ryanonjavascript -[at]- mcgeary [*dot*] org)
 */
(function($) {
  $.timeago = function(timestamp) {
    if (timestamp instanceof Date) {
      return inWords(timestamp);
    } else if (typeof timestamp === "string") {
      return inWords($.timeago.parse(timestamp));
    } else {
      return inWords($.timeago.datetime(timestamp));
    }
  };
  var $t = $.timeago;

  $.extend($.timeago, {
    settings: {
      refreshMillis: 60000,
      allowFuture: true,
      strings: {
        prefixAgo: null,
        prefixFromNow: null,
        suffixAgo: "ago",
        suffixFromNow: "from now",
        seconds: "less than a minute",
        minute: "about a minute",
        minutes: "%d minutes",
        hour: "about an hour",
        hours: "about %d hours",
        day: "a day",
        days: "%d days",
        month: "about a month",
        months: "%d months",
        year: "about a year",
        years: "%d years",
        numbers: []
      }
    },
    inWords: function(distanceMillis) {
      var $l = this.settings.strings;
      var prefix = $l.prefixAgo;
      var suffix = $l.suffixAgo;
      if (this.settings.allowFuture) {
        if (distanceMillis < 0) {
          prefix = $l.prefixFromNow;
          suffix = $l.suffixFromNow;
        }
        distanceMillis = Math.abs(distanceMillis);
      }

      var seconds = distanceMillis / 1000;
      var minutes = seconds / 60;
      var hours = minutes / 60;
      var days = hours / 24;
      var years = days / 365;

      function substitute(stringOrFunction, number) {
        var string = $.isFunction(stringOrFunction) ? stringOrFunction(number, distanceMillis) : stringOrFunction;
        var value = ($l.numbers && $l.numbers[number]) || number;
        return string.replace(/%d/i, value);
      }

      var words = seconds < 45 && substitute($l.seconds, Math.round(seconds)) ||
        seconds < 90 && substitute($l.minute, 1) ||
        minutes < 45 && substitute($l.minutes, Math.round(minutes)) ||
        minutes < 90 && substitute($l.hour, 1) ||
        hours < 24 && substitute($l.hours, Math.round(hours)) ||
        hours < 48 && substitute($l.day, 1) ||
        days < 30 && substitute($l.days, Math.floor(days)) ||
        days < 60 && substitute($l.month, 1) ||
        days < 365 && substitute($l.months, Math.floor(days / 30)) ||
        years < 2 && substitute($l.year, 1) ||
        substitute($l.years, Math.floor(years));

      return $.trim([prefix, words, suffix].join(" "));
    },
    parse: function(iso8601) {
      var s = $.trim(iso8601);
      s = s.replace(/\.\d\d\d+/,""); // remove milliseconds
      s = s.replace(/-/,"/").replace(/-/,"/");
      s = s.replace(/T/," ").replace(/Z/," UTC");
      s = s.replace(/([\+\-]\d\d)\:?(\d\d)/," $1$2"); // -04:00 -> -0400
      return new Date(s);
    },
    datetime: function(elem) {
      // jQuery's `is()` doesn't play well with HTML5 in IE
      var isTime = $(elem).get(0).tagName.toLowerCase() === "time"; // $(elem).is("time");
      var iso8601 = isTime ? $(elem).attr("datetime") : $(elem).attr("title");
      return $t.parse(iso8601);
    }
  });

  $.fn.timeago = function() {
    var self = this;
    self.each(refresh);

    var $s = $t.settings;
    if ($s.refreshMillis > 0) {
      setInterval(function() { self.each(refresh); }, $s.refreshMillis);
    }
    return self;
  };

  function refresh() {
    var data = prepareData(this);
    if (!isNaN(data.datetime)) {
      $(this).text(inWords(data.datetime));
    }
    return this;
  }

  function prepareData(element) {
    element = $(element);
    if (!element.data("timeago")) {
      element.data("timeago", { datetime: $t.datetime(element) });
      var text = $.trim(element.text());
      if (text.length > 0) {
        element.attr("title", text + ' UTC');
      }
    }
    return element.data("timeago");
  }

  function inWords(date) {
    return $t.inWords(distance(date));
  }

  function distance(date) {
    return (new Date().getTime() - date.getTime());
  }

  // fix for IE6 suckage
  document.createElement("abbr");
  document.createElement("time");
}(xi.jQuery));



/**
 * Copyright (c) 2009 Anders Ekdahl (http://coffeescripter.com/)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Version: 1.3.2
 *
 * Demo and documentation: http://coffeescripter.com/code/editable-select/
 */
(function($) {
  var instances = [];
  $.fn.editableSelect = function(options) {
    var defaults = { bg_iframe: false,
                     onSelect: false,
                     items_then_scroll: 10,
                     case_sensitive: false
    };
    var settings = $.extend(defaults, options);
    // Only do bg_iframe for browsers that need it
    if(settings.bg_iframe && !$.browser.msie) {
      settings.bg_iframe = false;
    };
    var instance = false;
    $(this).each(function() {
      var i = instances.length;
      if($(this).data('editable-selecter') !== null) {
        instances[i] = new EditableSelect(this, settings);
        $(this).data('editable-selecter', i);
      };
    });
    return $(this);
  };
  $.fn.editableSelectInstances = function() {
    var ret = [];
    $(this).each(function() {
      if($(this).data('editable-selecter') !== null) {
        ret[ret.length] = instances[$(this).data('editable-selecter')];
      };
    });
    return ret;
  };

  var EditableSelect = function(select, settings) {
    this.init(select, settings);
  };
  
  EditableSelect.prototype = {
    settings: false,
    text: false,
    select: false,
    select_width: 0,
    wrapper: false,
    list_item_height: 20,
    list_height: 0,
    list_is_visible: false,
    hide_on_blur_timeout: false,
    bg_iframe: false,
    current_value: '',
    init: function(select, settings) {
      this.settings = settings;
      this.wrapper = $(document.createElement('div'));
      this.wrapper.addClass('xi-editable-select-options');
      this.select = $(select);
      this.text = $('<input type="text">');
      this.text.attr('name', this.select.attr('name'));
      this.text.data('editable-selecter', this.select.data('editable-selecter'));
      // Because we don't want the value of the select when the form
      // is submitted
      this.select.attr('disabled', 'disabled');
      this.text[0].className = this.select[0].className;
      var id = this.select.attr('id');
      if(!id) {
        id = 'editable-select'+ instances.length;
      };
      this.text.attr('id', id);
      this.text.attr('autocomplete', 'off');
      this.text.addClass('xi-editable-select');
      this.select.attr('id', id +'_hidden_select');
      this.select.after(this.text);
      if(this.select.css('display') == 'none') {
        this.text.css('display', 'none');
      }
      if(this.select.css('visibility') == 'hidden') {
        this.text.css('visibility', 'visibility');
      }
      // Set to hidden, because we want to call .show()
      // on it to get it's width but not having it display
      // on the screen
      this.select.css('visibility', 'hidden');
      this.select.hide();
      this.initInputEvents(this.text);
      this.duplicateOptions();
      this.setWidths();
      $(document.body).append(this.wrapper);

      if(this.settings.bg_iframe) {
        this.createBackgroundIframe();
      };
    },
    /**
     * Take the select lists options and
     * populate an unordered list with them
     */
    duplicateOptions: function() {
      var context = this;
      var option_list = $(document.createElement('ul'));
      this.wrapper.append(option_list);
      var options = this.select.find('option');
      options.each(function() {
        if($(this).attr('selected')) {
          context.text.val($(this).val());
          context.current_value = $(this).val();
        };
        var li = $('<li>'+ $(this).val() +'</li>');
        context.initListItemEvents(li);
        option_list.append(li);
      });
      this.checkScroll();
    },
    /**
     * Check if the list has enough items to display a scroll
     */
    checkScroll: function() {
      var options = this.wrapper.find('li');
      if(options.length > this.settings.items_then_scroll) {
        this.list_height = this.list_item_height * this.settings.items_then_scroll;
        this.wrapper.css('height', this.list_height +'px');
        this.wrapper.css('overflow', 'auto');
      } else {
        this.wrapper.css('height', 'auto');
        this.wrapper.css('overflow', 'visible');
      };
    },
    addOption: function(value) {
      var li = $('<li>'+ value +'</li>');
      var option = $('<option>'+ value +'</option>');
      this.select.append(option);
      this.initListItemEvents(li);
      this.wrapper.find('ul').append(li);
      this.setWidths();
      this.checkScroll();
    },
    /**
     * Init the different events on the input element
     */
    initInputEvents: function(text) {
      var context = this;
      var timer = false;
      $(document.body).click(
        function() {
          context.clearSelectedListItem();
          context.hideList();
        }
      );
      text.focus(
        function() {
          // Can't use the blur event to hide the list, because the blur event
          // is fired in some browsers when you scroll the list
          context.showList();
          context.highlightSelected();
        }
      ).click(
        function(e) {
          e.stopPropagation();
          context.showList();
          context.highlightSelected();
        }
      ).keydown(
        // Capture key events so the user can navigate through the list
        function(e) {
          switch(e.keyCode) {
            // Down
            case 40:
              if(!context.listIsVisible()) {
                context.showList();
                context.highlightSelected();
              } else {
                e.preventDefault();
                context.selectNewListItem('down');
              };
              break;
            // Up
            case 38:
              e.preventDefault();
              context.selectNewListItem('up');
              break;
            // Tab
            case 9:
              context.pickListItem(context.selectedListItem());
              break;
            // Esc
            case 27:
              e.preventDefault();
              context.hideList();
              return false;
              break;
            // Enter, prevent form submission
            case 13:
              e.preventDefault();
              //XI-Changes : to prevent value change when click enter
//              context.pickListItem(context.selectedListItem());
              return false;
          };
        }
      ).keyup(
        function(e) {
          // Prevent lots of calls if it's a fast typer
          if(timer !== false) {
            clearTimeout(timer);
            timer = false;
          };
          timer = setTimeout(
            function() {
              // If the user types in a value, select it if it's in the list
              if(context.text.val() != context.current_value) {
                context.current_value = context.text.val();
                context.highlightSelected();
              };
            },
            200
          );
        }
      ).keypress(
        function(e) {
          if(e.keyCode == 13) {
            // Enter, prevent form submission
            e.preventDefault();
            return false;
          };
        }
      );
    },
    initListItemEvents: function(list_item) {
      var context = this;
      list_item.mouseover(
        function() {
          context.clearSelectedListItem();
          context.selectListItem(list_item);
        }
      ).mousedown(
        // Needs to be mousedown and not click, since the inputs blur events
        // fires before the list items click event
        function(e) {
          e.stopPropagation();
          context.pickListItem(context.selectedListItem());
        }
      );
    },
    selectNewListItem: function(direction) {
      var li = this.selectedListItem();
      if(!li.length) {
        li = this.selectFirstListItem();
      };
      if(direction == 'down') {
        var sib = li.next();
      } else {
        var sib = li.prev();
      };
      if(sib.length) {
        this.selectListItem(sib);
        this.scrollToListItem(sib);
        this.unselectListItem(li);
      };
    },
    selectListItem: function(list_item) {
      this.clearSelectedListItem();
      list_item.addClass('selected');
    },
    selectFirstListItem: function() {
      this.clearSelectedListItem();
      var first = this.wrapper.find('li:first');
      first.addClass('selected');
      return first;
    },
    unselectListItem: function(list_item) {
      list_item.removeClass('selected');
    },
    selectedListItem: function() {
      return this.wrapper.find('li.selected');
    },
    clearSelectedListItem: function() {
      this.wrapper.find('li.selected').removeClass('selected');
    },
    /**
     * The difference between this method and selectListItem
     * is that this method also changes the text field and
     * then hides the list
     */
    pickListItem: function(list_item) {
      if(list_item.length) {
        this.text.val(list_item.text());
        this.current_value = this.text.val();
      };
      if(typeof this.settings.onSelect == 'function') {
        this.settings.onSelect.call(this, list_item);
      };
      this.hideList();
    },
    listIsVisible: function() {
      return this.list_is_visible;
    },
    showList: function() {
      this.positionElements();
      this.setWidths();
      this.wrapper.show();
      this.hideOtherLists();
      this.list_is_visible = true;
      if(this.settings.bg_iframe) {
        this.bg_iframe.show();
      };
    },
    highlightSelected: function() {
      var context = this;
      var current_value = this.text.val();
      if(current_value.length < 0) {
        if(highlight_first) {
          this.selectFirstListItem();
        };
        return;
      };
      if(!context.settings.case_sensitive) {
        current_value = current_value.toLowerCase();
      };
      var best_candiate = false;
      var value_found = false;
      var list_items = this.wrapper.find('li');
      list_items.each(
        function() {
          if(!value_found) {
            var text = $(this).text();
            if(!context.settings.case_sensitive) {
              text = text.toLowerCase();
            };
            if(text == current_value) {
              value_found = true;
              context.clearSelectedListItem();
              context.selectListItem($(this));
              context.scrollToListItem($(this));
              return false;
            } else if(text.indexOf(current_value) === 0 && !best_candiate) {
              // Can't do return false here, since we still need to iterate over
              // all list items to see if there is an exact match
              best_candiate = $(this);
            };
          };
        }
      );
      if(best_candiate && !value_found) {
        context.clearSelectedListItem();
        context.selectListItem(best_candiate);
        context.scrollToListItem(best_candiate);
      } else if(!best_candiate && !value_found) {
        this.selectFirstListItem();
      };
    },
    scrollToListItem: function(list_item) {
      if(this.list_height) {
        this.wrapper.scrollTop(list_item[0].offsetTop - (this.list_height / 2));
      };
    },
    hideList: function() {
      this.wrapper.hide();
      this.list_is_visible = false;
      if(this.settings.bg_iframe) {
        this.bg_iframe.hide();
      };
    },
    hideOtherLists: function() {
      for(var i = 0; i < instances.length; i++) {
        if(i != this.select.data('editable-selecter')) {
          instances[i].hideList();
        };
      };
    },
    positionElements: function() {
      var offset = this.text.offset();
      offset = { top: offset.top, left: offset.left };
      offset.top += this.text[0].offsetHeight;
      this.wrapper.css({top: offset.top +'px', left: offset.left +'px'});
      // Need to do this in order to get the list item height
      this.wrapper.css('visibility', 'hidden');
      this.wrapper.show();
      this.list_item_height = this.wrapper.find('li')[0].offsetHeight;
      this.wrapper.css('visibility', 'visible');
      this.wrapper.hide();
    },
    setWidths: function() {
      // The text input has a right margin because of the background arrow image
      // so we need to remove that from the width
      this.select.show();
      var width = this.select.width() + 2;
      this.select.hide();
      var padding_right = parseInt(this.text.css('padding-right').replace(/px/, ''), 10);
      this.text.width(width - padding_right);
      this.wrapper.width(width + 2);
      if(this.bg_iframe) {
        this.bg_iframe.width(width + 4);
      };
    },
    createBackgroundIframe: function() {
      var bg_iframe = $('<iframe frameborder="0" class="xi-editable-select-iframe" src="about:blank;"></iframe>');
      $(document.body).append(bg_iframe);
      bg_iframe.width(this.select.width() + 2);
      bg_iframe.height(this.wrapper.height());
      bg_iframe.css({top: this.wrapper.css('top'), left: this.wrapper.css('left')});
      this.bg_iframe = bg_iframe;
    }
  };
})(xi.jQuery);



//Apprise 1.5 by Daniel Raftery
//http://thrivingkings.com/apprise
//
//Button text added by Adam Bezulski
//

(function($){
	$.apprise = function(string, args, callback)
	{
	var default_args =
		{
		'confirm'		:	false, 		// Ok and Cancel buttons
		'verify'		:	false,		// Yes and No buttons
		'input'			:	false, 		// Text input (can be true or string for default text)
		'animate'		:	false,		// Groovy animation (can true or number, default is 400)
		'textOk'		:	'Ok',		// Ok button default text
		'textCancel'	:	'Cancel',	// Cancel button default text
		'textYes'		:	'Yes',		// Yes button default text
		'textNo'		:	'No'		// No button default text
		}
	
	if(args) 
		{
		for(var index in default_args) 
			{ if(typeof args[index] == "undefined") args[index] = default_args[index]; } 
		}
	
	var aHeight = $(document).height();
	var aWidth = $(document).width();
	$('body').append('<div class="appriseOverlay" id="aOverlay"></div>');
	$('.appriseOverlay').css('height', aHeight).css('width', aWidth).fadeIn(100);
	$('body').append('<div class="appriseOuter"></div>');
	$('.appriseOuter').append('<div class="appriseInner"></div>');
	$('.appriseInner').append(string);
 $('.appriseOuter').css("left", ( $(window).width() - $('.appriseOuter').width() ) / 2+$(window).scrollLeft() + "px");
 
 if(args)
		{
		if(args['animate'])
			{ 
			var aniSpeed = args['animate'];
			if(isNaN(aniSpeed)) { aniSpeed = 400; }
			$('.appriseOuter').css('top', '-200px').show().animate({top:"100px"}, aniSpeed);
			}
		else
			{ $('.appriseOuter').css('top', '100px').fadeIn(200); }
		}
	else
		{ $('.appriseOuter').css('top', '100px').fadeIn(200); }
 
 if(args)
 	{
 	if(args['input'])
 		{
 		if(typeof(args['input'])=='string')
 			{
 			$('.appriseInner').append('<div class="aInput"><input type="text" class="aTextbox" t="aTextbox" value="'+args['input']+'" /></div>');
 			}
 		else
 			{
				$('.appriseInner').append('<div class="aInput"><input type="text" class="aTextbox" t="aTextbox" /></div>');
				}
			$('.aTextbox').focus();
 		}
 	}
 
 $('.appriseInner').append('<div class="aButtons"></div>');
 if(args)
 	{
		if(args['confirm'] || args['input'])
			{ 
			$('.aButtons').append('<button value="ok">'+args['textOk']+'</button>');
			$('.aButtons').append('<button value="cancel">'+args['textCancel']+'</button>'); 
			}
		else if(args['verify'])
			{
			$('.aButtons').append('<button value="ok">'+args['textYes']+'</button>');
			$('.aButtons').append('<button value="cancel">'+args['textNo']+'</button>');
			}
		else
			{ $('.aButtons').append('<button value="ok">'+args['textOk']+'</button>'); }
		}
 else
 	{ $('.aButtons').append('<button value="ok">Ok</button>'); }
	
	$(document).keydown(function(e) 
		{
		if($('.appriseOverlay').is(':visible'))
			{
			if(e.keyCode == 13) 
				{ $('.aButtons > button[value="ok"]').click(); }
			if(e.keyCode == 27) 
				{ $('.aButtons > button[value="cancel"]').click(); }
			}
		});
	
	var aText = $('.aTextbox').val();
	if(!aText) { aText = false; }
	$('.aTextbox').keyup(function()
 	{ aText = $(this).val(); });

 $('.aButtons > button').click(function()
 	{
 	$('.appriseOverlay').remove();
		$('.appriseOuter').remove();
 	if(callback)
 		{
			var wButton = $(this).attr("value");
			if(wButton=='ok')
				{ 
				if(args)
					{
					if(args['input'])
						{ callback(aText); }
					else
						{ callback(true); }
					}
				else
					{ callback(true); }
				}
			else if(wButton=='cancel')
				{ callback(false); }
			}
		});
	}
})(xi.jQuery);