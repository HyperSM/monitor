@extends('/layout')
@section('content')
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
	<div class="col-md-8">
		<div class="widget box">
			<div class="widget-header">
				<h4>Cisco SDWAN Last 10 Alarms in 24 hours</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<div id="ciscosdwanalarms" style="overflow: overlay; border:none;"></div>
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
	});

	setInterval(function(){
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
	});
</script>
@endsection
