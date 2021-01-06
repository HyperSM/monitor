@extends('/layout')
@section('content')
@include('casvd.menu')

<style>
    .field-required:after{
        content: "*";
        color: red;
    }
    p{
        margin:0;
    }
    .set-bold{
        font-weight: bold;
    }
</style>

<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>CA Service Desk | <a href="{{@Config::get('app.url')}}/admin/casvd/allrequests">All Request</a> | Create Request</h3>
	</div>
</div>

<!-- Init -->
<form class="form-vertical row-border" action="{{@Config::get('app.url')}}/admin/casvd/allrequests/create" method="POST" >
    @csrf
    <input type="hidden" name="h_affected_user" id="h_affected_user">
    <div class="col-md-12">
    <div class="widget box">
        <div class="widget-header">
            <h4>Create Request</h4>
        </div>
        <div class="widget-content">
                <div class="row">
                    <div class="form-group">
                            <div class="col-md-3 control-label" style="text-align: left;">
                                <a class="set-bold" href="javascript:void(0)"  onclick="openDialog('requester','{{@Config::get('app.url')}}/admin/casvd/popup/person/requester')">
                                    <b>Requester</b>
                                </a>
                                <input  type="text" id="requested_by" name="requested_by"  class="form-control" >

                            </div>
                            <div class="col-md-3 control-label" style="text-align: left;">
                                <a href="javascript:void(0)"  onclick="openDialog('affected_user','{{@Config::get('app.url')}}/admin/casvd/popup/person/requester')">
                                    <b class="field-required">Affected End User</b>
                                </a>
                                <input  type="text" id="affected_user" name="affected_user"  class="form-control" required>
                            </div>
                            <div class="col-md-3 control-label" style="text-align: left;">
                                <a href="javascript:void(0)" >
                                    <b>Request Area</b>
                                </a>
                                <input  type="text" name="category"  class="form-control" >
                            </div>
                            <div class="col-md-2 control-label" style="text-align: left;">
                                <p class="field-required">Status </p>
                                <select type="text" name="status" id="status" class="form-control">
                                    <?php $valarr = array("ACK","ASS","WIP","OP","PRBREJ"); ?>
                                        @foreach($droplist_status as $item)
                                            @if(in_array($item['id'],$valarr)==TRUE)
                                                @if($item['id'] == 'OP')
                                                    <option value="{{$item['id']}}" selected>{{$item['value']}}</option>
                                                @else
                                                    <option value="{{$item['id']}}">{{$item['value']}}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 control-label" style="text-align: left;">
                                <p class="field-required">Priority </p>
                                <select  name="priority" id="priority" class="form-control">
                                    <?php
                                    $arr = array("0","1","2","3","4","5","6");
                                    $tmp = '';
                                    foreach ($arr as $item) {
                                            $tmp = $tmp . '<option value="'.$item.'">'.$item.'</option>';
                                    }
                                    echo $tmp;
                                    ?>
                                    </select>
                            </div>
                    </div>
                </div>
        </div>
    </div>
</div>

    <!-- Detail -->
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4>Detail</h4>
            </div>
            <div class="widget-content">
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3 control-label" style="text-align: left;">
                            <a href="javascript:void(0)"  onclick="window.open('{{@Config::get('app.url')}}/admin/casvd/popup/person/requester', '_blank','width=1100,height=600')">
                                Report By
                            </a>
                            <input  type="text" name="report_by"  class="form-control" >
                        </div>
                        <div class="col-md-3 control-label" style="text-align: left;">
                            <a href="javascript:void(0)"  onclick="window.open('{{@Config::get('app.url')}}/admin/casvd/popup/group', '_blank','width=1100,height=600')">
                                Group
                            </a>
                            <input  type="text" name="group"  class="form-control" >
                        </div>
                        <div class="col-md-3 control-label" style="text-align: left;">
                            <a href="javascript:void(0)"  onclick="openDialog('assignee',window.open('{{@Config::get('app.url')}}/admin/casvd/popup/assignee/search'))">
                                Assignee
                            </a>
                            <input  type="text" name="assignee"  class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6 control-label" style="text-align: left;">
                            <p>Main CC</p>
                            <input  type="text" name="zccaddr"  class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6 control-label" style="text-align: left;">
                            <p> Serverity</p>
                            <select type="text" name="severity" id="severity" class="form-control">
                                <option value="">--None--</option>';
                                @foreach($droplist_severity as $item)
                                        <option value='{{$item["id"]}}'>{{$item["value"]}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 control-label" style="text-align: left;">
                            <p>Urgency</p>
                            <input  type="text" name="urgency"  class="form-control" >
                        </div>
                        <div class="col-md-3 control-label" style="text-align: left;">
                            <p >Impact</p>
                            <select type="text" name="impact"  class="form-control">
                                @foreach($droplist_impact as $item)
                                        <option value='{{$item["id"]}}'>{{$item["value"]}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6 control-label" style="text-align: left;">
                            <p> Charge Back ID</p>
                            <input type="text" name="charge_back_id"  class="form-control" >
                        </div>
                        <div class="col-md-3 control-label" style="text-align: left;">
                            <p>Call Back Date/Time</p>
                            <input  type="text" name="call_back_date"  class="form-control" >
                        </div>
                        <div class="col-md-3 control-label" style="text-align: left;">
                            <p >Resolution Code</p>
                            <input  type="text" name="resolution_code"  class="form-control" >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4>Summary Infomation</h4>
            </div>
            <div class="widget-content">
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6 control-label" style="text-align: left;">
                            <p>Summary</p>
                            <input  type="text" name="summary"  class="form-control" >
                        </div>
                        <div class="col-md-6 control-label" style="text-align: left;">
                            <p>Description</p>
                            <input  type="text" name="descrition"  class="form-control col-md-2" >
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-4 control-label" style="text-align: left;">
                            <p>Open Date/Time   </p>
                            <p></p>
                        </div>
                        <div class="col-md-4 control-label" style="text-align: left;">
                            <p>	Resolve Date/Time   </p>
                            <p></p>
                        </div>
                        <div class="col-md-4 control-label" style="text-align: left;">
                            <p>Close Date/Time      </p>
                            <p></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group align-center">
            <input type="submit" id="btn_submit" value="Submit" class="btn btn-primary">
            <button type="button" id="btn_cancel" class="back btn btn-primary">Cancel</button>
        </div>
    </div>
<br>
</form>
<script>
    $('#btn_cancel').click(function(){
        document.location.href="{{@Config::get('app.url')}}/admin/casvd/allrequests"
    });

    $(function () {
        document.getElementById('h_affected_user').setAttribute('value',localStorage.getItem('h_affected_user'));
        localStorage.clear();
    });

    function openDialog(item,url) {
        let params = 'width=1100,height=600';
        window.open(url, item, params);
    }


</script>

@endSection
