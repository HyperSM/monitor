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

    <div class="col-md-4">
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i>WAN Edge Health</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                    </div>
                </div>
            </div>
            <div style="height: 100px;"></div>
            <div class="row" align="center" style="vertical-align: middle;">
                <div class="col-md-4">
                    <h5>Normal</h5>
                    <input class="knob" data-width="100" data-angleOffset="-90" data-angleArc="360" value="0" data-readOnly="true" id="normal">
                </div>
                <div class="col-md-4">
                    <h5>Warning</h5>
                    <input class="knob" data-width="100" data-angleOffset="-90" data-angleArc="360" data-fgColor="#eb7d34" value="0" data-readOnly="true" id="warning">
                </div>
                <div class="col-md-4">
                    <h5>Error</h5>
                    <input class="knob" data-width="100" data-angleOffset="-90" data-angleArc="360" data-fgColor="red" value="0" data-readOnly="true" id="error">
                </div>
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
	});


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

    {{--    var start= moment().unix();--}}
    {{--    var end= moment().unix();--}}
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
	{{--});--}}

    $(document).ajaxComplete(function(event,xhr,settings){
        //console.log("URL",settings.url);
        if(settings.url.indexOf('ciscosdwan.dashboard.wanedgehealth')>0){
            if(wanedgehealthdiv.innerText!=''){
                var datas = JSON.parse(wanedgehealthdiv.innerText);
                var i;
                for (i=0;i<datas.length;i++){
                    if(datas[i]['name']=='normal'){
                        $('#normal').val(datas[i]['count']).trigger('change');
                    }else if(datas[i]['name']=='warning'){
                        $('#warning').val(datas[i]['count']).trigger('change');
                    }else if(datas[i]['name']=='error'){
                        $('#error').val(datas[i]['count']).trigger('change');
                    }
                }
            }
        }
            //End of display chart

    });
</script>
@endsection
