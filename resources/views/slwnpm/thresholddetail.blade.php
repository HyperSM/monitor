@extends('/layout')
@section('content')
@include('slwnpm.menu')

<style>
    div #tab_1_1, div #tab_1_2, div #tab_1_3 {
        -webkit-user-select: none;
        -moz-user-select: -moz-none;
        -ms-user-select: none;
        user-select: none;
    }
</style>

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
                <h4><i class="icon-reorder"></i> Edit Notify</h4> <span id="notify"></span>
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
                                <li><a href="#tab_1_2" data-toggle="tab">Trigger Condition</a></li>
                                <li><a href="#tab_1_3" data-toggle="tab">Reset Condition</a></li>
                                <li><a href="#tab_1_4" data-toggle="tab">Time of Day</a></li>
                                <li><a href="#tab_1_5" data-toggle="tab">Trigger Actions</a></li>
                                <li><a href="#tab_1_6" data-toggle="tab">Reset Actions</a></li>
                                <li><a href="#tab_1_7" data-toggle="tab">Summary</a></li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1_1">
                                    <div style="height: 20px;"></div>
                                    <h3><b>1. Properties</b></h3>
                                    <div style="height: 10px;"></div>
                                    <div class="form-group">
										<label class="col-md-12 control-label"><b>Name of alert definition (required)</b></label>
                                        <div class="col-md-5"><input type="text" class="form-control" value="{{$data['Name']}}"></div>
                                    </div>
                                    <div style="height: 70px;"></div>
                                    <div class="form-group">
										<label class="col-md-12 control-label"><b>Description of alert definition</b></label>
                                        <div class="col-md-5"><input type="text" class="form-control" value="{{$data['Name']}}"></div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="tab_1_2">
                                    <div style="height: 20px;"></div>
                                    <h3>2. Trigger Condition</h3>
                                    <div style="height: 10px;"></div>
                                    <h5><b>&emsp;Name of alert definition (required)</b></h5>
                                    <h5>&emsp;Name</h5>
                                    <div style="height: 10px;"></div>
                                    <h5><b>&emsp;Description of alert definition</b></h5>
                                    <h5>&emsp;Paging</h5>
                                </div>

                                <div class="tab-pane" id="tab_1_3">
                                    <div style="height: 20px;"></div>
                                    <h3>3. Reset Condition</h3>
                                    <div style="height: 10px;"></div>
                                    <h5><b>&emsp;Name of alert definition (required)</b></h5>
                                    <h5>&emsp;Name</h5>
                                    <div style="height: 10px;"></div>
                                    <h5><b>&emsp;Description of alert definition</b></h5>
                                    <h5>&emsp;Paging</h5>
                                </div>

                                <div class="tab-pane" id="tab_1_4">
                                    <div style="height: 20px;"></div>
                                    <h3>4. Time of Day</h3>
                                    <div style="height: 10px;"></div>
                                </div>

                                <div class="tab-pane" id="tab_1_5">
                                    <div style="height: 20px;"></div>
                                    <h3>5. Trigger Actions</h3>
                                    <div style="height: 10px;"></div>
                                </div>

                                <div class="tab-pane" id="tab_1_6">
                                    <div style="height: 20px;"></div>
                                    <h3>6. Reset Actions</h3>
                                    <div style="height: 10px;"></div>
                                </div>

                                <div class="tab-pane" id="tab_1_7">
                                    <div style="height: 20px;"></div>
                                    <h3>7. Summary</h3>
                                    <div style="height: 10px;"></div>
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