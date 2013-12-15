<?php
/**
* @copyright		Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license			http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package			PayPlans
* @subpackage		Frontend
* @contact 			payplans@readybytes.in
* website			http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();
?>

<div class="pp-statistics-linechart row-fluid">
	<div id='chart-tabs' class="tabbable">
	  
	  <ul class="nav nav-tabs">
	  	<li class="active"><a href="#tab-chart-overview" data-toggle="tab">Overview</a></li>
	  	<li ><a href="#tab-chart-revenue" data-toggle="tab">Revenue</a></li>
	  	<li ><a href="#tab-chart-sales" data-toggle="tab">Sales</a></li>
	  </ul>
	  
	  <div class="tab-content">
		<div class="tab-pane active" id="tab-chart-overview">
	  		<svg  id="area-chart-overview" class="pp-linechart-draw">&nbsp;</svg>

	  	</div>
		<div class="tab-pane" id="tab-chart-revenue">
	  		<svg  id="area-chart-revenue" class="pp-linechart-draw">&nbsp;</svg>
		</div>
			
		  <div class="tab-pane" id="tab-chart-sales">
			<svg  id="area-chart-sales" class="pp-linechart-draw">&nbsp;</svg>
		  </div>		  	  
	  </div>
	  
	</div>
</div>

<script type="text/javascript">

var statistics_data = <?php echo $recordsPerDay;?>;

	//why need to calculate other graph if they doesn't display 
	nv.addGraph(function() {
	  	var chart = nv.models.lineChart();

		chart.xAxis.tickFormat(function(d) {
			return d3.time.format('%d-%b')(new Date(parseInt(d*1000)))
		});

		chart.yAxis.tickFormat(d3.format(',.2f'));

		d3.select('#area-chart-overview')
		  .datum(getOverviewData())
		  .transition()
		  .duration(500)
		  .call(chart);

		nv.utils.windowResize(chart.update);
		return chart;
	});

	function getOverviewData() {
	  var allsales= [],
	      allrevenue = [];

	  	for(dated in statistics_data){
		  	if(dated == 'plans')
			  	continue;
	  		var revenue = statistics_data[dated]['revenue_all'];			
		  	allrevenue.push({x:dated , y: revenue}); //the nulls are to show how defined works 

	  		var sales = statistics_data[dated]['sales_all'];			
		  	allsales.push({x:dated , y: sales}); //the nulls are to show how defined works
		}

	  return [
	    {
	      area: false,
	      values: allsales,
	      key: "Sales", // XITODO : use language token 
	      color: "#<?php echo COM_PAYPLANS_CHART_COLOR_SALES;?>"
	    },
	    {
	      area: false,
	      values: allrevenue,
	      key: "Revenue", // XITODO : use language token 
	      color: "#<?php echo COM_PAYPLANS_CHART_COLOR_REVENUE;?>"
	    }
	  ];
	}

	function getSalesData()
	{
		var sales= [];
		var plans = statistics_data['plans'];

		
		for (plan_id in plans) {

			
			var value = [];
				for(dated in statistics_data){
			  		if(dated == 'plans')
				  		continue;
						value.push({x: dated, y: statistics_data[dated]['sales'][plan_id]});
				}

				sales.push({values: value,key: plans[plan_id]});
		}

		return sales;
	}

	function getRevenueData()
	{
		var revenue= [];
		var plans = statistics_data['plans'];

		for(plan_id in plans){
			revenue[plan_id] = [];
		}
		
	  	for(dated in statistics_data){
	  		if(dated == 'plans')
			  	continue;
		  	
	  		var actualdate = new Date(dated*1000);
	  		
		  	for(plan_id in plans){
		  		revenue[plan_id].push({x:actualdate , y: statistics_data[dated]['revenue'][plan_id]});
		  	}
		}

	  	var ret_arr = [];
	  	for(plan_id in plans){
		  	ret_arr.push(
		  			{
		  		      area: false,
		  		      values: revenue[plan_id],
		  		      key: plans[plan_id]
		  		    }
			);
		}
		
		return ret_arr;
	}

	function redrawSales()
	{
		var chart = nv.models.multiBarChart();
	    
	    chart.xAxis.tickFormat(function(d) {
			return d3.time.format('%d-%b')(new Date(parseInt(d*1000)))
		});
		
	    chart.yAxis
	         .tickFormat(d3.format(',.0f'));

	    d3.select('#area-chart-sales')
	      .datum(getSalesData())
	      .transition()
	      .duration(500)
	      .call(chart);

	    nv.utils.windowResize(chart.update);
	}
	
	function redrawRevenue()
	{
		var chart = nv.models.lineChart();
		 
    	chart.xAxis.tickFormat(function(d) {
    		return d3.time.format('%d-%b')(new Date(parseInt(d*1000)))
    	});

		chart.yAxis.tickFormat(d3.format(',.2f'));
   	 
    	d3.select('#area-chart-revenue')
          .datum(getRevenueData())
          .transition()
          .duration(500)
          .call(chart);
		nv.utils.windowResize(chart.update);
	}

	function redrawOverview()
	{
		var chart = nv.models.lineChart();

		chart.xAxis.tickFormat(function(d) {
			return d3.time.format('%d-%b')(new Date(parseInt(d*1000)))
		});

		chart.yAxis.tickFormat(d3.format(',.2f'));

		d3.select('#area-chart-overview')
		  .datum(getOverviewData())
		  .transition()
		  .duration(500)
		  .call(chart);
		nv.utils.windowResize(chart.update);
	}

	//we need to redraw only that chart whuich is on display, no need to calculate others
	payplans.jQuery('a[data-toggle="tab"]').on('shown', function (e) {

		var id = payplans.jQuery('.tab-pane.active').attr('id');
		if(id == 'tab-chart-overview')
		{
			redrawOverview();
		}
		if(id == 'tab-chart-revenue')
		{
			redrawRevenue();
		}
		if(id == 'tab-chart-sales')
		{
			redrawSales();
		}
	});

	//draw_charts();
</script>