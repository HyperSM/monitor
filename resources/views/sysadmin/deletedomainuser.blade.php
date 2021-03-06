@extends('sysadmin/layout')
@section('content')
@include('sysadmin.menu')

<div style="height: 10px;"></div>

<h4> <b> <a href="{{@Config::get('app.url')}}/sysadmin/domains">DOMAINS</a> | <a href="{{@Config::get('app.url')}}/sysadmin/domains/{{$domainid}}/users">DOMAIN USERS</a> | DELETE DOMAIN USER </b> </h4>

<div style="height: 10px;"></div>

<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-trash"></i> Delete user</h4>&nbsp;&nbsp;
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
      <div id="err_msg" style="display: none;">{{$err_msg}}</div>
			<div class="widget-content">
        <form class="form-horizontal row-border" action="{{@Config::get('app.url')}}/sysadmin/domains/{{$domainid}}/users/delete/{{$selecteduser->userid}}" method="POST">
          @csrf
          <div class="form-group">
            <label class="col-md-9 control-label">Are you sure you want to delete this user? This action CANNOT be undone. This will permanently delete the user <font size="+0.5"><b>{{$selecteduser->username}}</b></font>.</label>
            <label class="col-md-7 control-label"><br>Please type the username to confirm:&nbsp;&nbsp;</label>
          </div>

          <div class="form-group">
            <label class="col-md-5 control-label"> </label>
            <div class="col-md-2"><input type="text" name="username" class="form-control" placeholder="Type the username" required></div>
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
    document.location.href="{{@Config::get('app.url')}}/sysadmin/domains/{{$domainid}}/users";
  });
  $( document ).ready(function() {
    if (err_msg.innerText !=''){
      alert(err_msg.innerText);
    }
  });
</script>
@endsection
