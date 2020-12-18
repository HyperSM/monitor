@extends('/layout')

@section('content')

@include('centreon.menu')

<div class="page-header">
    <div class="">
        <h3>Add a Service</h3>
    </div>
</div>

<div class="row">
    <form class="form-horizontal row-border" action="{{@Config::get('app.url')}}/admin/centreon/service/add"
        method="POST">
        @csrf
        <div class="col-md-12">
            <div class="widget box">
                <div class="widget-header">
                    <h4><i class="icon-reorder"></i> Service Basic Information</h4>
                </div>
                <div id="err_msg" style="display: none;">{{$err_msg}}</div>
                <div class="widget-content">

                    <div class="form-group">
                        <label class="col-md-5 control-label">Description</label>
                        <div class="col-md-4"><input type="text" name="description"  class="form-control"
                                                     required></div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-5 control-label">Linked with Hosts</label>
                        <div class="row">
                                <div class="col-md-4">
                                    <select class="form-control" name="host">
                                        @foreach($hosts as $host)
                                            <option value="{{$host->name}}">{{$host->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-5 control-label">Template</label>
                        <div class="col-md-4">
                            <select class="form-control" name="template">
                                @foreach($templates as $template)
                                    <option value="{{$template->description}}">{{$template->description}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                </div>
            </div>

        </div>
        <div class="col-md-12">
            <div class="widget box">
                <div class="widget-header">
                    <h4><i class="icon-reorder"></i> Service Check Options</h4>
                </div>
                <div class="widget-content">
                    <div class="form-group">
                        <label class="col-md-5 control-label">Check Command</label>
                        <div class="col-md-4">
                            <select class="form-control" name="command">
                                @foreach($commands as $command)
                                    <option value="{{$command->name}}">{{$command->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label">Custom macros</label>
                        <div class="col-md-4">
                            <input type="text" name="macro_name" class="form-control" placeholder="name" > &nbsp&nbsp&nbsp
                            <input type="text" name="macro_value" class="form-control" placeholder="value" >
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-12">
            <div class="widget box">
                <div class="widget-header">
                    <h4><i class="icon-reorder"></i> Service Scheduling Options</h4>
                </div>
                <div class="widget-content">
                    <div class="form-group">
                        <label class="col-md-5 control-label"> Max Check Attempts</label>
                        <div class="col-md-4">
                            <input type="text" name="max_check_attempts"  class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label"> Normal Check Interval</label>
                        <div class="col-md-4"><input type="text" name="normal_check_interval" class="form-control" ></div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label">  Retry Check Interval</label>
                        <div class="col-md-4"><input type="text" name="retry_check_interval" class="form-control" ></div>
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
    document.location.href = "{{@Config::get('app.url')}}/admin/centreon/service";
});
$(document).ready(function() {
    if (err_msg.innerText != '') {
        alert(err_msg.innerText);
    }
});
</script>


@endsection
