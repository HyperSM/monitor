<!-- Menu -->
<div class="crumbs">
	<ul id="breadcrumbs" class="breadcrumb">
		<li>
			<i class="icon-home"></i>
			<a href="{{@Config::get('app.url')}}/admin/ciscosdwan">Admin Dashboard</a>
		</li>
	</ul>

	<ul class="crumb-buttons">
		<!-- <li class="dropdown"><a href="#" title="" data-toggle="dropdown"><i class="fas fa-chart-bar"></i><span>Reports</span><i class="icon-angle-down left-padding"></i></a>
			<ul class="dropdown-menu pull-right">
			<li><a href="#" title="">All Logs</a></li>
			</ul>
		</li> -->

		<li class="dropdown"><a href="#" title="" data-toggle="dropdown"><i class="fas fa-file-contract"></i><span>Templates</span><i class="icon-angle-down left-padding"></i></a>
			<ul class="dropdown-menu pull-right">
			<li><a href="{{@Config::get('app.url')}}/admin/ciscosdwan/templates" title="">All Templates</a></li>
			</ul>
		</li>

		<!--li><a href="charts.html" title=""><i class="icon-signal"></i><span>Statistics</span></a></li-->
		<li class="dropdown"><a href="#" title="" data-toggle="dropdown"><i class="icon-cog"></i><span>Settings</span><i class="icon-angle-down left-padding"></i></a>
			<ul class="dropdown-menu pull-right">
			@if ($user->slwnpmconfig==1)
			<li><a href="{{@Config::get('app.url')}}/admin/ciscosdwan/configserver" title="">VManages</a></li>
			@endif
			</ul>
		</li>

	</ul>
</div>
<!-- /End of menu -->
