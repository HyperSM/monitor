@extends('/layout')
@section('content')
@include('admin.menu')

<div style="height: 10px;"></div>

<h4> <b> <a href="{{@Config::get('app.url')}}/admin/dashboard/users">USERS</a> | EDIT USER </b> </h4>

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
        <form class="form-horizontal row-border" action="{{@Config::get('app.url')}}/admin/dashboard/users/edit/{{$selecteduser->userid}}" method="POST">
          @csrf
          <div class="form-group">
            <label class="col-md-5 control-label">Username:</label>
            <div class="col-md-2"><input type="text" name="username" id="username" class="form-control" value={{$selecteduser->username}} required></div>
          </div>

          <div class="form-group">
            <label class="col-md-5 control-label">Full name:</label>
            <div class="col-md-3"><input type="text" name="fullname" class="form-control" value={{$selecteduser->fullname}} required></div>
          </div>

          <div class="form-group align-center">
            <label class="col-md-5 control-label">Email:</label>
            <div class="col-md-3"><input type="text" name="email" class="form-control" value="{{$selecteduser->email}}" required></div>
          </div>

          <div class="form-group">
            <label class="col-md-5 control-label">Enable user: </label>
            <div class="col-md-3">
              <label class="checkbox">
                @if ($selecteduser->active==1)
                  <input type="checkbox" name="active" class="uniform" value="" checked>
                @else
                  <input type="checkbox" name="active" class="uniform" value="">
                @endif
              </label>
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-5 control-label">Rights: </label>
            <div class="col-md-3">
              <label class="checkbox">
                @if ($selecteduserrights->accountconfig==1)
                  <input type="checkbox" class="uniform" value="" name="accountconfig" checked> Admin
                @else
                  <input type="checkbox" class="uniform" value="" name="accountconfig"> Admin
                @endif
              </label>
              <div style="height: 20px;"></div>
              <label class="checkbox">
                @if ($selecteduserrights->slwnpmuse==1)
                  <input type="checkbox" class="uniform" value="" name="slwnpmuse" checked> Solarwinds NPM Use
                @else
                  <input type="checkbox" class="uniform" value="" name="slwnpmuse"> Solarwinds NPM Use
                @endif
              </label>
              <label class="checkbox">
                @if ($selecteduserrights->slwnpmconfig==1)
                  <input type="checkbox" class="uniform" value="" name="slwnpmconfig" checked> Solarwinds NPM Config
                @else
                  <input type="checkbox" class="uniform" value="" name="slwnpmconfig"> Solarwinds NPM Config
                @endif
              </label>
              <div style="height: 20px;"></div>
              <label class="checkbox">
                @if ($selecteduserrights->centreonuse==1)
                  <input type="checkbox" class="uniform" value="" name="centreonuse" checked> Centreon Use
                @else
                  <input type="checkbox" class="uniform" value="" name="centreonuse"> Centreon Use
                @endif
              </label>
              <label class="checkbox">
                @if ($selecteduserrights->centreonconfig==1)
                  <input type="checkbox" class="uniform" value="" name="centreonconfig" checked> Centreon Config
                @else
                  <input type="checkbox" class="uniform" value="" name="centreonconfig"> Centreon Config
                @endif
              </label>
              <div style="height: 20px;"></div>
              <label class="checkbox">
                @if ($selecteduserrights->casvduse==1)
                  <input type="checkbox" class="uniform" value="" name="casvduse" checked> CA Service Desk Manager Use
                @else
                  <input type="checkbox" class="uniform" value="" name="casvduse"> CA Service Desk Manager Use
                @endif
              </label>
              <label class="checkbox">
                @if ($selecteduserrights->casvdconfig==1)
                  <input type="checkbox" class="uniform" value="" name="casvdconfig" checked> CA Service Desk Manager Config
                @else
                  <input type="checkbox" class="uniform" value="" name="casvdconfig"> CA Service Desk Manager Config
                @endif
              </label>
              <div style="height: 20px;"></div>
              <label class="checkbox">
                @if ($selecteduserrights->ciscosdwanuse==1)
                  <input type="checkbox" class="uniform" value="" name="ciscosdwanuse" checked> Cisco SDWAN Use
                @else
                  <input type="checkbox" class="uniform" value="" name="ciscosdwanuse"> Cisco SDWAN Use
                @endif
              </label>
              <label class="checkbox">
                @if ($selecteduserrights->ciscosdwanconfig==1)
                  <input type="checkbox" class="uniform" value="" name="ciscosdwanconfig" checked> Cisco SDWAN Config
                @else
                  <input type="checkbox" class="uniform" value="" name="ciscosdwanconfig"> Cisco SDWAN Config
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
    document.location.href="{{@Config::get('app.url')}}/admin/dashboard/users";
  });
  $( document ).ready(function() {
    if (err_msg.innerText !=''){
      alert(err_msg.innerText);
    }
  });
</script>
  @endsection
