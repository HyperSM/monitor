@extends('/layout')
@section('content')
@include('admin.menu')

<!-- Page header -->
<div style="height: 10px;"></div>
<h4>
	<b>
	USERS
	</b>
</h4>
<div style="height: 10px;"></div>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> All users</h4>&nbsp;&nbsp;
				@if ($user->accountconfig==1)
				<span id="addnew">
					<a style="text-decoration: none;" href="{{@Config::get('app.url')}}/admin/dashboard/users/adduser"><i class="icon-plus"></i> Add New </a>
				</span>
				@endif
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
										@if ($user->accountconfig==1)
										<ul class="table-controls">

                    @if ($item->active==0)
    										<li>
                          <a href="{{@Config::get('app.url')}}/admin/dashboard/users/ena/{{$item->userid}}" class="bs-tooltip" title="Enable"><i class="icon-unlock"></i></a>
                        </li>
                    @else
                        <li>
                          <a href="{{@Config::get('app.url')}}/admin/dashboard/users/disa/{{$item->userid}}" class="bs-tooltip" title="Disable"><i class="icon-lock"></i></a>
                        </li>
										@endif

                      <li><a href="{{@Config::get('app.url')}}/admin/dashboard/users/edit/{{$item->userid}}" id="edit" class="bs-tooltip" title="Edit" style="text-decoration: none;"><i class="icon-edit"></i></a> </li>
											<li><a href="{{@Config::get('app.url')}}/admin/dashboard/users/delete/{{$item->userid}}" id="delete" class="bs-tooltip" title="Delete" style="text-decoration: none;"><i class="icon-trash"></i></a> </li>
										</ul>
										@endif
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
