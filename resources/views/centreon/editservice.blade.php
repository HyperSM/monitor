@extends('/layout')

@section('content')

    @include('centreon.menu')

    <?php
        $host_id = "host id";
        $host_name = "host name";
        $service_id = "id";
        $status = "activate";
        $check_command = "check command";
        $check_interval = "normal check interval";
        $retry_check_interval = "retry check interval";
        $max_check_attempts =  "max check attempts";
    ?>

    <div class="page-header">
        <div class="">
            <h3>Edit a Service</h3>
        </div>
    </div>

    <div class="row">
        <form class="form-horizontal row-border" action="{{@Config::get('app.url')}}/admin/centreon/service/edit"
              method="POST">
            @csrf
            <input type="hidden" name="servicename" value="{{$service->description}}">
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header">
                        <h4><i class="icon-reorder"></i> Service Basic Information</h4>
                    </div>
                    <div id="err_msg" style="display: none;">{{$err_msg}}</div>
                    <div class="widget-content">

                        <div class="form-group">
                            <label class="col-md-5 control-label">Description</label>
                            <div class="col-md-4">
                                <input type="text" name="description"  class="form-control" value="{{$service->description}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label">Linked with Hosts</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <select class="form-control" name="host" >
                                        @foreach($hosts as $host)
                                            @if($host->name == $service->$host_name)
                                                <option value="{{$host->name}}" selected="selected">{{$host->name}}</option>
                                            @else
                                                <option value="{{$host->name}}" >{{$host->name}}</option>
                                            @endif
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
                                        @if($template->description == $service->description)
                                            <option value="{{$template->description}}"  selected="selected">{{$template->description}}</option>
                                        @else
                                            <option value="{{$template->description}}" >{{$template->description}}</option>
                                        @endif
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
                                        @if($service->$check_command == $command->name)
                                             <option value="{{$command->name}}" selected>{{$command->name}}</option>
                                        @else
                                            <option value="{{$command->name}}" >{{$command->name}}</option>
                                        @endif
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
                                <input type="text" name="max_check_attempts"  class="form-control" value="{{$service->$max_check_attempts}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-5 control-label"> Normal Check Interval</label>
                            <div class="col-md-4"><input type="text" name="normal_check_interval" class="form-control"  value="{{$service->$check_interval}}"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-5 control-label"> Retry Check Interval</label>
                            <div class="col-md-4"><input type="text" name="retry_check_interval" class="form-control" value="{{$service->$retry_check_interval}}" ></div>
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
