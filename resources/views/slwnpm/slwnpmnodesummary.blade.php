@extends('/layout')

@section('content')

	@include('slwnpm.menu')

<div style="height: 10px;"></div>
<h4>
		<b>
		Node Details - 
		<img id="StatusTitle" src=""/>
		<font color="#006699">
			<span id="SysNameTitle"></span>
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
			<table class="table table-striped table-no-inner-border" style="width:95%;">
				<tbody>
					<tr>
						<td style="border:none; width:150px;">NODE STATUS</td>
						<td style="border:none; width:40px;" align="center">
							<img id="StatusIcon" scr="#"></td>
						</td>
						<td style="border:none;">
							<font color="#006699">
							<div id="StatusDescription"></div>
							</font>
						</td>
					</tr>
					<tr>
						<td style="border:none;">POLLING IP ADDRESS</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							<font color="#006699">
							<div id="IPAddress"></div>
							</font>
						</td>
					</tr>
					<tr>
						<td style="border:none;">DYNAMIC IP</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							<div id="DynamicIP"></div>
						</td>
					</tr>
					<tr>
						<td style="border:none;">MACHINE TYPE</td>
						<td style="border:none; width:40px;" align="center">
							<img id="VendorIcon" src="#" />
						</td>
						<td style="border:none;">
							<div id="Vendor"></div>
						</td>              
					</tr>
					<tr>
						<td style="border:none;">NODE CATEGORY</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							<div id="Category"></div>
						</td>
					</tr>

					<tr>
						<td style="border:none;">DNS</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							<div id="DNS"></div>
						</td>
					</tr>
					<tr>
						<td style="border:none;">SYSTEM NAME</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							<div id ="SysName"></div>
						</td>
					</tr>
					<tr>
						<td style="border:none;">DESCRIPTION</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							<div id="NodeDescription"></div>
						</td>
					</tr>
					<tr>
						<td style="border:none;">LOCATION</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							<div id="Location"></div>
						</td>
					</tr>

					<tr>
						<td style="border:none;">CONTACT</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							<div id="Contact"></div>
						</td>
					</tr>

					<tr>
						<td style="border:none;">SYSOBJECTID</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							<div id="SysObjectID"></div>
						</td>
					</tr>

					<tr>
						<td style="border:none;">LAST BOOT</td>
						<td style="border:none;"></td>
						<td style="border:none;">
						<div id="LastBoot"></div>
						</td>
					</tr>

					<tr>
						<td style="border:none;">SOFTWARE VERSION</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							<div id="IOSVersion"></div>
						</td>
					</tr>
					<tr>
						<td style="border:none;">SOFTWARE IMAGE</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							<div id="IOSImage"></div>
						</td>
					</tr>

					<tr>
						<td style="border:none;">NO OF CPUs</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							<div id="CPUCount"></div>
						</td>
					</tr>

					<tr>
						<td style="border:none;">TELNET</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							<div id="Telnet"></div>
						</td>
					</tr>

					<tr>
						<td style="border:none;">WEB BROWSE</td>
						<td style="border:none;"></td>
						<td style="border:none;">
							<div id="IPAddressGUID"></div>
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
							<div id="PoolIP"></div>
						</td>
					</tr>
					<tr>
						<td style="border:none;">POLLING ENGINE</td>
						<td style="border:none;">
							<div id="PoolDisplayName"></div>
						</td>
					</tr>
					<tr>
						<td style="border:none;">POLLING INTERVAL</td>
						<td style="border:none;">
							<div id="PollInterval"></div>
						</td>
					</tr>
					<tr>
						<td style="border:none;">NEXT POLL</td>
						<td style="border:none;">
						<div id="NextPoll"></div>
						</td>
					</tr>
					<tr><td colspan="2" style="border:none;"></tr>	
					<tr>
						<td style="border:none;">STATISTICS COLLECTION</td>
						<td style="border:none;">
							<div id="StatCollection"></div>
						</td>
					</tr>
					<tr>
						<td style="border:none;">ENABLE 64 BIT COUNTERS</td>
						<td style="border:none;">
							<div id="Allow64BitCounters"></div>
						</td>
					</tr>
					<tr><td colspan="2" style="border:none;"></tr>
					<tr>
						<td style="border:none;">REDISCOVERY INTERVAL</td>
						<td style="border:none;">
							<div id="RediscoveryInterval"></div>
						</td>
					</tr>
					<tr>
						<td style="border:none;">NEXT REDISCOVERY</td>
						<td style="border:none;">
						<div id="NextRediscovery"></div>
						</td>
					</tr>
					<tr><td colspan="2" style="border:none;"></tr>	
					<tr>
						<td style="border:none;">LAST SYNC</td>
						<td style="border:none;">
						<div id="LastSync"></div>
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
			<table class="table table-striped table-no-inner-border" style="width:95%;" id="CPULoad">
				<thead bgcolor="#efeff5">
					<tr>
						<td style="border:none;" align="left" width="150px;">RESOURCE</td>
						<td style="border:none;" align="left">Min</td>
						<td style="border:none;" align="left">Max</td>
						<td style="border:none;" align="left">Avg</td>
					</tr>
				</thead>				
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
			<table class="table table-striped table-no-inner-border" style="width:95%;" id="Utilization">
				<thead bgcolor="#efeff5">
					<tr>
						<td style="border:none; width:40px;"></td>
						<td style="border:none;" align="left" width="100px;">STATUS</td>
						<td style="border:none; width:40px;"></td>
						<td style="border:none;" align="left">INTERFACE</td>
						<td style="border:none;" align="left">TRANSMIT</td>
						<td style="border:none;" align="left">RECEIVE</td>
					</tr>
				</thead>
			</table>
		</div>
	</div>

	<div class="widget box">
		<div class="widget-header">
			<h4>All IP Addresses on this node</h4>
		</div>
		<div style="height: 10px;">
		</div>
		<div class="widget-content no-padding" align="center">			
			<table class="table table-striped table-no-inner-border" style="width:95%;" id="AllIP">
				<thead bgcolor="#efeff5">
					<tr>
						<td style="border:none;" align="left" width="150px;">IP VERSION</td>
						<td style="border:none;" align="left">IP ADDRESS</td>
						<td style="border:none;" align="left">SUBNET MASK</td>
					</tr>
				</thead>				
			</table>
		</div>
	</div>

	<div class="widget box">
		<div class="widget-header">
			<h4>Event Summary (Today)</h4>
		</div>
		<div style="height: 10px;"></div>
		<div class="widget-content no-padding" align="center">
			<table class="table table-striped table-no-inner-border" style="width:95%;" id="EVENTSUM">
				<thead bgcolor="#efeff5">
					<tr>
						<td style="border:none;" align="centr" width="40px;"></td>
						<td style="border:none;" align="left" width="70px;">COUNT</td>
						<td style="border:none;" align="left">TYPE</td>
					</tr>
				</thead>				
			</table>
		</div>
	</div>

	<div class="widget box">
		<div class="widget-header">
			<h4>All Alert On This Node</h4>
		</div>
		<div style="height: 10px;"></div>
		<div class="widget-content no-padding" align="center">
			<table class="table table-striped table-no-inner-border" style="width:95%;" id="ALLALERTS">
				<thead bgcolor="#efeff5">
					<tr>
						<td style="border:none;" align="left">ALERT NAME</td>
						<td style="border:none;" align="left">MESSAGE</td>
						<td style="border:none;" align="left">TRIGGER OBJECT</td>
						<td style="border:none;" align="left">ACTIVE TIME</td>
						<td style="border:none;" align="left">RELATED NODE</td>
					</tr>
				</thead>				
			</table>
		</div>
	</div>
</div>

<div id="devicediv" style="display: none"></div>
<div id="interfacesdiv" style="display: none"></div>
<div id="cpuloaddiv" style="display: none"></div>
<div id="allipaddressdiv" style="display: none"></div>
<div id="eventsumdiv" style="display: none"></div>
<div id="alertsdiv" style="display: none"></div>

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

	// $(window).bind("load", function() {
	  
	// });

	$(document).ready(function(){
	 	$('#devicediv').load("<?php echo URL::route('nodesummarydevice',["+$nodeid+"]) ?>");
	 	$('#interfacesdiv').load("<?php echo URL::route('nodesummaryinterfaces',["+$nodeid+"]) ?>");
	 	$('#cpuloaddiv').load("<?php echo URL::route('nodesummarycpuload',["+$nodeid+"]) ?>");
	 	$('#allipaddressdiv').load("<?php echo URL::route('nodesummaryallip',["+$nodeid+"]) ?>");
	 	$('#eventsumdiv').load("<?php echo URL::route('nodesummaryeventsum',["+$nodeid+"]) ?>");
	 	$('#alertsdiv').load("<?php echo URL::route('nodesummaryalerts',["+$nodeid+"]) ?>");
		
	});

  	$(document).ajaxComplete(function(){
  		var device = JSON.parse(devicediv.innerText);

  		document.getElementById("SysNameTitle").innerHTML=device['SysName'];
  		$('#StatusTitle').attr('src', "{{url('/')}}/images/slwnpm/StatusIcons/small-" + device['StatusIcon']);
  		document.getElementById("StatusDescription").innerHTML=device['StatusDescription'].replace(",","<br>");
  		$('#StatusIcon').attr('src', "{{url('/')}}/images/slwnpm/StatusIcons/small-" + device['StatusIcon']);
  		document.getElementById("IPAddress").innerHTML=device['IPAddress'];
  		document.getElementById("DynamicIP").innerHTML=device['DynamicIP']=='true'?'Yes':'No';
  		$('#VendorIcon').attr('src', "{{url('/')}}/images/slwnpm/NetPerfMon/Images/Vendors/" + device['VendorIcon']);
  		document.getElementById("Vendor").innerHTML=device['Vendor'];

  		var str = '';
		switch(device['Category']) {
			case 0:
				str = "Other";
				break;
			case 1:
				str = "Network";
				break;
			case 2:
				str = "Server";
				break;
		}
		document.getElementById("Category").innerHTML=str;
		document.getElementById("DNS").innerHTML=device['DNS'];
		document.getElementById("SysName").innerHTML=device['SysName'];
		document.getElementById("NodeDescription").innerHTML=device['NodeDescription'];
		document.getElementById("Location").innerHTML=device['Location'];
		document.getElementById("Contact").innerHTML=device['Contact'];
  		document.getElementById("SysObjectID").innerHTML=device['SysObjectID'];
  		document.getElementById("LastBoot").innerHTML= Date(device['LastBoot']).toLocaleString('en-US', { timeZone: 'Asia/Ho_Chi_Minh' }).replace("GMT+0700 (Indochina Time)","");
  		document.getElementById("IOSVersion").innerHTML=device['IOSVersion'];
  		document.getElementById("IOSImage").innerHTML=device['IOSImage'];
  		document.getElementById("CPUCount").innerHTML=device['CPUCount'];
  		document.getElementById("Telnet").innerHTML='<a href="telnet://' + device['IPAddress'] + '">' + device['IPAddress'] + '</a>';
  		document.getElementById("IPAddressGUID").innerHTML='<a href="http://' + device['IPAddress'] + '">' + device['IPAddress'] + '</a>';
  		document.getElementById("PoolIP").innerHTML=device['PoolIP'];
  		document.getElementById("PoolDisplayName").innerHTML=device['PoolDisplayName'];
  		document.getElementById("PollInterval").innerHTML=device['PollInterval'] + ' seconds';
  		document.getElementById("NextPoll").innerHTML= Date(device['NextPoll']).toLocaleString('en-US', { timeZone: 'Asia/Ho_Chi_Minh' }).replace("GMT+0700 (Indochina Time)","");
  		document.getElementById("StatCollection").innerHTML=device['StatCollection'] + ' minutes';
  		document.getElementById("Allow64BitCounters").innerHTML=device['Allow64BitCounters'];
  		document.getElementById("RediscoveryInterval").innerHTML=device['RediscoveryInterval'] + ' minutes';
  		document.getElementById("NextRediscovery").innerHTML= Date(device['NextRediscovery']).toLocaleString('en-US', { timeZone: 'Asia/Ho_Chi_Minh' }).replace("GMT+0700 (Indochina Time)","");
  		document.getElementById("LastSync").innerHTML= Date(device['LastSync']).toLocaleString('en-US', { timeZone: 'Asia/Ho_Chi_Minh' }).replace("GMT+0700 (Indochina Time)","");

  		
  		//Utilization Interface table
  		var interfaces = JSON.parse(interfacesdiv.innerHTML);

		var table = document.querySelector("#Utilization");
        var tbody = document.createElement("tbody");

        for(i=0;i<interfaces.length;i++) {
	        var row = document.createElement("tr");

	        //Cột 1
	        var cellElement = document.createElement("td");
	        var img = document.createElement('img'); 
	        img.src = "{{url('/')}}/images/slwnpm/StatusIcons/small-" + interfaces[i]['StatusIcon'];
	        
	        cellElement.appendChild(img);
	        cellElement.setAttribute('style','text-align :center; border: none;');
	        row.appendChild(cellElement);

	        //Cột 2
	        var tmpstatus;
	        switch (interfaces[i]['STATUS']) {
				case 0:
					tmpstatus = 'Unknown';
					break;
				case 1:
					tmpstatus = 'Up';
					break;
				case 2:
					tmpstatus = 'Down';
					break;
				case 3:
					tmpstatus = 'Warning';
					break;
				case 4:
					tmpstatus = 'Shutdown';
					break;
				case 5:
					tmpstatus = 'Testing';
					break;
				case 6:
					tmpstatus = 'Dormant';
					break;
				case 7:
					tmpstatus = 'Not Present';
					break;
				case 8:
					tmpstatus = 'Lower Layer Down';
					break;
				case 9:
					tmpstatus = 'Unmanaged';
					break;
				case 10:
					tmpstatus = 'Unplugged';
					break;
				case 11:
					tmpstatus = 'External';
					break;
				case 12:
					tmpstatus = 'Unreachable';
					break;
				case 14:
					tmpstatus = 'Critical';
					break;
				case 15:
					tmpstatus = 'Partly Available';
					break;
				case 16:
					tmpstatus = 'Misconfigured';
					break;
				case 17:
					tmpstatus = 'Undefined';
					break;
				case 19:
					tmpstatus = 'Unconfirmed';
					break;
				case 22:
					tmpstatus = 'Active';
					break;
				case 24:
					tmpstatus = 'Inactive';
					break;
				case 25:
					tmpstatus = 'Expired';
					break;
				case 26:
					tmpstatus = 'Monitoring Disabled';
					break;
				case 27:
					tmpstatus = 'Disabled';
					break;
				case 28:
					tmpstatus = 'Not Licensed';
					break;
				case 29:
					tmpstatus = 'Other';
					break;
				case 30:
					tmpstatus = 'Not Running';
					break;
			}
	        cellElement = document.createElement("td");
	        cellContent = document.createTextNode(tmpstatus);
	        cellElement.appendChild(cellContent);
	        cellElement.setAttribute('style','text-align :left; border: none;');
	        row.appendChild(cellElement);

	        //Cột 3
	        cellElement = document.createElement("td");
	        img = document.createElement('img'); 
	        img.src = "{{url('/')}}/images/slwnpm/NetPerfMon/images/Interfaces/" + interfaces[i]['Icon'];
	        cellElement.appendChild(img);
	        cellElement.setAttribute('style','text-align :left; border: none;');
	        row.appendChild(cellElement);

	        //Cột 4
	        cellElement = document.createElement("td");
	        a = document.createElement('a');
	        var linkText = document.createTextNode(interfaces[i]['FullName']);
      		a.appendChild(linkText);
		    a.title = interfaces[i]['FullName'];
		    a.href = "{{@Config::get('app.url')}}/admin/slwnpm/interfacedetail/" + interfaces[i]['InterfaceID'];
		    a.style = "text-decoration:none;";
	        cellElement.appendChild(a);
	        cellElement.setAttribute('style','text-align :left; border: none;');
	        row.appendChild(cellElement);

	        //Cột 5 InPercentUtil
	        cellElement = document.createElement("td");
	        var OuterDiv = document.createElement("div");
	        OuterDiv.className = "progress progress-striped active";
	        OuterDiv.style = 'height:10px;';	        

	        var InnerDiv = document.createElement("div");
	        InnerDiv.className = "progress-bar progress-bar-success";
	        InnerDiv.style="width: " + interfaces[i]['OutPercentUtil'] + "%; height:10px; background-color: green;";
	        OuterDiv.appendChild(InnerDiv);

	        var span = document.createElement('span')
			span.innerHTML = interfaces[i]['OutPercentUtil'] + '%';

			cellElement.appendChild(span);
	        cellElement.appendChild(OuterDiv);
	        cellElement.setAttribute('style','text-align :left; border: none;');
	        row.appendChild(cellElement);

	        //Cột 6
	       	cellElement = document.createElement("td");
	        OuterDiv = document.createElement("div");
	        OuterDiv.className = "progress progress-striped active";
	        OuterDiv.style = 'height:10px;';	        

	        InnerDiv = document.createElement("div");
	        InnerDiv.className = "progress-bar progress-bar-success";
	        InnerDiv.style="width: " + interfaces[i]['InPercentUtil'] + "%; height:10px; background-color: green;";
	        OuterDiv.appendChild(InnerDiv);
	        span = document.createElement('span')
			span.innerHTML = interfaces[i]['InPercentUtil'] + '%';
			cellElement.appendChild(span);
	        cellElement.appendChild(OuterDiv);
	        cellElement.setAttribute('style','text-align :left; border: none;');
	        row.appendChild(cellElement);

	        tbody.appendChild(row);
    	}
    	//$("#UtilizationBody").empty();
    	$("#Utilization tbody tr").remove();
        table.appendChild(tbody);

        //CPU Load Table
        var cpuload = JSON.parse(cpuloaddiv.innerText);
        var table = document.querySelector("#CPULoad");
        var tbody = document.createElement("tbody");

        var row = document.createElement("tr");

        //dòng 1
        //Cột 1
        var cellElement = document.createElement("td");
        var img = document.createElement('img'); 
        img.src = "{{@Config::get('app.url')}}/images/slwnpm/small/cpu.png";        
        cellElement.appendChild(img);
        cellContent = document.createTextNode("CPU Load");
        cellElement.appendChild(cellContent)
        cellElement.setAttribute('style','text-align :left; border: none;');
        row.appendChild(cellElement);


        //Cột 2
        cellElement = document.createElement("td");
        cellContent = document.createTextNode(cpuload['MinCPU'] + "%");
        cellElement.appendChild(cellContent);
        cellElement.setAttribute('style','text-align :left; border: none;');
        row.appendChild(cellElement);

        //Cột 3
        cellElement = document.createElement("td");
        cellContent = document.createTextNode(cpuload['MaxCPU'] + "%");
        cellElement.appendChild(cellContent);
        cellElement.setAttribute('style','text-align :left; border: none;');
        row.appendChild(cellElement);

        //Cột 4
         cellElement = document.createElement("td");
        cellContent = document.createTextNode(cpuload['AvgCPU'] + "%");
        cellElement.appendChild(cellContent);
        cellElement.setAttribute('style','text-align :left; border: none;');
        row.appendChild(cellElement);

        tbody.appendChild(row);

        //dòng 2
        var row = document.createElement("tr");

        //Cột 1
        var cellElement = document.createElement("td");
        var img = document.createElement('img'); 
        img.src = "{{@Config::get('app.url')}}/images/slwnpm/small/cpu.png";        
        cellElement.appendChild(img);
        cellContent = document.createTextNode("Memory Usage");
        cellElement.appendChild(cellContent)
        cellElement.setAttribute('style','text-align :left; border: none;');
        row.appendChild(cellElement);


        //Cột 2
        cellElement = document.createElement("td");
        cellContent = document.createTextNode(cpuload['MinMEM'] + "%");
        cellElement.appendChild(cellContent);
        cellElement.setAttribute('style','text-align :left; border: none;');
        row.appendChild(cellElement);

        //Cột 3
        cellElement = document.createElement("td");
        cellContent = document.createTextNode(cpuload['MaxMEM'] + "%");
        cellElement.appendChild(cellContent);
        cellElement.setAttribute('style','text-align :left; border: none;');
        row.appendChild(cellElement);

        //Cột 4
         cellElement = document.createElement("td");
        cellContent = document.createTextNode(cpuload['AvgMEM'] + "%");
        cellElement.appendChild(cellContent);
        cellElement.setAttribute('style','text-align :left; border: none;');
        row.appendChild(cellElement);

        tbody.appendChild(row);

        //$("#CPULoad").empty();
        $("#CPULoad tbody tr").remove();
        table.appendChild(tbody);

        // All ip on this node table
  		var allips = JSON.parse(allipaddressdiv.innerHTML);

		var table = document.querySelector("#AllIP");
        var tbody = document.createElement("tbody");

        for(i=0;i<allips.length;i++) {
	        var row = document.createElement("tr");

	        //Cột 1
	        cellElement = document.createElement("td");
	        cellContent = document.createTextNode(allips[i]['IPAddressType']);
	        cellElement.appendChild(cellContent);
	        cellElement.setAttribute('style','text-align :left; border: none;');
	        row.appendChild(cellElement);

	        //Cột 2
	        cellElement = document.createElement("td");
	        cellContent = document.createTextNode(allips[i]['IPAddress']);
	        cellElement.appendChild(cellContent);
	        cellElement.setAttribute('style','text-align :left; border: none;');
	        row.appendChild(cellElement);

	        //Cột 3
	        cellElement = document.createElement("td");
	        cellContent = document.createTextNode(allips[i]['SubnetMask']);
	        cellElement.appendChild(cellContent);
	        cellElement.setAttribute('style','text-align :left; border: none;');
	        row.appendChild(cellElement);	        

	        tbody.appendChild(row);
    	}
    	//$("#AllIP").empty();
    	$("#AllIP tbody tr").remove();
        table.appendChild(tbody);

        //Last 24h event sum
        var eventsum = JSON.parse(eventsumdiv.innerHTML);

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

        //All alerts table
        var alerts = JSON.parse(alertsdiv.innerHTML);

		var table = document.querySelector("#ALLALERTS");
        var tbody = document.createElement("tbody");

        for(i=0;i<alerts.length;i++) {
	        var row = document.createElement("tr");

	        //Cột 1
	        var cellElement = document.createElement("td");
	        var img = document.createElement('img'); 
	        img.src = "{{@Config::get('app.url')}}/images/slwnpm/small/critical.gif";        
	        cellElement.appendChild(img);
	        cellContent = document.createTextNode(alerts[i]['Name']);
	        cellElement.appendChild(cellContent);
	        cellElement.setAttribute('style','text-align :left; border: none; color:#006699;');
	        row.appendChild(cellElement);

	        //Cột 2
	        cellElement = document.createElement("td");
	        cellContent = document.createTextNode(alerts[i]['AlertMessage']);
	        cellElement.appendChild(cellContent);
	        cellElement.setAttribute('style','text-align :left; border: none; color:red;');
	        row.appendChild(cellElement);

	        //Cột 3
	        cellElement = document.createElement("td");
	        cellContent = document.createTextNode(alerts[i]['ObjectName']);
	        cellElement.appendChild(cellContent);
	        cellElement.setAttribute('style','text-align :left; border: none; color:#006699;');
	        row.appendChild(cellElement);

	        //Cột 4
	        cellElement = document.createElement("td");
	        cellContent = document.createTextNode(Date(alerts[i]['TriggerTimeStamp']).toLocaleString('en-US', { timeZone: 'Asia/Ho_Chi_Minh' }).replace("GMT+0700 (Indochina Time)",""));
	        cellElement.appendChild(cellContent);
	        cellElement.setAttribute('style','text-align :left; border: none; color:#006699;');
	        row.appendChild(cellElement);

	        //Cột 5
	        var tmpstr =SysNameTitle.innerHTML;
	        cellElement = document.createElement("td");
	        cellContent = document.createTextNode(tmpstr);
	        cellElement.appendChild(cellContent);
	        cellElement.setAttribute('style','text-align :left; border: none; color:#006699;');
	        row.appendChild(cellElement); 
	        

	        tbody.appendChild(row);
    	}
    	$("#ALLALERTS tbody tr").remove();
        table.appendChild(tbody);
        
  	});

	
</script>
@endsection

