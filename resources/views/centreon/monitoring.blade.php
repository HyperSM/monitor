@extends('/layout')
@section('content')
@include('centreon.menu')

<div id="result"></div>

<!-- Page header -->
<div class="page-header">
{{--    <div class="page-title">

    </div>--}}
</div>
<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i>Monitoring</h4>&nbsp;&nbsp;
                <div class="toolbar no-padding">
                    <div class="btn-group">
                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                    </div>
                </div>
            </div>
            <div class="widget-content no-padding" id="divMonitor">
                <table class="table table-striped table-bordered table-hover table-checkable table-responsive display"
                    data-display-length="25" id="monitor">
                    <thead>
                        <tr>
                            <th data-class="expand">Host</th>
                            <th> Services</th>
                            <th>Status</th>
                            <th>Hard State Duration</th>
                            <th>Last check</th>
                            <th>Tries</th>
                            <th>Status information</th>
                        </tr>
                    </thead>
                   {{-- <tbody>

                        @foreach($monitors as $monitor)
                                <tr>
                                    <td>
                                        @if(strcmp($temp,$monitor->host_id) != 0)
                                                <a href="{{@Config::get('app.url')}}/admin/centreon/hosts/edit/{{$monitor->host_id}}" >{{$monitor->name}}</a>
                                        @else
                                                <a href="#"></a>
                                        @endif

                                    </td>
                                    <td>{{$monitor->description}}</td>
                                    <td>
                                        @if(strpos($monitor->output,'WARNING') !== false)
                                            <span class="label label-warning">WARNING</span>
                                        @elseif(strpos($monitor->output,'CRITICAL') !== false)
                                            <span class="label label-danger">CRITICAL</span>
                                        @elseif(strpos($monitor->output,'UNKNOWN') !== false)
                                            <span class="label label-default">UNKNOWN</span>
                                        @elseif(strpos($monitor->output,'PENDING') !== false)
                                            <span class="label label-default">PENDING</span>
                                        @else
                                            <span class="label label-success">OK</span>
                                        @endif
                                    </td>
                                    <td>{{$monitor->last_hard_state_change}}</td>
                                    <td>{{$monitor->last_check}}</td>
                                    @if($monitor -> check_attempt != "" && $monitor->max_check_attempts != "")
                                        <td>{{$monitor ->check_attempt}} / {{$monitor->max_check_attempts}}</td>
                                    @else
                                        <td></td>
                                    @endif
                                    <td>{{$monitor->output}}</td>
                                </tr>
                        @endforeach

                    </tbody>--}}
                </table>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .td .tooltiptext {
        visibility: hidden;
        width: 120px;
        background-color: black;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;

        /* Position the tooltip */
        position: absolute;
        z-index: 1;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
    }

    .circle{
        width: 30px;
        height: 30px;
        position: absolute;
        top: 40%;
        left: 50%;
        margin-top:45px
    }

</style>


<script>
    $(document).ready(function() {
        // var refresherate = {{$refreshrate}}
        // setInterval(function () {
        //     loadpage();
        // }, refresherate);
    
        // var loadpage = function () {
            var table = $('.display');
    
            jQuery.ajax({
                headers: {},
                url: '<?php echo URL::route("ajaxmonitors") ?>',
                method: 'GET',
                beforeSend(){
                    var strCircle = "<div  class='circle'>";
                    strCircle    +=    "<img src='{{@Config::get('app.url')}}/images/casvd/loading.gif' style='width: 30px;height: 30px;margin:0 auto'>"
                    strCircle    += "</div>";
                    $('.display tbody').html(strCircle);
                },
                data: {},
                success: function (result, status, xhr) {
                    //console.log(result);
    
                    $.each(result, function (a, b) {
                        var classname = "",status = "";
                        var row = "";
                        row += "<tr><td>"+b.name+"</td>";
                        row += "<td>"+  b.description + "</td>";
                        var output = b.output.toString().toLowerCase();
                       // console.log(output);
                        if(output.includes("warning")){
                            classname = "label label-warning";
                            status = "WARNING";
                        }
                        else if(output.includes("critical")){
                            classname = "label label-danger";
                            status = "CRITICAL";
                        }
                        else if(output.includes("unknown")){
                            classname = "label label-default";
                            status = "UNKNOWN";
                        }
                        else if(output.includes("pending")){
                            classname = "label label-default";
                            status = "PENDING";
                        }
                        else{
                            classname = "label label-success";
                            status = "OK";
                        }
                        row += "<td><span class='"+ classname +"'>" + status + "</td>";
                        row += "<td>" + b.last_hard_state_change + "</td>" ;
                        row += "<td>" + b.host_last_check + "</td>" ;
                        row +="<td>" + b.tries + "</td>" ;
                        row += "<td>" + b.output + "</td></tr>";
                        table.append(row);
                    });
                    $('.circle').remove();
                    $('.display').DataTable();
                },
                error: function (xhr, textStatus, errorThrown) {
                }
            });
    
        // }
    
        loadpage();
    
    
    });
    </script>

@endsection
