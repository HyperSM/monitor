<!-- Menu -->
<div class="crumbs">
	<ul id="breadcrumbs" class="breadcrumb">
		<li>
			<i class="icon-home"></i>
			<a href="{{@Config::get('app.url')}}/admin/slwnpm">Solarwinds NPM Dashboard</a>
		</li>
	</ul>

	<ul class="crumb-buttons">
		<li><a href="{{@Config::get('app.url')}}/admin/slwnpm/nodes" title="">All Nodes</a></li>
		<li><a href="{{@Config::get('app.url')}}/admin/slwnpm/threshold" title="">Threshold</a></li>
		<li><a href="{{@Config::get('app.url')}}/admin/slwnpm/notify" title="">Notify</a></li>
		<li class="dropdown"><a href="#" title="" data-toggle="dropdown"><i class="fas fa-exclamation-triangle"></i><span>Alerts & Activity</span><i class="icon-angle-down left-padding"></i></a>
			<ul class="dropdown-menu pull-right">
			<li><a href="{{@Config::get('app.url')}}/admin/slwnpm/alerts" title="">Alerts</a></li>
			<li><a href="{{@Config::get('app.url')}}/admin/slwnpm/events" title="">Events</a></li>
			</ul>
		</li>

        <li class="dropdown"><a href="#" title="" data-toggle="dropdown"><i class="fas fa-exclamation-triangle"></i><span>Report</span><i class="icon-angle-down left-padding"></i></a>
            <ul class="dropdown-menu pull-right">
                <li><a href="{{@Config::get('app.url')}}/admin/slwnpm/report" title="">View Report</a></li>
            </ul>
        </li>

		<!--li><a href="charts.html" title=""><i class="icon-signal"></i><span>Statistics</span></a></li-->
		<li class="dropdown"><a href="#" title="" data-toggle="dropdown"><i class="icon-cog"></i><span>Settings</span><i class="icon-angle-down left-padding"></i></a>
			<ul class="dropdown-menu pull-right">
			@if ($user->slwnpmconfig==1)
			<li><a href="{{@Config::get('app.url')}}/admin/slwnpm/configserver" title="">Solarwinds Servers</a></li>
			<!-- <li><a href="{{@Config::get('app.url')}}/admin/slwnpm/configgroup" title="">View By Group</a></li> -->
			@endif
			</ul>
		</li>

	</ul>
</div>
<!-- /End of menu -->
