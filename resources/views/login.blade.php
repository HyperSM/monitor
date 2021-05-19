<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<title>Login | HyperSM</title>
	<link rel="shortcut icon" href="template/assets/img/logo.png" type="image/x-icon" />

	<!--=== CSS ===-->

	<!-- Bootstrap -->
	<link href="template/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

	<!-- Theme -->
	<link href="template/assets/css/main.css" rel="stylesheet" type="text/css" />
	<link href="template/assets/css/plugins.css" rel="stylesheet" type="text/css" />
	<link href="template/assets/css/responsive.css" rel="stylesheet" type="text/css" />
	<link href="template/assets/css/icons.css" rel="stylesheet" type="text/css" />

	<!-- Login -->
	<link href="template/assets/css/login.css" rel="stylesheet" type="text/css" />

	<link rel="stylesheet" href="template/assets/css/fontawesome/font-awesome.min.css">
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

	<script type="template/text/javascript" src="assets/js/libs/jquery-1.10.2.min.js"></script>

	<script type="template/text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
	<script type="template/text/javascript" src="assets/js/libs/lodash.compat.min.js"></script>

	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
		<script src="assets/js/libs/html5shiv.js"></script>
	<![endif]-->

	<!-- Beautiful Checkboxes -->
	<script type="template/text/javascript" src="plugins/uniform/jquery.uniform.min.js"></script>

	<!-- Form Validation -->
	<script type="template/text/javascript" src="plugins/validation/jquery.validate.min.js"></script>

	<!-- Slim Progress Bars -->
	<script type="template/text/javascript" src="plugins/nprogress/nprogress.js"></script>

	<!-- App -->
	<script type="template/text/javascript" src="assets/js/login.js"></script>
	<script>
	$(document).ready(function(){
		"use strict";

		Login.init(); // Init login JavaScript
	});
	</script>
</head>

<body class="login">
	<!-- Logo -->
	<div class="logo">
		<img src="template/assets/img/logo.png" style="width: 24px;" alt="logo" />
		<strong>HyperSM</strong>
	</div>
	<!-- /Logo -->

	<!-- Login Box -->
	<div class="box">
		<div class="content">
			<!-- Login Formular -->
			<form class="form-vertical login-form" action="doLogin" method="post">
				@csrf
				{{csrf_field()}}
				<!-- Title -->
				<h3 class="form-title">Sign In to your Account</h3>

				<!-- Error Message -->
				@if ($err_msg !='')
				<div class="alert fade in alert-danger" style="display: yes;">
					<i class="icon-remove close" data-dismiss="alert"></i>
					{{$err_msg}}
				</div>
				@endif

				<!-- Error Message -->
				<div class="alert fade in alert-danger" style="display: none;">
					<i class="icon-remove close" data-dismiss="alert"></i>
					Enter any username and password.
				</div>

				<!-- Input Fields -->
				<div class="form-group">
					<!--<label for="username">Username:</label>-->
					<div class="input-icon">
						<i class="icon-user"></i>
						<input type="text" name="username" class="form-control" placeholder="Username" autofocus="autofocus" data-rule-required="true" data-msg-required="Please enter your username." />
					</div>
				</div>
				<div class="form-group">
					<!--<label for="password">Password:</label>-->
					<div class="input-icon">
						<i class="icon-lock"></i>
						<input type="password" name="password" class="form-control" placeholder="Password" data-rule-required="true" data-msg-required="Please enter your password." />
					</div>
				</div>
				<!-- /Input Fields -->

				<!-- Form Actions -->
				<div class="form-actions">
					<button type="submit" class="submit btn btn-primary" style="margin: 0; left: 50%; -ms-transform: translate(-50%); transform: translate(-50%);">
						Sign In <i class="icon-angle-right"></i>
					</button>
				</div>
			</form>
			<!-- /Login Formular -->

		</div> <!-- /.content -->

		<!-- Forgot Password Form -->
		<div class="inner-box">
			<div class="content">
				<!-- Close Button -->
				<i class="icon-remove close hide-default"></i>

				<!-- Link as Toggle Button -->
				<a href="{{@Config::get('app.url')}}/sysadmin" class="forgot-password-link">Admin Login</a>

				<!-- Forgot Password Formular -->
				<form class="form-vertical forgot-password-form hide-default" action="doLogin" method="post">
					<!-- Input Fields -->
					<div class="form-group">
						<!--<label for="email">Email:</label>-->
						<div class="input-icon">
							<i class="icon-envelope"></i>
							<input type="text" name="email" class="form-control" placeholder="Enter email address" data-rule-required="true" data-rule-email="true" data-msg-required="Please enter your email." />
						</div>
					</div>
					<!-- /Input Fields -->

					<button type="submit" class="submit btn btn-default btn-block">
						Reset your Password
					</button>
				</form>
				<!-- /Forgot Password Formular -->

				<!-- Shows up if reset-button was clicked -->
				<div class="forgot-password-done hide-default">
					<i class="icon-ok success-icon"></i> <!-- Error-Alternative: <i class="icon-remove danger-icon"></i> -->
					<span>Great. We have sent you an email.</span>
				</div>
			</div> <!-- /.content -->
		</div>
		<!-- /Forgot Password Form -->
	</div>
	<!-- /Login Box -->


	<!-- Footer -->
	<div class="footer">
		@2020, FIS Service
	</div>
	<!-- /Footer -->
</body>
</html>