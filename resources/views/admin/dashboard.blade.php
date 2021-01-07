@extends('/layout')
@section('content')

<style type="text/css">
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

    .circle{
        width: 100px;
        height: 100px;
        position: absolute;
        top: 45%;
        left: 45%;
        opacity: 0.8;
        margin-top:30px;
        background-color: white;
    }
    .css-loading{
        opacity: 0.8;
    }

    .gif-loading{
        width: 30px;height: 30px;position: absolute;right: 10px;
    }
</style>


<!--=== Page Header ===-->
<!-- Menu -->
<div class="crumbs">
	<ul id="breadcrumbs" class="breadcrumb">
		<li>
			<i class="icon-home"></i>
			<a href="{{@Config::get('app.url')}}/admin/dashboard">Main Dashboard</a>
		</li>
	</ul>

	<ul class="crumb-buttons">
		<li><a href="#" title=""><i class="icon-signal"></i><span>Statistics</span></a></li>
		<li class="dropdown"><a href="#" title="" data-toggle="dropdown"><i class="fas fa-user"></i><span>Users <strong>(+3)</strong></span><i class="icon-angle-down left-padding"></i></a>
			<ul class="dropdown-menu pull-right">
			<li><a href="{{@Config::get('app.url')}}/admin/dashboard/users/adduser" title=""><i class="icon-plus"></i>Add new user</a></li>
			<li><a href="{{@Config::get('app.url')}}/admin/dashboard/users" title=""><i class="icon-reorder"></i>All users</a></li>
			</ul>
		</li>
	</ul>
</div>
<!-- /End of menu -->

<div class="page-header">
	<div class="page-title">
		<h3>Monitoring System</h3>
		<span>Company: {{$domain->company}} / Domain: {{$domain->domainname}}</span>
	</div>

	<!-- Page Stats -->
	<ul class="page-stats">
		<li>

		</li>
		<li>

		</li>
	</ul>
	<!-- /Page Stats -->
</div>
<!-- /Page Header -->

<!--=== Page Content ===-->
<!--=== Statboxes ===-->
<div class="row row-bg"> <!-- .row-bg -->
	<div class="col-sm-6 col-md-3 hidden-xs">

	</div> <!-- /.col-md-3 -->
</div> <!-- /.row -->
<!-- /Statboxes -->


<div class="row">
	<div class="col-md-4">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>Cisco SDWAN Server Detail</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<div id="ciscosdwanserverdetail" style="overflow: overlay; border:none;"></div>
			</div>
		</div>
	</div>
{{--	<div class="col-md-4">--}}
{{--        <div class="widget box">--}}
{{--            <div class="widget-header">--}}
{{--                <h4>Cisco SDWAN Last 10 Alarms in 24 hours</h4>--}}
{{--                <div class="toolbar no-padding">--}}
{{--                    <div class="btn-group">--}}
{{--                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="widget-content">--}}
{{--                <div id="ciscosdwanalarms" style="overflow: overlay; border:none;"></div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <div class="col-md-4">--}}
{{--        <div class="widget box">--}}
{{--            <div class="widget-header">--}}
{{--                <h4><i class="icon-reorder"></i>WAN Edge Health</h4>--}}
{{--                <div class="toolbar no-padding">--}}
{{--                    <div class="btn-group">--}}
{{--                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div style="height: 100px;"></div>--}}
{{--            <div class="row" align="center" style="vertical-align: middle;">--}}
{{--                <div class="col-md-4">--}}
{{--                    <h5>Normal</h5>--}}
{{--                    <input class="knob" data-width="100" data-angleOffset="-90" data-angleArc="360" value="0" data-readOnly="true" id="normal">--}}
{{--                </div>--}}
{{--                <div class="col-md-4">--}}
{{--                    <h5>Warning</h5>--}}
{{--                    <input class="knob" data-width="100" data-angleOffset="-90" data-angleArc="360" data-fgColor="#eb7d34" value="0" data-readOnly="true" id="warning">--}}
{{--                </div>--}}
{{--                <div class="col-md-4">--}}
{{--                    <h5>Error</h5>--}}
{{--                    <input class="knob" data-width="100" data-angleOffset="-90" data-angleArc="360" data-fgColor="red" value="0" data-readOnly="true" id="error">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

    <div class="col-md-4">
        <div class="widget box">
            <div class="widget-header">
                    <h4>Host </h4>

            </div>
            <div class="widget-content" style="position: relative">
                <div style="margin:0 0 20px 0;width: 100%">
                    <select class="form-control" id="hosts">
                        @foreach($hosts as $host)
                            <option value="{{$host->id}}">{{$host->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="loading-gif-change gif-loading">
                    <img src="{{@Config::get('app.url')}}/images/casvd/loading.gif" style="width: 30px;">
                </div>
                <div id="chart_pie" class="chart" ></div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="widget box">
            <div class="widget-header">
                <h4>CA Service Desk</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                    </div>
                </div>
            </div>
            <div class="widget-content">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header" style="display: grid; grid-template-columns: 1fr 2fr;">
                        <span style="grid-column: 1/2;"><b>INCIDENT</b></span>
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
                <div class="statbox widget box box-shadow">
                    <div class="widget-header" style="display: grid; grid-template-columns: 1fr 2fr;">
                        <span style="grid-column: 1/2;"><b>REQUEST</b></span>
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

                <div class="statbox widget box box-shadow">
                    <div class="widget-header" style="display: grid; grid-template-columns: 1fr 2fr;">
                        <span style="grid-column: 1/2;"><b>CHANGE</b></span>
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
    </div>
</div>

<div class="row">
    <!-- Incident chart -->
    <div class="col-md-4">
        <div class="widget box" >
            <div class="widget-header">
                <h4>Monthly Incident Tickets</h4>
            </div>
            <div style="height: 10px;"></div>
            <div class="widget-content no-padding" align="center" style="position:relative;">
                <div class="loading-gif-request gif-loading" >
                    <img src="{{@Config::get('app.url')}}/images/casvd/loading.gif" style="width: 30px;">
                </div>
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
            <div class="widget-content no-padding" align="center" style="position: relative">
                <div class="loading-gif-request gif-loading" >
                    <img src="{{@Config::get('app.url')}}/images/casvd/loading.gif" style="width: 30px;">
                </div>
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
            <div class="widget-content no-padding" align="center" style="position:relative;">
                <div class="loading-gif-request gif-loading" >
                    <img src="{{@Config::get('app.url')}}/images/casvd/loading.gif" style="width: 30px;">
                </div>
                <div id="change-chart" class="chart" style="width:95%; height: 320px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-md-4">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>NPM Last 10 Unacknowledged Alerts</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content" style="overflow:overlay; border:none;">
				<div id="slwnpmajaxnpmunack">
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>NPM Event Summary</h4>
				<span>TODAY</span>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content" style="overflow:overlay; border:none;">
				<div id="slwnpmajaxnpmeventsum"></div>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>NPM Last 10 Events</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content" style="overflow:overlay; border:none;">
				<div id="slwnpmajaxnpmlast10event"></div>
			</div>
		</div>
	</div>
</div>

<div id="ajaxchartdata" style="display: none !important;"></div>

<script>
    var incidentIntervalID;
    var requestIntervalID;
    var changeIntervalID;

	$( document ).ready(function() {

		var query = '<?php echo URL::route('ciscosdwan.dashboard.serverdetail') ?>';
	    $('#ciscosdwanserverdetail').load(query);

	    var query = '<?php echo URL::route('ciscosdwan.dashboard.alarms') ?>';
	    $('#ciscosdwanalarms').load(query);

	    var ajaxnpmeventsum = '<?php echo URL::route('ajaxnpmeventsum') ?>';
	    $('#slwnpmajaxnpmeventsum').load(ajaxnpmeventsum).fadeIn("slow");

	    var ajaxnpmlast10event = '<?php echo URL::route('ajaxnpmlast10event') ?>';
	    $('#slwnpmajaxnpmlast10event').load(ajaxnpmlast10event).fadeIn("slow");

	    var ajaxnpmunack = '<?php echo URL::route('ajaxnpmunack') ?>';
	    $('#slwnpmajaxnpmunack').load(ajaxnpmunack).fadeIn("slow");

        var query = '<?php echo URL::route('ciscosdwan.dashboard.sitehealth') ?>';
        $('#sitehealth').load(query).fadeIn("slow");

	    // CA Service Desk counting
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

        <!-- Chart CA Service Desk-->
        loadChartCASVR();

        <!-- Centreon-->
        getservices();

	});

	function loadChartCASVR(){
        var ajaxcasvddashboardticketchart = '<?php echo URL::route('ajaxcasvddashboardticketchart') ?>';
        $('#ajaxchartdata').load(ajaxcasvddashboardticketchart, function() {
            $('.loading-gif-request').hide();
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
    }

    function addMonthChart(source){
        var outputArr= [];
        var date = new Date();
        var start = date.getMonth();
        for(var i = 1 ;i<=12;i++){
            if((start+1) == i){
                outputArr.push(parseInt(source))
            }else{
                outputArr.push(null);
            }
        }
        return outputArr;
    }

    $('select').change(function () {
        getservices();
    });

    function drawchart(data,flag){        //var d_pie = [10,20,70];
        var d_pie = data;
        d_pie[2] = { label: "UP", data: Math.floor(d_pie[0]*100)+1 };
        d_pie[1] = { label: "DOWN", data: Math.floor(d_pie[1]*100)+1 };
        d_pie[0] = { label: "UNREACHABLE", data: Math.floor(d_pie[2]*100)+1 };
        d_pie[3] = { label: "SCHEDULED DOWNTIME", data: Math.floor(d_pie[3]*100)+1 };
        d_pie[4] = { label: "UNDETERMINED", data: Math.floor(d_pie[4]*100)+1 };
        $.plot("#chart_pie", d_pie, $.extend(true, {}, Plugins.getFlotDefaults(), {
            series: {
                pie: {
                    show: true,
                    radius: 1,
                    label: {
                        show: true
                    }
                }
            },
            grid: {
                hoverable: true
            },
            tooltip: true,
            tooltipOpts: {
                content: '%p.0%, %s', // show percentages, rounding to 2 decimal places
                shifts: {
                    x: 20,
                    y: 0
                }
            }
        }));
    }

    function getservices(){
        var hostname = $("#hosts :selected").text();
        //console.log(hostname);
        jQuery.ajax({
            headers: {
                //'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                //'_token': '{{ csrf_token() }}'
            },
            url: '<?php echo URL::route("ajaxgetservicebyhost") ?>',
            method: 'POST',
            data: {
                name: hostname,
                _token: '{{ csrf_token() }}'
            },
            beforeSend(){
                $('.loading-gif-change').show();
            },
            success: function (result, status, xhr) {
                $('.loading-gif-change').hide();
                var rs = result.services;
                //console.log(rs[0]);
                // UP
                if(rs[0]['host_state'] == 0){
                    var host_downtimes = parseInt(rs[0]['host_downtimes']);
                    var dt = [100,0,0,host_downtimes,0];
                    drawchart(dt);
                }
                // DOWN
                if(rs[0]['host_state'] == 2){
                    var host_downtimes = parseInt(rs[0]['host_downtimes']);
                    var dt = [0,100,0,host_downtimes,0];
                    drawchart(dt);
                }
                //UNREACT
                if(rs[0]['host_state'] == 3){
                    var host_downtimes = parseInt(rs[0]['host_downtimes']);
                    var dt = [0,0,100,host_downtimes,0];
                    drawchart(dt);
                }
                $('body').removeClass('css-loading');
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log(errorThrown)
            }
        });
    }


	{{--setInterval(function(){--}}
	{{--	var query = '<?php echo URL::route('ciscosdwan.dashboard.serverdetail') ?>';--}}
	{{--    $('#ciscosdwanserverdetail').load(query);--}}

	{{--    var query = '<?php echo URL::route('ciscosdwan.dashboard.alarms') ?>';--}}
	{{--    $('#ciscosdwanalarms').load(query);--}}

	{{--    var ajaxnpmeventsum = '<?php echo URL::route('ajaxnpmeventsum') ?>';--}}
	{{--    $('#slwnpmajaxnpmeventsum').load(ajaxnpmeventsum).fadeIn("slow");--}}

	{{--    var ajaxnpmlast10event = '<?php echo URL::route('ajaxnpmlast10event') ?>';--}}
	{{--    $('#slwnpmajaxnpmlast10event').load(ajaxnpmlast10event).fadeIn("slow");--}}

	{{--    var ajaxnpmunack = '<?php echo URL::route('ajaxnpmunack') ?>';--}}
	{{--    $('#slwnpmajaxnpmunack').load(ajaxnpmunack).fadeIn("slow");--}}

    {{--    var query = '<?php echo URL::route('ciscosdwan.dashboard.sitehealth') ?>';--}}
    {{--    $('#sitehealth').load(query).fadeIn("slow");--}}

    {{--    <!--Service Desk-->--}}
    {{--    var ajaxcasvddashboardtotalincidents = '<?php echo @Config::get('app.url') ?>';--}}
    {{--    ajaxcasvddashboardtotalincidents += ('/ajaxcasvddashboardtotalincidents/' + start + "/" + end);--}}
    {{--    $('#incidentcount').load(ajaxcasvddashboardtotalincidents, function() {--}}
    {{--        $('.loading-gif-incident').hide();--}}
    {{--    }).fadeIn("slow");--}}

    {{--    var ajaxcasvddashboardtotalrequests = '<?php echo @Config::get('app.url') ?>';--}}
    {{--    ajaxcasvddashboardtotalrequests += ('/ajaxcasvddashboardtotalrequests/' + start + "/" + end);--}}
    {{--    $('#requestcount').load(ajaxcasvddashboardtotalrequests, function() {--}}
    {{--        $('.loading-gif-request').hide();--}}
    {{--    }).fadeIn("slow");--}}

    {{--    var ajaxcasvddashboardtotalchanges = '<?php echo @Config::get('app.url') ?>';--}}
    {{--    ajaxcasvddashboardtotalchanges += ('/ajaxcasvddashboardtotalchanges/' + start + "/" + end);--}}
    {{--    $('#changecount').load(ajaxcasvddashboardtotalchanges, function() {--}}
    {{--        $('.loading-gif-change').hide();--}}
    {{--    }).fadeIn("slow");--}}

    {{--    loadChartCASVR();--}}

    {{--    <!--Centreon-->--}}
    {{--    getservices();--}}
	{{--});--}}

    // $(document).ajaxComplete(function(event,xhr,settings){
    //     //console.log("URL",settings.url);
    //     if(settings.url.indexOf('ciscosdwan.dashboard.wanedgehealth')>0){
    //         if(wanedgehealthdiv.innerText!=''){
    //             var datas = JSON.parse(wanedgehealthdiv.innerText);
    //             var i;
    //             for (i=0;i<datas.length;i++){
    //                 if(datas[i]['name']=='normal'){
    //                     $('#normal').val(datas[i]['count']).trigger('change');
    //                 }else if(datas[i]['name']=='warning'){
    //                     $('#warning').val(datas[i]['count']).trigger('change');
    //                 }else if(datas[i]['name']=='error'){
    //                     $('#error').val(datas[i]['count']).trigger('change');
    //                 }
    //             }
    //         }
    //     }
    //         //End of display chart
    // });
</script>
@endsection
