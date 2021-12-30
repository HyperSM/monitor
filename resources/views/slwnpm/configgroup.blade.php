@extends('/layout')

@section('content')

				@include('slwnpm.menu')

				<div style="height: 50px;"></div>

				<div class="row">
					<div class="col-md-2"></div>
					<div class="col-md-8">
						<div class="widget box">
							<div class="widget-header">
								<h4><i class="icon-reorder"></i> Group View On Node Tree</h4>
							</div>
							<div class="widget-content">
								<form class="form-horizontal row-border" action="{{@Config::get('app.url')}}/admin/slwnpm/configgroup" method="post">
									@csrf
									{{csrf_field()}}
									
									<div class="form-group">
										<label class="col-md-2 control-label">Type</label>
										<div class="col-md-2">
											<select class="form-control" name="nodegroupby">
												<option value="Default" selected>Default</option>
												@foreach ($groups as $group)
												<option value="{{$group['Field']}}" <?php if($group['Field']==$slwnpmserver->nodegroupby){echo 'selected';} ?>>{{$group['Field']}}</option>
												@endforeach
											</select>								
										</div>
									</div>									

									<div class="form-actions">
										@if ($user->slwnpmconfig==1)
										<input type="submit" value="Submit" class="btn btn-primary pull-right" style="width: 120px;"/>
										@endif
										<a href="{{@Config::get('app.url')}}/admin/slwnpm"><input type="button" value="Cancel" class="btn btn-primary pull-right" style="width: 120px;"/></a>
									</div>

								</form>
							</div>
						</div>
					</div>
					<div class="col-md-2"></div>
				</div>
@endsection

