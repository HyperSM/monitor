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

                                <!-- 2. Trigger Condition -->
                                <div class="tab-pane active" id="tab_1_2">
                                    <div style="height: 20px;"></div>
                                    <h3><b>2. Trigger Condition</b></h3>
                                    <h5>Trigger condition is simple condition or set of multiple nested conditions which must be met before the alert is triggered.</h5>
                                    <div style="height: 10px;"></div>
                                    <table style="border: 0px;">
                                        <tr>
                                            <td><h5><b>&emsp;I want to alert on:&emsp;</b></h5></td>
                                            <td>
                                                <select name="objecttype" id="objecttype" onchange="test(this)">
                                                    <!-- @foreach ($trigger as $item)
                                                        <option value="{{$item['Fullname']}}">{{$item['DisplayName']}}</option>
                                                    @endforeach -->
                                                    <!-- <option value="customsql">Custom SQL Alert (Advanced)</option>
                                                    <option value="customswql">Custom SWQL Alert (Advanced)</option> -->
                                                    <optgroup label="COMMON OBJECTS (4)">
                                                        <option value="obj_grp">Group</option>
                                                        <option value="obj_int">Interface</option>
                                                        <option value="obj_node">Node</option>
                                                        <option value="obj_vol">Volume</option>
                                                    </optgroup>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>

                                    <div style="height: 30px;"></div>
                                    <div class="widget box">
                                        <div class="widget-header">
                                            <h5><i class="icon-reorder"></i><b> The actual trigger condition: </b></h5>
                                        </div>
                                        <div class="widget-content">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <table>
                                                            <tbody>
                                                                <tr style="height: 35px;">
                                                                    <td style="background-color: #ebebeb; padding: 3px 12px;">Trigger alert when</td>
                                                                    <td style="padding: 0px;">
                                                                        <div style="height: 3px; width: 10px; background-color: #ebebeb;"></div>
                                                                    </td>
                                                                    <td colspan="3" style="background-color: #ffe89e; padding: 3px 12px; width: 500px;">
                                                                        <span>
                                                                            <select name="group-condition" id="group-condition" style="border: solid 1px #d5d5d5;">
                                                                                <option value="AND" data-shortname="And">All child conditions must be satisfied (AND)</option>
                                                                                <option value="OR" data-shortname="Or">At least one child condition must be satisfied (OR)</option>
                                                                                <option value="NONE" data-shortname="And">All child conditions must NOT be satisfied</option>
                                                                                <option value="NOTALL" data-shortname="Or">At least one child condition must NOT be satisfied</option>
                                                                            </select>
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td rowspan="2" colspan="2"></td>
                                                                    <td style="padding: 0px;">
                                                                        <table id="tbl-conditions">
                                                                            <tr>
                                                                                <td style="padding: 0px;">
                                                                                    <div style="margin-left: 10px; height: 50px; width: 3px; background-color: #ebebeb;"></div>
                                                                                </td>
                                                                                <td style="padding: 0px;">
                                                                                    <div style="height: 3px; width: 10px; background-color: #ebebeb;"></div>
                                                                                </td>
                                                                                <td colspan="3" style="padding: 0px;">
                                                                                    <ul style="margin: 0px; padding: 0px;">
                                                                                        <li>
                                                                                            <table style="padding: 0px;">
                                                                                                <tr>
                                                                                                    <td style="background-color: #ebebeb; padding: 3px 12px; height: 35px; width: 500px;">
                                                                                                        <span style="white-space: nowrap;">
                                                                                                            <select name="group-condition" id="group-condition" style="border: solid 1px #d5d5d5;">
                                                                                                                <optgroup label="Common objects">
                                                                                                                    <?php 
                                                                                                                        
                                                                                                                    ?>
                                                                                                                </optgroup>
                                                                                                            </select>
                                                                                                        </span>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </table>
                                                                                        </li>
                                                                                    </ul>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>

                                    <div style="height: 30px;"></div>
                                    <div class="widget box">
                                        <div class="widget-header">
                                            <h5><i class="icon-reorder"></i><b> The actual trigger condition: </b></h5>
                                        </div>
                                        <div class="widget-content">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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