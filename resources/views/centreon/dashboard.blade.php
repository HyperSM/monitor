@extends('/layout')
@section('content')
@include('centreon.menu')

<style type="text/css">
    .circle{
        width: 100px;
        height: 100px;
        position: absolute;
        top: 45%;
        left: 45%;
        opacity: 0.8;
        margin-top:30px;
        background-color: white;
    }
    .css-loading{
        opacity: 0.8;
    }
</style>
<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		{{--<h3>Dashboard</h3>--}}
        <div style="width: 100%">
            <p>Host </p>
            <select class="form-control" id="hosts">
                @foreach($hosts as $host)
                    <option value="{{$host->id}}">{{$host->name}}</option>
                @endforeach
            </select>
        </div>

	</div>
</div>


<div class="row">

    <div class="col-md-6">
        <div class="widget box">
            <div class="widget-header"></div>
            <div class="widget-content">
{{--                <div class="circle">--}}
{{--                    <img src="{{@Config::get('app.url')}}/images/casvd/loading.gif">--}}
{{--                </div>--}}
                <div id="chart_pie" class="chart"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="widget box">
            <div class="widget-header">

            </div>
            <div class="widget-content no-padding">
                <table class="table table-striped table-bordered table-hover table-checkable table-responsive "
                       data-display-length="25" id="hosttbl">
                    <thead>
                    <tr>

                        <th data-class="expand">State</th>
                     {{--   <th> Duration</th>--}}
                        <th> Total Time</th>
                        <th> Mean time</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i>State Breakdowns For Host Services </h4>&nbsp;&nbsp;
            </div>
            <div class="widget-content no-padding">
                <table class="table table-striped table-bordered table-hover table-checkable table-responsive"
                       data-display-length="25" id="services">
                    <thead>
                    <tr>
                        <th data-class="expand">Service</th>
                        <th> OK</th>
                        <th> Warning</th>
                        <th> Critical</th>
                        <th> Unknown</th>
                        <th> Scheduled downtime</th>
                    </tr>
                    </thead>
                   <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="circle">
    <img src="{{@Config::get('app.url')}}/images/casvd/loading.gif" style="    width: 50px;height: 50px;margin: 0 auto;position: absolute;top: 33%;left: 27%;">
</div>

    <script>
        $(document).ready(function(){
            // setDefaultHost();
            // loadURL();

            $('.circle').show();
            $('select').change(function () {
                var val = $(this).find(":selected").text();
                setDefaultHost();
                getservices();
                loadURL();
            });

            function drawchart(data,flag){
                //var d_pie = [10,20,70];
                var d_pie = data;
                d_pie[2] = { label: "UP", data: Math.floor(d_pie[0]*100)+1 };
                d_pie[1] = { label: "DOWN", data: Math.floor(d_pie[1]*100)+1 };
                d_pie[0] = { label: "UNREACHABLE", data: Math.floor(d_pie[2]*100)+1 };
                d_pie[3] = { label: "SCHEDULED DOWNTIME", data: Math.floor(d_pie[3]*100)+1 };
                d_pie[4] = { label: "UNDETERMINED", data: Math.floor(d_pie[4]*100)+1 };
                $.plot("#chart_pie", d_pie, $.extend(true, {}, Plugins.getFlotDefaults(), {
                    series: {
                        pie: {
                            show: true,
                            radius: 1,
                            label: {
                                show: true
                            }
                        }
                    },
                    grid: {
                        hoverable: true
                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: '%p.0%, %s', // show percentages, rounding to 2 decimal places
                        shifts: {
                            x: 20,
                            y: 0
                        }
                    }
                }));
            }

            function getservices(){
                var hostname = $("#hosts :selected").text();
                //console.log(hostname);
                jQuery.ajax({
                    headers: {
                        //'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        //'_token': '{{ csrf_token() }}'
                    },
                    url: '<?php echo URL::route("ajaxgetservicebyhost") ?>',
                    method: 'POST',
                    data: {
                        name: hostname,
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend(){
                        $('.circle').show();
                        $('body').addClass('css-loading');
                    },
                    success: function (result, status, xhr) {
                        var rs = result.services;
                        //console.log(rs[0]);
                        if(rs[0]['host_state'] == 0){
                            var host_downtimes = parseInt(rs[0]['host_downtimes']);
                            var dt = [100,0,0,host_downtimes,0];
                            formathost(dt);
                            drawchart(dt);
                        }
                        if(rs[0]['host_state'] == 2){
                            var host_downtimes = parseInt(rs[0]['host_downtimes']);
                            var dt = [0,100,0,host_downtimes,0];
                            formathost(dt);
                            drawchart(dt);
                        }
                        if(rs[0]['host_state'] == 3){
                            var host_downtimes = parseInt(rs[0]['host_downtimes']);
                            var dt = [0,0,100,host_downtimes,0];
                            formathost(dt);
                            drawchart(dt);
                        }
                        $('.circle').hide();
                        $('body').removeClass('css-loading');
                        formatservices(result);
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        console.log(errorThrown)
                    }
                });
            }

            function formathost(data) {
                var strhost = "";
                strhost += "<tr>";
                strhost +=      "<td>UP</td>";
                /*  strhost +=      "<td></td>";*/
                strhost +=      "<td>"+ data[0]+"%</td>";
                strhost +=      "<td></td>";
                strhost += "</tr>";
                strhost += "<tr>";
                strhost +=      "<td>DOWN</td>";
                /*    strhost +=      "<td></td>";*/
                strhost +=      "<td>"+ data[1]+"%</td>";
                strhost +=      "<td></td>";
                strhost += "</tr>";
                strhost += "<tr>";
                strhost +=      "<td>UNREACHABLE</td>";
                /*  strhost +=      "<td></td>";*/
                strhost +=      "<td>"+ data[2]+"%</td>";
                strhost +=      "<td></td>";
                strhost += "</tr>";
                strhost += "<tr>";
                strhost +=      "<td>SCHEDULED DOWNTIME</td>";
                /*  strhost +=      "<td></td>";*/
                strhost +=      "<td>"+ data[3]+"%</td>";
                strhost +=      "<td></td>";
                strhost += "</tr>";
                strhost += "<tr>";
                strhost +=      "<td>UNDETERMINED</td>";
                /*strhost +=      "<td></td>";*/
                strhost +=      "<td>"+ data[4]+"%</td>";
                strhost +=      "<td></td>";
                strhost += "</tr>";
                $('#hosttbl tbody').html(strhost);
            }

            function formatservices(result) {
                //console.log(result);
                var html  = "";
                var services = result.services;
                for(var i = 0; i<services.length;i++)
                {
                    html += "<tr>";
                    html += "<td>"+ services[i].description + "</td>";
                    var output = services[i].output.toLocaleString().toLowerCase();
                    if (output.indexOf("OK".toLowerCase()) != -1) {
                        html += "<td>100%</td>";
                        html += "<td>0%</td>";
                        html += "<td>0%</td>";
                        html += "<td>0%</td>";
                        html += "<td>"+services[i].service_downtimes+"%</td>";
                    }
                    if (output.indexOf("WARNING".toLowerCase())!= -1) {
                        html += "<td>0%</td>";
                        html += "<td>100%</td>";
                        html += "<td>0%</td>";
                        html += "<td>0%</td>";
                        html += "<td>"+services[i].service_downtimes+"%</td>";
                    }
                    if (output.indexOf("CRITICAL".toLowerCase())!= -1) {
                        html += "<td>0%</td>";
                        html += "<td>0%</td>";
                        html += "<td>100%</td>";
                        html += "<td>0%</td>";
                        html += "<td>"+ services[i].service_downtimes +"%</td>";
                    }
                    if (output.indexOf("UNKNOWN".toLowerCase())!= -1) {
                        html += "<td>0%</td>";
                        html += "<td>0%</td>";
                        html += "<td>0%</td>";
                        html += "<td>100%</td>";
                        html += "<td>"+ services[i].service_downtimes +"%</td>";
                    }

                    html += "</tr>";
                }
                $('#services tbody').html(html);
            }

            function setDefaultHost() {
                var selectedhost = $('select').find(":selected").text();
                if(selectedhost){
                    console.log(selectedhost);
                    localStorage.setItem('host_name', selectedhost);
                }
            }

            function loadURL() {
                var host = localStorage.getItem('host_name');
                if(host){
                    document.getElementById('detail_report').setAttribute('href','{{@Config::get('app.url')}}/admin/centreon/report/'+ host)
                }
                else{
                    document.getElementById('detail_report').setAttribute('href','javascript:void(0)')
                }
            }

            getservices();

            var refresherate = {{$refreshrate}};
            //console.log(refresherate);
            setInterval(function () {
                getservices();
                setDefaultHost();
                loadURL();
            },refresherate);

        });
    </script>

@endsection
