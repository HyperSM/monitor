@extends('/blanklayout')
@section('content')

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
				<div class="ct-control-status" style="overflow-x: overlay; border:none;" align="center">
                    <form class="form-vertical row-border" action="{{@Config::get('app.url')}}/admin/casvd/popup/person/requester/{{$id}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <table width="100%" >
                                <body>
                                    <tr>
                                        <td class="col-md-1 control-label" style="text-align: left;"><b>Last Name</b></td>
                                        <td class="col-md-1 control-label" style="text-align: left;"><b>First Name</b></td>
                                        <td class="col-md-1 control-label" style="text-align: left;"><b>Middle Name</b></td>
                                        <td class="col-md-1 control-label" style="text-align: left;"><b>Contact Type</b></td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-2"><input type="text" name="last_name" id="last_name" class="form-control" ></td>
                                        <td class="col-md-2"><input type="text" name="first_name" id="first_name" class="form-control" ></td>
                                        <td class="col-md-2"><input type="text" name="middle_name" id="middle_name" class="form-control" ></td>
                                        <td class="col-md-2"><select type="text" name="contact_type" id="contact_type" class="form-control" >
                                            <option value=""></option>';
                                            @foreach($droplist_contact_type as $item)
                                                    <option value="{{$item['value']}}">{{$item['value']}}</option>
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-1 control-label" style="text-align: left;"><b>Active</b></td>
                                        <td class="col-md-1 control-label" style="text-align: left;"><b>User ID</b></td>
                                        <td class="col-md-1 control-label" style="text-align: left;"><b>Access Type</b></td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-2">
                                            <select type="text" name="active" id="active" class="form-control" >
                                                <option value=""></option>';
                                                @foreach($droplist_active as $item)
                                                        <option value="{{$item['value']}}">{{$item['value']}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="col-md-2"><input type="text" name="userid" id="userid" class="form-control" ></td>
                                        <td class="col-md-2">
                                            <select type="text" name="access_type" id="access_type" class="form-control" >
                                                <option value=""></option>';
                                                @foreach($droplist_access_type as $item)
                                                        <option value="{{$item['value']}}">{{$item['value']}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-1 control-label" style="text-align: left;"><b>Email Address</b></td>
                                        <td class="col-md-1 control-label" style="text-align: left;"><b>Location</b></td>
                                        <td class="col-md-1 control-label" style="text-align: left;"><b>Telephone Number</b></td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-2"><input type="text" name="email" id="email" class="form-control" ></td>
                                        <td class="col-md-2"><input type="text" name="location" id="location" class="form-control" ></td>
                                        <td class="col-md-2"><input type="text" name="telephone" id="telephone" class="form-control" ></td>
                                    </tr>
                                    <tr height = 20px></tr>
                                </body>
                            </table>
                        </div>
                        <input type="submit" id="btn_submit" value="Search" class="btn btn-primary">
                        <button type="button" id="btn_cancel" class="back btn">Cancel</button>
                    </form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
    $('#btn_cancel').click(function(){
        window.close();
    });
</script>
@endsection