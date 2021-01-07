@extends('/layout')
@section('content')
@include('casvd.menu')

<style>
	.count-box {
		display: grid;
		grid-template-columns: fit-content(100px) 1fr fit-content(100px);
		grid-template-rows: 1fr 2fr;
	}
	.count-box .visual {
		grid-column: 1/2;
		grid-row: 1/3;
	}
	.count-box .title {
		grid-column: 2/4;
		grid-row: 1/2;
	}
	.count-box .value {
		grid-column: 3/4;
		grid-row: 2/3;
		margin-left: 10px;
	}
</style>

<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>CA Service Desk</h3>
		<span>Company: {{$domain->company}} / Domain: {{$domain->domainname}}</span>
	</div>
</div>

<!--=== Counting widget ===-->
<div class="row row-bg" style="display: flex; justify-content: space-around;">
	<!-- Incident counting -->
	<div class="col-md-3">
		<div class="statbox widget box box-shadow">
			<div class="widget-header" style="display: grid; grid-template-columns: 1fr 2fr;">
				<span style="grid-column: 1/2;"><b>INCIDENT</b></span>
				<div id="incidentrange" style="cursor: pointer; grid-column: 2/3; text-align: right;">
					<span></span> &nbsp;
					<i class="fa fa-calendar"></i>&nbsp;
					<i class="fa fa-caret-down"></i>
				</div>
			</div>
			<div class="widget-content count-box">
				<div class="visual" style="padding: 0px; margin: 0px; margin-left: 8px;">
					<img src="{{@Config::get('app.url')}}/images/casvd/incident.png" style="width: 50px;">
				</div>
				<div class="title">Total</div>
				<div class="loading-gif-incident" style="text-align: right; grid-column: 2/3; grid-row: 2/3;">
					<img src="{{@Config::get('app.url')}}/images/casvd/loading.gif" style="width: 30px;">
				</div>
				<div class="value" id="incidentcount">N/A</div>
			</div>
		</div>
	</div>

	<!-- Request counting -->
	<div class="col-md-3">
		<div class="statbox widget box box-shadow">
			<div class="widget-header" style="display: grid; grid-template-columns: 1fr 2fr;">
				<span style="grid-column: 1/2;"><b>REQUEST</b></span>
				<div id="requestrange" style="cursor: pointer; grid-column: 2/3; text-align: right;">
					<span id="requestrangespan"></span> &nbsp;
					<i class="fa fa-calendar"></i>&nbsp;
					<i class="fa fa-caret-down"></i>
				</div>
			</div>
			<div class="widget-content count-box">
				<div class="visual" style="padding: 0px; margin: 0px; margin-left: 7px;">
					<img src="{{@Config::get('app.url')}}/images/casvd/request.png" style="width: 50px;">
				</div>
				<div class="title">Total</div>
				<div class="loading-gif-request" style="text-align: right; grid-column: 2/3; grid-row: 2/3;">
					<img src="{{@Config::get('app.url')}}/images/casvd/loading.gif" style="width: 30px;">
				</div>
				<div class="value" id="requestcount">N/A</div>
			</div>
		</div>
	</div>

	<!-- Change counting -->
	<div class="col-md-3">
		<div class="statbox widget box box-shadow">
			<div class="widget-header" style="display: grid; grid-template-columns: 1fr 2fr;">
				<span style="grid-column: 1/2;"><b>CHANGE</b></span>
				<div id="changerange" style="cursor: pointer; grid-column: 2/3; text-align: right;">
					<span id="changerangespan"></span> &nbsp;
					<i class="fa fa-calendar"></i>&nbsp;
					<i class="fa fa-caret-down"></i>
				</div>
			</div>
			<div class="widget-content count-box">
				<div class="visual" style="padding: 0px; margin: 0px; margin-left: 4px;">
					<img src="{{@Config::get('app.url')}}/images/casvd/change.png" style="width: 50px;">
				</div>
				<div class="title">Total</div>
				<div class="loading-gif-change" style="text-align: right; grid-column: 2/3; grid-row: 2/3;">
					<img src="{{@Config::get('app.url')}}/images/casvd/loading.gif" style="width: 30px;">
				</div>
				<div class="value" id="changecount">N/A</div>
			</div>
		</div>
	</div>
</div>
<!--=== /Counting widget ===-->


<!--=== Monthly tickets charts ===-->
<div class="row">
	<!-- Incident chart -->
	<div class="col-md-4">
		<div class="widget box">
			<div class="widget-header">
				<h4>Monthly Incident Tickets</h4>
			</div>
			<div style="height: 10px;">
			</div>
			<div class="widget-content no-padding" align="center">
				<div id="incident-chart" class="chart" style="width:95%; height: 320px;"></div>
			</div>
		</div>
	</div>

	<!-- Request chart -->
	<div class="col-md-4">
		<div class="widget box">
			<div class="widget-header">
				<h4>Monthly Request Tickets</h4>
			</div>
			<div style="height: 10px;">
			</div>
			<div class="widget-content no-padding" align="center">
				<div id="request-chart" class="chart" style="width:95%; height: 320px;"></div>
			</div>
		</div>
	</div>

	<!-- Change chart -->
	<div class="col-md-4">
		<div class="widget box">
			<div class="widget-header">
				<h4>Monthly Change Tickets</h4>
			</div>
			<div style="height: 10px;">
			</div>
			<div class="widget-content no-padding" align="center">
				<div id="change-chart" class="chart" style="width:95%; height: 320px;"></div>
			</div>
		</div>
	</div>
</div>
<!--=== /Monthly tickets charts ===-->

<!--=== Top10 ===-->
<div class="row">
	<!-- Top10 Incidents -->
	<div class="col-md-4">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>Top 10 open incidents</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content" style="vertical-align: middle;">
				<div class="ct-control-status" style="overflow-x: overlay; border:none;" align="center">
					<!-- <div id="mytree"></div> -->
					<div id="ajaxcasvddashboardincidents" style="height: 320px;"></div>
				</div>
			</div>

		</div>
	</div>

	<!-- Top10 Requests -->
	<div class="col-md-4">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>Top 10 open requests</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content" style="vertical-align: middle;">
				<div class="ct-control-status" style="overflow-x: overlay; border:none;" align="center">
					<!-- <div id="mytree"></div> -->
					<div id="ajaxcasvddashboardrequests" style="height: 320px;"></div>
				</div>
			</div>

		</div>
	</div>

	<!-- Top10 Changes -->
	<div class="col-md-4">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>Top 10 open changes</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content" style="vertical-align: middle;">
				<div class="ct-control-status" style="overflow-x: overlay; border:none;" align="center">
					<!-- <div id="mytree"></div> -->
					<div id="ajaxcasvddashboardchanges" style="height: 320px;"></div>
				</div>
			</div>

		</div>
	</div>
</div>
<!--=== /Top10 ===-->

<div id="ajaxchartdata" style="display: none !important;"></div>

<script>
	// Init interval id for Counting widget
		var incidentIntervalID;
		var requestIntervalID;
		var changeIntervalID;

	// Set interval for top10 and charts
		setInterval(function () {
			var ajaxcasvddashboardincidents = '<?php echo URL::route('ajaxcasvddashboardincidents') ?>';
			$('#ajaxcasvddashboardincidents').load(ajaxcasvddashboardincidents).fadeIn("slow");

			var ajaxcasvddashboardrequests = '<?php echo URL::route('ajaxcasvddashboardrequests') ?>';
			$('#ajaxcasvddashboardrequests').load(ajaxcasvddashboardrequests).fadeIn("slow");

			var ajaxcasvddashboardchanges = '<?php echo URL::route('ajaxcasvddashboardchanges') ?>';
			$('#ajaxcasvddashboardchanges').load(ajaxcasvddashboardchanges).fadeIn("slow");
		}, {{ $refreshrate }});

	// Set interval for charts
		setInterval(function () {
			var ajaxcasvddashboardticketchart = '<?php echo URL::route('ajaxcasvddashboardticketchart') ?>';
			$('#ajaxchartdata').load(ajaxcasvddashboardticketchart, function() {
				var response = $('#ajaxchartdata').html();

				var tmpArr = response.split(";");
                var incidentDatas = tmpArr[0];
                var requestDatas = tmpArr[1];
                var changetDatas = tmpArr[2];

				Highcharts.chart('incident-chart', {
					chart: {
						type: 'line'
					},
					title: {
						text: 'Monthly Incident Tickets'
					},
					xAxis: {
						categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
					},
					yAxis: {
						title: {
							text: 'Incidents'
						}
					},
					plotOptions: {
						line: {
							dataLabels: {
							enabled: true
							},
							enableMouseTracking: false
						}
					},
					series: [{
						color: App.getLayoutColorCode('red'),
						name: 'Incident',
						data: addMonthChart(incidentDatas)
					}]
				});

				Highcharts.chart('request-chart', {
					chart: {
						type: 'line'
					},
					title: {
						text: 'Monthly Request Tickets'
					},
					xAxis: {
						categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
					},
					yAxis: {
						title: {
							text: 'Requests'
						}
					},
					plotOptions: {
						line: {
							dataLabels: {
							enabled: true
							},
							enableMouseTracking: false
						}
					},
					series: [{
						color: App.getLayoutColorCode('purple'),
						name: 'Request',
						data: addMonthChart(requestDatas)
					}]
				});

				Highcharts.chart('change-chart', {
					chart: {
						type: 'line'
					},
					title: {
						text: 'Monthly Changes Tickets'
					},
					xAxis: {
						categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
					},
					yAxis: {
						title: {
							text: 'Changes'
						}
					},
					plotOptions: {
						line: {
							dataLabels: {
							enabled: true
							},
							enableMouseTracking: false
						}
					},
					series: [{
						color: App.getLayoutColorCode('ocean'),
						name: 'Change',
						data: addMonthChart(changetDatas)
					}]
				});
			});
		}, 60000)

	$(document).ready(function () {
		// Load ajax Top10
			var ajaxcasvddashboardincidents = '<?php echo URL::route('ajaxcasvddashboardincidents') ?>';
			$('#ajaxcasvddashboardincidents').load(ajaxcasvddashboardincidents).fadeIn("slow");

			var ajaxcasvddashboardrequests = '<?php echo URL::route('ajaxcasvddashboardrequests') ?>';
			$('#ajaxcasvddashboardrequests').load(ajaxcasvddashboardrequests).fadeIn("slow");

			var ajaxcasvddashboardchanges = '<?php echo URL::route('ajaxcasvddashboardchanges') ?>';
			$('#ajaxcasvddashboardchanges').load(ajaxcasvddashboardchanges).fadeIn("slow");

		// Load ajax Counting
			var start= moment().unix();
			var end= moment().unix();

			var ajaxcasvddashboardtotalincidents = '<?php echo @Config::get('app.url') ?>';
			ajaxcasvddashboardtotalincidents += ('/ajaxcasvddashboardtotalincidents/' + start + "/" + end);
			$('#incidentcount').load(ajaxcasvddashboardtotalincidents, function() {
				$('.loading-gif-incident').hide();
			}).fadeIn("slow");

			var ajaxcasvddashboardtotalrequests = '<?php echo @Config::get('app.url') ?>';
			ajaxcasvddashboardtotalrequests += ('/ajaxcasvddashboardtotalrequests/' + start + "/" + end);
			$('#requestcount').load(ajaxcasvddashboardtotalrequests, function() {
				$('.loading-gif-request').hide();
			}).fadeIn("slow");

			var ajaxcasvddashboardtotalchanges = '<?php echo @Config::get('app.url') ?>';
			ajaxcasvddashboardtotalchanges += ('/ajaxcasvddashboardtotalchanges/' + start + "/" + end);
			$('#changecount').load(ajaxcasvddashboardtotalchanges, function() {
				$('.loading-gif-change').hide();
			}).fadeIn("slow");

		// Load ajax ticket charts
		var ajaxcasvddashboardticketchart = '<?php echo URL::route('ajaxcasvddashboardticketchart') ?>';
		$('#ajaxchartdata').load(ajaxcasvddashboardticketchart, function() {
			var response = $('#ajaxchartdata').html();

			var tmpArr = response.split(";");
            var incidentDatas = tmpArr[0];
            var requestDatas = tmpArr[1];
            var changetDatas = tmpArr[2];

			Highcharts.chart('incident-chart', {
				chart: {
					type: 'line'
				},
				title: {
					text: 'Monthly Incident Tickets'
				},
				xAxis: {
					categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
				},
				yAxis: {
					title: {
						text: 'Incidents'
					}
				},
				plotOptions: {
					line: {
						dataLabels: {
						enabled: true
						},
						enableMouseTracking: false
					}
				},
				series: [{
					color: App.getLayoutColorCode('red'),
					name: 'Incident',
                    data :addMonthChart(incidentDatas)
                }]
			});

			Highcharts.chart('request-chart', {
				chart: {
					type: 'line'
				},
				title: {
					text: 'Monthly Request Tickets'
				},
				xAxis: {
					categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
				},
				yAxis: {
					title: {
						text: 'Requests'
					}
				},
				plotOptions: {
					line: {
						dataLabels: {
						enabled: true
						},
						enableMouseTracking: false
					}
				},
				series: [{
					color: App.getLayoutColorCode('purple'),
					name: 'Request',
					data: addMonthChart(requestDatas)
				}]
			});

			Highcharts.chart('change-chart', {
				chart: {
					type: 'line'
				},
				title: {
					text: 'Monthly Changes Tickets'
				},
				xAxis: {
					categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
				},
				yAxis: {
					title: {
						text: 'Changes'
					}
				},
				plotOptions: {
					line: {
						dataLabels: {
						enabled: true
						},
						enableMouseTracking: false
					}
				},
				series: [{
					color: App.getLayoutColorCode('ocean'),
					name: 'Change',
					data: addMonthChart(changetDatas)
				}]
			});
		});
	});

    function addMonthChart(source){
        var outputArr= [];
        var start = parseInt(source);
        for(var k = 1;k<start;k++){
            outputArr.push(null);
        }
        outputArr.push(parseInt(source));
        for(var i=start +1;i<=12;i++){
            outputArr.push(null);
        }
        console.log(outputArr);
        return outputArr;
    }

    // Init Date range picker + refresh ajaxGetTotal
    // Incident
    $(function() {
        var start = moment().startOf('day');
        var end = moment().startOf('day');

        $('#incidentrange').daterangepicker(
            {
                startDate: start,
                endDate: end,
                alwaysShowCalendars: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            },

            function (start, end) {
                $('#incidentrange span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
                ajaxGetTotal('incident',start.unix(),end.unix());
            }
        );

        $('#incidentrange span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
        ajaxGetTotal('incident',start.unix(),end.unix());
    });
    // Request
    $(function() {
        var start = moment().startOf('day');
        var end = moment().startOf('day');

        $('#requestrange').daterangepicker(
            {
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            },

            function (start, end) {
                $('#requestrange span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
                ajaxGetTotal('request',start.unix(),end.unix());
            }
        );

        $('#requestrange span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
        ajaxGetTotal('request',start.unix(),end.unix());
    });
    //Change
    $(function() {
        var start = moment().startOf('day');
        var end = moment().startOf('day');

        $('#changerange').daterangepicker(
            {
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            },

            function (start, end) {
                $('#changerange span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
                ajaxGetTotal('change',start.unix(),end.unix());
            }
        );

        $('#changerange span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
        ajaxGetTotal('change',start.unix(),end.unix());
    });

    function ajaxGetTotal (type, start, end) {
        refreshrate = {{ $refreshrate }};

        switch (type) {
            case "incident":
                clearInterval(incidentIntervalID);
                incidentIntervalID = setInterval(function () {
                    var ajaxcasvddashboardtotalincidents = '<?php echo @Config::get('app.url') ?>';
                    ajaxcasvddashboardtotalincidents += ('/ajaxcasvddashboardtotalincidents/' + start + "/" + end);
                    $('.loading-gif-incident').show();
                    $('#incidentcount').load(ajaxcasvddashboardtotalincidents, function() {
                        $('.loading-gif-incident').hide();
                    }).fadeIn("slow");
                }, refreshrate);
                break;
            case "request":
                clearInterval(requestIntervalID);
                requestIntervalID = setInterval(function () {
                    var ajaxcasvddashboardtotalrequests = '<?php echo @Config::get('app.url') ?>';
                    ajaxcasvddashboardtotalrequests += ('/ajaxcasvddashboardtotalrequests/' + start + "/" + end);
                    $('.loading-gif-request').show();
                    $('#requestcount').load(ajaxcasvddashboardtotalrequests, function() {
                        $('.loading-gif-request').hide();
                    }).fadeIn("slow");
                }, refreshrate);
                break;
            case "change":
                clearInterval(changeIntervalID);
                changeIntervalID = setInterval(function () {
                    var ajaxcasvddashboardtotalchanges = '<?php echo @Config::get('app.url') ?>';
                    ajaxcasvddashboardtotalchanges += ('/ajaxcasvddashboardtotalchanges/' + start + "/" + end);
                    $('.loading-gif-change').show();
                    $('#changecount').load(ajaxcasvddashboardtotalchanges, function() {
                        $('.loading-gif-change').hide();
                    }).fadeIn("slow");
                }, refreshrate);
                break;
        }
    };
</script>

@endsection
