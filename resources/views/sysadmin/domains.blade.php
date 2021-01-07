@extends('sysadmin/layout')
@section('content')
@include('sysadmin.menu')

<!-- Page header -->
<div style="height: 10px;"></div>
<h4>
	<b>
	DOMAINS
	</b>
</h4>
<div style="height: 10px;"></div>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> All domains</h4>&nbsp;&nbsp;
				<span id="addnew">
					<a style="text-decoration: none;" href="{{@Config::get('app.url')}}/sysadmin/domains/adddomain"><i class="icon-plus"></i> Add New </a>
				</span>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<table class="table table-striped table-bordered table-hover table-checkable datatable" data-display-length="10">
					<thead>
						<tr>
							<th>Domain Name</th>
							<th>Company</th>
							<th>Address</th>
							<th>Tel</th>
							<th style="text-align: center;">Action</th>
						</tr>
					</thead>
					<tbody>
            @if(isset($domains))
              @foreach($domains as $item)
                <tr>
					<td>{{$item->domainname}}</td>
					<td>{{$item->company}}</td>
					<td>{{$item->address}}</td>
					<td>{{$item->tel}}</td>
					<td class="align-center" style="width: 100px;">
							<ul class="table-controls">
								<li><a href="{{@Config::get('app.url')}}/sysadmin/domains/{{$item->domainid}}/users" id="domainusers" class="bs-tooltip" title="Domain Users" style="text-decoration: none;"><i class="icon-user"></i></a> </li>
								@if ($item->domainactive==0)
									<li>
										<a href="{{@Config::get('app.url')}}/sysadmin/domains/ena/{{$item->domainid}}" class="bs-tooltip" title="Enable"><i class="icon-unlock"></i></a>
									</li>
								@else
									<li>
										<a href="{{@Config::get('app.url')}}/sysadmin/domains/disa/{{$item->domainid}}" class="bs-tooltip" title="Disable"><i class="icon-lock"></i></a>
									</li>
								@endif
								<li><a href="{{@Config::get('app.url')}}/sysadmin/domains/edit/{{$item->domainid}}" id="edit" class="bs-tooltip" title="Edit" style="text-decoration: none;"><i class="icon-edit"></i></a> </li>
								<li><a href="{{@Config::get('app.url')}}/sysadmin/domains/delete/{{$item->domainid}}" id="delete" class="bs-tooltip" title="Delete" style="text-decoration: none;"><i class="icon-trash"></i></a> </li>
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
