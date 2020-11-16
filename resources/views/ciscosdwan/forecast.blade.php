@extends('/layout')

@section('content')

	@include('ciscosdwan.menu')	
<div style="height: 10px;"></div>
<h4>
	<b>
	BANDWIDTH FORECASTING
	</b>
</h4>
<div style="height: 10px;"></div>
<section class="content">
     <div class="row">
        <div class="col-md-12">
          <div class="form-inline">

            <label>Device</label>                
            <select class="form-control" style="width:250px;" id="deviceid">
            	<option value="0">Select to display</option>
            	@if (isset($devices))
            		@foreach ($devices as $device)
                        <?php $tmp = 'host-name'; ?>
            			<option value="{{$device->deviceId}}">{{$device->$tmp}}</option>
            		@endforeach
            	@endif
            </select>
            <select class="form-control" style="width:150px;" id="range">                  
	            <option value="tomorrow">Tomorrow</option>
	            <option value="nextweek">Next 7 days</option>
            </select>
            <button type="submit" class="btn btn-primary" style="width: 100px;" id="btn_OK">OK</button>

          </div>
        </div>
    </div>

    <div style="height: 20px;">
    </div>

    <div class="box box-default color-palette-box">
        <div class="box-body">
          <div class="row">
            <div class="col-sm-12 col-md-12">        
                <div align="center" style="vertical-align: middle; align-content: center;">
                    <img src="{{@Config::get('app.url')}}/images/ajax.jpg" style="display: none; width: 300px;" id="ajax_loading">
                    <div id="highchart_show" class="chart" style="width:95%;"></div>
                </div>
            </div>
          </div>
        </div>
    </div>
</section>
    <!-- /.content -->

</div>

<script>
    $(document).on('click', '#btn_OK', function(event) {     
        var id = document.getElementById('deviceid');
        if (id.value=='0'){
            alert('Please select a device to display data');
        }
        else{
            var img = document.getElementById('ajax_loading')
            img.style.display = "block";
            var tmprange = document.getElementById('range');
            var range = -7;

            if (tmprange.value == 'tomorrow'){
                range = -7;
            }else{
                range = -20;
            }

            jQuery.ajax({ 
                 headers: {
                 },
                 url: "{{ url('/admin/ciscosdwan/bandwidth/bandwidthvalue') }}",
                 method: 'post',
                 data: {
                    _token: '{{ csrf_token() }}',
                    ip: id.value,
                    range: range
                 },
                 success: function(result,response){
                    //alert('Record has been inserted');                       
                    img.style.display = "none";
                    var data = JSON.parse(result);
                    var travg = data['travg'];
                    var txavg = data['txavg'];
                    var trforecast = data['trforecast'];
                    var txforecast = data['txforecast'];
                    var time = data['time']; 
                    //console.log(data['travg']);
                    //console.log(data['txavg']);
                    //console.log(data['trforecast']);
                    //console.log(data['txforecast']);
                    //console.log(data['time']);
                    console.log(trforecast);
                    //Vẽ sơ đồ
                    var i = 0;
                    var travgdata = [];
                    var txavgdata = [];
                    var trforecastdata = [];
                    var txforecastdata = [];
                    //console.log(travg);

                    for (i=0; i<= travg.length-1; i< i++){
                        travgdata.push([time[i]*1000,travg[i]]);
                        txavgdata.push([time[i]*1000,txavg[i]]);
                        //txavgdata.push(data['time'][i], txavg[i]);
                    }

                    //trforecastdata.push([time[time.length-2]*1000,travg[travg.length-2]]);

                    if (tmprange.value == 'tomorrow'){
                        trforecastdata.push([(time[time.length-1]*1000),travg[travg.length-1]]);
                        trforecastdata.push([(time[time.length-1]*1000) + (1*86400000),trforecast[1]]);

                        txforecastdata.push([(time[time.length-1]*1000),txavg[travg.length-1]]);
                        txforecastdata.push([(time[time.length-1]*1000) + (1*86400000),txforecast[1]]);
                    }else{
                        trforecastdata.push([(time[time.length-1]*1000),travg[travg.length-1]]);
                        txforecastdata.push([(time[time.length-1]*1000),txavg[txavg.length-1]]);
                        var j = 1;
                        for (j=1; j<7; j++){
                            trforecastdata.push([(time[time.length-1]*1000) + (j*86400000),trforecast[j]]);
                            txforecastdata.push([(time[time.length-1]*1000) + (j*86400000),txforecast[j]]);
                        }
                    }
                    
                    //trforecastdata.push([(time[time.length-1]*1000) + (2*86400000),trforecast[2]]);
                    
                    var chart = Highcharts.chart('highchart_show', {
                        chart: {
                            type: 'line',
                            zoomType: 'x',
                            borderColor: '#eee',
                            borderWidth: 2,
                        },
                        title: {
                            text: 'Device Bandwidth'
                        },
                        subtitle: {
                            text: document.ontouchstart === undefined ?
                                'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
                        },
                        xAxis: {
                            type: 'datetime',
                            dateTimeLabelFormats: {
                                millisecond: '%Y %M %d'
                            },
                        },
                        yAxis: {
                            title: {
                                text: 'kpbs'
                            }
                        },

                        tooltip: {
                                    formatter: function() {
                                        return  '<b>' +Highcharts.dateFormat('%b %e,%H:%M:%S',new Date(this.x))  +'</b><br/>' 
                                        + '<b>' + this.series.name + '</b>'
                                        + ': <b>' + this.y + ' kpbs </b>';
                                    }
                        },
                        tooltip: {
                            formatter: function() {
                                var s = [];
                                s.push('<b>' + Highcharts.dateFormat('%b %e,%H:%M',new Date(this.x)) + '</b>')
                                $.each(this.points, function(i, point) {
                                    s.push('<span style="color:#D31B22;font-weight:bold;">'+ point.series.name +' : '+
                                        point.y +' kpbs <span>');
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
                            name: 'Transmit',
                            data: txavgdata,
                            color: 'rgba(102, 0, 51, 1)'
                        },
                        {
                            name: 'Receivce',
                            data: travgdata,
                            color: 'rgba(22, 160, 133, 1)'
                        },
                        {
                            name: 'Receivce Forecasting',
                            data: trforecastdata,
                            color: 'rgba(255, 153, 0, 1)'
                        },
                        {
                            name: 'Transmit Forecasting',
                            data: txforecastdata,
                            color: 'rgba(255, 153, 0, 1)'
                        },
                        ]
                    });
                    /////////////////////////////////
                 },
                 error: function (xhr, textStatus, errorThrown) {  
                    img.style.display = "none"; 
                    
                 }  
            });
        }

    });
</script>
@endsection

