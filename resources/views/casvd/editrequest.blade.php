@extends('/layout')
@section('content')
@include('casvd.menu')

<!-- <style>
    label {
        border: 2px solid #ccc;
    }
</style> -->

<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>CA Service Desk | <a href="{{@Config::get('app.url')}}/admin/casvd/allrequests">All Request</a> | Edit Request</h3>
	</div>
</div>

<!-- Init -->

<div class="col-md-12">
    <div class="widget box">
        <div class="widget-header">
            <h4>Edit Request</h4>
        </div>
        <div class="widget-content">
            <form class="form-vertical row-border" action="{{@Config::get('app.url')}}/admin/casvd/allrequests/edit/{{$refnum}}" method="POST">
                @csrf
                <div class="form-group">
                    <table width="100%" >
                        <body>
                            <tr>
                                <td class="col-md-3 control-label" style="text-align: left;">
                                    <a href="{{@Config::get('app.url')}}/admin/casvd/popup/person/requester" target="popup" onclick="window.open('{{@Config::get('app.url')}}/admin/casvd/popup/person/requester/{{$tmpstr["Requester ID"]}}', '_blank','width=1100,height=600')">
                                        <b>Requester</b>
                                    </a>
                                </td>
                                <td class="col-md-4 control-label" style="text-align: left;">
                                    <a href="{{@Config::get('app.url')}}/admin/casvd/popup/person/customer" target="popup" onclick="window.open('{{@Config::get('app.url')}}/admin/casvd/popup/person/customer/{{$tmpstr["Affected End User ID"]}}', '_blank','width=1100,height=600')">
                                        <b>Affected End User</b>
                                    </a>
                                </td>
                                <td class="col-md-3 control-label" style="text-align: left;">
                                    <a href="{{@Config::get('app.url')}}/admin/casvd/popup/category" target="popup" onclick="window.open('{{@Config::get('app.url')}}/admin/casvd/popup/category/{{$tmpstr["Request Area ID"]}}', '_blank','width=1100,height=600')">
                                        <b>Request Area</b>
                                    </a>
                                </td>
                                <td class="col-md-2 control-label" style="text-align: left;"><b>Status</b></td>
                                <td class="col-md-1 control-label" style="text-align: left;"><b>Priority</b></td>
                            </tr>
                            <tr>
                                <td class="col-md-2"><input value='{{$tmpstr["Requester"]}}' type="text" name="requested_by" id='{{$tmpstr["Requester ID"]}}' class="form-control" ></td>
                                <td class="col-md-2"><input value='{{$tmpstr["Affected End User"]}}' type="text" name="customer" id='{{$tmpstr["Affected End User ID"]}}' class="form-control" ></td>
                                <td class="col-md-2"><input value='{{$tmpstr["Request Area"]}}' type="text" name="category" id='{{$tmpstr["Request Area ID"]}}' class="form-control" ></td>
                                <td class="col-md-3">
                                    <select type="text" name="status" id="status" class="form-control">
                                        <option value="">--None--</option>';
                                        <?php $valarr = array("ACK","ASS","WIP","OP","PRBREJ"); ?>
                                        @if(in_array($tmpstr["Status ID"],$valarr)==FALSE)
                                            <option value="{{$tmpstr['Status ID']}}" selected="selected">{{$tmpstr['Status']}}</option>
                                            @foreach($droplist_status as $item)
                                                @if(in_array($item['id'],$valarr)==TRUE)
                                                    <option value="{{$item['id']}}">{{$item['value']}}</option>
                                                @endif
                                            @endforeach
                                        @else
                                            @foreach($droplist_status as $item)
                                                @if($item['id']==$tmpstr["Status ID"])
                                                    <option value="{{$item['id']}}" selected="selected">{{$item['value']}}</option>
                                                    @$check=0;
                                                @else
                                                    <option value="{{$item['id']}}">{{$item['value']}}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                          
                                </td>
                                <td class="col-md-3"><select type="text" name="priority" id="priority" class="form-control">
                                    <?php 
                                        $arr = array("0","1","2","3","4","5","6");
                                        $tmp = '';
                                        foreach ($arr as $item) {
                                            if (strcmp($item, $tmpstr["Priority"]) == 0) {
                                                $tmp = $tmp . '<option value="'.$item.'" selected="selected">'.$item.'</option>';
                                            } else {
                                                $tmp = $tmp . '<option value="'.$item.'">'.$item.'</option>';
                                            }
                                        }
                                        echo $tmp;
                                    ?>
                                </selected></td>
                            </tr>
                        </body>
                    </table>

                    <div style="display: inline-block;"></div>

                    <div class="row" style="padding: 10px;">
                        <div class="widget box">
                            <div class="widget-header">
                                <h4>Detail</h4>
                                <div class="toolbar no-padding">
                                    <div class="btn-group">
                                        <span class="btn btn-xs" data-toggle="collapse" data-target="#detail"><i class="icon-angle-down"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content" id="detail">
                                <table>
                                    <body>
                                        <tr>
                                            <td class="col-md-2 control-label" style="text-align: left;"><b>Reported By</b></td>
                                            <td class="col-md-3 control-label" style="text-align: left;">
                                                <a href="{{@Config::get('app.url')}}/admin/casvd/popup/group" target="popup" onclick="window.open('{{@Config::get('app.url')}}/admin/casvd/popup/group/{{$tmpstr["Group ID"]}}', '_blank','width=1100,height=600')">
                                                    <b>Group</b>
                                                </a>
                                            </td>
                                            <td class="col-md-2 control-label" style="text-align: left;">
                                                <a href="{{@Config::get('app.url')}}/admin/casvd/popup/assignee" target="popup" onclick="window.open('{{@Config::get('app.url')}}/admin/casvd/popup/assignee/{{$tmpstr["Assignee ID"]}}', '_blank','width=1100,height=600')">
                                                    <b>Assignee</b>
                                                </a>
                                            </td>
                                            <td class="col-md-2 control-label" style="text-align: left;">
                                                <a href="{{@Config::get('app.url')}}/admin/casvd/popup/ci" target="popup" onclick="window.open('{{@Config::get('app.url')}}/admin/casvd/popup/ci/{{$tmpstr["Configuration Item ID"]}}', '_blank','width=1100,height=600')">
                                                    <b>Configuration Item</b>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-md-2"><input value='{{$tmpstr["Reported By"]}}' type="text" name="log_agent" id="log_agent" class="form-control" readonly></td>
                                            <td class="col-md-2"><input value='{{$tmpstr["Group"]}}' type="text" name="group" id='{{$tmpstr["Group ID"]}}' class="form-control" ></td>
                                            <td class="col-md-2"><input value='{{$tmpstr["Assignee"]}}' type="text" name="assignee" id='{{$tmpstr["Assignee ID"]}}' class="form-control" ></td>
                                            <td class="col-md-2"><input value='{{$tmpstr["Configuration Item"]}}' type="text" name="affected_resource" id='{{$tmpstr["Configuration Item ID"]}}' class="form-control" ></td> 
                                        </tr>
                                        <tr><td>&nbsp;</td></tr>
                                        <tr>
                                            <td class="col-md-2 control-label" style="text-align: left;"><b>Mail CC</b></td>
                                        </tr>
                                        <tr>
                                            <td class="col-md-2"><input value='{{$tmpstr["Mail CC"]}}' type="text" name="zccaddr" id="zccaddr" class="form-control" ></td>
                                        </tr>
                                        <tr><td>&nbsp;</td></tr>
                                        <tr>
                                            <td class="col-md-2 control-label" style="text-align: left;"><b>Severity</b></td>
                                            <td class="col-md-3 control-label" style="text-align: left;"><b>Urgency</b></td>
                                            <td class="col-md-2 control-label" style="text-align: left;"><b>Impact</b></td>
                                            <td class="col-md-2 control-label" style="text-align: left;"><b>Active?</b></td>
                                        </tr>
                                        <tr>
                                            <td class="col-md-2">
                                                <select type="text" name="severity" id="severity" class="form-control">
                                                    <option value="">--None--</option>';
                                                    @foreach($droplist_severity as $item)
                                                        @if($item['id']==$tmpstr['Severity ID'])
                                                            <option value='{{$item["id"]}}' selected="selected">{{$item["value"]}}</option>
                                                        @else
                                                            <option value='{{$item["id"]}}'>{{$item["value"]}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="col-md-3"><input value='{{$tmpstr["Urgency"]}}' type="text" name="urgency" id="urgency" class="form-control" ></td>
                                            <td class="col-md-2"><input value='{{$tmpstr["Impact"]}}' type="text" name="impact" id="impact" class="form-control" ></td>
                                            <td class="col-md-2"><input value='{{$tmpstr["Active?"]}}' type="text" name="active id="active" class="form-control" ></td>
                                        </tr>
                                        <tr><td>&nbsp;</td></tr>
                                        <tr>
                                            <td class="col-md-2 control-label" style="text-align: left;"><b>Charge Back ID</b></td>
                                            <td class="col-md-3 control-label" style="text-align: left;"><b>Call Back Date/Time</b></td>
                                            <td class="col-md-2 control-label" style="text-align: left;"><b>Resolution Code</b></td>
                                            <td class="col-md-2 control-label" style="text-align: left;"><b>Requester Phone</b></td>
                                            <td class="col-md-2 control-label" style="text-align: left;"><b>Resolution Method By</b></td>
                                            <td class="col-md-1 control-label" style="text-align: left;"><b>ZmainTech</b></td>
                                        </tr>
                                        <tr>
                                            <td class="col-md-2"><input value='{{$tmpstr["Charge Back ID"]}}' type="text" name="charge_back_id" id="charge_back_id" class="form-control" ></td>
                                            <td class="col-md-3"><input value='{{$tmpstr["Call Back Date/Time"]}}' type="text" name="call_back_date" id="call_back_date" class="form-control" ></td>
                                            <td class="col-md-2"><input value='{{$tmpstr["Resolution Code"]}}' type="text" name="resolution_code" id="resolution_code" class="form-control" ></td>
                                            <td class="col-md-2"><input value='{{$tmpstr["Requester Phone"]}}' type="text" name="phonenumber" id="phonenumber" class="form-control" ></td>
                                            <td class="col-md-2"><input value='{{$tmpstr["Resolution Method By"]}}' type="text" name="resolution_method" id="resolution_method" class="form-control" ></td>
                                            <td class="col-md-1"><input value='{{$tmpstr["ZmainTech"]}}' type="text" name="zmain_tech" id="zmain_tech" class="form-control" ></td>
                                        </tr>
                                        <tr><td>&nbsp;</td></tr>
                                        <tr>
                                            <td class="col-md-2 control-label" style="text-align: left;"><b>Change</b></td>
                                            <td class="col-md-3 control-label" style="text-align: left;"><b>Caused by Change Order</b></td>
                                            <td class="col-md-4 control-label" style="text-align: left;"><b>External System Ticket</b></td>
                                        </tr>
                                        <tr>
                                            <td class="col-md-2"><input value='{{$tmpstr["Change"]}}' type="text" name="change" id="change" class="form-control" ></td>
                                            <td class="col-md-3"><input value='{{$tmpstr["Caused by Change Order"]}}' type="text" name="caused_by_chg" id="caused_by_chg" class="form-control" ></td>
                                            <td class="col-md-4"><input value='{{$tmpstr["External System Ticket"]}}' type="text" name="external_system_ticket" id="external_system_ticket" class="form-control" ></td>
                                        </tr>
                                        <tr><td>&nbsp;</td></tr>
                                    </body>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding: 10px;">
                        <div class="widget box">
                            <div class="widget-header">
                                <h4>Summary Information</h4>
                                <div class="toolbar no-padding">
                                    <div class="btn-group">
                                        <span class="btn btn-xs" data-toggle="collapse" data-target="#summaryinfo"><i class="icon-angle-down"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content" id="summaryinfo">
                                <table width="100%" >
                                    <body>
                                        <tr style="background-color: #D0D5D8;">
                                            <td colspan="3"><b>Reported By</b></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="border-right: 5px solid white;"><input type="text" name="summary" id="summary" class="form-control" value={{$refnum}} required></td>
                                        </tr>
                                        <tr style="background-color: #D0D5D8;">
                                            <td colspan="3"><b>Reported By</b></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="border-right: 5px solid white;"><input type="text" name="summary" id="summary" class="form-control" value={{$refnum}} required></td>
                                        </tr>
                                        <tr style="background-color: #D0D5D8;">
                                            <td style="border-right: 5px solid white;"><b>Reported By</b></td>
                                            <td style="border-right: 5px solid white;"><b>Reported By</b></td>
                                            <td><b>Reported By</b></td>
                                        </tr>
                                        <tr>
                                            <td style="border-right: 5px solid white;"><input type="text" name="summary" id="summary" class="form-control" value={{$refnum}} required></td>
                                            <td style="border-right: 5px solid white;"><input type="text" name="summary" id="summary" class="form-control" value={{$refnum}} required></td>
                                            <td style="border-right: 5px solid white;"><input type="text" name="summary" id="summary" class="form-control" value={{$refnum}} required></td>
                                        </tr>
                                    </body>          
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions align-center">
                    <input type="submit" id="btn_submit" value="Submit" class="btn btn-primary">
                    <button type="button" id="btn_cancel" class="back btn btn-primary">Cancel</button>
                </div>
            </form>
        </div>     
    </div>
</div>

<script>
    $('#btn_cancel').click(function(){
        document.location.href="{{@Config::get('app.url')}}/admin/casvd/allrequests"
    });
    $(document).ready(function(){
        if (err_msg.innerText != '') {
            alert(err_msg.innerText);
        }
    });
</script>

@endSection