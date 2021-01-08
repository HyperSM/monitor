@extends('sysadmin/layout')
@section('content')
@include('sysadmin.menu')

<!-- Page header -->
<div style="height: 10px;"></div>
	<div class="page-header">
	<h4> <b> <a href="{{@Config::get('app.url')}}/sysadmin/domains">DOMAINS</a> | DOMAIN USERS </b> </h4>
	<div class="page-title">
		<span>Company: {{$domain->company}} / Domain: {{$domain->domainname}}</span>
	</div>
</div>
<!-- /Page Header -->

<div style="height: 10px;"></div>

<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> Domain users</h4>&nbsp;&nbsp;
				<span id="addnew">
					<a style="text-decoration: none;" href="{{@Config::get('app.url')}}/sysadmin/domains/{{$domain->domainid}}/users/adduser"><i class="icon-plus"></i> Add New </a>
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
							<th>UserName</th>
							<th>Full Name</th>
							<th>Email</th>
							<th style="text-align: center;">Action</th>
						</tr>
					</thead>
					<tbody>
            @if(isset($users))
              @foreach($users as $item)
                <tr>
					<td>{{$item->username}}</td>
					<td>{{$item->fullname}}</td>
					<td>{{$item->email}}</td>
					<td class="align-center" style="width: 100px;">
						<ul class="table-controls">
							@if ($item->active==0)
								<li>
									<a href="{{@Config::get('app.url')}}/sysadmin/domains/{{$domain->domainid}}/users/ena/{{$item->userid}}" class="bs-tooltip" title="Enable"><i class="icon-unlock"></i></a>
								</li>
							@else
								<li>
									<a href="{{@Config::get('app.url')}}/sysadmin/domains/{{$domain->domainid}}/users/disa/{{$item->userid}}" class="bs-tooltip" title="Disable"><i class="icon-lock"></i></a>
								</li>
							@endif

							<li><a href="{{@Config::get('app.url')}}/sysadmin/domains/{{$domain->domainid}}/users/edit/{{$item->userid}}" id="edit" class="bs-tooltip" title="Edit" style="text-decoration: none;"><i class="icon-edit"></i></a> </li>
							<li><a href="{{@Config::get('app.url')}}/sysadmin/domains/{{$domain->domainid}}/users/delete/{{$item->userid}}" id="delete" class="bs-tooltip" title="Delete" style="text-decoration: none;"><i class="icon-trash"></i></a> </li>
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
