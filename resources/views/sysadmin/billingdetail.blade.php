@extends('sysadmin/layout')
@section('content')
@include('sysadmin.menu')

<!-- Page header -->
<div style="height: 10px;"></div>
<h4>
	<b>
	BILLING DETAIL
	</b>
</h4>
<div style="height: 10px;"></div>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> All domains</h4>&nbsp;&nbsp;
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
							<th style="width: 16%">Domain Name</th>
							<th style="width: 16%">Company</th>
							<th style="width: 16%">CA Service Desk</th>
							<th style="width: 16%">Centreon</th>
							<th style="width: 16%">Solarwinds NPM</th>
							<th style="width: 16%">Cisco SDWAN</th>
						</tr>
					</thead>
					<tbody>
            @if(isset($domains))
              @foreach($domains as $item)
                <tr>
					<td><a href="{{@Config::get('app.url')}}/sysadmin/billing/detail/{{$item->domainid}}">{{$item->domainname}}</a></td>
					<td>{{$item->company}}</td>
					<td>
						<select class="form-control" name="test" id="test">
							<option value="1">CA Service Desk</option>
							<option value="2">2</option>
						</select>
					</td>
					<td></td>
					<td></td>
					<td></td>
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
