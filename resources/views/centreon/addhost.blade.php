@extends('/layout')

@section('content')

@include('centreon.menu')
	<div style="height: 10px;"></div>
	<h4>
		<b>
		ADD HOST
		</b>
	</h4>
	<div style="height: 10px;"></div>
  <div class="row">
    <div class="col-md-12">
      <div class="widget box">
        <div class="widget-header">
          <h4><i class="icon-reorder"></i> Add new host</h4>
        </div>
				<div id="err_msg" style="display: none;">{{$err_msg}}</div>
        <div class="widget-content">
          <form class="form-horizontal row-border" action="{{@Config::get('app.url')}}/admin/centreon/hosts/addhost" method="POST">
            @csrf
            <div class="form-group">
              <label class="col-md-5 control-label">Host name:</label>
              <div class="col-md-4"><input type="text" name="hostname" id="hostname" class="form-control" required></div>
            </div>

            <div class="form-group">
              <label class="col-md-5 control-label">Alias</label>
              <div class="col-md-4"><input type="text" name="alias" class="form-control" required></div>
            </div>

            <div class="form-group">
              <label class="col-md-5 control-label">IP Address / DNS:</label>
              <div class="col-md-4"><input type="text" name="address" class="form-control" required></div>
            </div>

            <div class="form-group align-center">
              <label class="col-md-5 control-label">Monitored from:</label>
              <div class="row">
								<div class="col-md-4">
									<select class="form-control" name="select">
										<option value="opt1">col-md-3</option>
										<option value="opt2">Option 2</option>
										<option value="opt3">Option 3</option>
									</select>
								</div>
							</div>
            </div>

            <div class="form-group">
              <label class="col-md-5 control-label">Templates: </label>
              <div class="row">
								<div class="col-md-4">
									<select class="form-control" name="select">
										<option value="opt1">col-md-3</option>
										<option value="opt2">Central</option>
									</select>
								</div>
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
      document.location.href="{{@Config::get('app.url')}}/admin/centreon/hosts";
    });
    $( document ).ready(function() {
      if (err_msg.innerText !=''){
        alert(err_msg.innerText);
      }
    });
  </script>
@endsection
