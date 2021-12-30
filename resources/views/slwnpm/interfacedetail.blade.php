@extends('/layout')

@section('content')

	@include('slwnpm.menu')

<div style="height: 10px;"></div>
<h4>
		<b>
		Interface Details - 
		<img id="StatusTitle" src=""/>
		<font color="#006699">
			@if(isset($device['FullName']))
			{{$device['FullName']}}
			@endif
		</font>
		</b>
		
</h4>
<div style="height: 10px;"></div>

<div class="col-md-5">
	<div class="widget box">
		<div class="widget-header">
			<h4>Percent Utilization</h4>
		</div>
		<div class="widget-content no-padding" align="center">
			<ul class="stats">
				<li>
					<div class="circular-chart demo-reload" data-percent="<?php if (isset($device['InPercentUtil'])){echo $device['InPercentUtil']; } ?>" <?php if (isset($device['InPercentUtil']) && $device['InPercentUtil']>50){ echo 'data-bar-color="#e25856"'; }?> >
						<span>
						@if (isset($device['InPercentUtil']))
						{{$device['InPercentUtil']}}
						@endif
						</span>
						%
					</div>
					% Receive Utilization
				</li>
				<li>
					<div class="circular-chart demo-reload" data-percent="<?php if (isset($device['OutPercentUtil'])){echo $device['OutPercentUtil']; } ?>" <?php if (isset($device['OutPercentUtil']) && $device['OutPercentUtil']>50){ echo 'data-bar-color="#e25856"'; }?> >
						<span>
						@if (isset($device['OutPercentUtil']))
						{{$device['OutPercentUtil']}}
						@endif
						</span>
						%
					</div>
					% Transmit Utilization
				</li>
			</ul>
		</div>
	</div>

	<div class="widget box">
		<div class="widget-header">
			<h4>Interface Details</h4>
		</div>
		<div style="height: 10px;">
		</div>
		<div class="widget-content no-padding" align="center">
			<table class="table table-striped table-no-inner-border" style="width:95%;">
				<tbody>					
					<tr>
						<td style="border:none; width:200px;">STATUS</td>
						<td style="border:none; width:40px;" align="center">

							<?php
								if (isset($device['OperStatusLED'])){
									//$imageurl = "http://". $slwnpmserver->hostname . "/NetPerfMon/images/" . $device['OperStatusLED'];
									echo '<img src="'. url('/') . "/images/slwnpm/NetPerfMon/images/" . $device['OperStatusLED'].'"/>';
								}
							?>
						</td>
						<td style="border:none;" colspan="2">
							<?php
								if (isset($device['StatusIcon'])){
									echo str_replace(".gif","",$device['StatusIcon']);
								}
							?>
						</td>              
					</tr>
					<tr>
						<td style="border:none;">NAME</td>
						<td style="border:none;"></td>
						<td style="border:none;" colspan="2">
							@if (isset($device['InterfaceCaption']))
							{{$device['InterfaceCaption']}}
							@endif
						</td>
					</tr>

					<tr>
						<td style="border:none;">ALIAS</td>
						<td style="border:none;"></td>
						<td style="border:none;" colspan="2">
							@if (isset($device['Alias']))
							{{$device['Alias']}}
							@endif
						</td>
					</tr>
					<tr>
						<td style="border:none;">INDEX</td>
						<td style="border:none;"></td>
						<td style="border:none;" colspan="2">
							@if (isset($device['Index']))
							{{$device['Index']}}
							@endif
						</td>
					</tr>
					<tr>
						<td style="border:none;">INTERFACE TYPE</td>
						<td style="border:none;">
							<?php
								if(isset($device['InterfaceIcon'])){
									//$imageurl = "http://" . $slwnpmserver->hostname . "/NetPerfMon/images/Interfaces/" . $device['InterfaceIcon'];
									echo '<img src="'. url('/') . "/images/slwnpm/NetPerfMon/images/Interfaces/" . $device['InterfaceIcon'].'"/>';
								}
							?>
						</td>
						<td style="border:none;" colspan="2">
							@if (isset($device['InterfaceTypeName']))
							{{$device['InterfaceTypeName']}}
							@endif
						</td>
					</tr>
					<tr>
						<td style="border:none;">MAC ADDRESS</td>
						<td style="border:none;"></td>
						<td style="border:none;" colspan="2">
							@if (isset($device['MAC']))
							{{$device['MAC']}}
							@endif
						</td>
					</tr>

					<tr>
						<td style="border:none;">IP ADDRESS</td>
						<td style="border:none;"></td>
						<td style="border:none;" colspan="2">
							@if (isset($device['IPAddress']))
							{{$device['IPAddress']}}
							@endif
						</td>
					</tr>

					<tr>
						<td style="border:none;">ADMINISTRATIVE STATUS</td>
						<td style="border:none;">
							<?php
								if(isset($device['AdminStatusLED'])){
									//$imageurl = "http://" . $slwnpmserver->hostname . "/NetPerfMon/images/Interfaces/" . $device['InterfaceIcon'];
									echo '<img src="'. url('/') . "/images/slwnpm/NetPerfMon/images/small-" . $device['AdminStatusLED'].'"/>';
								}
							?>
						</td>
						<td style="border:none;" colspan="2">
							<?php
								if (isset($device['AdminStatusLED'])){
									echo str_replace(".gif","",$device['AdminStatusLED']);
								}
							?>
						</td>
					</tr>

					<tr>
						<td style="border:none;">OPERATIONAL STATUS</td>
						<td style="border:none;">
							<?php
								if(isset($device['OperStatusLED'])){
									//$imageurl = "http://" . $slwnpmserver->hostname . "/NetPerfMon/images/Interfaces/" . $device['InterfaceIcon'];
									echo '<img src="'. url('/') . "/images/slwnpm/NetPerfMon/images/small-" . $device['OperStatusLED'].'"/>';
								}
							?>
						</td>
						<td style="border:none;" colspan="2">
							<?php
								if (isset($device['OperStatusLED'])){
									echo str_replace(".gif","",$device['OperStatusLED']);
								}
							?>
						</td>
					</tr>

					<tr>
						<td style="border:none;">LAST STATUS CHANGE</td>
						<td style="border:none;"></td>
						<td style="border:none;" colspan="2">
							@if(isset($device['InterfaceLastChange']))
							<?php
								$utc = $device['InterfaceLastChange'];
					            $dt = new DateTime($utc);
					            //echo 'Original: ', $dt->format('r'), PHP_EOL;
					            $tz = new DateTimeZone('Asia/Ho_Chi_Minh');
					            $dt->setTimezone($tz);
						    	echo $dt->format('M d, Y h:i A');
							?>
							@endif
						</td>
					</tr>
					<tr>
						<td style="border:none;" colspan="4"></td>						
					</tr>

					<tr>
						<td style="border:none;"></td>
						<td style="border:none;"></td>
						<td style="border:none;">RECEIVE</td>
						<td style="border:none;">TRANSMIT</td>
					</tr>

					<tr>
						<td style="border:none;">INTERFACE BANDWIDTH</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							@if (isset($device['InBandwidth']))
							{{$device['InBandwidth']/1000}} kbps
							@endif
						</td>
						<td style="border:none;">
							@if (isset($device['OutBandwidth']))
							{{$device['OutBandwidth']/1000}} kbps
							@endif
						</td>
					</tr>
					<tr>
						<td style="border:none;"><font color="#006699">CURRENT TRAFFIC</font></td>
						<td style="border:none;"></td>
						<td style="border:none;">
							<font color="#006699">
							@if (isset($device['Inbps']))
							{{$device['Inbps']}} bps
							@endif
							</font>
						</td>
						<td style="border:none;">
							<font color="#006699">
							@if (isset($device['Outbps']))
							{{$device['Outbps']}} bps
							@endif
							</font>
						</td>
					</tr>

					<tr>
						<td style="border:none;"><font color="#006699">PERCENT UTILIZATION</font></td>
						<td style="border:none;"></td>
						<td style="border:none;">
							<font color="#006699">
							@if (isset($device['InPercentUtil']))
							{{$device['InPercentUtil']}}%
							@endif
							</font>
						</td>
						<td style="border:none;">
							<font color="#006699">
							@if (isset($device['OutPercentUtil']))
							{{$device['OutPercentUtil']}}%
							@endif
							</font>
						</td>
					</tr>

					<tr>
						<td style="border:none;"><font color="#006699">PACKETS PER SECOND</font></td>
						<td style="border:none;"></td>
						<td style="border:none;">
							<font color="#006699">
							@if (isset($device['InPps']))
							{{$device['InPps']}} pps
							@endif
							</font>
						</td>
						<td style="border:none;">
							<font color="#006699">
							@if (isset($device['OutPps']))
							{{$device['OutPps']}} pps
							@endif
							</font>
						</td>
					</tr>

					<tr>
						<td style="border:none;">AVERAGE PACKET SIZE</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							@if (isset($device['InPktSize']))
							{{$device['InPktSize']}} bytes
							@endif
						</td>
						<td style="border:none;">
							@if (isset($device['OutPktSize']))
							{{$device['OutPktSize']}} bytes
							@endif
						</td>
					</tr>

					<tr>
						<td style="border:none;"></td>
						<td style="border:none;"></td>
						<td style="border:none;"></td>
						<td style="border:none;"></td>
					</tr>

					<tr>
						<td style="border:none;"></td>
						<td style="border:none;"></td>
						<td style="border:none;"></td>
						<td style="border:none;"></td>
					</tr>

					<tr>
						<td style="border:none;">MTU</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							@if (isset($device['MTU']))
							{{$device['MTU']/1000}} kbytes
							@endif
						</td>
						<td style="border:none;"></td>
					</tr>

					<tr>
						<td style="border:none;">CONFIGURED INTERFACE SPEED</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							@if (isset($device['Speed']))
							{{$device['Speed']/1000}} kbytes
							@endif
						</td>
						<td style="border:none;"></td>
					</tr>
					
					<tr>
						<td style="border:none;">COUNTER 64 SUPPORT</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							@if (isset($device['Counter64']))
							{{$device['Counter64']=='Y'?'Yes':'No'}}
							@endif
						</td>
						<td style="border:none;"></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<div class="widget box">
		<div class="widget-header">
			<h4>Interface Polling Details</h4>
		</div>
		<div style="height: 10px;"></div>
		<div class="widget-content no-padding" align="center">
			<table class="table table-striped table-no-inner-border" style="width:95%;">
				<tbody>
					<tr>
						<td style="border:none; width:240px;">POLLING ENGINE</td>
						<td style="border:none;">
							@if (isset($device['DisplayName'])&&isset($device['EngineIP']))
							{{$device['DisplayName']}} ({{$device['EngineIP']}})
							@endif
						</td>
					</tr>
					<tr>
						<td style="border:none;">POLLING INTERVAL</td>
						<td style="border:none;">
							@if (isset($device['PollInterval']))
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
							{{$device['StatCollection']}} minutes
							@endif
						</td>
					</tr>
					<tr>
						<td style="border:none;">ENABLE 64 BIT COUNTERS</td>
						<td style="border:none;">
							@if (isset($device['Counter64']))
							{{$device['Counter64']=='Y'?'Yes':'No'}}
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
							@if (isset($device['RediscoveryInterval']))
							{{$device['RediscoveryInterval']}} minutes
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
					            //echo 'Original: ', $dt->format('r'), PHP_EOL;
					            $tz = new DateTimeZone('Asia/Ho_Chi_Minh');
					            $dt->setTimezone($tz);
						    	echo $dt->format('M d, Y h:i A');
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
			<h4>Today Event Summary</h4>
		</div>
		<div style="height: 10px;"></div>
		<div class="widget-content no-padding" align="center">
			<table class="table table-striped table-no-inner-border" style="width:95%; border:none;" id="EVENTSUM" border="0">			
			</table>
		</div>
	</div>
</div>
<div class="col-md-7">
	<div class="widget box">
		<div class="widget-header">
			<h4>Percent Utilization - Chart</h4>
		</div>
		<div style="height: 10px;">
		</div>
		<div class="widget-content no-padding" align="center">
			<div id="chart_multiple" class="chart" style="width:95%;"></div>
		</div>
	</div>

	<div class="widget box">
		<div class="widget-header">
			<h4>In/Out Errors and Discards</h4>
		</div>
		<div style="height: 10px;">
		</div>
		<div class="widget-content no-padding" align="center">			
			<div id="chart_errordiscards" class="chart" style="width:95%;"></div>
		</div>
	</div>

	<div class="widget box">
		<div class="widget-header">
			<h4>Interface Downtime Last 24h</h4>
		</div>
		<div style="height: 20px;"></div>
		<div class="widget-content no-padding" align="center" id="DownTime" align="center">	
			<table style="width: 100%">
				<tr>
					<td style="width:20px;"></td>
					<td>
						<table id="DownTimeTable" border="0"></table>
					</td>
					<td style="width:20px;"></td>
				</tr>
			</table>			
		</div>
		<div style="height: 20px;"></div>
		<div class="widget-content no-padding" align="center" id="DownTime" align="left">
			<table syte="95%">
				<tr>
					<td><div style="background-color: #999999; width: 40px; height: 15px;">&nbsp;</div></td>
					<td>Unknown</td>
					<td style="width: 10px;"></td>
					<td><div style="background-color: #77BD2D; width: 40px; height: 15px;">&nbsp;</div></td>
					<td>Up</td>
					<td style="width: 10px;"></td>
					<td><div style="background-color: #E61929; width: 40px; height: 15px;">&nbsp;</div></td>
					<td>Down</td>
					<td style="width: 10px;"></td>
					<td><div style="background-color: #FCD928; width: 40px; height: 15px;">&nbsp;</div></td>
					<td>Warning</td>
					<td style="width: 10px;"></td>
					<td><div style="background-color: #F99D1C; width: 40px; height: 15px;">&nbsp;</div></td>
					<td>Shutdown</td>
					<td style="width: 10px;"></td>
					<td><div style="background-color: #3366ff; width: 40px; height: 15px;">&nbsp;</div></td>
					<td>Unmanaged</td>
					<td style="width: 10px;"></td>
					<td><div style="background-color: #a8d8e8; width: 40px; height: 15px;">&nbsp;</div></td>
					<td>Unplugged</td>					
				</tr>
			</table>
		</div>
		<div style="height: 20px;"></div>

	</div>

<div id="eventsummarydiv" style="display: none;"></div>
<div id="percentutildiv" style="display: none;"></div>
<div id="errordiscardsdiv" style="display: none;"></div>
<div id="downtimediv" style="display: none;"></div>

<script>

	// $(window).bind("load", function() {
	// });

	$(document).ready(function(){
	 	$('#eventsummarydiv').load("<?php echo URL::route('slwnpm.intdetail.eventsum',["+$interfaceid+"]) ?>");
	 	$('#percentutildiv').load("<?php echo URL::route('slwnpm.intdetail.percentutil',["+$interfaceid+"]) ?>");
	 	$('#errordiscardsdiv').load("<?php echo URL::route('slwnpm.intdetail.errordiscards',["+$interfaceid+"]) ?>");
	 	$('#downtimediv').load("<?php echo URL::route('slwnpm.intdetail.downtime',["+$interfaceid+"]) ?>");
		
	});

  	$(document).ajaxComplete(function(){
  		//Last 24h event sum
        var eventsum = JSON.parse(eventsummarydiv.innerHTML);

		var table = document.querySelector("#EVENTSUM");
        var tbody = document.createElement("tbody");

        for(i=0;i<eventsum.length;i++) {
	        var row = document.createElement("tr");

	        //Cột 1
	        var cellElement = document.createElement("td");
	        var tmpstr = eventsum[i]['Name'];

	        if(tmpstr.includes("Up")!=0){
	        	imgurl = "{{@Config::get('app.url')}}/images/slwnpm/small/up.gif";
	        }else if (tmpstr.includes("Down")!=0){
	        	imgurl = "{{@Config::get('app.url')}}/images/slwnpm/small/down.gif";
	        }else if (tmpstr.includes("Critical")!=0){
	        	imgurl = "{{@Config::get('app.url')}}/images/slwnpm/small/critical.gif";
	        }else if (tmpstr.includes("Alert")!=0){
	        	imgurl = "{{@Config::get('app.url')}}/images/slwnpm/small/alert.gif";
	        }else if (tmpstr.includes("Warning")!=0){
	        	imgurl = "{{@Config::get('app.url')}}/images/slwnpm/small/alert.gif";
	        }else if (tmpstr.includes("Fail")!=0){
	        	imgurl = "{{@Config::get('app.url')}}/images/slwnpm/small/failed.gif";
	        }else if (tmpstr.includes("Remove")!=0){
	        	imgurl = "{{@Config::get('app.url')}}/images/slwnpm/small/remove.gif";
	        }else if (tmpstr.includes("Remapped")!=0){
	        	imgurl = "{{@Config::get('app.url')}}/images/slwnpm/small/remap.gif";
	        }else if (tmpstr.includes("Rebooted")!=0){
	        	imgurl = "{{@Config::get('app.url')}}/images/slwnpm/small/reboot.gif";
	        }else{
	        	imgurl = "{{@Config::get('app.url')}}/images/slwnpm/small/Info.gif";
	        }      
	        var img = document.createElement('img'); 
	        img.src = imgurl;        
	        cellElement.appendChild(img);
	        cellElement.setAttribute('style','text-align :left; border: none;');
	        row.appendChild(cellElement);

	        //Cột 2
	        cellElement = document.createElement("td");
	        cellContent = document.createTextNode(eventsum[i]['Total']);
	        cellElement.appendChild(cellContent);
	        cellElement.setAttribute('style','text-align :left; border: none;');
	        row.appendChild(cellElement);

	        //Cột 3
	        cellElement = document.createElement("td");
	        cellContent = document.createTextNode(eventsum[i]['Name']);
	        cellElement.appendChild(cellContent);
	        cellElement.setAttribute('style','text-align :left; border: none;');
	        row.appendChild(cellElement);	        

	        tbody.appendChild(row);
    	}
    	//$("#EVENTSUM").empty();
    	$("#EVENTSUM tbody tr").remove();
        table.appendChild(tbody);

        //Display utilize chart
        var i = 0;
        var inutil = [];
        var oututil = [];
        var alldata = JSON.parse(percentutildiv.innerText);
        for (i=0;i<alldata.length;i++){
        	var date = new Date("{{Date("Y")}}-{{Date("m")}}-{{Date("d")}} " + alldata[i]['STATTIME'] + ":00:00 UTC");
        	inutil.push([date,alldata[i]['InPercentUtil']]);
        	oututil.push([date,alldata[i]['OutPercentUtil']]);
        }
        //alert(alldata[0]['OutPercentUtil']);	

		var series_multiple = [
			{
				label: "In Percent Util (%)",
				data: inutil,
				color: App.getLayoutColorCode('red'),
				lines: {
					fill: false
				},
				points: {
					show: true
				}
			},{
				label: "Out Percent Util (%)",
				data: oututil,
				color: App.getLayoutColorCode('blue'),
				points: {
					show: true
				}
			}
		];

		// Initialize flot
		var plot = $.plot("#chart_multiple", series_multiple, $.extend(true, {}, Plugins.getFlotDefaults(), {
			series: {
				lines: { show: true },
				points: { show: true },
				grow: { active: true }
			},
			grid: {
				hoverable: true,
				clickable: true
			},
			tooltip: true,
			tooltipOpts: {
				content: '%s: %y'
			},
			xaxis: {
			    mode: "time",
			    timeformat: "%I:%M %P"
			}
		}));
        //Endof display chart

        //Display utilize chart
        var i = 0;
        var inerror = [];
        var outerror = [];
        var indiscard = [];
        var outdiscard = [];
        var alldata = JSON.parse(errordiscardsdiv.innerText);
        for (i=0;i<alldata.length;i++){
        	var date = new Date("{{Date("Y")}}-{{Date("m")}}-{{Date("d")}} " + alldata[i]['STATTIME'] + ":00:00 UTC");
        	inerror.push([date,alldata[i]['InErrors']]);
        	outerror.push([date,alldata[i]['OutErrors']]);
        	indiscard.push([date,alldata[i]['InDiscards']]);
        	outdiscard.push([date,alldata[i]['OutDiscards']]);
        }
        //alert(alldata[0]['OutPercentUtil']);	

		var series_multiple = [
			{
				label: "Receive Errors",
				data: inerror,
				color: App.getLayoutColorCode('red'),
				lines: {
					fill: false
				},
				points: {
					show: true
				}
			},{
				label: "Transmit Errors",
				data: outerror,
				color: App.getLayoutColorCode('blue'),
				points: {
					show: true
				}
			},
			{
				label: "Receive Discards",
				data: indiscard,
				color: App.getLayoutColorCode('purple'),
				points: {
					show: true
				}
			},
			{
				label: "Transmit Discards",
				data: outdiscard,
				color: App.getLayoutColorCode('yellow'),
				points: {
					show: true
				}
			}
		];

		// Initialize flot
		var plot = $.plot("#chart_errordiscards", series_multiple, $.extend(true, {}, Plugins.getFlotDefaults(), {
			series: {
				lines: { show: true },
				points: { show: true },
				grow: { active: true }
			},
			grid: {
				hoverable: true,
				clickable: true
			},
			tooltip: true,
			tooltipOpts: {
				content: '%s: %y'
			},
			xaxis: {
			    mode: "time",
			    timeformat: "%I:%M %P"
			},
			yaxis: {
				zeroAxis: true,
				min: 0
			}
		}));
        //End of display chart

        //UP down time
        var totalpixel = 1440;
        var scale = document.getElementById("DownTime").clientWidth - 20;
        //thực tế = field value * scale/totalpixel;

        var data = JSON.parse(downtimediv.innerText);
        var table = document.querySelector("#DownTimeTable");
        var tbody = document.createElement("tbody");
        var row = document.createElement("tr");
        for(i=0;i<data.length;i++) {
	        //Cột 1
	        var cellElement = document.createElement("td");
	        var statuscolor = '';
	        switch (data[i]['State']){
	        	case 1:
	        		statuscolor = " background-color: #77BD2D;";
	        		break;
	        	case 2:
	        		statuscolor = " background-color: #E61929;";
	        		break;
	        	case 3:
	        		statuscolor = " background-color: #FCD928;";
	        		break;
	        	case 4:
	        		statuscolor = " background-color: #F99D1C;";
	        		break;
	        	case 9:
	        		statuscolor = " background-color: #F99D1C;";
	        		break;
	        	case 10:
	        		statuscolor = " background-color: #a8d8e8;";
	        		break;
	        	default:
	        		statuscolor = " background-color: #999999;";
	        }
	        
	        cellElement.style = "width: " + Math.floor(data[i]['TotalDurationMin'] * (scale/totalpixel)) + "px; " + statuscolor + " height:10px; ";
	        //var title = Date(data[i]['DateTimeFrom']).toLocaleString('en-US', { timeZone: 'Asia/Ho_Chi_Minh' });
	        //var from = Date(data[i]['DateTimeFrom']).toLocaleString('en-US', { timeZone: 'Asia/Ho_Chi_Minh' }).replace("GMT+0700 (Indochina Time)","");
	        //var to = Date(data[i]['DateTimeUntil']).toLocaleString('en-US', { timeZone: 'Asia/Ho_Chi_Minh' }).replace("GMT+0700 (Indochina Time)","");
	        var options = {
			    timeZone: "Asia/Ho_Chi_Minh",
			    year: 'numeric', month: 'numeric', day: 'numeric',
			    hour: 'numeric', minute: 'numeric', second: 'numeric'
			};

			var formatter = new Intl.DateTimeFormat([], options);
			
			var UTCTimeFrom =  data[i]['DateTimeFrom'];
			var From = formatter.format(new Date(UTCTimeFrom));

			var UTCTimeTo =  data[i]['DateTimeUntil'];
			var To = formatter.format(new Date(UTCTimeTo));

	        cellElement.title = data[i]['TotalDurationMin'] + " minutes. From " + From + " to " + To;
	        row.appendChild(cellElement);	  
    	}
    	tbody.appendChild(row);
    	//$("#EVENTSUM").empty();
    	$("#DownTimeTable tbody tr").remove();
        table.appendChild(tbody);
        
  	});

	
</script>
@endsection

