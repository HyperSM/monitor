@extends('/layout')
@section('content')
@include('slwnpm.menu')

<div style="height: 10px;"></div>

<h4> <b> <a href="{{@Config::get('app.url')}}/admin/slwnpm/nodes">NODES</a> | ADD NODE </b> </h4>

<div style="height: 10px;"></div>

<div class="row">
  <div class="col-md-12">
    <div class="widget box">
      <div class="widget-header">
        <h4><i class="icon-reorder"></i> Add new node</h4>
      </div>
      <div class="widget-content">
        <form class="form-horizontal row-border" action="{{@Config::get('app.url')}}/admin/slwnpm/addnode" method="POST">
          @csrf
          <div class="form-group">
            <label class="col-md-5 control-label">Name:</label>
            <div class="col-md-2"><input type="text" name="nodename" id="nodename" class="form-control" required></div>
          </div>

          <div class="form-group">
            <label class="col-md-5 control-label">Polling IP Address:</label>
            <div class="col-md-3"><input type="text" name="ipaddress" class="form-control"></div>
          </div>

          <!-- <div class="form-group">
            <label class="col-md-5 control-label">Password:</label>
            <div class="col-md-3"><input type="password" name="password" class="form-control" required></div>
          </div> -->

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
    document.location.href="{{@Config::get('app.url')}}/admin/slwnpm/nodes";
  });
  $( document ).ready(function() {
    if (err_msg.innerText !=''){
      alert(err_msg.innerText);
    }
  });
</script>
  @endsection
