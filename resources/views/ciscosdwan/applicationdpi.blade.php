@extends('/layout')

@section('content')

	@include('ciscosdwan.menu')

<div style="height: 10px;"></div>
<h4>
		<b>
		Application DPI for device - 
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
				<option value="System Status">System Status</option>
				<option value="Application-DPI" selected>Application-DPI</option>
				<option value="Events">Events</option>
				<option value="Connections">Connections</option>
			</select>
		</td>
	</tr>
</table>

<div style="height: 20px;"></div>

<div class="widget box">
	<div class="widget-header">
		<h4><i class="icon-reorder"></i>APPLICATION USAGE</h4>
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
        <div class="row" style="height: 10px;"></div>
        <div class="row" style="height: 20px;" id="highchart_label"></div>
        <div class="row" style="height: 10px;"></div>
		<div class="row">
			<div class="col-md-12">
				<div id="highchart_show" style="width:95%; height:300px;" style="vertical-align: middle;"></div>
			</div>
		</div>
        <div class="row" style="height: 30px;"></div>
        <div class="row" align="center" style="width:95%;">
            <table class="table table-striped table-bordered table-hover datatable" data-display-length="10">
                <thead>
                    <tr>
                        <th>Application Family</th>
                        <th>Usage</th>
                        <th>FEC Recovery rate %)</th>
                        <th>Percentage of Total Trafic</th>
                    </tr>
                </thead>
                <tbody id="showAppicationTable">
                </tbody>
            </table>
        </div>
        <div class="row" style="height: 20px;"></div>
	</div>
</div>


<script>
    var hour = 1;
    var applicationData = [];    

    $(document).ready(function(){

        /*Lấy thông tin chung của thiết bị, shutdown, crash*/       
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

        //Load ajax application dpi để hiển thị vào chart
        loadApplicationDPI(hour,5);
    });

	$(document).ajaxComplete(function(event,xhr,settings){
	    //console.log("URL",settings.url);
	    // if(settings.url.indexOf('ciscosdwan.network.ajaxsummary')>0){
	    //	//loadApplication(deviceIP,lastTime,interval);
	    //}
	});

    //Chuyển view summary, application dpi, interface ...
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

    //CLick vào nút 1h 3h 12h 24h
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
        loadApplicationDPI(hour,5);
        //gọi hàm load application dpi để nạp lại chart theo thời gian đã chọn (1h, 3h, 12h, 24h)
    });

    //Ajax call back laod application dpi
    function loadApplicationDPI(lastTime,interval){
        var deviceId = "{{$deviceid}}";
        jQuery.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                '_token': '{{ csrf_token() }}'
            },
            url: '<?php echo URL::route('ciscosdwan.network.loadapplicationdpi') ?>',
            method: 'post', 
            data: {
                deviceId : deviceId,
                lastTime : lastTime,    
                interval : interval, 
                _token: '{{ csrf_token() }}'
            },
            success: function(result,response){
                if (result=='No data'){
                    highchart_label.innerText = 'No data display';
                    obj = [{
                        data : []
                        }]
                    highchart(obj);
                }else{
                    highchart_label.innerText = "";
                    applicationData = $.parseJSON(result);
                    var obj;
                    var showData = []
                    applicationData.map(function(value,index){
                        var value1 = value.shift();
                        obj = {
                            name : value1,
                            data : value.map(function(v,i){
                                    return v;
                            })
                        }
                        
                        showData.push(obj);
                    })
                    highchart(showData);
                    loadTableApplicationDPI(showData)
                }
            },
            error: function (xhr, textStatus, errorThrown) {  
            }  
        });
    }

    //Vẽ biểu đồ
    function highchart(dataArr){
        Highcharts.setOptions({
            time: {
                useUTC: false,   
            }
        });
        Highcharts.chart('highchart_show', {
            chart: {
                type: 'area',
                zoomType: 'x',
                borderColor: '#eee',
                borderWidth: 2,
            },
            title: {
                text: 'Application DPI'
            },
            subtitle: {
                text: document.ontouchstart === undefined ?
                    'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
            },
            xAxis: {
                type: 'datetime',
                dateTimeLabelFormats: {
                    millisecond: '%H:%M:%S.%L %P',
                    second: '%H:%M:%S',
                    minute: '%H:%M',
                    hour: '%H:%M',
                    day: '%e. %b',
                    week: '%e. %b',
                    month: '%b \'%y',
                    year: '%Y'
                },
            },
            yAxis: {
                title: {
                    text: 'Exchange rate'
                }
            },
            tooltip: {
                formatter: function() {
                    var s = [];
                    s.push('<b>' + Highcharts.dateFormat('%b %e,%H:%M',new Date(this.x)) + '</b>')
                    $.each(this.points, function(i, point) {
                        s.push('<span style="color:#D31B22;font-weight:bold;">'+ point.series.name +' : '+
                            point.y +'KB <span>');
                    });
                    return s.join(' <br/> ');
                },
                shared: true,
                useHTML: true,
                followPointer: true
                
            },
            legend: {
                enabled: false
            },
            // series: [{
            //     data : []
            // }],
            series: dataArr,
            plotOptions: {
                series: {
                    states: {
                        inactive: {
                            opacity: 1
                        }
                    }
                }
            }                 
        });
    }

    //Vẽ bảng
    function loadTableApplicationDPI(dataArr){
        var usageTotal = 0;
        var applicationArray = []
        dataArr.map(function(value,index){
            
            console.log(value.name)
            var tmpData = value.data;
            var usageApp = 0;
            tmpData.map(function(v,i){
                usageTotal += v[1]; 
                usageApp   += v[1];
            })
            var obj = {
                name : value.name,
                usageApp : usageApp
            }
            applicationArray.push(obj);
        })
        var html = "";
        applicationArray.map(function(value,index){
            if(value.usageApp < 1024){
                usage = value.usageApp.toFixed(2) + " KB";
            }else{
                usage = (value.usageApp/1024).toFixed(2) + " MB";
            }
            html += "<tr class='monitor_network_td' >" +
                    "<td><span>" + value.name + "</span></td>" + 
                    "<td>" + usage + "</td>" + 
                    "<td>" + "N/A"  + "</td>" +
                    "<td>"+
                            "<div class='progress progress-xs mr-3' style='display: inline-flex;width: 70%;'>"+
                                "<div class='progress-bar bg-primary progress-bar-striped' role='progressbar' aria-valuenow='100' aria-valuemin='0' aria-valuemax='100' style='width:"+ (value.usageApp/usageTotal*100).toFixed() +"%'>" + 
                                "</div>"+
                            "</div>  " + 
                            (value.usageApp/usageTotal*100).toFixed(2) +"%"+
                    "</td>" +
                "</tr>";
        })
        $("#showAppicationTable").html(html);
    }

</script>
@endsection

