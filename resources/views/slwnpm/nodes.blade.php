@extends('/layout')
@section('content')
@include('slwnpm.menu')

<!-- Page header -->
<div style="height: 10px;"></div>
<h4>
	<b>
	NODES
	</b>
</h4>
<div style="height: 10px;"></div>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> All nodes</h4>&nbsp;&nbsp;
				<span id="addnew">
					<a style="text-decoration: none;" href="{{@Config::get('app.url')}}/admin/slwnpm/addnode"><i class="icon-plus"></i> Add New </a>
				</span>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable" data-display-length="10">
					<thead>
						<tr>
							<th class="checkbox-column">
                                <input type="checkbox" class="uniform">
                            </th>
							<th>Name</th>
							<th>Polling IP Address</th>
							<th>Status</th>
							<th style="text-align: center;">Action</th>
						</tr>
					</thead>
					<tbody>
                        @if(isset($data))
                        @foreach($data as $item)
                        <tr>
                            <td class="checkbox-column">
                                <input type="checkbox" class="uniform">
                            </td>
                            <td><a href="{{@Config::get('app.url')}}/admin/slwnpm/nodesummary/{{$item['NodeID']}}">{{$item['DisplayName']}}</a></td>
                            <td>{{$item['IPAddress']}}</td>
                            <td>{{$item['StatusDescription']}}</td>
                            <td class="align-center" style="width: 100px;">
                                    <ul class="table-controls">
                                        @if ($item['Unmanaged']==False)
                                            <li><a href="{{@Config::get('app.url')}}/admin/slwnpm/unmanage/{{$item['NodeID']}}" class="bs-tooltip" title="Maintenance mode"><i class="icon-wrench"></i></a> </li>
                                        @else
                                            <li><a href="{{@Config::get('app.url')}}/admin/slwnpm/manage/{{$item['NodeID']}}" class="bs-tooltip" title="Manage again"><i class="icon-bell-alt"></i></a> </li>
                                        @endif
                                        <li><a href="{{@Config::get('app.url')}}/admin/slwnpm/nodesummary/{{$item['NodeID']}}" id="edit" class="bs-tooltip" title="Edit" style="text-decoration: none;"><i class="icon-edit"></i></a> </li>
                                        <li><a href="{{@Config::get('app.url')}}/admin/slwnpm/deletenode/{{$item['NodeID']}}" id="delete" class="bs-tooltip" title="Delete" style="text-decoration: none;"><i class="icon-trash"></i></a> </li>
                                </ul>
                            </td>
                        </tr>
                        @endforeach
                        @endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->

<!-- end Modal -->

@endsection
