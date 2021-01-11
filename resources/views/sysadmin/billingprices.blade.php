@extends('sysadmin/layout')
@section('content')
@include('sysadmin.menu')

<!-- Page header -->
<div style="height: 10px;"></div>
<h4>
	<b>
	PRICE MANAGEMENT
	</b>
</h4>
<div style="height: 10px;"></div>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> All product prices</h4>&nbsp;&nbsp;
				<span id="addnew">
					<a style="text-decoration: none;" href="{{@Config::get('app.url')}}/sysadmin/billing/prices/addprice"><i class="icon-plus"></i> Add New </a>
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
							<th>Product</th>
							<th>Price</th>
							<th style="text-align: center;">Action</th>
						</tr>
					</thead>
					<tbody>
            @if(isset($billingprices))
              @foreach($billingprices as $item)
                <tr>
					<td>{{$item->product}}</td>
					<td>{{$item->price}}</td>
					<td class="align-center" style="width: 100px;">
							<ul class="table-controls">
								<li><a href="{{@Config::get('app.url')}}/sysadmin/billing/prices/edit/{{$item->product}}" id="edit" class="bs-tooltip" title="Edit" style="text-decoration: none;"><i class="icon-edit"></i></a> </li>
								<li><a href="{{@Config::get('app.url')}}/sysadmin/billing/prices/delete/{{$item->product}}" id="delete" class="bs-tooltip" title="Delete" style="text-decoration: none;"><i class="icon-trash"></i></a> </li>
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
