@extends('/layout')
@section('content')
@include('slwnpm.menu')

<div style="height: 10px;"></div>
	<h4>
		Threshold detail
	</h4>
<div style="height: 20px;"></div>

<!--=== Inline Tabs ===-->
<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i> Edit Alerts</h4> <span id="notify"></span>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                    </div>
                </div>
            </div>
            <div class="widget-content">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Tabs-->
                        <div class="tabbable tabbable-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab_1_1" data-toggle="tab">Properties</a></li>
                            </ul>

                            <div class="tab-content">
                                <!-- 1. Properties -->
                                <div class="tab-pane" id="tab_1_1">
                                    <div style="height: 20px;"></div>
                                    <h3><b>1. Properties</b></h3>
                                    <div style="height: 10px;"></div>
                                    <div class="form-group">
										<label class="col-md-12 control-label"><b>Name of alert definition (required)</b></label>
                                        <div class="col-md-5"><input type="text" class="form-control" value="{{$properties['Name']}}"></div>
                                    </div>
                                    <div style="height: 70px;"></div>
                                    <div class="form-group">
										<label class="col-md-12 control-label"><b>Description of alert definition</b></label>
                                        <div class="col-md-5"><input type="text" class="form-control" value="{{$properties['Description']}}"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--END TABS-->
                    </div>
                </div> <!-- /.row -->
            </div> <!-- /.widget-content -->
        </div> <!-- /.widget .box -->
    </div> <!-- /.col-md-12 -->
</div> <!-- /.row -->
<!-- /Inline Tabs -->

@endsection