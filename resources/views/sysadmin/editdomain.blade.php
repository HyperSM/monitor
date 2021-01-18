@extends('sysadmin/layout')
@section('content')
@include('sysadmin.menu')

<div style="height: 10px;"></div>

<h4> <b> <a href="{{@Config::get('app.url')}}/sysadmin/domains">DOMAINS</a> | EDIT DOMAIN </b> </h4>

<div style="height: 10px;"></div>

<div class="row">
  <div class="col-md-12">
    <div class="row" style="height:20px;"></div>
    <div class="widget box">
      <div class="widget-header">
        <h4><i class="icon-reorder"></i> Edit domain</h4>
      </div>
      <div id="err_msg" style="display: none;">{{$err_msg}}</div>
      <div class="widget-content">
        <form class="form-horizontal row-border" action="{{@Config::get('app.url')}}/sysadmin/domains/edit/{{$selecteddomain->domainid}}" method="POST">
          @csrf
          <div class="form-group">
            <label class="col-md-5 control-label">Domain name:</label>
            <div class="col-md-2"><input type="text" name="domainname" id="domainname" class="form-control" value="{{$selecteddomain->domainname}}" required></div>
          </div>

          <div class="form-group">
            <label class="col-md-5 control-label">Company:</label>
            <div class="col-md-3"><input type="text" name="company" class="form-control" value="{{$selecteddomain->company}}"></div>
          </div>

          <div class="form-group">
            <label class="col-md-5 control-label">Address:</label>
            <div class="col-md-3"><input type="text" name="address" class="form-control" value="{{$selecteddomain->address}}"></div>
          </div>

          <div class="form-group">
            <label class="col-md-5 control-label">Telephone:</label>
            <div class="col-md-3"><input type="text" name="tel" class="form-control" value="{{$selecteddomain->tel}}"></div>
          </div>

          <div class="form-group">
            <label class="col-md-5 control-label">Enable domain: </label>
            <div class="col-md-3">
              <label class="checkbox">
                @if ($selecteddomain->domainactive==1)
                  <input type="checkbox" name="active" class="uniform" value="" checked>
                @else
                  <input type="checkbox" name="active" class="uniform" value="">
                @endif
              </label>
            </div>
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
    document.location.href="{{@Config::get('app.url')}}/sysadmin/domains";
  });
  $( document ).ready(function() {
    if (err_msg.innerText !=''){
      alert(err_msg.innerText);
    }
  });
</script>
@endsection
