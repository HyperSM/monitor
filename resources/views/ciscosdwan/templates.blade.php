@extends('/layout')

@section('content')

	@include('ciscosdwan.menu')	
<div style="height: 10px;"></div>
<h4>
	<b>
	TEMPLATE FOR CISCO SDWAN DEVICES
	</b>
</h4>
<div style="height: 10px;"></div>


<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> All templates</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<table class="table table-striped table-bordered table-hover table-checkable datatable" data-display-length="5">
					<thead>
						<tr>
							<th>Template ID</th>
							<th>Template Name</th>
							<th>Description</th>
							<th>Device Attached</th>
							<th>Updated By</th>
							<th>Last Update</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						@if(!isset($enable))
                         	@if(count($templates) > 0 )
                           		@foreach($templates as $template)
                              		<tr>
                                		<td>{{ $template->templateId }}</td>
                                		<td>{{ $template->templateName }}</td>
                                		<td>{{ $template->templateDescription }}</td>
                                		<td>{{ $template->devicesAttached }}</td>
                                		<td>{{ $template->lastUpdatedBy }}</td>
                                		<td>{{ date('M d, Y h:i A',($template->lastUpdatedOn+25200000)/1000) }}</td>
                                		<td>
                                			@if ($user->ciscosdwanconfig==1)
                                  			<ul>									
												<li class="dropdown">
													<button class="btn btn-primary btn-sm" data-toggle="dropdown">
		                                    			<span class="fa fa-angle-double-right"></span>
		                                  			</button>	
													<ul class="dropdown-menu pull-right">
													
														<li><a href="{{@Config::get('app.url')}}/admin/ciscosdwan/templates/{{ $template->templateId }}/attach" title="">Attach</a></li>
														<li><a href="{{@Config::get('app.url')}}/admin/ciscosdwan/templates/{{ $template->templateId }}/detach" title="">Detach</a></li>
													
													</ul>
												</li>
											</ul>
											@endif                            			
                                		</td>
                              		</tr>
                           		@endforeach
                        	@endif
                        @endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

@endsection

