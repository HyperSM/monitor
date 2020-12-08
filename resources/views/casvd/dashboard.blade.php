@extends('/layout')
@section('content')
@include('casvd.menu')


<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>CA Service Desk</h3>
		<span>Company: {{$domain->company}} / Domain: {{$domain->domainname}}</span>
	</div>
</div>

<!--=== Statboxes ===-->
<!-- <div class="row row-bg">
	<div class="col-md-12">
		<div class="widget box" style="display:grid; grid-template-columns: 1fr;">
			<div class="widget-header" style="display:grid; grid-template-columns: 0px fit-content(800px) fit-content(800px) fit-content(800px) fit-content(300px);">
				<div class="itemtype" style="margin: auto; margin-left: 15px;">
					<label for="itemtype" style="margin: 0;">Type:&nbsp&nbsp&nbsp</label>
					<select class="itemtype_sel" name="itemtype" id="itemtype" style="width: 150px;">
						<option value="all">All</option>
						<option value="incident">Incident</option>
						<option value="request">Request</option>
						<option value="change">Change</option>
					</select>
				</div>
				<div class="timeframe" style="margin: auto 50px;">
					<label for="timeframe" style="margin: 0;">Time frame:&nbsp&nbsp&nbsp</label>
					<select class="timeframe_sel" name="timeframe" id="timeframe" style="width: 150px;" onchange="seltimeframe(this.value)">
						<option value="today" selected="selected">Today</option>
						<option value="5d">Last 5 days</option>
						<option value="1w">Last 1 week</option>
						<option value="2w">Last 2 week</option>
						<option value="1m">Last 1 month</option>
						<option value="all">All</option>
						<option value="custom">--Custom--</option>
					</select>
				</div>
				<div class="daterange" style="margin: auto 50px; display: none;">
					<label for="daterange" style="margin: 0;">Date range:&nbsp&nbsp&nbsp</label>
					<label for="daterange_from" style="margin: 0;">from &nbsp&nbsp</label>
					<input type="date" id="daterange_from" name="daterange_from" style="height: 25px; margin-top:5px;">
					<label for="daterange_to" style="margin: 0; margin-left:5px;">to &nbsp&nbsp</label>
					<input type="date" id="daterange_to" name="daterange_to" style="height: 25px; margin-top:5px;">
				</div>
				<div class="daterange_btn">
					<button class="btn btn-primary" style="padding: 2px 13px; margin-bottom: 2px; margin-left: 50px;" onclick="settimeframe()">Set</button>
				</div>
			</div>
			<div class="widget-content" style="display: inline-block; white-space: nowrap">
				<div class="col-md-4">
					<div class="statbox widget box box-shadow" style="margin: 0;">
						<div class="widget-header" style="text-align:center; padding: 0;">
							<h4 style="margin:0;">Incident</h4>
						</div>
						<div class="widget-content" style="display:grid; grid-template-columns:1fr 1fr; grid-template-rows:1fr 1fr;">
							<div style="padding: 0px; margin: 0px; grid-row: 1/3; grid-column: 1/2;">
								<h3><b>Total</b></h3>
							</div>
							<div class="title" style="grid-row: 1/2; grid-column: 2/3;">Total</div>
							<div class="value" id="incidentcount" style="grid-row: 2/3; grid-column: 2/3;">N/A</div>
						</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="statbox widget box box-shadow" style="margin: 0;">
						<div class="widget-header" style="text-align:center; padding: 0;">
							<h4 style="margin:0;">Request</h4>
						</div>
						<div class="widget-content" style="display:grid; grid-template-columns:1fr 1fr; grid-template-rows:1fr 1fr;">
							<div style="padding: 0px; margin: 0px; grid-row: 1/3; grid-column: 1/2;">
								<h3><b>Total</b></h3>
							</div>
							<div class="title" style="grid-row: 1/2; grid-column: 2/3;">Total</div>
							<div class="value" id="requestcount" style="grid-row: 2/3; grid-column: 2/3;">N/A</div>
						</div>
					</div>
				</div>

				<div class="col-md-4 hidden-xs">
					<div class="statbox widget box box-shadow" style="margin: 0;">
						<div class="widget-header" style="text-align:center; padding: 0;">
							<h4 style="margin:0;">Change</h4>
						</div>
						<div class="widget-content" style="display:grid; grid-template-columns:1fr 1fr; grid-template-rows:1fr 1fr;">
							<div style="padding: 0px; margin: 0px; grid-row: 1/3; grid-column: 1/2;">
								<h3><b>Total</b></h3>
							</div>
							<div class="title" style="grid-row: 1/2; grid-column: 2/3;">Total</div>
							<div class="value" id="changecount" style="grid-row: 2/3; grid-column: 2/3;">N/A</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	

</div> -->
<!-- /Statboxes -->

<!--=== Statboxes ===-->
<div class="row row-bg" style="display: flex; justify-content: space-around;">
	<div class="col-md-3">
		<div class="statbox widget box box-shadow">
			<div class="widget-header" style="display: grid; grid-template-columns: 60px 1fr;">
				<span style="grid-column: 1/2;"><b>INCIDENT</b></span>
				<div id="incidentrange" style="cursor: pointer; grid-column: 2/3; text-align: right;">
					<span></span> &nbsp;
					<i class="fa fa-calendar"></i>&nbsp;
					<i class="fa fa-caret-down"></i>
				</div>
			</div>
			<div class="widget-content">
				<div class="visual" style="padding: 0px; margin: 0px;">
					<img src="{{@Config::get('app.url')}}/images/casvd/incident.png" style="width: 50px;">
				</div>
				<div class="title">Total</div>
				<div class="value" id="incidentcount">N/A</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="statbox widget box box-shadow">
			<div class="widget-header" style="display: grid; grid-template-columns: 60px 1fr;">
				<span style="grid-column: 1/2;"><b>REQUEST</b></span>
				<div id="requestrange" style="cursor: pointer; grid-column: 2/3; text-align: right;">
					<span id="requestrangespan"></span> &nbsp;
					<i class="fa fa-calendar"></i>&nbsp;
					<i class="fa fa-caret-down"></i>
				</div>
			</div>
			<div class="widget-content">
				<div class="visual" style="padding: 0px; margin: 0px;">
					<img src="{{@Config::get('app.url')}}/images/casvd/request.png" style="width: 50px;">
				</div>
				<div class="title">Total</div>
				<div class="value" id="requestcount">N/A</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="statbox widget box box-shadow">
			<div class="widget-header" style="display: grid; grid-template-columns: 60px 1fr 1fr;">
				<span style="grid-column: 1/2;"><b>CHANGE</b></span>
				<div id="changerange" style="cursor: pointer; grid-column: 2/4; text-align: right;">
					<!-- <span></span> &nbsp; -->
					<input type="text" class="form-control" style="border: none; outline: none; background: transparent;" disabled="disabled"></input>
					<i class="fa fa-calendar"></i>&nbsp;
					<i class="fa fa-caret-down"></i>
				</div>
			</div>
			<div class="widget-content">
				<div class="visual" style="padding: 0px; margin: 0px;">
					<img src="{{@Config::get('app.url')}}/images/casvd/change.png" style="width: 50px;">
				</div>
				<div class="title">Total</div>
				<div class="value" id="changecount">N/A</div>
			</div>
		</div>
	</div>
</div>
<!-- /Statboxes -->

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
					<div id="ajaxcasvddashboardincidents"></div>
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
					<div id="ajaxcasvddashboardrequests"></div>
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
					<div id="ajaxcasvddashboardchanges"></div>
				</div>
			</div>

		</div>
	</div>
</div>
<!-- /Top10 -->

<script>
	setInterval(function () {
		var ajaxcasvddashboardincidents = '<?php echo URL::route('ajaxcasvddashboardincidents') ?>';
		$('#ajaxcasvddashboardincidents').load(ajaxcasvddashboardincidents).fadeIn("slow");

		var ajaxcasvddashboardrequests = '<?php echo URL::route('ajaxcasvddashboardrequests') ?>';
		$('#ajaxcasvddashboardrequests').load(ajaxcasvddashboardrequests).fadeIn("slow");

		var ajaxcasvddashboardchanges = '<?php echo URL::route('ajaxcasvddashboardchanges') ?>';
		$('#ajaxcasvddashboardchanges').load(ajaxcasvddashboardchanges).fadeIn("slow");
	}, {{ $refreshrate }});

	$(document).ready(function () {
		var ajaxcasvddashboardincidents = '<?php echo URL::route('ajaxcasvddashboardincidents') ?>';
		$('#ajaxcasvddashboardincidents').load(ajaxcasvddashboardincidents).fadeIn("slow");

		var ajaxcasvddashboardrequests = '<?php echo URL::route('ajaxcasvddashboardrequests') ?>';
		$('#ajaxcasvddashboardrequests').load(ajaxcasvddashboardrequests).fadeIn("slow");

		var ajaxcasvddashboardchanges = '<?php echo URL::route('ajaxcasvddashboardchanges') ?>';
		$('#ajaxcasvddashboardchanges').load(ajaxcasvddashboardchanges).fadeIn("slow");

	});

	$(function() {
		var start = moment().subtract(29, 'days');
		var end = moment();

		function cb(start, end) {
			$('#incidentrange span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
		}

		$('#incidentrange').daterangepicker({
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
		}, cb);

		cb(start, end);
	});

	$(function() {
		var start = moment().subtract(29, 'days');
		var end = moment();

		function cb(start, end) {
		$('#requestrange span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
		}

		$('#requestrange').daterangepicker({
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
		}, cb);

		cb(start, end);
	});

	$(function() {
		var start = moment().subtract(29, 'days');
		var end = moment();

		function cb(start, end) {
			$('#changerange input').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
			// var el = document.getElementById("changerangediv");
			// el.value = (start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
		}

		$('#changerange').daterangepicker({
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
		}, cb);

		cb(start, end);
	});

	function changerangeFunction() {
		alert("test");
	}

</script>

@endsection