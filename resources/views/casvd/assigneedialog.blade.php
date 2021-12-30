@extends('/blanklayout')
@section('content')

    <style type="text/css">
        .css-requester:hover{
            cursor: pointer;
            font-weight: bold;
        }
    </style>

    <!-- Page header -->
    <div class="page-header">
        <div class="page-title">
            <h2>Contact Search</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="widget box">
                <div class="widget-header">
                    <h4><i class="icon-reorder"></i>Search Filter</h4>
                    <div class="toolbar no-padding">
                        <div class="btn-group">
                            <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                        </div>
                    </div>
                </div>

                <div class="widget-content" style="vertical-align: middle;">
                    <div class="ct-control-status" align="center">
{{--                        <form class="form-vertical row-border" >--}}
{{--                            @csrf--}}
{{--                            <div class="form-group">--}}
{{--                                <table width="100%" >--}}
{{--                                    <body>--}}
{{--                                    <tr>--}}
{{--                                        <td class="col-md-1 control-label" style="text-align: left;"><b>Last Name</b></td>--}}
{{--                                        <td class="col-md-1 control-label" style="text-align: left;"><b>First Name</b></td>--}}
{{--                                        <td class="col-md-1 control-label" style="text-align: left;"><b>Middle Name</b></td>--}}
{{--                                        <td class="col-md-1 control-label" style="text-align: left;"><b>Contact Type</b></td>--}}
{{--                                    </tr>--}}
{{--                                    <tr>--}}
{{--                                        <td class="col-md-2"><input type="text" name="last_name" id="last_name" class="form-control" ></td>--}}
{{--                                        <td class="col-md-2"><input type="text" name="first_name" id="first_name" class="form-control" ></td>--}}
{{--                                        <td class="col-md-2"><input type="text" name="middle_name" id="middle_name" class="form-control" ></td>--}}
{{--                                        <td class="col-md-2">--}}
{{--                                            <select type="text" name="contact_type" id="contact_type" class="form-control" >--}}
{{--                                                <option value=""></option>';--}}
{{--                                                @foreach($droplist_contact_type as $item)--}}
{{--                                                    <option value="{{$item['value']}}">{{$item['value']}}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </td>--}}
{{--                                    </tr>--}}
{{--                                    <tr>--}}
{{--                                        <td class="col-md-1 control-label" style="text-align: left;"><b>Active</b></td>--}}
{{--                                        <td class="col-md-1 control-label" style="text-align: left;"><b>User ID</b></td>--}}
{{--                                        <td class="col-md-1 control-label" style="text-align: left;"><b>Access Type</b></td>--}}
{{--                                        <td class="col-md-1 control-label" style="text-align: left;"><b>Email Address</b></td>--}}
{{--                                    </tr>--}}
{{--                                    <tr>--}}
{{--                                        <td class="col-md-2">--}}
{{--                                            <select type="text" name="active" id="active" class="form-control" >--}}
{{--                                                <option value=""></option>';--}}
{{--                                                @foreach($droplist_active as $item)--}}
{{--                                                    <option value="{{$item['value']}}">{{$item['value']}}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </td>--}}
{{--                                        <td class="col-md-2"><input type="text" name="userid" id="userid" class="form-control" ></td>--}}
{{--                                        <td class="col-md-2">--}}
{{--                                            <select type="text" name="access_type" id="access_type" class="form-control" >--}}
{{--                                                <option value=""></option>';--}}
{{--                                                @foreach($droplist_access_type as $item)--}}
{{--                                                    <option value="{{$item['value']}}">{{$item['value']}}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </td>--}}
{{--                                        <td class="col-md-2"><input type="text" name="email" id="email" class="form-control" ></td>--}}
{{--                                    </tr>--}}
{{--                                    <tr height = 20px></tr>--}}
{{--                                    </body>--}}
{{--                                </table>--}}
{{--                            </div>--}}
{{--                            <input type="submit" id="btn_submit" value="Search" class="btn btn-primary">--}}
{{--                            <button type="button" id="btn_cancel" class="back btn">Cancel</button>--}}
{{--                        </form>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="widget box">
                <div class="widget-header"></div>
                <div class="widget-content" style="vertical-align: middle;">
                    <div class="ct-control-status" >
                        <table class="table table-striped table-bordered table-hover" id="requesters" style="width: 100%;" >
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>User ID</th>
                                <th>Area</th>
                                <th>Email Address</th>
                                <th>Contact Type</th>
                                <th>Access Type</th>
                                <th>Workshift</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($result as $item)
                                <tr >
                                    <td>{{$item['id']}}</td>
                                    <td ><p class="css-requester">{{$item['name']}}</p></td>
                                    <td>{{$item['user_id']}}</td>
                                    <td>{{$item['area']}}</td>
                                    <td>{{$item['email_address']}}</td>
                                    <td>{{$item['contact_type']}}</td>
                                    <td>{{$item['access_type']}}</td>
                                    <td>{{$item['workshift']}}</td>
                                    <td>{{$item['status']}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#btn_cancel').click(function () {
                window.close();
            });

            $('table').find('p.css-requester').click(function () {
                    var value = $(this).text();
                    var name  = window.name;
                    if(name == 'requester') {
                        window.opener.document.getElementById('requested_by').value = value;
                    }
                    if(name == 'customer') {
                        window.opener.document.getElementById('customer').value = value;
                    }
                    window.close();
            });

        });
    </script>
@endsection
