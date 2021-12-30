@extends('/layout')

@section('content')

@include('centreon.menu')

<div class="page-header">
    <div class="">
        <h3>Edit Service Group</h3>
    </div>
</div>

<div class="row">
    <form class="form-horizontal row-border" action="{{@Config::get('app.url')}}/admin/centreon/srvgroup/edit"
        method="POST">
        @csrf
        <input type="hidden" name="servicegroup_name" value="{{$servicegroup->name}}">
        <div class="col-md-12">
            <div class="widget box">
                <div class="widget-header">
                    <h4><i class="icon-reorder"></i> General Information</h4>
                </div>
                <div id="err_msg" style="display: none;">{{$err_msg}}</div>
                <div class="widget-content">

                    <div class="form-group">
                        <label class="col-md-5 control-label">Name</label>
                        <div class="col-md-4"><input type="text" name="name" class="form-control" value="{{$servicegroup->name}}"></div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-5 control-label">Description</label>
                        <div class="col-md-4"><input type="text" name="alias" class="form-control" value="{{$servicegroup->alias}}"></div>
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
    document.location.href = "{{@Config::get('app.url')}}/admin/centreon/srvgroup";
});
$(document).ready(function() {
    if (err_msg.innerText != '') {
        alert(err_msg.innerText);
    }
});
</script>


@endsection
