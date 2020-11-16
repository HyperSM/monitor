@extends('/layout')

@section('content')

	@include('slwnpm.menu')

<div style="height: 10px;"></div>
	<h4>
		All Active Alerts
	</h4> 
<div style="height: 20px;"></div>

<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> Results</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<table class="table table-striped table-bordered table-hover table-checkable datatable" data-display-length="10">
					<thead>
						<tr>
							<th></th>
							<th>Alert Name</th>
							<th>Message</th>
							<th>Object that triggered this alert</th>
							<th>Trigger Time</th>
						</tr>
					</thead>
					<tbody>
						@if (isset($alerts) && count($alerts)>0)
						@foreach ($alerts as $alert)
						<tr>
							<td class="checkbox-column">
								<input type="checkbox" class="uniform">
							</td>
							<td>
								<span>
									<img src="{{@Config::get('app.url')}}/images/slwnpm/small/critical.gif"/>
								</span>
								<font color="red">
								@if(isset($alert['AlertName']))
								{{$alert['AlertName']}}
								@endif
								</font>
							</td>
							<td>
								@if(isset($alert['AlertMessage']))
								{{$alert['AlertMessage']}}
								@endif								
							</td>
							<td>
								<font color = "#006699">
								@if(isset($alert['StatusIcon']))
								<span>
									<img src="{{url('/')}}/images/slwnpm/StatusIcons/small-{{$alert['StatusIcon']}}">
								</span>
								@endif
								@if(isset($alert['RelatedNodeCaption']) && isset($alert['RelatedNodeId']))
								<a href="{{@Config::get('app.url')}}/admin/slwnpm/nodesummary/{{$alert['RelatedNodeId']}}">
									{{$alert['RelatedNodeCaption']}}
								</a>
								@endif
								</font>						
							</td>
							<td>
								@if(isset($alert['TriggerTime']))
								<?php
									$utc = $alert['TriggerTime'];
						            $dt = new DateTime($utc);

						            $tz = new DateTimeZone('Asia/Ho_Chi_Minh');
						            $dt->setTimezone($tz);
							    	echo $dt->format('M d, Y H:i A');
								?>
								@endif
							</td>
						</tr>
						@endforeach
						@endif									
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

@endsection