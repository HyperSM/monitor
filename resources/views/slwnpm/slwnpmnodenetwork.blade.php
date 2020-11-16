@extends('/layout')

@section('content')

	@include('slwnpm.menu')

<div style="height: 10px;"></div>
<h4>
		<b>
		Node Details - 
		@if (isset($device['StatusIcon']))
		<img src="{{url('/')}}/images/slwnpm/StatusIcons/small-{{$device['StatusIcon']}}">
		@endif
		<font color="#006699">			
			<?php if(isset($device['SysName'])){echo($device['SysName']);}?>
		</font>
		</b>
		
</h4>
<div style="height: 10px;"></div>
<table border="0">
	<tr>
		<td style="width:10px;"></td>
		<td>View Device By</td>
		<td style="width:20px;"></td>
		<td>
			<select onchange="selectview()" id="switchview">
				<option selected>Network</option>
				<option>Summary</option>
			</select>
		</td>
	</tr>
</table>

<div style="height: 20px;"></div>

<div class="col-md-5">
	<div class="widget box">
		<div class="widget-header">
			<h4>Tools</h4>
		</div>
		
		<div class="widget-content no-padding" align="center">
		
		</div>
	</div>

	<div class="widget box">
		<div class="widget-header">
			<h4>Hardware Details</h4>
		</div>
		<div style="height: 10px;">
		</div>
		<div class="widget-content no-padding" align="center">
			<table width="100%" border="0">
				<tr>
					<td style="width: 70px;" align="center">
						@if (isset($hwhealth['StatusDescription']))
						<?php
							$tmp = $hwhealth['StatusDescription'];
							$tmp = $tmp=="Could Not Poll"?"Unknown":$tmp;
						?>
						<img src="{{url('/')}}/images/slwnpm/HardwareHealth/Images/Server_{{$tmp}}.png">
						@endif
					</td>
					<td align="left">
						<table style="width:95%;" border="0">				
							<tr>
								<td style="width: 200px;" align="left"><b>Hardware Status</b></td>						
								<td style="width: 10px;"></td>
								<td align="left">
									@if (isset($hwhealth['StatusDescription']))
									{{$hwhealth['StatusDescription']}}
									@endif
								</td>
							</tr>
							<tr>
								<td style="width: 200px;" align="left"><b>Manufacturer</b></td>						
								<td style="width: 10px;"></td>
								<td align="left">
									@if (isset($hwhealth['Manufacturer']))
									{{$hwhealth['Manufacturer']}}
									@endif
								</td>
							</tr>
							<tr>
								<td style="width: 200px;" align="left"><b>Model</b></td>						
								<td style="width: 10px;"></td>
								<td align="left">
									@if (isset($hwhealth['Model']))
									{{$hwhealth['Model']}}
									@endif
								</td>
							</tr>
							<tr>
								<td style="width: 200px;" align="left"><b>Service Tag</b></td>						
								<td style="width: 10px;"></td>
								<td align="left">
									@if (isset($hwhealth['ServiceTag']))
									{{$hwhealth['ServiceTag']}}
									@endif
								</td>
							</tr>
						</table>
						<div style="height: 10px;"></div>
					</td>
				</tr>				
			</table>
		</div>
	</div>

	<div class="widget box">
		<div class="widget-header">
			<h4>Active Vlans On Node</h4>
		</div>
		<div style="height: 10px;"></div>
		<div class="widget-content no-padding" align="center">
			<table class="table table-striped table-no-inner-border" style="width:95%;">
				<thead bgcolor="#efeff5">
					<tr>
						<td style="border:none;" align="left" width="150px;">VLANID</td>
						<td style="border:none;" align="left">NAME</td>
						<td style="border:none;" align="left">TAG</td>
					</tr>
				</thead>
				<tbody>
					@if (isset($vlans))
					@foreach ($vlans as $vlan)
					<tr>
						<td style="border:none;">
						@if (isset($vlan['VlanId']))
							<span>
								<img src="{{url('/')}}/images/slwnpm/Interfaces/Images/Icons/vlan_icon16x16_active.png"/>
							</span>
							<b>{{$vlan['VlanId']}}</b>
						@endif
						</td>																	
						<td style="border:none;">
						@if (isset($vlan['VlanName']))
							<font color="#006699">
							<b>{{$vlan['VlanName']}}</b>
							</font>
						@endif
						</td>
						<td style="border:none;">
						@if (isset($vlan['VlanTag']))
							{{$vlan['VlanTag']}}
						@endif
						</td>									
					</tr>
					@endforeach
					@endif
				</tbody>
			</table>
		</div>
	</div>

	<div class="widget box">
		<div class="widget-header">
			<h4>Routing Neighbors</h4>
		</div>
		<div style="height: 10px;"></div>
		<div class="widget-content no-padding" align="center">
			<table class="table table-striped table-no-inner-border" style="width:95%;">
				<thead bgcolor="#efeff5">
					<tr>
						<td style="border:none;" align="left">PROTOCOL</td>
						<td style="border:none;" align="left">STATUS</td>
						<td style="border:none;" align="left">IP ADDRESS</td>
						<td style="border:none;">LAST CHANGE</td>
					</tr>
				</thead>
				<tbody>
					@if (isset($r_neighbors))
					@foreach ($r_neighbors as $r_neighbor)
					<tr>
						<td style="border:none;">
						@if (isset($r_neighbor['DisplayName']))
							{{$r_neighbor['DisplayName']}}
						@endif
						</td>																	
						<td style="border:none;">
						@if (isset($r_neighbor['status']))
							{{$r_neighbor['status']}}
						@endif
						</td>
						<td style="border:none;">
						@if (isset($r_neighbor['NeighborIP']))
							{{$r_neighbor['NeighborIP']}}
						@endif
						</td>
						<td style="border:none;">
						@if(isset($flapping['lastchange']))
						<?php
							$utc = $r_neighbor['lastchange'];
				            $dt = new DateTime($utc);
				            //echo 'Original: ', $dt->format('r'), PHP_EOL;
				            $tz = new DateTimeZone('Asia/Ho_Chi_Minh');
				            $dt->setTimezone($tz);
					    	echo $dt->format('M d, yy H:i:s');
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

	<div class="widget box">
		<div class="widget-header">
			<h4>Routing Table</h4>
		</div>
		<div style="height: 10px;">
		</div>
		<div class="widget-content no-padding" align="center">
			<table class="table table-striped table-bordered table-hover table-checkable datatable" data-display-length="25" style="width:95%;">
				<thead bgcolor="#efeff5">
					<tr>
						<td style="border:none;" align="left" width="100px;">DESTINATION NETWORK</td>
						<td style="border:none;" align="left">NEXT HOP</td>
						<td style="border:none;" align="left"></td>
						<td style="border:none;" align="left">INTERFACE</td>
						<td style="border:none;" align="left" class="hidden-xs hidden-sm hidden-md">METRIC</td>
						<td style="border:none;" align="left" class="hidden-xs hidden-sm hidden-md">SOURCE</td>
					</tr>
				</thead>
				<tbody>
					@if (isset($routingtables))
					@foreach ($routingtables as $routingtable)
					<tr>
						<td style="border:none;">
							@if (isset($routingtable['RouteDestination']))
								{{$routingtable['RouteDestination']}}
							@endif
						</td>																	
						<td style="border:none;">
							<font color="#006699">
							@if (isset($routingtable['RouteNextHop']))
								{{$routingtable['RouteNextHop']}}
							@endif
							</font>
						</td>
						<td style="border:none; width:40px;">
							@if (isset($routingtable['StatusIcon']))							
							<img src="{{url('/')}}/images/slwnpm/NetPerfmon/Images/small-{{$routingtable['StatusIcon']}}"/>
							@endif
						</td>
						<td style="border:none;">
							<font color="#006699">
							@if (isset($routingtable['Caption']))
								{{$routingtable['Caption']}}
							@endif
							</font>
						</td>
						<td style="border:none;" class="hidden-xs hidden-sm hidden-md">
							@if (isset($routingtable['Metric']))
								{{$routingtable['Metric']}}
							@endif
						</td>
						<td style="border:none;" class="hidden-xs hidden-sm hidden-md">
							@if (isset($routingtable['ProtocolName']))
								{{$routingtable['ProtocolName']}}
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
<div class="col-md-7">
	<div class="widget box">
		<div class="widget-header">
			<h4>Last 10 Flapping Routes</h4>
		</div>
		<div style="height: 10px;">
		</div>
		<div class="widget-content no-padding" align="center">
			<table class="table table-striped table-no-inner-border" style="width:95%;">
				<thead bgcolor="#efeff5">
					<tr>
						<td style="border:none;" align="left" width="150px;">DESTINATION</td>
						<td style="border:none;" align="left">CIDR</td>
						<td style="border:none;" align="left">NEXT HOP</td>
						<td style="border:none;" align="left">LAST CHANGE</td>
					</tr>
				</thead>
				<tbody>
					@if (isset($flappings))
					@foreach ($flappings as $flapping)
					<tr>
						<td style="border:none;">
						@if (isset($flapping['RouteDestination']))
							{{$flapping['RouteDestination']}}
						@endif
						</td>																	
						<td style="border:none;">
						@if (isset($flapping['CIDR']))
							{{$flapping['CIDR']}}
						@endif
						</td>
						<td style="border:none;">
						@if (isset($flapping['RouteNextHop']))
							<font color="#006699">
							<i>{{$flapping['RouteNextHop']}}</i>
							</font>
						@endif
						</td>
						<td style="border:none;">
						@if(isset($flapping['DateTime']))
						<?php
							$utc = $flapping['DateTime'];
				            $dt = new DateTime($utc);
				            //echo 'Original: ', $dt->format('r'), PHP_EOL;
				            $tz = new DateTimeZone('Asia/Ho_Chi_Minh');
				            $dt->setTimezone($tz);
					    	echo $dt->format('M d, yy H:i:s');
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

	<div class="widget box">
		<div class="widget-header">
			<h4>List of VRFs on Node</h4>
		</div>
		<div style="height: 10px;"></div>
		<div class="widget-content no-padding" align="center">
			<table class="table table-striped table-no-inner-border" style="width:95%;">
				<thead bgcolor="#efeff5">
					<tr>
						<td style="border:none;" align="left">ROUTE DISTINGUISHER</td>
						<td style="border:none;" align="left">VRF NAME</td>
						<td style="border:none;" align="left">DESCRIPTION</td>
					</tr>
				</thead>
				<tbody>
					@if (isset($vrfs))
					@foreach ($vrfs as $vrf)
					<tr>
						<td style="border:none;">
						@if (isset($vrf['Status']))
							<?php
								switch ($vrf['Status']) {
									case '0':
										$img = url('/') . "/images/slwnpm/NetPerfmon/Images/small-Unknown.gif";
										break;
									case '1':
										$img = url('/') . "/images/slwnpm/NetPerfmon/Images/small-Up.gif";
										break;
									case '2':
										$img = url('/') . "/images/slwnpm/NetPerfmon/Images/small-Down.gif";
										break;
									case '3':
										$img = url('/') . "/images/slwnpm/NetPerfmon/Images/small-Warning.gif";
										break;
									case '4':
										$img = url('/') . "/images/slwnpm/NetPerfmon/Images/small-Shutdown.gif";
										break;
									case '5':
										$img = url('/') . "/images/slwnpm/NetPerfmon/Images/small-Testing.gif";
										break;
									case '6':
										$img = url('/') . "/images/slwnpm/NetPerfmon/Images/small-Dormant.gif";
										break;
									case '7':
										$img = url('/') . "/images/slwnpm/NetPerfmon/Images/small-Not-Present.gif";
										break;
									case '8':
										$img = url('/') . "/images/slwnpm/NetPerfmon/Images/small-Down.gif";
										break;
									case '9':
										$img = url('/') . "/images/slwnpm/NetPerfmon/Images/small-Unmanaged.gif";
										break;
									case '10':
										$img = url('/') . "/images/slwnpm/NetPerfmon/Images/small-Unplugged.gif";
										break;
									case '11':
										$img = url('/') . "/images/slwnpm/NetPerfmon/Images/small-External.gif";
										break;
									case '12':
										$img = url('/') . "/images/slwnpm/NetPerfmon/Images/small-Unreachable.gif";
										break;
									case '14':
										$img = url('/') . "/images/slwnpm/NetPerfmon/Images/small-Critical.gif";
										break;
									case '15':
										$img = url('/') . "images/slwnpm/NetPerfmon/Images/small-PartlyAvailable.gif";
										break;
									case '16':
										$img = url('/') . "/images/slwnpm/NetPerfmon/Images/small-Misconfigured.gif";
										break;
									case '17':
										$img = url('/') . "/images/slwnpm/NetPerfmon/Images/small-Undefined.gif";
										break;
									case '19':
										$img = url('/') . "/images/slwnpm/NetPerfmon/Images/small-Unconfirmed.gif";
										break;
									case '22':
										$img = url('/') . "/images/slwnpm/NetPerfmon/Images/small-Active.gif";
										break;
									case '24':
										$img = url('/') . "/images/slwnpm/NetPerfmon/Images/small-Inactive.gif";
										break;
									case '25':
										$img = url('/') . "/images/slwnpm/NetPerfmon/Images/small-Expired.gif";
										break;
									case '26':
										$img = url('/') . "/images/slwnpm/NetPerfmon/Images/small-Monitoring Disabled.gif";
										break;
									case '27':
										$img = url('/') . "/images/slwnpm/NetPerfmon/Images/small-Disabled.gif";
										break;
									case '28':
										$img = url('/') . "/images/slwnpm/NetPerfmon/Images/small-Not Licensed.gif";
										break;
									case '29':
										$img = url('/') . "/images/slwnpm/NetPerfmon/Images/small-Other.gif";
										break;
									case '30':
										$img = url('/') . "/images/slwnpm/NetPerfmon/Images/small-Not-Running.gif";
										break;
									default:
										# code...
										break;
								}
							?>
							<span>
								<img src="<?php echo $img;?>"/>
							</span>
							@if (isset($vrf['RouteDistinguisher']))
							<font color="#006699">
							{{$vrf['RouteDistinguisher']==''?'N/A':$vrf['RouteDistinguisher']}}
							</font>
							@endif
						@endif
						</td>																	
						<td style="border:none;">
						@if (isset($vrf['Name']))
							{{$vrf['Name']}}
						@endif
						</td>
						<td style="border:none;">
						@if (isset($vrf['Description']))
							{{$vrf['Description']}}
						@endif
						</td>									
					</tr>
					@endforeach
					@endif
				</tbody>
			</table>
		</div>
	</div>

	<div class="widget box">
		<div class="widget-header">
			<h4>Current Percent Utilization of Each Interface</h4>
		</div>
		<div style="height: 10px;">
		</div>
		<div class="widget-content no-padding" align="center">			
			<table class="table table-striped table-no-inner-border" style="width:95%;">
				<thead bgcolor="#efeff5">
					<tr>
						<td style="border:none; width:40px;"></td>
						<td style="border:none;" align="left">STATUS</td>
						<td style="border:none; width:40px;"></td>
						<td style="border:none;" align="left">INTERFACE</td>
						<td style="border:none;" align="left">TRANSMIT</td>
						<td style="border:none;" align="left">RECEIVE</td>
					</tr>
				</thead>
				<tbody>
					@foreach ($interfaces as $interface)
					<tr>
						<td style="border:none;">
							@if(isset($interface['StatusIcon']))
							<img src="{{url('/')}}/images/slwnpm/NetPerfMon/images/small-{{$interface['StatusIcon']}}"/>
							@endif
						</td>
						<td style="border:none;">
							@if(isset($interface['STATUS']))
							<?php
								switch ($interface['STATUS']) {
									case '0':
										echo 'Unknown';
										break;
									case '1':
										echo 'Up';
										break;
									case '2':
										echo 'Down';
										break;
									case '3':
										echo 'Warning';
										break;
									case '4':
										echo 'Shutdown';
										break;
									case '5':
										echo 'Testing';
										break;
									case '6':
										echo 'Dormant';
										break;
									case '7':
										echo 'Not Present';
										break;
									case '8':
										echo 'Lower Layer Down';
										break;
									case '9':
										echo 'Unmanaged';
										break;
									case '10':
										echo 'Unplugged';
										break;
									case '11':
										echo 'External';
										break;
									case '12':
										echo 'Unreachable';
										break;
									case '14':
										echo 'Critical';
										break;
									case '15':
										echo 'Partly Available';
										break;
									case '16':
										echo 'Misconfigured';
										break;
									case '17':
										echo 'Undefined';
										break;
									case '19':
										echo 'Unconfirmed';
										break;
									case '22':
										echo 'Active';
										break;
									case '24':
										echo 'Inactive';
										break;
									case '25':
										echo 'Expired';
										break;
									case '26':
										echo 'Monitoring Disabled';
										break;
									case '27':
										echo 'Disabled';
										break;
									case '28':
										echo 'Not Licensed';
										break;
									case '29':
										echo 'Other';
										break;
									case '30':
										echo 'Not Running';
										break;
									default:
										# code...
										break;
								}
							?>
							@endif
						</td>
						<td style="border:none;">
							@if (isset($interface['Icon']))
							<img src="{{url('/')}}/images/slwnpm/NetPerfMon/images/Interfaces/{{$interface['Icon']}}"/>
							@endif
						</td>
						<td style="border:none;">
							@if (isset($interface['FullName']))
							<a href="{{@Config::get('app.url')}}/admin/slwnpm/interfacedetail/{{$interface['InterfaceID']}}" style="text-decoration: none;">{{$interface['FullName']}}</a>
							@endif
						</td>
						<td style="border:none;">
							@if (isset($interface['OutPercentUtil']))
							{{$interface['OutPercentUtil']}}%
							<div class="progress progress-striped active" style="height:10px;">
								<div style="width: {{$interface['OutPercentUtil']}}%; height:10px;" class="progress-bar progress-bar-success"></div>
							</div>
							@endif
						</td>
						<td style="border:none;">
							@if (isset($interface['InPercentUtil']))
							{{$interface['InPercentUtil']}}%
							<div class="progress progress-striped active" style="height:10px;">
								<div style="width: {{$interface['InPercentUtil']}}%; height:10px;" class="progress-bar progress-bar-success"></div>
							</div>
							@endif
						</td>													
					</tr>
					@endforeach												
				</tbody>
			</table>
		</div>
	</div>

	<div class="widget box">
		<div class="widget-header">
			<h4>Current Cisco Buffer Misses</h4>
		</div>
		<div style="height: 10px;"></div>
		<div class="widget-content no-padding" align="center">
			<table class="table table-striped table-no-inner-border" style="width:95%;">
				<thead bgcolor="#efeff5">
					<tr>
						<td style="border:none;" align="left"></td>
						<td style="border:none;" align="left">THIS HOUR</td>
						<td style="border:none;" align="left">TODAY</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="border:none;">
							<font color="#006699">
								Out-Of-Memory Errors
							</font>
						</td>																	
						<td style="border:none;">
							<font color="#006699">
								@if (isset($device['BufferNoMemThisHour']))
								{{$device['BufferNoMemThisHour']}} misses
								@endif
							</font>
						</td>
						<td style="border:none;">
							<font color="#006699">
								@if (isset($device['BufferNoMemToday']))
								{{$device['BufferNoMemToday']}} misses
								@endif
							</font>
						</td>							
					</tr>
					<tr>
						<td style="border:none;">
							<font color="#006699">
								Small Buffer Misses
							</font>
						</td>																	
						<td style="border:none;">
							<font color="#006699">
								@if (isset($device['BufferSmMissThisHour']))
								{{$device['BufferSmMissThisHour']}} misses
								@endif
							</font>
						</td>
						<td style="border:none;">
							<font color="#006699">
								@if (isset($device['BufferSmMissToday']))
								{{$device['BufferSmMissToday']}} misses
								@endif
							</font>
						</td>							
					</tr>
					<tr>
						<td style="border:none;">
							<font color="#006699">
								Medium Buffer Misses
							</font>
						</td>																	
						<td style="border:none;">
							<font color="#006699">
								@if (isset($device['BufferMdMissThisHour']))
								{{$device['BufferMdMissThisHour']}} misses
								@endif
							</font>
						</td>
						<td style="border:none;">
							<font color="#006699">
								@if (isset($device['BufferMdMissToday']))
								{{$device['BufferMdMissToday']}} misses
								@endif
							</font>
						</td>							
					</tr>
					<tr>
						<td style="border:none;">
							<font color="#006699">
								Big Buffer Misses
							</font>
						</td>																	
						<td style="border:none;">
							<font color="#006699">
								@if (isset($device['BufferBgMissThisHour']))
								{{$device['BufferBgMissThisHour']}} misses
								@endif
							</font>
						</td>
						<td style="border:none;">
							<font color="#006699">
								@if (isset($device['BufferBgMissToday']))
								{{$device['BufferBgMissToday']}} misses
								@endif
							</font>
						</td>							
					</tr>
					<tr>
						<td style="border:none;">
							<font color="#006699">
								Large Buffer Misses
							</font>
						</td>																	
						<td style="border:none;">
							<font color="#006699">
								@if (isset($device['BufferLgMissThisHour']))
								{{$device['BufferLgMissThisHour']}} misses
								@endif
							</font>
						</td>
						<td style="border:none;">
							<font color="#006699">
								@if (isset($device['BufferLgMissToday']))
								{{$device['BufferLgMissToday']}} misses
								@endif
							</font>
						</td>							
					</tr>
					<tr>
						<td style="border:none;">
							<font color="#006699">
								Huge Buffer Misses
							</font>
						</td>																	
						<td style="border:none;">
							<font color="#006699">
								@if (isset($device['BufferHgMissThisHour']))
								{{$device['BufferBgMissThisHour']}} misses
								@endif
							</font>
						</td>
						<td style="border:none;">
							<font color="#006699">
								@if (isset($device['BufferBgMissToday']))
								{{$device['BufferHgMissToday']}} misses
								@endif
							</font>
						</td>							
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script>
	function selectview() {
		var e = document.getElementById("switchview");
		//var value = e.options[e.selectedIndex].value;
		var text = e.options[e.selectedIndex].text;
		
  		//alert($("#switchview :selected").text(););
  		if (text=='Summary'){
  			var url = '{{@Config::get('app.url')}}/admin/slwnpm/nodesummary/{{$nodeid}}';
  			window.location.href = url;
  		}else{
  			var url = '{{@Config::get('app.url')}}/admin/slwnpm/nodenetwork/{{$nodeid}}';
  			window.location.href = url;
  		}
  		
	}
</script>
@endsection

