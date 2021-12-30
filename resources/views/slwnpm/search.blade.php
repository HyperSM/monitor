@extends('/layout')

@section('content')

	@include('slwnpm.menu')

<div style="height: 10px;"></div>
	<h4>
		Search By {{$searchtype}}
	</h4>
	Condition: like % {{$searchtext}} %
<div style="height: 20px;"></div>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> Results</h4>
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
							<th>Node Name</th>
							<th style="width: 150px;">Machine Type</th>
							<th>Description</th>
							<th style="width: 150px;">IP Address</th>
							<th style="width: 250px;">OS</th>
							<th style="width: 250px;">Version</th>
						</tr>
					<thead></thead>
					<body>
						@if (isset($nodes) && count($nodes)>0)
						@foreach ($nodes as $node)
							<tr>
								<td>
									@if (isset($node['NodeName']))
										<a href="{{@Config::get('app.url')}}/admin/slwnpm/nodesummary/{{$node['NodeID']}}" style="text-decoration: none;">
										{{$node['NodeName']}}
										</a>
									@endif
								</td>
								<td>
									@if (isset($node['MachineType']))
										{{$node['MachineType']}}
									@endif
								</td>
								<td>
									@if (isset($node['StatusDescription']))
										{{$node['StatusDescription']}}
									@endif
								</td>
								<td>
									@if (isset($node['IPAddress']))
										{{$node['IPAddress']}}
									@endif
								</td>
								<td>
									@if (isset($node['IOSImage']))
										{{$node['IOSImage']}}
									@endif
								</td>
								<td>
									@if (isset($node['IOSVersion']))
										{{$node['IOSVersion']}}
									@endif
								</td>
							</tr>
						@endforeach
						@endif
					</body>
				</table>
			</div>
		</div>
	</div>
</div>

@endsection