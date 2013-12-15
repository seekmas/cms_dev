/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Javascript
* @contact 		payplans@readybytes.in
*/

// define payplans, if not defined.
(function($){
// START : 	
// Scoping code for easy and non-conflicting access to $.
// Should be first line, write code below this line.	

		$(document).ready(function() {
			// code to show and hide extra information at dashboard page.
			$(".pp-parenthover").hover(function(){
			  $(this).find('.pp-childhover').show();
			
			},function(){
			     $(this).find('.pp-childhover').hide(); 
			});
		});

// ENDING :
// Scoping code for easy and non-conflicting access to $.
// Should be last line, write code above this line.
})(payplans.jQuery);