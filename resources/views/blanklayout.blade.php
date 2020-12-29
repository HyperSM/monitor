<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<title>Monitoring System</title>
	<link rel="shortcut icon" href="{{@Config::get('app.url')}}/template/assets/img/logo.png" type="image/x-icon" />

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
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>

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
	<script type="text/javascript" src="{{@Config::get('app.url')}}/template/plugins/datatables/jquery.dataTables.min.js"></script>
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
    <div class="container">
        @section('content')
        @show
    </div>
</body>

</html>