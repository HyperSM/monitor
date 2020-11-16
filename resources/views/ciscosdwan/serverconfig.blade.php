@extends('/layout')

@section('content')

				@include('ciscosdwan.menu')

				<div style="height: 50px;"></div>

				<div class="row">
					<div class="col-md-2"></div>
					<div class="col-md-8">
						<div class="widget box">
							<div class="widget-header">
								<h4><i class="icon-reorder"></i>Cisco SDWAN VManage Properties</h4>
							</div>
							<div class="widget-content">
								<form class="form-horizontal row-border" action="{{@Config::get('app.url')}}/admin/ciscosdwan/ciscosdwanserver" method="post">
									@csrf
									<div class="form-group">
										<label class="col-md-2 control-label">Display name:</label>										
										<div class="col-md-3 input-width-large">
											@if (!empty($ciscosdwanserver->displayname))
												<input name="displayname" type="text" class="form-control" value="{{$ciscosdwanserver->displayname}}">
											@else
												<input name="displayname" type="text" class="form-control" value="">
											@endif
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-2 control-label">Hostname:</label>										
										<div class="col-md-6">
											@if (!empty($ciscosdwanserver->hostname))
												<input name="hostname" type="text" class="form-control" value="{{$ciscosdwanserver->hostname}}">
											@else
												<input name="hostname" type="text" class="form-control" value="">
											@endif
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-2 control-label">Type</label>
										<div class="col-md-2">
											@if (empty($ciscosdwanserver->secures))
												<select class="form-control" name="secures">
													<option value="https">https</option>
													<option value="http">http</option>
												</select>
											@else
												<select class="form-control" name="secures">
													<option value="https" <?php if($ciscosdwanserver->secures=='https'){echo 'selected';}?>>https</option>
													<option value="http" <?php if($ciscosdwanserver->secures=='http'){echo 'selected';}?>>http</option>
												</select>
											@endif									
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-2 control-label">Port:</label>
										<div class="col-md-1 input-width-large">
										@if (empty($ciscosdwanserver->port))									
											<input name="port" type="text" class="form-control" value="8443">
										@else
											<input name="port" type="text" class="form-control" value="{{$ciscosdwanserver->port}}">
										@endif
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-2 control-label">Base string:</label>
										<div class="col-md-8">
											@if (empty($ciscosdwanserver->basestring))
												<input name="basestring" type="text" class="form-control" value="/dataservice/">
											@else
												<input name="basestring" type="text" class="form-control" value="{{$ciscosdwanserver->basestring}}">
											@endif
										</div>
										<div class="col-md-2"></div>
									</div>

									<div class="form-group">
										<label class="col-md-2 control-label">User:</label>
										<div class="col-md-3 input-width-large">
											@if (!empty($ciscosdwanserver->user))
												<input name="user" type="text" class="form-control" value="{{$ciscosdwanserver->user}}">
											@else
												<input name="user" type="text" class="form-control" value="">
											@endif
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-2 control-label">Password:</label>
										<div class="col-md-3 input-width-large">
											@if (!empty($ciscosdwanserver->password))
												<input name="password" type="password" class="form-control" value="{{$ciscosdwanserver->password}}">
											@else
												<input name="password" type="password" class="form-control" value="">
											@endif
										</div>
									</div>

									<div class="form-actions">
										@if ($user->ciscosdwanconfig==1)
										<input type="submit" value="Submit" class="btn btn-primary pull-right" style="width: 120px;"/>
										@endif
										<a href="{{@Config::get('app.url')}}/admin/ciscosdwan"><input type="button" value="Cancel" class="btn btn-primary pull-right" style="width: 120px;"/></a>
									</div>

								</form>
							</div>
						</div>
					</div>
					<div class="col-md-2"></div>
				</div>
@endsection

