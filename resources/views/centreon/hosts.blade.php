@extends('/layout')
@section('content')
@include('centreon.menu')
<!-- Page header -->
<div class="page-header">
    <div class="page-title">
        <h3>Hosts</h3>

    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i>List Hosts </h4>&nbsp;&nbsp;
                <span id="addnew">
                    <a style="text-decoration: none;" href="{{@Config::get('app.url')}}/admin/centreon/hosts/addhost"><i
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

                            <th data-class="expand">Name</th>
                            <th> Alias</th>
                            <th>IP Address / DNS</th>
                            <th>Status</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hosts as $host)
                                <tr>
                                    <td><a href="{{@Config::get('app.url')}}/admin/centreon/hosts/edit/{{$host->id}}">{{$host->name}}</a></td>
                                    <td>{{$host->alias}}</td>
                                    <td>{{$host->address}}</td>

                                    @if($host->activate == 1)
                                        <td><span class="label label-success">ENABLE</span></td>
                                    @else
                                        <td><span class="label label-danger">DISABLED</span></td>
                                    @endif
                                    <td style="text-align: center;">
                                      {{--  <a href="{{@Config::get('app.url')}}/admin/centreon/hosts/edit/{{$host->id}}" id="edit" class="bs-tooltip mr-1" title="Edit" style="text-decoration: none;"><i class="icon-edit"></i></a>--}}
                                        <a href="{{@Config::get('app.url')}}/admin/centreon/hosts/delete/{{$host->name}}" class="bs-tooltip mr-1" title="Delete" style="text-decoration: none;"><i class="icon-trash"></i></a>
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
