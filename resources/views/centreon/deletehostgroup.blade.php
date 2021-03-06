@extends('/layout')

@section('content')

@include('admin.menu')
<div style="height: 10px;"></div>
<h4>
	<b>
	DELETE HOST GROUP
	</b>
</h4>
<div style="height: 10px;"></div>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-trash"></i> Delete host</h4>&nbsp;&nbsp;
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
      <div id="err_msg" style="display: none;">{{$err_msg}}</div>
			<div class="widget-content">
        <form class="form-horizontal row-border" action="{{@Config::get('app.url')}}/admin/centreon/hostgroup/delete/{{$name}}" method="POST">
          @csrf

          <div class="form-group">
            <label class="col-md-9 control-label">Are you sure you want to delete this host? This action CANNOT be undone. This will permanently delete  <font size="+0.5"><b>{{$name}}</b></font>.</label>
          </div>

          <div class="form-actions align-center">
            <button type="button" id="btn_cancel" class="back btn btn-default">Cancel</button>
            <input type="submit" id="btn_submit" value="Delete" class="btn btn-primary">
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
