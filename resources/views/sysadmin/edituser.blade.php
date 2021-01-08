@extends('sysadmin/layout')
@section('content')
@include('sysadmin.menu')

<div style="height: 10px;"></div>

<h4> <b> <a href="{{@Config::get('app.url')}}/sysadmin/users">USERS</a> | EDIT USER </b> </h4>

<div style="height: 10px;"></div>

<div class="row">
  <div class="col-md-12">
    <div class="row" style="height:20px;"></div>
    <div class="widget box">
      <div class="widget-header">
        <h4><i class="icon-reorder"></i> Edit user</h4>
      </div>
      <div id="err_msg" style="display: none;">{{$err_msg}}</div>
      <div class="widget-content">
        <form class="form-horizontal row-border" action="{{@Config::get('app.url')}}/sysadmin/users/edit/{{$selecteduser->userid}}" method="POST">
          @csrf
          <div class="form-group">
            <label class="col-md-5 control-label">Username:</label>
            <div class="col-md-2"><input type="text" name="username" id="username" class="form-control" value="{{$selecteduser->username}}" required></div>
          </div>

          <div class="form-group">
            <label class="col-md-5 control-label">Full name:</label>
            <div class="col-md-3"><input type="text" name="fullname" class="form-control" value="{{$selecteduser->fullname}}"></div>
          </div>

          <div class="form-actions align-center">
            <input type="submit" id="btn_submit" value="Submit" class="btn btn-primary">
            <button type="button" id="btn_cancel" class="back btn">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  $('#btn_cancel').click(function() {
    document.location.href="{{@Config::get('app.url')}}/sysadmin/users";
  });
  $( document ).ready(function() {
    if (err_msg.innerText !=''){
      alert(err_msg.innerText);
    }
  });
</script>
  @endsection
