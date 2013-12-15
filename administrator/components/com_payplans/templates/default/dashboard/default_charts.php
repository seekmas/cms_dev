<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();


define('COM_PAYPLANS_CHART_COLOR_SALES',   'FF9900');
define('COM_PAYPLANS_CHART_COLOR_REVENUE', '00CCFF');
define('COM_PAYPLANS_CHART_COLOR_GROWTH',  '33CC00');
define('COM_PAYPLANS_CHART_COLOR_UNPAID',  'FFCC00');

define('COM_PAYPLANS_CHART_COLOR_UPGRADE',  'A87957');
define('COM_PAYPLANS_CHART_COLOR_RENEWAL',  'A85D57');
define('COM_PAYPLANS_CHART_COLOR_DISCOUNT', 'A89B57');
define('COM_PAYPLANS_CHART_COLOR_COUPON',   'A89B57');

?>

<?php 
XiHtml::script(XI_PATH_MEDIA.DS.'js'.DS.'nvd3'.DS.'d3.v2.js');
XiHtml::script(XI_PATH_MEDIA.DS.'js'.DS.'nvd3'.DS.'nv.d3.js');
XiHtml::stylesheet(XI_PATH_MEDIA.DS.'css'.DS.'nv.d3.css');
?>
<div class='span12'>
		<?php echo $this->loadTemplate('charts_numeric'); ?> 		
	  	<div class="pp-gap-charts">&nbsp;</div>
	
	  	<?php echo $this->loadTemplate('charts_linechart'); ?>
	 	<div class="pp-gap-charts">&nbsp;</div>
	  	
  	
	  	<?php echo $this->loadTemplate('charts_details'); ?>
	  	<div class="pp-gap-charts">&nbsp;</div>
</div>
<script type="text/javascript">

(function($){

	$(document).ready(function(){

		// hide back items
		$('.pp-statistics-block').each( function(index){
			var height = $(this).children('.pp-statistics-block-back').height();
			$(this).children('.pp-statistics-block-back').css("top", height+'px');
		});

		// on hove show back item
		$('.pp-statistics-block').hover(function(){
			var height = $(this).children('.pp-statistics-block-front').height();
			$(this).children('.pp-statistics-block-front').stop().animate({ "top" : '-'+height+'px'}, 700);
			$(this).children('.pp-statistics-block-back').stop().animate({ "top" : '0px'}, 700);
	    }, function() {
	    	var height = $(this).children('.pp-statistics-block-front').height();
	    	$(this).children('.pp-statistics-block-front').stop().animate({ "top" : '0px'}, 700);
	    	$(this).children('.pp-statistics-block-back').stop().animate({ "top" : height+'px'}, 700);   
	    });
	});
	
})(payplans.jQuery);


</script>

<?php 
