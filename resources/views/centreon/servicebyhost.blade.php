@extends('/layout')
@section('content')
@include('centreon.menu')
<!-- Page header -->
<div class="page-header">
    <div class="page-title">
        <h3>Service by Host</h3>

    </div>
</div>

<?php
    $host_id = "host id";
    $host_name = "host name";
    $service_id = "id";
    $status = "activate";
    $check_interval = "normal check interval";
    $retry_check_interval = "retry check interval";
    $count =0 ;
?>

<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i>List Services </h4>&nbsp;&nbsp;
                <span id="addnew">
                    <a style="text-decoration: none;" href="{{@Config::get('app.url')}}/admin/centreon/service/add"><i
                            class="icon-plus"></i> Add New </a>
                </span>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                    </div>
                </div>
            </div>
            <div class="widget-content no-padding">
                <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable"
                    data-display-length="25" id="hosts">
                    <thead>
                        <tr>
                            <th data-class="expand">Host</th>
                            <th> Service</th>
                            <th >Scheduling</th>
                            <th >Template</th>
                            <th >Status</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($services as $service)
                                <tr>
                                    <td>
                                        <a href="{{@Config::get('app.url')}}/admin/centreon/hosts/edit/{{$service->$host_id}}">{{$service->$host_name}}</a>
                                    </td>
                                    <td><a href="{{@Config::get('app.url')}}/admin/centreon/service/edit/{{$service->$service_id}}">{{$service->description}}</a></td>
                                    @if($service->$check_interval != "" && $service->$retry_check_interval != "")
                                        <td >
                                            {{$service->$check_interval}} min / {{$service->$retry_check_interval}} min
                                        </td>
                                    @else
                                        <td></td>
                                    @endif
                                    <td>{{$service->template}}</td>

                                    @if($service->$status == 1)
                                        <td><span class="label label-success">ENABLED</span></td>
                                    @else
                                        <td><span class="label label-danger">DISABLED</span></td>
                                    @endif
                                    <td style="text-align: center;">
                                        <a href="{{@Config::get('app.url')}}/admin/centreon/service/delete/host/{{$service->$host_name}}/service/{{$service->description}}" class="bs-tooltip mr-1" title="Delete" style="text-decoration: none;"><i class="icon-trash"></i></a>
                                    </td>

                                </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- <script>
$(document).ready(function(){
		$('#hosts').DataTable({
			"aaSorting": [[ 1, "asc" ]],
			"iDisplayLength": 10,
			"aLengthMenu": [5, 10, 15, 25, 50, "All"]
		});
    });
</script>  -->

@endsection
