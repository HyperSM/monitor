@extends('/layout')

@section('content')

@include('ciscosdwan.menu')

<div class="page-header">
	<div class="page-title">
		<h3>Cisco SDWAN</h3>
		<span>Company: {{$domain->company}} / Domain: {{$domain->domainname}}</span>
	</div>

</div>
<!-- /Page Header -->

<div id="alldevicesdiv" style="display: none;"></div>
<div id="wanedgehealthdiv" style="display: none;"></div>
<div id="transporthealth" style="display:none;"></div>

<!--=== Page Content ===-->
<!--=== Statboxes ===-->
<div class="row row-bg"> <!-- .row-bg -->

	<div class="col-md-2">
		<div class="statbox widget box box-shadow">
			<div class="widget-content">
				<div class="visual" style="padding: 0px; margin: 0px;">
					<img src="{{@Config::get('app.url')}}/images/ciscosdwan/vsmart.png" style="width: 50px;">
				</div>
				<div class="title">Total</div>
				<div class="value" id="vsmartcount">N/A</div>
				<a id="vs" class="click_devices open-options button more" href="#">VSMART <i class="pull-right icon-angle-right"></i></a>
			</div>
		</div> <!-- /.smallstat -->
	</div> <!-- /.col-md-2 -->

	<div class="col-md-2">
		<div class="statbox widget box box-shadow">
			<div class="widget-content">
				<div class="visual" style="padding: 0px; margin: 0px;">
					<img src="{{@Config::get('app.url')}}/images/ciscosdwan/wanedge.png" style="width: 50px;">
				</div>
				<div class="title">Total</div>
				<div class="value" id="wanedgecount">N/A</div>
				<a id="ve" class="click_devices open-options button more" href="#">WAN EDGE <i class="pull-right icon-angle-right"></i></a>
			</div>
		</div> <!-- /.smallstat -->
	</div> <!-- /.col-md-2 -->

	<div class="col-md-2 hidden-xs">
		<div class="statbox widget box box-shadow">
			<div class="widget-content">
				<div class="visual" style="padding: 0px; margin: 0px;">
					<img src="{{@Config::get('app.url')}}/images/ciscosdwan/vbond.png" style="width: 50px;">
				</div>
				<div class="title">Total</div>
				<div class="value" id="vbondcount">N/A</div>
				<a id="vb" class="click_devices open-options button more" href="#">VBOND <i class="pull-right icon-angle-right"></i></a>
			</div>
		</div> <!-- /.smallstat -->
	</div> <!-- /.col-md-2 -->

	<div class="col-md-2">
		<div class="statbox widget box box-shadow">
			<div class="widget-content">
				<div class="visual" style="padding: 0px; margin: 0px;">
					<img src="{{@Config::get('app.url')}}/images/ciscosdwan/vmanage.png" style="width: 50px;">
				</div>
				<div class="title">Total</div>
				<div class="value" id="vmanagecount">N/A</div>
				<a id="vm" class="click_devices open-options button more" href="#">VMANAGE <i class="pull-right icon-angle-right"></i></a>
			</div>
		</div> <!-- /.smallstat -->
	</div> <!-- /.col-md-2 -->

	<div class="col-md-2">
		<div class="statbox widget box box-shadow" style="height: 90px;">
			<div class="widget-content" style="height: 65px;">
				<div class="visual" style="padding: 0px; margin: 0px;">
					<img src="{{@Config::get('app.url')}}/images/ciscosdwan/reboot.png" style="width: 50px;">
				</div>
				<div class="title">Last 24h</div>
				<div class="value" id="rebootcount">N/A</div>
				<!-- <a class="more" href="javascript:void(0);">REBOOT <i class="pull-right icon-angle-right"></i></a> -->
			</div>
		</div> <!-- /.smallstat -->
	</div> <!-- /.col-md-2 -->

	<div class="col-md-2">
		<div class="statbox widget box box-shadow" style="height: 90px;">
			<div class="widget-content" style="height: 65px;">
				<div class="visual" style="padding: 0px; margin: 0px; height: 55px;">
					<img src="{{@Config::get('app.url')}}/images/ciscosdwan/certificates.png" style="width: 50px;">
				</div>
				<div class="title" id="warningcount">Warning</div>
				<div class="title" id="invalidcount">Invalid</div>
				<!-- <a class="more" href="javascript:void(0);">CERTIFICATES <i class="pull-right icon-angle-right"></i></a> -->
			</div>
		</div> <!-- /.smallstat -->
	</div> <!-- /.col-md-2 -->

</div> <!-- /.row -->
<!-- /Statboxes -->


<div class="row">
	<div class="col-md-4">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>Control status</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content" style="height: 350px; vertical-align: middle;">
				<div class="ct-control-status" style="overflow: overlay; border:none;" align="center">
            		<!-- <div id="mytree"></div> -->
            		<div id="controlstatus"></div>
          		</div>
			</div>

		</div>
	</div>

	<div class="col-md-8">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> Map</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>

			<div class="widget-content">
				<!--div id="gmap_markers" class="gmaps"></div-->
				<div id="ciscosdwanmap" style="height: 325px; width: 100%"></div>
			</div>
		</div>
	</div> <!-- /.col-md-12 -->
</div> <!-- /.row -->

<div class="row">
	<div class="col-md-4">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>WAN Edge Inventory</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<div id="wanedgeinventory" style="height: 150px; width: 100%"></div>
			</div>
		</div>
	</div>
	<!-- /Condensed Table -->
	<div class="col-md-4">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>Site Health</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
            	<div id="sitehealth" style="height: 150px; overflow: overlay; border:none;"></div>
			</div>

		</div>
	</div>
	<div class="col-md-4">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>Transport Interface Distribution</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
            	<div id="transportinterface" style="height: 150px; overflow: overlay; border:none;"></div>
			</div>

		</div>
	</div>
</div>

<div class="row">

	<!--=== Condensed Table ===-->
	<div class="col-md-4">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>WAN Edge Health</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div style="height: 100px;"></div>
			<div class="row" align="center" style="vertical-align: middle;">
				<div class="col-md-4">
					<h5>Normal</h5>
					<input class="knob" data-width="100" data-angleOffset="-90" data-angleArc="360" value="0" data-readOnly="true" id="normal">
				</div>
				<div class="col-md-4">
					<h5>Warning</h5>
					<input class="knob" data-width="100" data-angleOffset="-90" data-angleArc="360" data-fgColor="#eb7d34" value="0" data-readOnly="true" id="warning">
				</div>
				<div class="col-md-4">
					<h5>Error</h5>
					<input class="knob" data-width="100" data-angleOffset="-90" data-angleArc="360" data-fgColor="red" value="0" data-readOnly="true" id="error">
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-8">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>Transport Health</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<div style="height: 320px; overflow: overlay; border:none;">
					<div id="transporthealth_chart" style="height: 320px;"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-4">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>Server Detail</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<div id="serverdetail" style="height: 300px; overflow: overlay; border:none;"></div>
			</div>
		</div>
	</div>
	<div class="col-md-8">
		<div class="widget box">
			<div class="widget-header">
				<h4>Last 10 Alarms in 24 hours</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<div id="alarms" style="height: 300px; overflow: overlay; border:none;"></div>
			</div>
		</div>
	</div>
</div> <!-- /.row -->

<div id="modal" data-izimodal-group="group1" data-izimodal-loop="" data-izimodal-title="Cisco SDWAN">
	<span><img src="{{@Config::get('app.url')}}/images/ajax.jpg" style="width: 70px;" id="ajax"></span><span id="message"></span>
    <table class="table table-hover table-striped table-bordered table-highlight-head" id='showDetailDevices'>
    	<thead>

    	</thead>
    	<tbody>
    	</tbody>
    </table>
</div>

<script>

	$( document ).ready(function() {
		$('#ciscosdwanmap').vectorMap({
          map: 'world_mill',
          scaleColors: ['#C8EEFF', '#0071A4'],
          normalizeFunction: 'polynomial',
          hoverOpacity: 0.7,
          hoverColor: false,
          markerStyle: {
            initial: {
              fill: '#F8E23B',
              stroke: '#383f47'
            }
          },
          backgroundColor: '#383f47',
          markers: [
            //{latLng: [41.90, 12.45], name: 'A', style: {fill: '#03fc1c'}},
            //{latLng: [43.73, 7.41], name: 'B', style: {fill: 'red'}},
          ]
        });

	    var query = '<?php echo URL::route('ciscosdwan.dashboard.alldevices') ?>';
	    $('#alldevicesdiv').load(query);

	    var query = '<?php echo URL::route('ciscosdwan.dashboard.rebootcount') ?>';
	    $('#rebootcount').load(query);

	    var query = '<?php echo URL::route('ciscosdwan.dashboard.warningcount') ?>';
	    $('#warningcount').load(query);

	    var query = '<?php echo URL::route('ciscosdwan.dashboard.invalidcount') ?>';
	    $('#invalidcount').load(query);

	    var query = '<?php echo URL::route('ciscosdwan.dashboard.wanedgehealth') ?>';
	    $('#wanedgehealthdiv').load(query);

	    var query = '<?php echo URL::route('ciscosdwan.dashboard.controlstatus') ?>';
	    $('#controlstatus').load(query);

	    var query = '<?php echo URL::route('ciscosdwan.dashboard.wanedgeinventory') ?>';
	    $('#wanedgeinventory').load(query);

	    var query = '<?php echo URL::route('ciscosdwan.dashboard.sitehealth') ?>';
	    $('#sitehealth').load(query);

	    var query = '<?php echo URL::route('ciscosdwan.dashboard.transportinterface') ?>';
	    $('#transportinterface').load(query);

	    var query = '<?php echo URL::route('ciscosdwan.dashboard.transporthealth') ?>';
	    $('#transporthealth').load(query);

	    var query = '<?php echo URL::route('ciscosdwan.dashboard.alarms') ?>';
	    $('#alarms').load(query);

	    var query = '<?php echo URL::route('ciscosdwan.dashboard.serverdetail') ?>';
	    $('#serverdetail').load(query);

	});

	setInterval(function(){
	    var query = '<?php echo URL::route('ciscosdwan.dashboard.alldevices') ?>';
	    $('#alldevicesdiv').load(query);

	    var query = '<?php echo URL::route('ciscosdwan.dashboard.rebootcount') ?>';
	    $('#rebootcount').load(query);

	    var query = '<?php echo URL::route('ciscosdwan.dashboard.warningcount') ?>';
	    $('#warningcount').load(query);

	    var query = '<?php echo URL::route('ciscosdwan.dashboard.invalidcount') ?>';
	    $('#invalidcount').load(query);

	    var query = '<?php echo URL::route('ciscosdwan.dashboard.wanedgehealth') ?>';
	    $('#wanedgehealthdiv').load(query);

	    var query = '<?php echo URL::route('ciscosdwan.dashboard.controlstatus') ?>';
	    $('#controlstatus').load(query);

	    var query = '<?php echo URL::route('ciscosdwan.dashboard.wanedgeinventory') ?>';
	    $('#wanedgeinventory').load(query);

	    var query = '<?php echo URL::route('ciscosdwan.dashboard.sitehealth') ?>';
	    $('#sitehealth').load(query);

	    var query = '<?php echo URL::route('ciscosdwan.dashboard.transportinterface') ?>';
	    $('#transportinterface').load(query);

	    var query = '<?php echo URL::route('ciscosdwan.dashboard.transporthealth') ?>';
	    $('#transporthealth').load(query);

	    var query = '<?php echo URL::route('ciscosdwan.dashboard.alarms') ?>';
	    $('#alarms').load(query);

	    var query = '<?php echo URL::route('ciscosdwan.dashboard.serverdetail') ?>';
	    $('#serverdetail').load(query);
	},120000);

    $(document).ajaxComplete(function(event,xhr,settings){
	    //console.log("URL",settings.url);
	    if(settings.url.indexOf('ciscosdwan.dashboard.alldevices')>0){
	    	var tmp = alldevicesdiv.textContent;
	    	if (tmp!=''){
    			var devices = JSON.parse(tmp);
    			var i;
    			var vmanage = 0;
    			var vsmart = 0;
    			var vbond = 0;
    			var wanedge = 0;
    			var color = '';

    			var markers = [];

    			for(i=0;i<devices.length;i++){
    				switch (devices[i]['personality']){
    					case 'vmanage':
    						vmanage = vmanage + 1;
    						break;
    					case 'vsmart':
    						vsmart = vsmart + 1;
    						break;
    					case 'vbond':
    						vbond = vbond + 1;
    						break;
    					case 'vedge':
    						wanedge = wanedge + 1;
    						break;
    					default:
    				}

    				switch (devices[i]['status']){
    					case 'normal':
    						color = '#03fc1c';
    						break;
    					default:
    						color = 'red';
    						break;
    				}
    				markers.push({latLng: [devices[i]['latitude'], devices[i]['longitude']], name: devices[i]['host-name'], style: {fill: color}});
    			}
    			vmanagecount.innerText = vmanage;
    			vsmartcount.innerText = vsmart;
    			vbondcount.innerText = vbond;
    			wanedgecount.innerText = wanedge;


    			$('#ciscosdwanmap').empty();
				$('#ciscosdwanmap').vectorMap({
				  map: 'world_mill',
		          scaleColors: ['#C8EEFF', '#0071A4'],
		          normalizeFunction: 'polynomial',
		          hoverOpacity: 0.7,
		          hoverColor: false,
		          markerStyle: {
		            initial: {
		              fill: '#F8E23B',
		              stroke: '#383f47'
		            }
		          },
		          backgroundColor: '#383f47',
		          markers: markers
				});

	    	}
	    }else if(settings.url.indexOf('ciscosdwan.dashboard.wanedgehealth')>0){
	    	if(wanedgehealthdiv.innerText!=''){
	    		var datas = JSON.parse(wanedgehealthdiv.innerText);
	    		var i;
	    		for (i=0;i<datas.length;i++){
	    			if(datas[i]['name']=='normal'){
	    				$('#normal').val(datas[i]['count']).trigger('change');
	    			}else if(datas[i]['name']=='warning'){
	    				$('#warning').val(datas[i]['count']).trigger('change');
	    			}else if(datas[i]['name']=='error'){
	    				$('#error').val(datas[i]['count']).trigger('change');
	    			}
	    		}
	    	}
	    }else if(settings.url.indexOf('ciscosdwan.dashboard.transporthealth')>0){

	        //Display utilize chart
	        var i = 0;
	        var loss_percentage = [];
	        var latency = [];
	        var jitter = [];
	        var alldata = JSON.parse(transporthealth.innerText);
	        for (i=0;i<alldata.length;i++){
	        	var date = new Date("{{Date("Y")}}-{{Date("m")}}-{{Date("d")}} " + alldata[i]['entry_time'] + ":00:00 UTC");
	        	loss_percentage.push([date,alldata[i]['loss_percentage']]);
	        	latency.push([date,alldata[i]['latency']]);
	        	jitter.push([date,alldata[i]['jitter']]);
	        }
	        //alert(alldata[0]['OutPercentUtil']);

			var series_multiple = [
				{
					label: "Avg. Loss (ms)",
					data: loss_percentage,
					color: App.getLayoutColorCode('red'),
					lines: {
						fill: false
					},
					points: {
						show: true
					}
				},{
					label: "Avg. Latency (ms)",
					data: latency,
					color: App.getLayoutColorCode('blue'),
					points: {
						show: true
					}
				},
				{
					label: "Avg. Jitter (ms)",
					data: jitter,
					color: App.getLayoutColorCode('purple'),
					points: {
						show: true
					}
				}
			];

			// Initialize flot
			var plot = $.plot("#transporthealth_chart", series_multiple, $.extend(true, {}, Plugins.getFlotDefaults(), {
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
	    }
	});


	$('#modal').iziModal({
	    headerColor: '#4d7496',
	    width: '70%',
	    overlayColor: 'rgba(0, 0, 0, 0.5)',
	    fullscreen: true,
	    transitionIn: 'fadeInUp',
	    transitionOut: 'fadeOutDown',
	    bodyOverflow: true,
	    padding: 10
   	});

   	$(document).on('closed', '#modal', function (e) {
       	$("#wrapper").css({
              'zIndex' : 'unset'
      	})
   	});

   	$(document).on('opening', '#modal', function (e) {
       	$("#wrapper").css({
              'zIndex' : -1
      	})
   	});


	$(document).on('click', '.click_devices', function (event) {
	    event.preventDefault();
	    //var modal = $('#modal').iziModal();
		$('#modal').iziModal('open');
		$('#modal').iziModal('setZindex', 99999999);

		var devicetype = $(this).attr("id");

		switch(devicetype){
			case 've':
			case 'vb':
			case 'vs':
			case 'vm':
				/*Click vào device*/
				message.innerText = 'Please be patient while data is loading. Do not close this window.';
				$("#showDetailDevices tbody tr").remove();

				$("#showDetailDevices thead tr").remove();
		        $("#showDetailDevices thead").html(
		           	'<tr>'+
		              	'<th>Reachability</th>' +
		              	'<th>Hostname</th>'+
		              	'<th>System IP</th>'+
		              	'<th>Site ID</th>'+
				        '<th>BFD</th>'+
				        '<th>OMP</th>'+
				        '<th>Control</th>'+
				        '<th>Version</th>'+
				        '<th>Chassis Number/ID</th>'+
				        '<th>Serial Number</th>'+
		          	'</tr>'
		        );

		        document.getElementById("ajax").style.display = "inline";
		        $.ajax({
			        url: '<?php echo URL::route('ciscosdwan.dashboard.alldevices') ?>',
			        type:"GET",
			        data:{
			            _token: "{{ csrf_token() }}"
			        },
			        success:function(response){
			        	//document.getElementById('message').value = "";
			            if(response) {
			            	var data = JSON.parse(response);
			                message.innerText ='';
			                document.getElementById("ajax").style.display = "none";
			                var html= '';
			                var i = 0;
			                for (i=0;i<data.length;i++){
			                	if (data[i]["personality"].substr(0,2)==devicetype){
			                		html = html + '<tr>'+
					                '<td>'+data[i]["reachability"]+'</td>'+
					                '<td>'+data[i]["host-name"]+'</td>'+
					                '<td>'+data[i]["system-ip"]+'</td>'+
					                '<td>'+data[i]["site-id"]+'</td>'+
					                '<td>'+data[i]["bfdSessionsUp"]+'</td>'+
					                '<td>'+data[i]["ompPeers"]+'</td>'+
					                '<td>'+data[i]["controlConnections"]+'</td>'+
					                '<td>'+data[i]["version"]+'</td>'+
					                '<td>'+data[i]["uuid"]+'</td>'+
					                '<td>'+data[i]["board-serial"]+'</td>'+
				               		'</tr>';
			                	}
		               		}

		               		$("#showDetailDevices tbody tr").remove();
		               		$("#showDetailDevices tbody").html(html);

			            }
			        },
			        error: function (xhr, textStatus, errorThrown) {

		         	}
			    });
				/*Kết thúc device*/
				break;
			case 'Control up':
			case 'Control down':
			case 'Partial':
				if (devicetype=='Control up'){
					$url= '<?php echo URL::route("ciscosdwan.dashboard.ajaxcontrol",["up"]) ?>';
				}else if (devicetype=='Control down'){
					$url= '<?php echo URL::route("ciscosdwan.dashboard.ajaxcontrol",["down"]) ?>';
				}else{
					$url= '<?php echo URL::route("ciscosdwan.dashboard.ajaxcontrol",["partial"]) ?>';
				}
				message.innerText = 'Please be patient while data is loading. Do not close this window.';
				$("#showDetailDevices tbody tr").remove();
				$("#showDetailDevices thead tr").remove();
		        $("#showDetailDevices thead").html(
		           	'<tr>'+
		              	'<th>Device Model</th>' +
		              	'<th>Hostname</th>'+
		              	'<th>System IP</th>'+
		              	'<th>Site ID</th>'+
				        '<th>Version</th>'+
				        '<th>UUID</th>'+
				        '<th>Device Type</th>'+
				        '<th>Serial Number</th>'+
				        '<th>Reachability</th>'+
		          	'</tr>'
		        );

		        document.getElementById("ajax").style.display = "inline";

		        $.ajax({
			        url: $url,
			        type:"GET",
			        data:{
			            _token: "{{ csrf_token() }}"
			        },
			        success:function(response){
			        	if(response) {
			            	var data = JSON.parse(response);
			                message.innerText ='';
			                document.getElementById("ajax").style.display = "none";
			                var html= '';
			                var i = 0;
			                for (i=0;i<data.length;i++){
		                		html = html + '<tr>'+
				                '<td>'+data[i]["device-model"]+'</td>'+
				                '<td>'+data[i]["host-name"]+'</td>'+
				                '<td>'+data[i]["system-ip"]+'</td>'+
				                '<td>'+data[i]["site-id"]+'</td>'+
				                '<td>'+data[i]["version"]+'</td>'+
				                '<td>'+data[i]["uuid"]+'</td>'+
				                '<td>'+data[i]["device-type"]+'</td>'+
				                '<td>'+data[i]["board-serial"]+'</td>'+
				                '<td>'+data[i]["reachability"]+'</td>'+
			               		'</tr>';
		               		}

		               		$("#showDetailDevices tbody tr").remove();
		               		$("#showDetailDevices tbody").html(html);

			            }
			        },
			        error: function (xhr, textStatus, errorThrown) {

		         	}
		        });
				break;
				break;
			case 'Total':
			case 'Authorized':
			case 'Deployed':
			case 'Staging':
				if(devicetype == "Total"){
			        inventoryData = "detail";
			    }else if(devicetype == "Authorized"){
			        inventoryData = "detail?status=authorized";
			    }else if(devicetype == "Deployed"){
			        inventoryData = "detail?status=deployed";
			    }else{
			        inventoryData = "detail?status=staging";
			    }

			    /*Preparing interface for inventory*/
			    message.innerText = 'Please be patient while data is loading. Do not close this window.';
				$("#showDetailDevices tbody tr").remove();

				$("#showDetailDevices thead tr").remove();
		        $("#showDetailDevices thead").html(
		           	'<tr>'+
		              	'<th>Host name</th>' +
		              	'<th>System IP</th>'+
		              	'<th>Site ID</th>'+
				        '<th>Validity</th>'+
				        '<th>Chassics Number/Unique ID</th>'+
				        '<th>Serial Number</th>'+
		          	'</tr>'
		        );
		        document.getElementById("ajax").style.display = "inline";
		        /*End of preparing*/

		        /*call ajax*/
		        $.ajax({
			        url: '<?php echo URL::route('ciscosdwan.dashboard.ajaxinventory') ?>',
			        type:"GET",
			        data:{
			            _token: "{{ csrf_token() }}",
			            data : inventoryData
			        },
			        success:function(response){
			        	//document.getElementById('message').value = "";
			            if(response) {
			            	var data = JSON.parse(response);
			                message.innerText ='';
			                document.getElementById("ajax").style.display = "none";
			                var html= '';
			                var i = 0;
			                for (i=0;i<data.length;i++){
		                		html = html + '<tr>'+
				                '<td>'+data[i]["host-name"]+'</td>'+
				                '<td>'+data[i]["system-ip"]+'</td>'+
				                '<td>'+data[i]["site-id"]+'</td>'+
				                '<td>'+data[i]["validity"]+'</td>'+
				                '<td>'+data[i]["chasisNumber"]+'</td>'+
				                '<td>'+data[i]["serialNumber"]+'</td>'+
			               		'</tr>';
		               		}

		               		$("#showDetailDevices tbody tr").remove();
		               		$("#showDetailDevices tbody").html(html);

			            }
			        },
			        error: function (xhr, textStatus, errorThrown) {

		         	}
		        });
		        /*end of call ajax*/
				break;
			case 'Full WAN Connectivity':
			case 'Partial WAN Connectivity':
			case 'No WAN Connectivity':

				if(devicetype == "Full WAN Connectivity"){
			        state = "detail?state=siteup";
			    }else if(devicetype == "Partial WAN Connectivity"){
			        state = "detail?state=sitepartial";
			    }else{
			        state = "detail?state=sitedown";
			    }

				/*Preparing interface for inventory*/
			    message.innerText = 'Please be patient while data is loading. Do not close this window.';
				$("#showDetailDevices tbody tr").remove();

				$("#showDetailDevices thead tr").remove();
		        $("#showDetailDevices thead").html(
		           	'<tr>'+
		              	'<th>Hostname</th>'+
		                '<th>Reachability</th>' +
		                '<th>System IP</th>'+
		                '<th>Site ID</th>'+
		                '<th>BFD Sessions</th>'+
		          	'</tr>'
		        );
		        document.getElementById("ajax").style.display = "inline";
		        /*End of preparing*/

		        /*call ajax*/
		        $.ajax({
			        url: '<?php echo URL::route('ciscosdwan.dashboard.ajaxsitehealth') ?>',
			        type:"GET",
			        data:{
			            _token: "{{ csrf_token() }}",
			            data : state
			        },
			        success:function(response){
			        	//document.getElementById('message').value = "";
			            if(response) {
			            	var data = JSON.parse(response);
			                message.innerText ='';
			                document.getElementById("ajax").style.display = "none";
			                var html= '';
			                var i = 0;
			                for (i=0;i<data.length;i++){
		                		html = html + '<tr>'+
				                '<td>'+data[i]["host-name"]+'</td>'+
				                '<td>'+data[i]["reachability"]+'</td>'+
				                '<td>'+data[i]["system-ip"]+'</td>'+
				                '<td>'+data[i]["site-id"]+'</td>'+
				                '<td>'+data[i]["bfdSessions"]+'</td>'+
			               		'</tr>';
		               		}

		               		$("#showDetailDevices tbody tr").remove();
		               		$("#showDetailDevices tbody").html(html);

			            }
			        },
			        error: function (xhr, textStatus, errorThrown) {

		         	}
		        });
		        /*end of call ajax*/
				break;
			case 'less_than_10_mbps':
			case '10_mbps_100_mbps':
			case '100_mbps_500_mbps':
			case 'greater_than_500_mbps':

			    state = "detail?util=" + devicetype;
				/*Preparing interface for inventory*/
			    message.innerText = 'Please be patient while data is loading. Do not close this window.';
				$("#showDetailDevices tbody tr").remove();

				$("#showDetailDevices thead tr").remove();
		        $("#showDetailDevices thead").html(
		           	'<tr>'+
		              	'<th>System IP</th>'+
		                '<th>Interface</th>' +
		                '<th>Average</th>'+
		          	'</tr>'
		        );
		        document.getElementById("ajax").style.display = "inline";
		        /*End of preparing*/

		        /*call ajax*/
		        $.ajax({
			        url: '<?php echo URL::route('ciscosdwan.dashboard.ajaxsiteinterface') ?>',
			        type:"GET",
			        data:{
			            _token: "{{ csrf_token() }}",
			            data : state
			        },
			        success:function(response){
			        	//document.getElementById('message').value = "";
			            if(response) {
			            	var data = JSON.parse(response);
			                message.innerText ='';
			                document.getElementById("ajax").style.display = "none";
			                var html= '';
			                var i = 0;
			                for (i=0;i<data.length;i++){
		                		html = html + '<tr>'+
				                '<td>'+data[i]["system-ip"]+'</td>'+
				                '<td>'+data[i]["interface"]+'</td>'+
				                '<td>'+data[i]["average"]+'</td>'+
			               		'</tr>';
		               		}

		               		$("#showDetailDevices tbody tr").remove();
		               		$("#showDetailDevices tbody").html(html);

			            }
			        },
			        error: function (xhr, textStatus, errorThrown) {

		         	}
		        });
		        /*end of call ajax*/
				break;
			default:
				break;
		}



	});


</script>
@endsection

