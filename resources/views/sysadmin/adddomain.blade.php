@extends('sysadmin/layout')
@section('content')
@include('sysadmin.menu')

<div style="height: 10px;"></div>

<h4> <b> <a href="{{@Config::get('app.url')}}/sysadmin/domains">DOMAINS</a> | ADD DOMAIN </b> </h4>

<div style="height: 10px;"></div>

<div class="row">
  <div class="col-md-12">
    <div class="widget box">
      <div class="widget-header">
        <h4><i class="icon-reorder"></i> Add new domain</h4>
      </div>
      <div id="err_msg" style="display: none;">{{$err_msg}}</div>
      <div class="widget-content">
        <form class="form-horizontal row-border" action="{{@Config::get('app.url')}}/sysadmin/domains/adddomain" method="POST">
          @csrf
          <div class="form-group">
            <label class="col-md-5 control-label">Domain Name:</label>
            <div class="col-md-2"><input type="text" name="domainname" id="domainname" class="form-control" required></div>
          </div>

          <div class="form-group">
            <label class="col-md-5 control-label">Company:</label>
            <div class="col-md-3"><input type="text" name="company" class="form-control" required></div>
          </div>

          <div class="form-group">
            <label class="col-md-5 control-label">Address:</label>
            <div class="col-md-3"><input type="text" name="address" class="form-control" required></div>
          </div>

          <div class="form-group">
            <label class="col-md-5 control-label">Tel:</label>
            <div class="col-md-3"><input type="text" name="tel" class="form-control" required></div>
          </div>

          <div class="form-group">
            <label class="col-md-5 control-label">Enable domain: </label>
            <div class="col-md-3">
              <label class="checkbox">
                <input type="checkbox" name="active" class="uniform" value="">
              </label>
            </div>
          </div>

          <div class="form-actions align-center">
            <input type="submit" id="btn_submit" value="Submit" class="btn btn-primary">
            <button type="button" id="btn_cancel" class="back btn btn-default">Cancel</button>
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
