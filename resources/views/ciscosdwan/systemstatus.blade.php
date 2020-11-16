@extends('/layout')

@section('content')

	@include('ciscosdwan.menu')

<div style="height: 10px;"></div>
<h4>
		<b>
		Device Detail - 
		<img id="StatusTitle" src=""/>
		<font color="#006699">			
			<span>{{$deviceid}}</span>
			<span id ="hostname"></span>
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
				<option value="System Status" selected>System Status</option>
				<option value="Application-DPI">Application-DPI</option>
				<option value="Events">Events</option>
				<option value="Connections">Connections</option>
			</select>
		</td>
	</tr>
</table>

<div style="height: 20px;"></div>

<div class="row row-bg"> 
	<div class="col-md-3"></div>
	<div class="col-md-3">
		<div class="statbox widget box box-shadow">
			<div class="widget-content">
				<div class="visual" style="padding: 0px; margin: 0px;">
					<img src="{{@Config::get('app.url')}}/images/ciscosdwan/reboot.svg" style="width: 45px;">
				</div>
				<div class="title">Total</div>
				<div class="value" id="rebootcount">N/A</div>
				<a class="open-options button more" href="#">REBOOT <i class="pull-right icon-angle-right"></i></a>
			</div>
		</div> 
	</div>

	<div class="col-md-3">
		<div class="statbox widget box box-shadow">
			<div class="widget-content">
				<div class="visual" style="padding: 0px; margin: 0px;">
					<img src="{{@Config::get('app.url')}}/images/ciscosdwan/crash.svg" style="width: 45px;">
				</div>
				<div class="title">Total</div>
				<div class="value" id="crashcount">N/A</div>
				<a class="open-options button more" href="#">CRASH <i class="pull-right icon-angle-right"></i></a>
			</div>
		</div> 
	</div> 
	<div class="col-md-3"></div>
</div> 

<div class="widget box">
	<div class="widget-header">
		<h4><i class="icon-reorder"></i> CPU AND MEMORY</h4>
	</div>
	<div style="height: 10px;"></div>
	<div class="widget-content no-padding" align="center">
		<div class="row">
			<div class="col-md-12 d-flex">
                <div align="right">
                    <span class="set_time selected_time badge badge-primary" id="1h">1h</span>
                    <span class="set_time notselect_time" id="3h">3h</span>
                    <span class="set_time notselect_time" id="12h">12h</span>
                    <span class="set_time notselect_time" id="24h">24h</span>
                </div>
            </div>
		</div>
		<div class="row">
			<div class="col-md-2">
				<div class="border-box memDisplay">
                    <img src="{{@Config::get('app.url')}}/images/ciscosdwan/cpu.svg" alt="">
                    <p class="text-percentage" id="cpuPer">Loading data</p>
                    <p class="text-name">CPU</p>
                </div>
                <div class="middle-box">
                    <span>Load average over <span id="hours">1</span>hour</span>
                </div>
                <div class="border-box memDisplay">
                    <img src="{{@Config::get('app.url')}}/images/ciscosdwan/memory.svg" alt="">
                    <p class="text-percentage" id="memPer">Loading data</p>
                    <p class="text-name">MEMORY</p>
                </div>
                <div style="height: 20px;"></div>
			</div>
			<div class="col-md-10">
				<div id="highchart_show" style="width:95%; height:500px;"></div>
			</div>
		</div>
	</div>
</div>


<script>
    var deviceIP = "{{ $deviceid }}";

	function selectview() {
		var e = document.getElementById("switchview");
		//var value = e.options[e.selectedIndex].value;
		var text = e.options[e.selectedIndex].text;
		
  		//alert($("#switchview :selected").text(););
        switch (text){
            case 'System Status':
                var url = '{{@Config::get('app.url')}}/admin/ciscosdwan/network/detail/{{$deviceid}}/systemstatus';
                break;
            case 'Application-DPI':
                var url = '{{@Config::get('app.url')}}/admin/ciscosdwan/network/detail/{{$deviceid}}/applicationdpi';
                break;
            case 'Events':
                var url = '{{@Config::get('app.url')}}/admin/ciscosdwan/network/detail/{{$deviceid}}/events';
                break;
            case 'Connections':
                var url = '{{@Config::get('app.url')}}/admin/ciscosdwan/network/detail/{{$deviceid}}/connections';
                break;
            case 'Interfaces':
                var url = '{{@Config::get('app.url')}}/admin/ciscosdwan/network/detail/{{$deviceid}}/interfaces';
                break;
            default:
                break;
        }

        window.location.href = url;
	}

	var hour=1;

	//Chọn thời gian
	$(".set_time").click(function(){
        var time = $(this).attr('id');        
        if(time == "1h"){
            hour = 1;
            $("#1h").removeClass("selected_time badge badge-primary");            
            $("#3h").removeClass("selected_time badge badge-primary");
            $("#12h").removeClass("selected_time badge badge-primary");
            $("#24h").removeClass("selected_time badge badge-primary");
            $("#1h").addClass("selected_time badge badge-primary");
            $("#3h").addClass("notselect_time");
            $("#12h").addClass("notselect_time");
            $("#24h").addClass("notselect_time");
        }else if(time == "3h"){
            hour = 3;
            $("#1h").removeClass("selected_time badge badge-primary");            
            $("#3h").removeClass("selected_time badge badge-primary");
            $("#12h").removeClass("selected_time badge badge-primary");
            $("#24h").removeClass("selected_time badge badge-primary");
            $("#3h").addClass("selected_time badge badge-primary");
            $("#1h").addClass("notselect_time");
            $("#12h").addClass("notselect_time");
            $("#24h").addClass("notselect_time");
        }else if(time == "12h"){
            hour = 12;
            $("#1h").removeClass("selected_time badge badge-primary");            
            $("#3h").removeClass("selected_time badge badge-primary");
            $("#12h").removeClass("selected_time badge badge-primary");
            $("#24h").removeClass("selected_time badge badge-primary");
            $("#12h").addClass("selected_time badge badge-primary");
            $("#1h").addClass("notselect_time");
            $("#3h").addClass("notselect_time");
            $("#24h").addClass("notselect_time");
        }else{
            $("#1h").removeClass("selected_time badge badge-primary");            
            $("#3h").removeClass("selected_time badge badge-primary");
            $("#12h").removeClass("selected_time badge badge-primary");
            $("#24h").removeClass("selected_time badge badge-primary");
            $("#24h").addClass("selected_time badge badge-primary");
            $("#1h").addClass("notselect_time");
            $("#3h").addClass("notselect_time");
            $("#12h").addClass("notselect_time");
            hour = 24;
        }
        cpuPer.innerText = "Loading Data";
        memPer.innerText = "Loading Data";
        loadcpumemory(hour);
    });

    function loadcpumemory(time){
        jQuery.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                '_token': '{{ csrf_token() }}'
            },
            url: '<?php echo URL::route('ciscosdwan.network.loadcpumemory') ?>',
            method: 'post', 
            data: {
                deviceIP: deviceIP,
                time    : time,
                _token: '{{ csrf_token() }}'
            },
            success: function(result,response){
                var data = jQuery.parseJSON(result);
                $("#cpuPer").html(data['resTotalCpuPer'] + " % ");
                $("#memPer").html(data['resTotalMemPer'] + " % ");
                loadchart(data['cpuPercentage'],data['memPercentage']);
                // console.log('resTotalCpuPer: ',data['cpuPercentage']);
                // console.log('resTotalMemPer: ',data['memPercentage']);
                $("#hours").html(time);
                // console.log(result);
            },
            error: function (xhr, textStatus, errorThrown) {  
                $("#cpuPer").html("Error");
                $("#cpuPer").html("Error");
            }  
        });
    }

	

    $(document).ready(function(){
		/*Lấy thông tin chung của thiết bị, shutdown, crash*/
		/*call ajax*/
        $.ajax({
	        url: '<?php echo URL::route('ciscosdwan.network.ajaxreboot') ?>',
	        type:"GET",
	        data:{
	            _token: "{{ csrf_token() }}",
	            data : "{{$deviceid}}"
	        },
	        success:function(response){
	        	//document.getElementById('message').value = "";
	            if(response) {
	            	rebootcount.innerText = response;              
	            }
	        },
	        error: function (xhr, textStatus, errorThrown) {  

         	}
        });
        /*end of call ajax*/

        $.ajax({
	        url: '<?php echo URL::route('ciscosdwan.network.ajaxcrash') ?>',
	        type:"GET",
	        data:{
	            _token: "{{ csrf_token() }}",
	            data : "{{$deviceid}}"
	        },
	        success:function(response){
	        	//document.getElementById('message').value = "";
	            if(response) {
	            	crashcount.innerText = response;              
	            }
	        },
	        error: function (xhr, textStatus, errorThrown) {  

         	}
        });

        $.ajax({
	        url: '<?php echo URL::route('ciscosdwan.network.ajaxsummary') ?>',
	        type:"GET",
	        data:{
	            _token: "{{ csrf_token() }}",
	            data : "{{$deviceid}}"
	        },
	        success:function(response){
	        	//document.getElementById('message').value = "";
	            if(response) {
	            	var data = JSON.parse(response);
	            	hostname.innerHTML = data[0]["host-name"];
	            }
	        },
	        error: function (xhr, textStatus, errorThrown) {  

         	}
        });

        

		/*Kết thúc lấy thông tin chung*/
	});

	$(document).ajaxComplete(function(event,xhr,settings){
	    //console.log("URL",settings.url);
	    if(settings.url.indexOf('ciscosdwan.network.ajaxsummary')>0){
	    	loadcpumemory(hour);
	    }
	});

	function loadchart(cpuDataArr,memDataArr){
        var chart = Highcharts.chart('highchart_show', {
            chart: {
                type: 'line',
                zoomType: 'x',
                borderColor: '#eee',
                borderWidth: 2,
            },
            title: {
                text: 'CPU and Memory Usage'
            },
            subtitle: {
                text: document.ontouchstart === undefined ?
                    'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
            },
            xAxis: {
                type: 'datetime',
                dateTimeLabelFormats: {
                    millisecond: '%H:%M:%S.%L %P',
                    second: '%H:%M:%S %P',
                    minute: '%H:%M %P',
                    hour: '%H:%M %P',
                    day: '%e. %b',
                    week: '%e. %b',
                    month: '%b \'%y',
                    year: '%Y'
                },
            },
            yAxis: {
                title: {
                    text: '% Usage'
                }
            },

            tooltip: {
                        formatter: function() {
                            return  '<b>' +Highcharts.dateFormat('%b %e,%H:%M:%S',new Date(this.x))  +'</b><br/>' 
                            + '<b>' + this.series.name + '</b>'
                            + ': <b>' + this.y + ' % </b>';
                        }
            },
            tooltip: {
                formatter: function() {
                    var s = [];
                    s.push('<b>' + Highcharts.dateFormat('%b %e,%H:%M',new Date(this.x)) + '</b>')
                    $.each(this.points, function(i, point) {
                        s.push('<span style="color:#D31B22;font-weight:bold;">'+ point.series.name +' : '+
                            point.y +'% <span>');
                    });
                    
                    return s.join(' <br/> ');
                },
                shared: true
            },
            legend: {
                enabled: false
            },
            series: [
            {
                name: 'CPU',
                data: cpuDataArr,
                color: 'rgba(22, 160, 133, 0.75)'
            },
            {
                name: 'Memory',
                data: memDataArr,
                color: 'rgba(22, 160, 133, 0.75)'
            },
            ]
        });
	};


</script>
@endsection

