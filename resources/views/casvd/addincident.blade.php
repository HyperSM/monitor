@extends('/layout')
@section('content')
@include('casvd.menu')

<div style="height: 10px;"></div>
<h4>
  <b>
  ADD INCIDENT
  </b>
</h4>
<div style="height: 10px;"></div>
<div class="row">
  <div class="col-md-12">
    <div class="widget box">
      <div class="widget-header">
        <h4><i class="icon-reorder"></i> Add new incident</h4>
      </div>
      <div id="err_msg" style="display: none;">{{$err_msg}}</div>
      <div class="widget-content">
        <form class="form-horizontal row-border" action="{{@Config::get('app.url')}}/admin/casvd/addincident" method="POST">
          @csrf
          <div class="form-group">
            <label class="col-md-5 control-label">Summary:</label>
            <div class="col-md-4"><input type="text" name="summary" id="summary" class="form-control" required></div>
          </div>

          <div class="form-group">
            <label class="col-md-5 control-label">Priority:</label>
            <div class="col-md-1 clearfix">
                <?php echo $dl_priority; ?>
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-5 control-label">Category:</label>
            <div class="col-md-2 clearfix">
                <?php echo $dl_category; ?>
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-5 control-label">Configuration Item (CI):</label>
            <div class="col-md-5 clearfix">
            <?php echo $dl_ci; ?>
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-5 control-label">Status:</label>
            <div class="col-md-2 clearfix">
              <select name="status" class="select2 full-width-fix required">
                <option></option>
                <option value="Open">Open</option>
                <option value="Researching">Researching</option>
                <option value="In Progress">In Progress</option>
                <option value="Closed Unresolved">Closed Unresolved</option>
              </select> 
            </div>
          </div>

          <div class="form-group align-center">
            <label class="col-md-5 control-label">Group:</label>
            <div class="col-md-5 clearfix">
            <?php echo $dl_group; ?>
            </div>
          </div>

          <div class="form-group align-center">
            <label class="col-md-5 control-label">Assigned to:</label>
            <div class="col-md-3"><input type="text" name="assignee" class="form-control" required></div>
          </div>

          <div class="form-group align-center">
            <label class="col-md-5 control-label">Main assignee:</label>
            <div class="col-md-3"><input type="text" name="zmain_tech" class="form-control"></div>
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
    document.location.href="{{@Config::get('app.url')}}/admin/casvd";
  });
  $( document ).ready(function() {
    if (err_msg.innerText !=''){
      alert(err_msg.innerText);
    }
  });

</script>
@endsection
