<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<title>HyperSM</title>
	<link rel="shortcut icon" href="{{@Config::get('app.url')}}/template/assets/img/logo.png" type="image/x-icon" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
	<!--=== CSS ===-->

	<!-- Bootstrap -->
	<link href="{{@Config::get('app.url')}}/template/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

	<!-- jQuery UI -->
	<!--<link href="plugins/jquery-ui/jquery-ui-1.10.2.custom.css" rel="stylesheet" type="text/css" />-->
	<!--[if lt IE 9]>
		<link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/>
	<![endif]-->

	<!-- Theme -->
	<link href="{{@Config::get('app.url')}}/template/assets/css/main.css" rel="stylesheet" type="text/css" />
	<link href="{{@Config::get('app.url')}}/template/assets/css/plugins.css" rel="stylesheet" type="text/css" />
	<link href="{{@Config::get('app.url')}}/template/assets/css/responsive.css" rel="stylesheet" type="text/css" />
	<link href="{{@Config::get('app.url')}}/template/assets/css/icons.css" rel="stylesheet" type="text/css" />

	<link rel="stylesheet" href="{{@Config::get('app.url')}}/template/assets/css/fontawesome/font-awesome.min.css">
	<link rel="stylesheet" href="{{@Config::get('app.url')}}/template/plugins/fontawesome-free/css/all.min.css">
	<!--[if IE 7]>
		<link rel="stylesheet" href="assets/css/fontawesome/font-awesome-ie7.min.css">
	<![endif]-->

	<!--[if IE 8]>
		<link href="assets/css/ie8.css" rel="stylesheet" type="text/css" />
	<![endif]-->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>

	<!--=== JavaScript ===-->

	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/assets/js/libs/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>

	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/assets/js/libs/lodash.compat.min.js"></script>

	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
		<script src="assets/js/libs/html5shiv.js"></script>
	<![endif]-->

	<!-- Smartphone Touch Events -->
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/event.swipe/jquery.event.move.js"></script>
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/event.swipe/jquery.event.swipe.js"></script>

	<!-- General -->
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/assets/js/libs/breakpoints.js"></script>
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/respond/respond.min.js"></script> <!-- Polyfill for min/max-width CSS3 Media Queries (only for IE8) -->
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/cookie/jquery.cookie.min.js"></script>
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/slimscroll/jquery.slimscroll.horizontal.min.js"></script>

	<!-- Page specific plugins -->
	<!-- Charts -->
	<!--[if lt IE 9]>
		<script type="text/javascript" src="plugins/flot/excanvas.min.js"></script>
	<![endif]-->
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/sparkline/jquery.sparkline.min.js"></script>
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/flot/jquery.flot.min.js"></script>
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/flot/jquery.flot.tooltip.min.js"></script>
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/flot/jquery.flot.resize.min.js"></script>
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/flot/jquery.flot.time.min.js"></script>
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/flot/jquery.flot.growraf.min.js"></script>

	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/daterangepicker/moment.min.js"></script>
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/daterangepicker/daterangepicker.js"></script>
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/blockui/jquery.blockUI.min.js"></script>

	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/fullcalendar/fullcalendar.min.js"></script>

	<!-- Noty -->
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/noty/jquery.noty.js"></script>
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/noty/layouts/top.js"></script>
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/noty/themes/default.js"></script>

	<!-- Forms -->
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/uniform/jquery.uniform.min.js"></script>
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/select2/select2.min.js"></script>

	<!-- App -->
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/assets/js/app.js"></script>
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/assets/js/plugins.js"></script>
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/assets/js/plugins.form-components.js"></script>

	<link rel="stylesheet" href="{{@Config::get('app.url')}}/plugins/mytreeview/css/mytreeview.css">
	<script type="text/javascript" src="{{@Config::get('app.url')}}/plugins/mytreeview/js/mytreeview.js"></script>

	<link rel="stylesheet" href="{{@Config::get('app.url')}}/plugins/jstree/themes/default/style.min.css" />
	<script src="{{@Config::get('app.url')}}/plugins/jstree/jstree.js"></script>
	<!-- <link rel="stylesheet" href="{{@Config::get('app.url')}}/plugins/treeview-bs4/css/bstreeview.css">
	<script type="text/javascript" src="{{@Config::get('app.url')}}/plugins/treeview-bs4/js/bstreeview.js"></script> -->

	<!-- Jvectormap -->
	<link rel="stylesheet" href="{{@Config::get('app.url')}}/plugins/jvectormap/jquery-jvectormap-2.0.5.css">
	<script src="{{@Config::get('app.url')}}/plugins/jvectormap/jquery-jvectormap-2.0.5.min.js"></script>
	<script src="{{@Config::get('app.url')}}/plugins/jvectormap/jquery-jvectormap-world-mill.js"></script>

	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/flot/jquery.flot.pie.js"></script>

	<!-- DataTables -->
	{{--<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/datatables/jquery.dataTables.min.js"></script>--}}
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.22/datatables.min.js"></script>

    <script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/datatables/tabletools/TableTools.min.js"></script> <!-- optional -->
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/datatables/colvis/ColVis.min.js"></script> <!-- optional -->
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/datatables/DT_bootstrap.js"></script>



	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>

	<!-- Pickers -->
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/pickadate/picker.js"></script>
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/pickadate/picker.date.js"></script>
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/pickadate/picker.time.js"></script>

	<!-- Circle Dials -->
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/knob/jquery.knob.js"></script>

	<!-- Izi Modal -->
	<script src="{{@Config::get('app.url')}}/plugins/izimodal/izimodal.min.js"></script>
  	<link rel="stylesheet" href="{{@Config::get('app.url')}}/plugins/izimodal/izimodal.css">

  	<!-- my css -->
  	<link rel="stylesheet" href="{{@Config::get('app.url')}}/plugins/css/mycss.css">

  	<!-- high chart -->
  	<link rel="stylesheet" href="{{@Config::get('app.url')}}/plugins/highchart/chart.css">
	<script src="{{@Config::get('app.url')}}/plugins/highchart/highcharts.js"></script>

	<!-- dual list box -->
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/duallistbox/jquery.duallistbox.min.js"></script>

	<script>
	$(document).ready(function(){
        "use strict";

        App.init(); // Init layout and core plugins
        Plugins.init(); // Init all plugins
        FormComponents.init(); // Init all form-specific plugins

    });
	</script>

	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/assets/js/custom.js"></script>

</head>

<body>

	<!-- Header -->
	<header class="header navbar navbar-fixed-top" role="banner">
		<!-- Top Navigation Bar -->
		<div class="container">

			<!-- Only visible on smartphones, menu toggle -->
			<ul class="nav navbar-nav">
				<li class="nav-toggle"><a href="javascript:void(0);" title=""><i class="icon-reorder"></i></a></li>
			</ul>

			<!-- Logo -->
			<a class="navbar-brand" href="{{@Config::get('app.url')}}/admin/dashboard">
				<img src="{{@Config::get('app.url')}}/template/assets/img/logo.png" style="width: 24px;" alt="logo" />
				<strong>HYPERSM</strong>
			</a>
			<!-- /logo -->

			<!-- Sidebar Toggler -->
			<a href="#" class="toggle-sidebar bs-tooltip" data-placement="bottom" data-original-title="Toggle navigation">
				<i class="icon-reorder"></i>
			</a>
			<!-- /Sidebar Toggler -->


			<!-- Top Right Menu -->
			<ul class="nav navbar-nav navbar-right">
				<!-- Notifications -->
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-warning-sign"></i>
						<span class="badge">5</span>
					</a>
					<ul class="dropdown-menu extended notification">
						<li class="title">
							<p>You have 5 new notifications</p>
						</li>
						<li>
							<a href="javascript:void(0);">

							</a>
						</li>
						<li>
							<a href="javascript:void(0);">
								<span class="label label-success"><i class="icon-plus"></i></span>
								<span class="message">New user registration.</span>
								<span class="time">1 mins</span>
							</a>
						</li>
						<li>
							<a href="javascript:void(0);">
								<span class="label label-danger"><i class="icon-warning-sign"></i></span>
								<span class="message">High CPU load on cluster #2.</span>
								<span class="time">5 mins</span>
							</a>
						</li>
						<li>
							<a href="javascript:void(0);">
								<span class="label label-success"><i class="icon-plus"></i></span>
								<span class="message">New user registration.</span>
								<span class="time">10 mins</span>
							</a>
						</li>
						<li>
							<a href="javascript:void(0);">
								<span class="label label-info"><i class="icon-bullhorn"></i></span>
								<span class="message">New items are in queue.</span>
								<span class="time">25 mins</span>
							</a>
						</li>
						<li>
							<a href="javascript:void(0);">
								<span class="label label-warning"><i class="icon-bolt"></i></span>
								<span class="message">Disk space to 85% full.</span>
								<span class="time">55 mins</span>
							</a>
						</li>
						<li class="footer">
							<a href="javascript:void(0);">View all notifications</a>
						</li>
					</ul>
				</li>

				<!-- .row .row-bg Toggler -->
				<li>
					<a href="#" class="dropdown-toggle row-bg-toggle">
						<i class="icon-resize-vertical"></i>
					</a>
				</li>

				<!-- User Login Dropdown -->
				<li class="dropdown user">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<!--<img alt="" src="assets/img/avatar1_small.jpg" />-->
						<i class="icon-male"></i>
						<span class="username"><?php echo session('mymonitor_userid');?></span>
						<i class="icon-caret-down small"></i>
					</a>
					<ul class="dropdown-menu">
						<li><a href="pages_user_profile.html"><i class="icon-user"></i> My Profile</a></li>
						<li class="divider"></li>
						<li><a href="{{@Config::get('app.url')}}/admin/logout"><i class="icon-key"></i> Log Out</a></li>
					</ul>
				</li>
				<!-- /user login dropdown -->
			</ul>
			<!-- /Top Right Menu -->
		</div>
		<!-- /top navigation bar -->
	</header> <!-- /.header -->

	<div id="container" class="sidebar-closed">
		<div id="sidebar" class="sidebar-fixed">
			<div id="sidebar-content">

				<!--=== Navigation ===-->
				<ul id="nav">
					<li class="current">
						<a href="{{@Config::get('app.url')}}/admin/dashboard">
							<i class="icon-dashboard"></i>
							Dashboard
						</a>
					</li>
					<li>
						<a href="javascript:void(0);">
							<i class="fas fa-network-wired"></i>
							NETWORK
						</a>
						<ul class="sub-menu">
							@if ($user->slwnpmuse==1)
							<li>
								<a href="{{@Config::get('app.url')}}/admin/slwnpm">
								<i class="icon-angle-right"></i>
								Solarwinds NPM
								</a>
							</li>
							@endif
						</ul>
					</li>
					<li>
						<a href="javascript:void(0);">
							<i class="fas fa-database"></i>
							DATABASE
						</a>
						<ul class="sub-menu">
							<li>
								<a href="#">
								<i class="icon-angle-right"></i>
								Solarwinds DPA
								</a>
							</li>
						</ul>
					</li>

					<li>
						<a href="javascript:void(0);">
							<i class="fas fa-th-large"></i>
							APPLICATION
						</a>
						<ul class="sub-menu">
							<li>
								<a href="#">
								<i class="icon-angle-right"></i>
								Solarwinds SAM
								</a>
							</li>
							<li>
								<a href="{{@Config::get('app.url')}}/admin/centreon">
								<i class="icon-angle-right"></i>
								Centreon
								</a>
							</li>
						</ul>
					</li>

					<li>
						<a href="javascript:void(0);">
							<i class="fas fa-recycle"></i>
							SERVICE DESK
						</a>
						<ul class="sub-menu">
							@if ($user->casvduse==1)
							<li>
								<a href="{{@Config::get('app.url')}}/admin/casvd">
								<i class="icon-angle-right"></i>
								CA Service Desk
								</a>
							</li>
							@endif
						</ul>
					</li>

					<li>
						<a href="javascript:void(0);">
							<i class="fas fa-random"></i>
							SDWAN
						</a>
						<ul class="sub-menu">
							@if ($user->ciscosdwanuse==1)
							<li>
								<a href="{{@Config::get('app.url')}}/admin/ciscosdwan">
								<i class="icon-angle-right"></i>
								Cisco SDWAN
								</a>
							</li>
							@endif
						</ul>
					</li>

					<li>
						<a href="javascript:void(0);">
							<i class="fas fa-cog"></i>
							CONFIGURATION
						</a>
						<ul class="sub-menu">
							<li>
								<a href="#">
								<i class="icon-angle-right"></i>
								Something
								</a>
							</li>
						</ul>
					</li>

					<li>
						<a href="javascript:void(0);">
							<i class="icon-bar-chart"></i>
							STATISTICS
						</a>
						<ul class="sub-menu">
							<li>
								<a href="#">
								<i class="icon-angle-right"></i>
								Something
								</a>
							</li>
						</ul>
					</li>
				</ul>

				<!-- /Navigation -->
				<div class="sidebar-title">
					<span>Notifications</span>
				</div>
				<ul class="notifications demo-slide-in"> <!-- .demo-slide-in is just for demonstration purposes. You can remove this. -->
					<li style="display: none;"> <!-- style-attr is here only for fading in this notification after a specific time. Remove this. -->
						<div class="col-left">
							<span class="label label-danger"><i class="icon-warning-sign"></i></span>
						</div>
						<div class="col-right with-margin">
							<span class="message">Server <strong>#512</strong> crashed.</span>
							<span class="time">few seconds ago</span>
						</div>
					</li>
					<li style="display: none;"> <!-- style-attr is here only for fading in this notification after a specific time. Remove this. -->
						<div class="col-left">
							<span class="label label-info"><i class="icon-envelope"></i></span>
						</div>
						<div class="col-right with-margin">
							<span class="message"><strong>John</strong> sent you a message</span>
							<span class="time">few second ago</span>
						</div>
					</li>
					<li>
						<div class="col-left">
							<span class="label label-success"><i class="icon-plus"></i></span>
						</div>
						<div class="col-right with-margin">
							<span class="message"><strong>Emma</strong>'s account was created</span>
							<span class="time">4 hours ago</span>
						</div>
					</li>
				</ul>

				<div class="sidebar-widget align-center">
					<div class="btn-group" data-toggle="buttons" id="theme-switcher">
						<label class="btn active">
							<input type="radio" name="theme-switcher" data-theme="bright"><i class="icon-sun"></i> Bright
						</label>
						<label class="btn">
							<input type="radio" name="theme-switcher" data-theme="dark"><i class="icon-moon"></i> Dark
						</label>
					</div>
				</div>

			</div>
			<div id="divider" class="resizeable"></div>
		</div>
		<!-- /Sidebar -->

		<div id="content">
			<div class="container">

				@section('content')
				@show
			</div>
			<!-- /.container -->

		</div>


	</div>

</body>
</html>
