/* Copyright (C) YOOtheme GmbH, http://www.gnu.org/licenses/gpl.html GNU/GPL */

(function(a){var c;a.each(files,function(f,d){if(!c){var e=a("head [data-file='"+d.target+"']");e.length&&a.less.getCSS(d.source,{compress:!0}).done(function(b){"rtl"==a("html").attr("dir")&&(b=a.rtl.convert2RTL(b));e.html(b)}).fail(function(){c=!0})}})})(jQuery);
