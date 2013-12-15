<?php
/**
* @copyright		Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license			http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package			PayPlans
* @subpackage		Backend
* @contact 			payplans@readybytes.in
* website			http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();
?>
<div class="pp-statistics-piechart row-fluid">
  		<div class="span6">
	  		<div class="pp-piechart-title">
	  			<div class="pp-icon-plane pp-piechart-icon pull-left"></div>
	  			<div class="pp-piechart-text pull-left">Sales Per Plan</div>
	  		</div>
	  		<div id="chart1" >
	  				<svg class="pp-piechart-draw"> </svg>
	  		</div>
  		</div>
  		
  		<div class="span6">
		  		<div class="pp-piechart-title">
	  				<div class="pp-icon-gift pp-piechart-icon pull-left"></div>
	  				<div class="pp-piechart-text pull-left">Revenue Per Plan</div>
	  			</div>
	  			<div id="chart2">
	  					<svg class="pp-piechart-draw"> </svg>
	  			</div>
				
  		</div>
</div>
<script type="text/javascript">
		var salesPerPlan = <?php echo $salesPerPlan; ?>;
		var data = [];
		for(title in salesPerPlan){
			  data.push({ key: title , y: salesPerPlan[title] });
		}   
		
		nv.addGraph(function() {
		  var chart = nv.models.pieChart()
		      .x(function(d) { return d.key })
		//      .y(function(d) { return d.y })
		      .values(function(d) { return d })
		      // .labelThreshold(.08)
		      .showLegend(false)
		      .showLabels(false)
		      .width(300)
		      .height(300)
		      .color(d3.scale.category10().range())
		      .donut(true);
		
		    d3.select("#chart1 svg")
		        	.datum([data])
		      	.transition().duration(1200)
		        	.call(chart);
		    
		    nv.utils.windowResize(chart.update);
		
		  return chart;
		});
		
		
		var revenuePerPlan = <?php echo $revenuePerPlan; ?>;
		var datan = [];
		for(title in revenuePerPlan){
		datan.push({ key: title , y: revenuePerPlan[title] });
		}
		nv.addGraph(function() {
		  var chart = nv.models.pieChart()
		      .x(function(d) { return d.key })
		      //.y(function(d) { return d.value })
		      .values(function(d) { return d })
		      //.labelThreshold(.08)
		      .showLegend(false)
		      .showLabels(false)
		      .width(300)
		      .height(300)
		      .color(d3.scale.category10().range())
		      .donut(true);
		
		    d3.select("#chart2 svg")
		        .datum([datan])
		      .transition().duration(1200)
		        .call(chart);
		
		    nv.utils.windowResize(chart.update);
		
		  return chart;
		});
 </script>