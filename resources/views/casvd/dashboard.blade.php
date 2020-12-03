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
<div class="row row-bg"> <!-- .row-bg -->
	<div class="col-md-12">
		<div class="widget box" style="display:grid; grid-template-columns: 1fr;">
			<div class="widget-header" style="display:grid; grid-template-columns: 0px fit-content(800px) 1fr 3fr;">
				<div class="itemtype" style="margin: auto; margin-left: 50px;">
					<label for="itemtype">Type:&nbsp&nbsp&nbsp</label>
					<select name="itemtype" id="itemtype" style="width: 150px;">
						<option value="all">All</option>
						<option value="incident">Incident</option>
						<option value="request">Request</option>
						<option value="change">Change</option>
					</select>
				</div>
				<div class="timeframe" style="margin: auto; margin-left: 50px;">
					<label for="timeframe">Time frame:&nbsp&nbsp&nbsp</label>
					<select name="timeframe" id="timeframe">
						<option value="today">Today</option>
						<option value="5d">Last 5 days</option>
						<option value="1w">Last 1 week</option>
						<option value="2w">Last 2 week</option>
						<option value="1m">Last 1 month</option>
						<option value="all">All</option>
						<option value="custom">--Custom--</option>
					</select>
				</div>
				<div class="daterange" style="margin: auto; margin-left: 50px;">
					<label for="daterange">Date range:&nbsp&nbsp&nbsp</label>
				</div>
			</div>
			<div class="widget-content" style="display: inline-block; white-space: nowrap">
				<div class="col-md-4">
					<div class="statbox widget box box-shadow" style="margin: 0;">
						<div class="widget-content">
							<div class="visual" style="padding: 0px; margin: 0px;">
								<img src="{{@Config::get('app.url')}}/images/ciscosdwan/vsmart.png" style="width: 50px;">
							</div>
							<div class="title">Total</div>
							<div class="value" id="vsmartcount">N/A</div>
							<a id="vs" class="click_devices open-options button more" href="#">Incident <i class="pull-right icon-angle-right"></i></a>
						</div>
					</div> <!-- /.smallstat -->
				</div> <!-- /.col-md-2 -->

				<div class="col-md-4">
					<div class="statbox widget box box-shadow" style="margin: 0;">
						<div class="widget-content">
							<div class="visual" style="padding: 0px; margin: 0px;">
								<img src="{{@Config::get('app.url')}}/images/ciscosdwan/wanedge.png" style="width: 50px;">
							</div>
							<div class="title">Total</div>
							<div class="value" id="wanedgecount">N/A</div>
							<a id="ve" class="click_devices open-options button more" href="#">Request <i class="pull-right icon-angle-right"></i></a>
						</div>
					</div> <!-- /.smallstat -->
				</div> <!-- /.col-md-2 -->

				<div class="col-md-4 hidden-xs">
					<div class="statbox widget box box-shadow" style="margin: 0;">
						<div class="widget-content">
							<div class="visual" style="padding: 0px; margin: 0px;">
								<img src="{{@Config::get('app.url')}}/images/ciscosdwan/vbond.png" style="width: 50px;">
							</div>
							<div class="title">Total</div>
							<div class="value" id="vbondcount">N/A</div>
							<a id="vb" class="click_devices open-options button more" href="#">Change <i class="pull-right icon-angle-right"></i></a>
						</div>
					</div> <!-- /.smallstat -->
				</div> <!-- /.col-md-2 -->
			</div>
		</div>
	</div>
	

</div> <!-- /.row -->
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
		setInterval(function(){
			var ajaxcasvddashboardincidents = '<?php echo URL::route('ajaxcasvddashboardincidents') ?>';
			$('#ajaxcasvddashboardincidents').load(ajaxcasvddashboardincidents).fadeIn("slow");

			var ajaxcasvddashboardrequests = '<?php echo URL::route('ajaxcasvddashboardrequests') ?>';
			$('#ajaxcasvddashboardrequests').load(ajaxcasvddashboardrequests).fadeIn("slow");

			var ajaxcasvddashboardchanges = '<?php echo URL::route('ajaxcasvddashboardchanges') ?>';
			$('#ajaxcasvddashboardchanges').load(ajaxcasvddashboardchanges).fadeIn("slow");
		},{{$refreshrate}});

		$( document ).ready(function() {
		    var ajaxcasvddashboardincidents = '<?php echo URL::route('ajaxcasvddashboardincidents') ?>';
			$('#ajaxcasvddashboardincidents').load(ajaxcasvddashboardincidents).fadeIn("slow");
			
			var ajaxcasvddashboardrequests = '<?php echo URL::route('ajaxcasvddashboardrequests') ?>';
			$('#ajaxcasvddashboardrequests').load(ajaxcasvddashboardrequests).fadeIn("slow");
			
			var ajaxcasvddashboardchanges = '<?php echo URL::route('ajaxcasvddashboardchanges') ?>';
		    $('#ajaxcasvddashboardchanges').load(ajaxcasvddashboardchanges).fadeIn("slow");
		});
</script>

@endsection
