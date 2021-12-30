@extends('/layout')

@section('content')

	@include('slwnpm.menu')

<div style="height: 10px;"></div>
<h4>
		Node Details - 
		@if (isset($device['StatusIcon']))
		<img src="http://{{$slwnpmserver->hostname}}/Orion/images/StatusIcons/small-{{$device['StatusIcon']}}">
		<font color="#006699">
			<?php if(isset($device['SysName'])){echo($device['SysName']);}?>
		</font>
		@endif
</h4>
<div style="height: 10px;"></div>
<table border="0">
	<tr>
		<td style="width:10px;"></td>
		<td>View Device By</td>
		<td style="width:20px;"></td>
		<td>
			<select onchange="selectview()" id="switchview">
				<option selected>Summary</option>
				<option>Network</option>
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
			<h4>Node Details</h4>
		</div>
		<div style="height: 10px;">
		</div>
		<div class="widget-content no-padding" align="center">
			<div id="slwnpmnodedetaildevice"></div>
			<table class="table table-striped table-no-inner-border" style="width:95%;">
				<tbody>
					<tr>
						<td style="border:none; width:150px;">NODE STATUS</td>
						<td style="border:none; width:40px;" align="center">
							@if(isset($device['StatusIcon']))
							<img src="http://{{$slwnpmserver->hostname}}/Orion/images/StatusIcons/{{$device['StatusIcon']}}"/>
							@endif
						</td>
						<td style="border:none;">
							<font color="#006699">
							<?php
							if(isset($device['StatusDescription'])){
								$a = explode(",",$device['StatusDescription']);
								foreach ($a as $item) {
									echo $item . '<br>';
								}
							}
							?>
							</font>
						</td>
					</tr>
					<tr>
						<td style="border:none;">POLLING IP ADDRESS</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							<font color="#006699">
							@if (isset($device['IPAddress']))
							{{$device['IPAddress']}}
							@endif
							</font>
						</td>
					</tr>
					<tr>
						<td style="border:none;">DYNAMIC IP</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							<?php if(isset($device['DynamicIP'])){echo ($device['DynamicIP']=='true'?'Yes':'No');}?>
						</td>
					</tr>
					<tr>
						<td style="border:none;">MACHINE TYPE</td>
						<td style="border:none; width:40px;" align="center">
							@if(isset($device['VendorIcon']))
							<img src="http://{{$slwnpmserver->hostname}}/NetPerfMon/Images/Vendors/{{$device['VendorIcon']}}"/>
							@endif
						</td>
						<td style="border:none;">
							@if (isset($device['Vendor']))
							{{$device['Vendor']}}
							@endif
						</td>              
					</tr>
					<tr>
						<td style="border:none;">NODE CATEGORY</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							@if (isset($device['Category']))
							<?php 
								switch ($device['Category']) {
									case '0':
										echo 'Other';
										break;
									case '1':
										echo 'Network';
										break;
									case '2':
										echo 'Server';
										break;
									default:
										# code...
										break;
								}
							?>
							@endif
						</td>
					</tr>

					<tr>
						<td style="border:none;">DNS</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							@if (isset($device['DNS']))
							{{$device['DNS']}}
							@endif
						</td>
					</tr>	

					<tr>
						<td style="border:none;">SYSTEM NAME</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							@if (isset($device['SysName']))
							{{$device['SysName']}}
							@endif
						</td>
					</tr>

					<tr>
						<td style="border:none;">DESCRIPTION</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							@if (isset($device['NodeDescription']))
							{{$device['NodeDescription']}}
							@endif
						</td>
					</tr>

					<tr>
						<td style="border:none;">LOCATION</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							@if (isset($device['Location']))
							{{$device['Location']}}
							@endif
						</td>
					</tr>

					<tr>
						<td style="border:none;">CONTACT</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							@if(isset($device['Contact']))
							{{$device['Contact']}}
							@endif
						</td>
					</tr>

					<tr>
						<td style="border:none;">SYSOBJECTID</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							@if (isset($device['SysObjectID']))
							{{$device['SysObjectID']}}
							@endif
						</td>
					</tr>

					<tr>
						<td style="border:none;">LAST BOOT</td>
						<td style="border:none;"></td>
						<td style="border:none;">
						@if(isset($device['LastBoot']))
						<?php
							$utc = $device['LastBoot'];
				            $dt = new DateTime($utc);
				            //echo 'Original: ', $dt->format('r'), PHP_EOL;
				            $tz = new DateTimeZone('Asia/Ho_Chi_Minh');
				            $dt->setTimezone($tz);
					    	echo $dt->format('M d, yy H:i:s');
						?>
						@endif
						</td>
					</tr>

					<tr>
						<td style="border:none;">SOFTWARE VERSION</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							@if(isset($device['IOSVersion']))
							{{$device['IOSVersion']}}
							@endif
						</td>
					</tr>

					<tr>
						<td style="border:none;">SOFTWARE IMAGE</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							@if(isset($device['IOSImage']))
							{{$device['IOSImage']}}
							@endif
						</td>
					</tr>

					<tr>
						<td style="border:none;">NO OF CPUs</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							@if(isset($device['CPUCount']))
							{{$device['CPUCount']}}
							@endif
						</td>
					</tr>

					<tr>
						<td style="border:none;">TELNET</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							@if(isset($device['IPAddress']))
							<a href="telnet://{{$device['IPAddress']}}">telnet://{{$device['IPAddress']}}</a>
							@endif
						</td>
					</tr>

					<tr>
						<td style="border:none;">WEB BROWSE</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							@if(isset($device['IPAddress']))
							<a href="http://{{$device['IPAddressGUID']}}">http://{{$device['IPAddress']}}</a>
							@endif
						</td>
					</tr>		
				</tbody>
			</table>
		</div>
	</div>

	<div class="widget box">
		<div class="widget-header">
			<h4>Polling Details</h4>
		</div>
		<div style="height: 10px;"></div>
		<div class="widget-content no-padding" align="center">
			<table class="table table-striped table-no-inner-border" style="width:95%;">
				<tbody>
					<tr>
						<td style="border:none; width:250px;">POLLING IP ADDRESS</td>
						<td style="border:none;">
							@if (isset($device['PoolIP']))
							{{$device['PoolIP']}}
							@endif
						</td>
					</tr>
					<tr>
						<td style="border:none;">POLLING ENGINE</td>
						<td style="border:none;">
							@if (isset($device['PoolDisplayName']))
							{{$device['PoolDisplayName']}}
							@endif
						</td>
					</tr>
					<tr>
						<td style="border:none;">POLLING INTERVAL</td>
						<td style="border:none;">
							@if(isset($device['PollInterval']))
							{{$device['PollInterval']}} seconds
							@endif
						</td>
					</tr>
					<tr>
						<td style="border:none;">NEXT POLL</td>
						<td style="border:none;">
						@if(isset($device['NextPoll']))
						<?php
							$utc = $device['NextPoll'];
				            $dt = new DateTime($utc);
				            //echo 'Original: ', $dt->format('r'), PHP_EOL;
				            $tz = new DateTimeZone('Asia/Ho_Chi_Minh');
				            $dt->setTimezone($tz);
					    	echo $dt->format('h:i A');
						?>
						@endif
						</td>
					</tr>
					<tr><td colspan="2" style="border:none;"></tr>	
					<tr>
						<td style="border:none;">STATISTICS COLLECTION</td>
						<td style="border:none;">
							@if (isset($device['StatCollection']))
							{{$device['StatCollection']}}
							@endif
							 minutes
						</td>
					</tr>
					<tr>
						<td style="border:none;">ENABLE 64 BIT COUNTERS</td>
						<td style="border:none;">
							@if (isset($device['Allow64BitCounters']))
							{{$device['Allow64BitCounters']==true?'true':'false'}}
							@endif
						</td>
					</tr>
					<tr><td colspan="2" style="border:none;"></tr>
					<tr>
						<td style="border:none;">REDISCOVERY INTERVAL</td>
						<td style="border:none;">
							@if (isset($device['RediscoveryInterval']))
							{{$device['RediscoveryInterval']}} minutes
							@endif
						</td>
					</tr>
					<tr>
						<td style="border:none;">NEXT REDISCOVERY</td>
						<td style="border:none;">
						@if (isset($device['NextRediscovery']))
						<?php
							$utc = $device['NextRediscovery'];
				            $dt = new DateTime($utc);
				            $tz = new DateTimeZone('Asia/Ho_Chi_Minh');
				            $dt->setTimezone($tz);
				            echo $dt->format('h:i A');
						?>
						@endif
						</td>
					</tr>
					<tr><td colspan="2" style="border:none;"></tr>	
					<tr>
						<td style="border:none;">LAST SYNC</td>
						<td style="border:none;">
						@if(isset($device['LastSync']))
						<?php
							$utc = $device['LastSync'];
				            $dt = new DateTime($utc);
				            $tz = new DateTimeZone('Asia/Ho_Chi_Minh');
				            $dt->setTimezone($tz);
					    	echo $dt->format('M d, yy H:i:s');
						?>
						@endif
						</td>
					</tr>	
				</tbody>
			</table>
		</div>
	</div>

	<div class="widget box">
		<div class="widget-header">
			<h4>Last 7 Days CPU Load Avg</h4>
		</div>
		<div style="height: 10px;"></div>
		<div class="widget-content no-padding" align="center">
			<table class="table table-striped table-no-inner-border" style="width:95%;">
				<thead bgcolor="#efeff5">
					<tr>
						<td style="border:none;" align="left" width="150px;">RESOURCE</td>
						<td style="border:none;" align="left">Min</td>
						<td style="border:none;" align="left">Max</td>
						<td style="border:none;" align="left">Avg</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="border:none;">
							<span>
							<img src="{{@Config::get('app.url')}}/images/slwnpm/small/cpu.png">
							</span>
							CPU Load
						</td>																	
						<td style="border:none;">
							<?php if(isset($cpuload['MinCPU'])) echo $cpuload['MinCPU']; ?>%
						</td>
						<td style="border:none;">
							<?php  if(isset($cpuload['MaxCPU'])) echo $cpuload['MaxCPU']; ?>%
						</td>
						<td style="border:none;">
							<?php  if(isset($cpuload['AvgCPU'])) echo $cpuload['AvgCPU']; ?>%
						</td>										
					</tr>
					<tr>
						<td style="border:none;">
							<span>
							<img src="{{@Config::get('app.url')}}/images/slwnpm/small/memory.gif">
							</span>
							Memory Usage
						</td>																	
						<td style="border:none;">
							<?php  if(isset($cpuload['MinMEM'])) echo round($cpuload['MinMEM']); ?>%
						</td>
						<td style="border:none;">
							<?php  if(isset($cpuload['MaxMEM'])) echo round($cpuload['MaxMEM']); ?>%
						</td>
						<td style="border:none;">
							<?php  if(isset($cpuload['AvgMEM'])) echo round($cpuload['AvgMEM']); ?>%
						</td>										
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="col-md-7">
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
							<img src="http://{{$slwnpmserver->hostname}}/NetPerfMon/images/small-{{$interface['StatusIcon']}}"/>
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
							<img src="http://{{$slwnpmserver->hostname}}/NetPerfMon/images/Interfaces/{{$interface['Icon']}}"/>
							@endif
						</td>
						<td style="border:none;">
							@if (isset($interface['FullName']))
							{{$interface['FullName']}}
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
			<h4>All IP Addresses on <?php if(isset($device['SysName'])){echo $device['SysName'];}?></h4>
		</div>
		<div style="height: 10px;">
		</div>
		<div class="widget-content no-padding" align="center">			
			<table class="table table-striped table-no-inner-border" style="width:95%;">
				<thead bgcolor="#efeff5">
					<tr>
						<td style="border:none;" align="left" width="150px;">IP VERSION</td>
						<td style="border:none;" align="left">IP ADDRESS</td>
						<td style="border:none;" align="left">SUBNET MASK</td>
					</tr>
				</thead>
				<tbody>
					@foreach ($ips as $ip)
					@if ($ip['IPAddress']==$device['IPAddress'])
					<tr>
						<td style="border:none;">
							<b>{{$ip['IPAddressType']}}</b>
						</td>																	
						<td style="border:none;">
							<b>{{$ip['IPAddress']}} Pooling Address</b>
						</td>
						<td style="border:none;">
							<b>{{$ip['SubnetMask']}}</b>
						</td>												
					</tr>
					@endif
					@endforeach

					@foreach ($ips as $ip)
					@if ($ip['IPAddress']!=$device['IPAddress'])
					<tr>
						<td style="border:none;">
							{{$ip['IPAddressType']}}
						</td>																	
						<td style="border:none;">
							{{$ip['IPAddress']}}
						</td>
						<td style="border:none;">
							{{$ip['SubnetMask']}}
						</td>												
					</tr>
					@endif
					@endforeach									
				</tbody>
			</table>
		</div>
	</div>

	<div class="widget box">
		<div class="widget-header">
			<h4>Last 24 Hours Event Summary</h4>
		</div>
		<div style="height: 10px;"></div>
		<div class="widget-content no-padding" align="center">
			<table class="table table-striped table-no-inner-border" style="width:95%;">
				<thead bgcolor="#efeff5">
					<tr>
						<td style="border:none;" align="centr" width="40px;"></td>
						<td style="border:none;" align="left" width="70px;">COUNT</td>
						<td style="border:none;" align="left">TYPE</td>
					</tr>
				</thead>
				<tbody>
					@foreach ($eventsums as $eventsum)
					<tr>
						<td style="border:none;">
							@if (strpos($eventsum['Name'], 'Up') !== false)
				                <img src="{{@Config::get('app.url')}}/images/slwnpm/small/up.gif" style="width:14px;">
				            @elseif (strpos($eventsum['Name'], 'Down') !== false)
				            	<img src="{{@Config::get('app.url')}}/images/slwnpm/small/down.gif" style="width:14px;">
				            @elseif (strpos($eventsum['Name'], 'Critical') !== false)
				            	<img src="{{@Config::get('app.url')}}/images/slwnpm/small/critical.gif" style="width:14px;">
				            @elseif (strpos($eventsum['Name'], 'Alert') !== false)
				                <img src="{{@Config::get('app.url')}}/images/slwnpm/small/alert.gif" style="width:14px;">
				            @elseif (strpos($eventsum['Name'], 'Warning') !== false)
				                <img src="{{@Config::get('app.url')}}/images/slwnpm/small/alert.gif" style="width:14px;">
				            @elseif (strpos($eventsum['Name'], 'Fail') !== false)
				                <img src="{{@Config::get('app.url')}}/images/slwnpm/small/failed.gif" style="width:14px;">
				            @elseif (strpos($eventsum['Name'], 'Remove') !== false)
				                <img src="{{@Config::get('app.url')}}/images/slwnpm/small/remove.gif" style="width:14px;">
				            @elseif (strpos($eventsum['Name'], 'Remapped') !== false)
				                <img src="{{@Config::get('app.url')}}/images/slwnpm/small/remap.gif" style="width:14px;">
				            @elseif (strpos($eventsum['Name'], 'Rebooted') !== false)
				                <img src="{{@Config::get('app.url')}}/images/slwnpm/small/reboot.gif" style="width:14px;">
				            @else
				                <img src="{{@Config::get('app.url')}}/images/slwnpm/small/Info.gif" style="width:14px;">
							@endif						
						</td>																	
						<td style="border:none;">
							{{$eventsum['Total']}}
						</td>
						<td style="border:none;">
							{{$eventsum['Name']}}
						</td>									
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

	<div class="widget box">
		<div class="widget-header">
			<h4>All Alert On This Node</h4>
		</div>
		<div style="height: 10px;"></div>
		<div class="widget-content no-padding" align="center">
			<table class="table table-striped table-no-inner-border" style="width:95%;">
				<thead bgcolor="#efeff5">
					<tr>
						<td style="border:none;" align="left">ALERT NAME</td>
						<td style="border:none;" align="left">MESSAGE</td>
						<td style="border:none;" align="left">TRIGGER OBJECT</td>
						<td style="border:none;" align="left">ACTIVE TIME</td>
						<td style="border:none;" align="left">RELATED NODE</td>
					</tr>
				</thead>
				<tbody>
					@foreach ($alerts as $alert)
					<tr>																
						<td style="border:none;">
							<span>
							<img src="{{@Config::get('app.url')}}/images/slwnpm/small/critical.gif" style="width:14px;">
							</span>
							<font color="#006699">{{$alert['Name']}}</font>
						</td>
						<td style="border:none;">
							<font color="Red">{{$alert['AlertMessage']}}</font>
						</td>
						<td style="border:none;">
							<font color="#006699">{{$alert['ObjectName']}}</font>
						</td>
						<td td style="border:none;">
							<font color="Red">
							<?php
							$sdate = strtotime($alert['TriggerTimeStamp']);
				            $edate = strtotime(gmdate("Y-m-d\TH:i:s\Z"));
				            $phut = round(($edate - $sdate)/60);

				            if ($phut>60*24){
				                //$days = round($phut/(60*24));
				                //$hours = $phut - $phut/(60*24)*$days;
				                //$time = $days . ' days '. $hours . 'hours';
				                $time = '> 1d';
				            }elseif ($phut>60) {
				                $hours = floor($phut/60);
				                $mins = $phut - 60*$hours;
				                $time = $hours . 'h ' . $mins . 'm';
				            }else{
				                $time = $phut . 'm';
				            }
				            echo $time;
							?>
							</font>
						</td>
						<td style="border:none;">
							<font color="#006699">{{$device['SysName']}}</font>
						</td>							
					</tr>
					@endforeach
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
  			var url = '{{@Config::get('app.url')}}/admin/slwnpm/nodedetail/{{$nodeid}}';
  			window.location.href = url;
  		}else{
  			var url = '{{@Config::get('app.url')}}/admin/slwnpm/nodenetwork/{{$nodeid}}';
  			window.location.href = url;
  		}
  		
	}
	
</script>
@endsection

