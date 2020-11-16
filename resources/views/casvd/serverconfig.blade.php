@extends('/layout')

@section('content')

				@include('casvd.menu')

				<div style="height: 50px;"></div>

				<div class="row">
					<div class="col-md-2"></div>
					<div class="col-md-8">
						<div class="widget box">
							<div class="widget-header">
								<h4><i class="icon-reorder"></i> CA Service Desk Server Properties</h4>
							</div>
							<div class="widget-content">
								<form class="form-horizontal row-border" action="{{@Config::get('app.url')}}/admin/casvd/casvdserver" method="post">
									@csrf
									{{csrf_field()}}
									<div class="form-group">
										<label class="col-md-2 control-label">Display name:</label>
										<div class="col-md-3 input-width-large">
											@if (!empty($casvdserver->displayname))
												<input name="displayname" type="text" class="form-control" value="{{$casvdserver->displayname}}">
											@else
												<input name="displayname" type="text" class="form-control" value="">
											@endif
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-2 control-label">Hostname:</label>
										<div class="col-md-3 input-width-large">
											@if (!empty($casvdserver->hostname))
												<input name="hostname" type="text" class="form-control" value="{{$casvdserver->hostname}}">
											@else
												<input name="hostname" type="text" class="form-control" value="">
											@endif
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-2 control-label">Type</label>
										<div class="col-md-2">
											@if (empty($casvdserver->secures))
												<select class="form-control" name="secures">
													<option value="https">https</option>
													<option value="http">http</option>
												</select>
											@else
												<select class="form-control" name="secures">
													<option value="https" <?php if($casvdserver->secures=='https'){echo 'selected';}?>>https</option>
													<option value="http" <?php if($casvdserver->secures=='http'){echo 'selected';}?>>http</option>
												</select>
											@endif
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-2 control-label">Port:</label>
										<div class="col-md-1 input-width-large">
										@if (empty($casvdserver->port))
											<input name="port" type="text" class="form-control" value="17778">
										@else
											<input name="port" type="text" class="form-control" value="{{$casvdserver->port}}">
										@endif
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-2 control-label">Base string:</label>
										<div class="col-md-8">
											@if (empty($casvdserver->basestring))
												<input name="basestring" type="text" class="form-control" value="basestring" required>
											@else
												<input name="basestring" type="text" class="form-control" value="{{$casvdserver->basestring}}" required>
											@endif
										</div>
										<div class="col-md-2"></div>
									</div>

									<div class="form-group">
										<label class="col-md-2 control-label">User:</label>
										<div class="col-md-3 input-width-large">
											@if (!empty($casvdserver->user))
												<input name="user" type="text" class="form-control" value="{{$casvdserver->user}}">
											@else
												<input name="user" type="text" class="form-control" value="">
											@endif
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-2 control-label">Password:</label>
										<div class="col-md-3 input-width-large">
											@if (!empty($casvdserver->password))
												<input name="password" type="password" class="form-control" value="{{$casvdserver->password}}">
											@else
												<input name="password" type="password" class="form-control" value="">
											@endif
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
