@extends('/layout')
@section('content')
@include('slwnpm.menu')

<div style="height: 10px;"></div>
	<h4>
		Notify
	</h4>
<div style="height: 20px;"></div>

<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> Manage Actions</h4>
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
							<th style="width: 35%;">Action Name</th>
                            <th style="width: 5%;">Enabled (On/Off)</th>
                            <th style="width: 11%;">Action on Alert</th>
                            <th style="width: 8%;">Action Type</th>
                            <th style="width: 35%;">Assigned Alerts</th>
                            <th style="width: 6%;">Environment</th>
						</tr>
					</thead>
					<body>
						@if (isset($data) && count($data)>0)
                            @foreach ($data as $item)
                                <tr>
                                    <td>
                                        @if (isset($item['Title']))
                                            <!-- <a href="{{@Config::get('app.url')}}/admin/slwnpm/notify/{{$item['ActionID']}}" style="text-decoration: none;"> -->
                                                {{$item['Title']}}
                                            <!-- </a> -->
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        @if (isset($item['Enabled']))
                                            <ul class="table-controls">
                                                @if ($item['Enabled'] == 'True')
                                                    <li><a href="{{@Config::get('app.url')}}/admin/slwnpm/notify/disable/{{$item['ActionID']}}" class="bs-tooltip" title="Disable"><i class="icon-check"></i></a></li>
                                                @else
                                                    <li><a href="{{@Config::get('app.url')}}/admin/slwnpm/notify/enable/{{$item['ActionID']}}" class="bs-tooltip" title="Enable"><i class="icon-check-empty"></i></a></li>
                                                @endif
                                            </ul>
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($item['ActionTypeID']))
                                            <a href="#" class="bs-popover" data-trigger="hover" data-placement="bottom" data-content="{{$item['Description']}}" data-original-title="{{$item['Assigned Alerts']}}" style="text-decoration: none;">
                                                {{$item['ActionTypeID']}}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($item['CategoryType']))
                                                {{$item['CategoryType']}} Action
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($item['Assigned Alerts']))
                                            <a href="{{@Config::get('app.url')}}/admin/slwnpm/threshold/{{$item['AlertID']}}" class="bs-popover" data-trigger="hover" data-placement="bottom" data-content="{{$item['Assigned Alerts Pop']}}" data-original-title="{{$item['Assigned Alerts']}}" style="text-decoration: none;">
                                                {{$item['Assigned Alerts']}}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($item['EnvironmentType']))
                                            {{$item['EnvironmentType']}}
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