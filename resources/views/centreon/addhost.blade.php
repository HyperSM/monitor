@extends('/layout')

@section('content')

@include('centreon.menu')

<div class="page-header">
    <div class="">
        <h3>Add Host</h3>
    </div>
</div>

<div class="row">
    <form class="form-horizontal row-border" action="{{@Config::get('app.url')}}/admin/centreon/hosts/addhostsubmit"
        method="POST">
        <div class="col-md-12">
            <div class="widget box">
                <div class="widget-header">
                    <h4><i class="icon-reorder"></i> Host basic information</h4>
                </div>
                <div id="err_msg" style="display: none;">{{$err_msg}}</div>
                <div class="widget-content">
                    @csrf
                    <div class="form-group">
                        <label class="col-md-5 control-label">Host name:</label>
                        <div class="col-md-4"><input type="text" name="hostname" id="hostname" class="form-control"
                                required></div>
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
                                <select class="form-control" name="poller">
                                    @foreach($pollers as $poller)
                                        <option value="{{$poller->name}}">{{$poller->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-5 control-label">Max Check Attempts:</label>
                        <div class="col-md-4"><input type="text" name="max_check_item" class="form-control" ></div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label">Normal Check Interval:</label>
                        <div class="col-md-3"><input type="text" name="check_interval" class="form-control" ></div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label">Retry Check Interval:</label>
                        <div class="col-md-3"><input type="text" name="retry_check_interval" class="form-control" ></div>
                    </div>

                    <div class="form-actions align-center">
                        <input type="submit" id="btn_submit" value="Save" class="btn btn-primary">
                        <button type="button" id="btn_cancel" class="back btn btn-default">Cancel</button>

                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
<script>
$('#btn_cancel').click(function() {
    document.location.href = "{{@Config::get('app.url')}}/admin/centreon/hosts";
});
$(document).ready(function() {
    if (err_msg.innerText != '') {
        alert(err_msg.innerText);
    }
});
</script>


@endsection
