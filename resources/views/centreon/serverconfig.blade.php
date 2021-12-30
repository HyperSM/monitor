@extends('/layout')

@section('content')
@include('centreon.menu')

<div style="height: 50px;"></div>
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<div class="widget box">
					<div class="widget-header">
								<h4><i class="icon-reorder"></i> Centreon Server Properties</h4>
							</div>
							<div class="widget-content">
								<form class="form-horizontal row-border" action="{{@Config::get('app.url')}}/admin/centreon/centreonserver" method="post">
									@csrf
									{{csrf_field()}}
									<div class="form-group">
										<label class="col-md-2 control-label">Display name:</label>										
										<div class="col-md-3 input-width-large">
											@if (!empty($centreonserver->displayname))
												<input name="displayname" type="text" class="form-control" value="{{$centreonserver->displayname}}" required>
											@else
												<input name="displayname" type="text" class="form-control" value="" required>
											@endif
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-2 control-label">Hostname:</label>										
										<div class="col-md-3 input-width-large">
											@if (!empty($centreonserver->hostname))
												<input name="hostname" type="text" class="form-control" value="{{$centreonserver->hostname}}">
											@else
												<input name="hostname" type="text" class="form-control" value="">
											@endif
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-2 control-label">Type</label>
										<div class="col-md-2">
											@if (empty($centreonserver->secures))
												<select class="form-control" name="secures">
													<option value="https">https</option>
													<option value="http">http</option>
												</select>
											@else
												<select class="form-control" name="secures">
													<option value="https" <?php if($centreonserver->secures=='https'){echo 'selected';}?>>https</option>
													<option value="http" <?php if($centreonserver->secures=='http'){echo 'selected';}?>>http</option>
												</select>
											@endif									
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-2 control-label">Port:</label>
										<div class="col-md-1 input-width-large">
										@if (empty($centreonserver->port))									
											<input name="port" type="text" class="form-control" value="17778">
										@else
											<input name="port" type="text" class="form-control" value="{{$centreonserver->port}}">
										@endif
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-2 control-label">Base string:</label>
										<div class="col-md-8">
											@if (empty($centreonserver->basestring))
												<input name="basestring" type="text" class="form-control" value="">
											@else
												<input name="basestring" type="text" class="form-control" value="{{$centreonserver->basestring}}">
											@endif
										</div>
										<div class="col-md-2"></div>
									</div>

									<div class="form-group">
										<label class="col-md-2 control-label">User:</label>
										<div class="col-md-3 input-width-large">
											@if (!empty($centreonserver->user))
												<input name="user" type="text" class="form-control" value="{{$centreonserver->user}}">
											@else
												<input name="user" type="text" class="form-control" value="">
											@endif
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-2 control-label">Password:</label>
										<div class="col-md-3 input-width-large">
											@if (!empty($centreonserver->password))
												<input name="password" type="password" class="form-control" value="{{$centreonserver->password}}">
											@else
												<input name="password" type="password" class="form-control" value="">
											@endif
										</div>
									</div>

									<div class="form-actions">
										@if ($user->slwnpmconfig==1)
										<input type="submit" value="Submit" class="btn btn-primary pull-right" style="width: 120px;"/>
										@endif
										<a href="{{@Config::get('app.url')}}/admin/centreon"><input type="button" value="Cancel" class="btn btn-primary pull-right" style="width: 120px;"/></a>
									</div>

								</form>
							</div>
						</div>
					</div>
	<div class="col-md-2"></div>
</div>
@endsection

