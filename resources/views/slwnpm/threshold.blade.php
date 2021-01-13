@extends('/layout')
@section('content')
@include('slwnpm.menu')

<div style="height: 10px;"></div>
	<h4>
		Threshold
	</h4>
<div style="height: 20px;"></div>

<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> Threshold</h4>
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
							<th style="width: 23%;">Alert Name</th>
                            <th style="width: 5%;">Enabled (On/Off)</th>
                            <th style="width: 23%;">Alert Description</th>
                            <th style="width: 10%;">Property to Monitor</th>
                            <th style="width: 21%;">Trigger Action(s)</th>
                            <th style="width: 10%;">Owner</th>
                            <th style="width: 8%;">Type</th>
						</tr>
					<thead></thead>
					<body>
						@if (isset($data) && count($data)>0)
                            @foreach ($data as $item)
                                <tr>
                                    <td>
                                        @if (isset($item['Name']))
                                            <a href="{{@Config::get('app.url')}}/admin/slwnpm/threshold/{{$item['AlertID']}}" style="text-decoration: none;">
                                                {{$item['Name']}}
                                            </a>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        @if (isset($item['Enabled']))
                                            <ul class="table-controls">
                                                @if ($item['Enabled'] == 'True')
                                                    <li><a href="{{@Config::get('app.url')}}/admin/slwnpm/threshold/disable/{{$item['AlertID']}}" class="bs-tooltip" title="Disable"><i class="icon-check"></i></a></li>
                                                @else
                                                    <li><a href="{{@Config::get('app.url')}}/admin/slwnpm/threshold/enable/{{$item['AlertID']}}" class="bs-tooltip" title="Enable"><i class="icon-check-empty"></i></a></li>
                                                @endif
                                            </ul>
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($item['Description']))
                                            {{$item['Description']}}
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($item['ObjectType']))
                                            {{$item['ObjectType']}}
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($item['Title']))
                                            {{$item['Title']}}
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($item['CreatedBy']))
                                            {{$item['CreatedBy']}}
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($item['Canned']))
                                            @if ($item['Canned']=='True')
                                                Out-of-the-box
                                            @else
                                                User-defined
                                            @endif
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