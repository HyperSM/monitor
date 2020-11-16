@extends('/layout')

@section('content')

	@include('admin.menu')
	<div style="height: 10px;"></div>
	<h4>
		<b>
		ADD USER
		</b>
	</h4>
	<div style="height: 10px;"></div>
  <div class="row">
    <div class="col-md-12">
      <div class="widget box">
        <div class="widget-header">
          <h4><i class="icon-reorder"></i> Add new user</h4>
        </div>
				<div id="err_msg" style="display: none;">{{$err_msg}}</div>
        <div class="widget-content">
          <form class="form-horizontal row-border" action="{{@Config::get('app.url')}}/admin/dashboard/users/adduser" method="POST">
            @csrf
            <div class="form-group">
              <label class="col-md-5 control-label">Username:</label>
              <div class="col-md-2"><input type="text" name="username" id="username" class="form-control" required></div>
            </div>

            <div class="form-group">
              <label class="col-md-5 control-label">Full name:</label>
              <div class="col-md-3"><input type="text" name="fullname" class="form-control" required></div>
            </div>

            <div class="form-group">
              <label class="col-md-5 control-label">Password:</label>
              <div class="col-md-3"><input type="password" name="password" class="form-control" required></div>
            </div>

            <div class="form-group align-center">
              <label class="col-md-5 control-label">Email:</label>
              <div class="col-md-3"><input type="text" name="email" class="form-control" required></div>
            </div>

            <div class="form-group">
              <label class="col-md-5 control-label">Enable user: </label>
              <div class="col-md-3">
                <label class="checkbox">
                  <input type="checkbox" name="active" class="uniform" value="">
                </label>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-5 control-label">Rights: </label>
              <div class="col-md-3">
                <label class="checkbox">
                  <input type="checkbox" class="uniform" value="" name="slwnpmuse"> Solarwinds NPM Use
                </label>
                <label class="checkbox">
                  <input type="checkbox" class="uniform" value="" name="slwnpmconfig"> Solarwinds NPM Config
                </label>
								<label class="checkbox">
                  <input type="checkbox" class="uniform" value="" name="casvduse"> CA Service Desk Manager Use
                </label>
								<label class="checkbox">
                  <input type="checkbox" class="uniform" value="" name="casvdconfig"> CA Service Desk Manager Config
                </label>
              </div>

            </div>

            <div class="form-actions align-center">
              <button type="button" id="btn_cancel" class="back btn btn-default">Cancel</button>
              <input type="submit" id="btn_submit" value="Submit" class="btn btn-primary">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script>
    $('#btn_cancel').click(function() {
      document.location.href="{{@Config::get('app.url')}}/admin/dashboard/users";
    });
    $( document ).ready(function() {
      if (err_msg.innerText !=''){
        alert(err_msg.innerText);
      }
    });
  </script>
  @endsection
