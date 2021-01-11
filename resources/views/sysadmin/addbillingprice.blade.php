@extends('sysadmin/layout')
@section('content')
@include('sysadmin.menu')

<div style="height: 10px;"></div>

<h4> <b> <a href="{{@Config::get('app.url')}}/sysadmin/billing/prices">PRICE MANAGEMENT</a> | ADD PRODUCT PRICE </b> </h4>

<div style="height: 10px;"></div>

<div class="row">
  <div class="col-md-12">
    <div class="widget box">
      <div class="widget-header">
        <h4><i class="icon-reorder"></i> Add new price</h4>
      </div>
      <div id="err_msg" style="display: none;">{{$err_msg}}</div>
      <div class="widget-content">
        <form class="form-horizontal row-border" action="{{@Config::get('app.url')}}/sysadmin/billing/prices/addprice" method="POST">
          @csrf
          <div class="form-group">
            <label class="col-md-5 control-label">Product:</label>
            <div class="col-md-2">
              <!-- <input type="text" name="product" id="product" class="form-control" required> -->
              <select class="form-control" name="product" id="product" required>
                <option value="casvd">CA Service Desk</option>
                <option value="centreon">Centreon</option>
                <option value="slwnpm">Solarwinds NPM</option>
                <option value="sdwan">Cisco SDWAN</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-5 control-label">Price($):</label>
            <div class="col-md-2"><input type="text" name="price" class="form-control" required></div>
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
    document.location.href="{{@Config::get('app.url')}}/sysadmin/billing/prices";
  });
  $( document ).ready(function() {
    if (err_msg.innerText !=''){
      alert(err_msg.innerText);
    }
  });
</script>
  @endsection
