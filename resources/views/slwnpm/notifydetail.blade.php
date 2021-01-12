@extends('/layout')
@section('content')
@include('slwnpm.menu')

<div style="height: 10px;"></div>
	<h4>
		Notify detail
	</h4>
<div style="height: 20px;"></div>

<!--=== Inline Tabs ===-->
<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i> Configure Action: </h4> <span id="notify"></span>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                    </div>
                </div>
            </div>
            <div class="widget-content">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal row-border" id="notifydetailform" action="{{@Config::get('app.url')}}/admin/slwnpm/notify/{{$data['ActionID']}}" method="POST">
                            @csrf
                            <input type="text" name="nURI" id="nameURI" value="{{$data['A URI']}}" style="display: none;">
                            <input type="text" name="mURI" id="messageURI" value="{{$data['P URI']}}" style="display: none;">
                            <div class="form-group">
                                <label class="col-md-12"><b>Name of action</b></label>
                                <div class="col-md-6"><input type="text" name="title" class="form-control" value="{{$data['Title']}}"></div>
                            </div>
                            <div class="widget box">
                                <div class="widget-header">
                                    <h4><i class="icon-reorder"></i> Settings </h4>
                                </div>
                                <div class="widget-content">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-md-12"><b>Message</b></label>
                                                <div class="col-md-10"><textarea rows="6" cols="100" name="message" form="notifydetailform">{{$data["PropertyValue"]}}</textarea></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group align-center">
                                    <input type="submit" id="btn_submit" value="Submit" class="btn btn-primary">
                                    <button type="button" id="btn_cancel" class="back btn">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#btn_cancel').click(function(){
        document.location.href="{{@Config::get('app.url')}}/admin/slwnpm/notify"
    });
</script>
@endsection