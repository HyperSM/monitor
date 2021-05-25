@extends('/layout')

@section('content')

	@include('ciscosdwan.menu')

<div style="height: 10px;"></div>
	<h4>
		Network
	</h4> 
<div style="height: 20px;"></div>

<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>All devices</h4>
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
							<th>No</th>
							<th>Host Name</th>
							<th>System IP</th>
							<th>Device Model</th>
							<th>Chassis Number / ID</th>
							<th>Status</th>
							<th>Reachability</th>
							<th>Site ID</th>
							<th>BFD</th>
							<th>Version</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($devices))
							<?php $i=0; ?>
							@foreach ($devices as $device)
							<?php $i=$i+1; ?>
							<tr>
								<td>
									{{$i}}
								</td>
								<td>
									<span>
										<img src="{{@Config::get('app.url')}}/images/ciscosdwan/{{$device->personality}}.png" style="width: 16px;">
									</span>
									<a style="text-decoration: none;" href="{{@Config::get('app.url')}}/admin/ciscosdwan/network/detail/{{$device->deviceId}}/systemstatus">
									<?php $x = 'host-name';?>
									{{$device->$x}}
									</a>
								</td>
								<td>
									<?php $x = 'system-ip';?>
									{{$device->$x}}
								</td>
								<td>
									<?php $x = 'device-model';?>
									{{$device->$x}}
								</td>
								<td>
									{{$device->uuid}}
								</td>
								<td align="center">									
									@if($device->status == "normal")
										<img src="{{@Config::get('app.url')}}/images/ciscosdwan/device_state_green.png">
					                @else
					                	<img src="{{@Config::get('app.url')}}/images/ciscosdwan/device_state_other.png">
					                @endif
								</td>
								<td>
									{{$device->reachability}}
								</td>
								<td>
									<?php $x = 'site-id';?>
									{{$device->$x}}
								</td>
								<td>
									<?php
										if (isset($device->bfdSessions)){echo $device->bfdSessions;}
									?>
								</td>
								<td>
									{{$device->version}}
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