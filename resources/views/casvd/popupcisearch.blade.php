@extends('/blanklayout')
@section('content')

<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h2>Configuration Item Search</h2>
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
                    <form class="form-vertical row-border" action="{{@Config::get('app.url')}}/admin/casvd/popup/ci/{{$id}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <table width="100%" >
                                <body>
                                    <tr>
                                        <td class="col-md-1 control-label" style="text-align: left;"><b>Name</b></td>
                                        <td class="col-md-1 control-label" style="text-align: left;"><b>Class</b></td>
                                        <td class="col-md-1 control-label" style="text-align: left;"><b>Family</b></td>
                                        <!-- <td class="col-md-1 control-label" style="text-align: left;"><b>Standard CI</b></td> -->
                                        <!-- <td class="col-md-1 control-label" style="text-align: left;"><b>Hostname</b></td> -->
                                    </tr>
                                    <tr>
                                        <td class="col-md-2"><input type="text" name="name" id="name" class="form-control" ></td>
                                        <td class="col-md-2"><input type="text" name="class" id="class" class="form-control" ></td>
                                        <td class="col-md-2"><input type="text" name="family" id="family" class="form-control" ></td>
                                        <!-- <td class="col-md-2"><input type="text" name="standard_ci" id="standard_ci" class="form-control" ></td> -->
                                        <!-- <td class="col-md-2"><input type="text" name="hostname" id="hostname" class="form-control" ></td> -->
                                    </tr>
                                    <tr>
                                        <!-- <td class="col-md-1 control-label" style="text-align: left;"><b>MAC Address</b></td> -->
                                        <!-- <td class="col-md-1 control-label" style="text-align: left;"><b>Alt CI ID</b></td> -->
                                        <!-- <td class="col-md-1 control-label" style="text-align: left;"><b>DNS Name</b></td> -->
                                        <!-- <td class="col-md-1 control-label" style="text-align: left;"><b>Serial Number</b></td> -->
                                        <td class="col-md-1 control-label" style="text-align: left;"><b>Active</b></td>
                                    </tr>
                                    <tr>
                                        <!-- <td class="col-md-2"><input type="text" name="mac_address" id="mac_address" class="form-control" ></td> -->
                                        <!-- <td class="col-md-2"><input type="text" name="alt_ci_id" id="alt_ci_id" class="form-control" ></td> -->
                                        <!-- <td class="col-md-2"><input type="text" name="dns_name" id="dns_name" class="form-control" ></td> -->
                                        <!-- <td class="col-md-2"><input type="text" name="serial_number" id="serial_number" class="form-control" ></td> -->
                                        <td class="col-md-2">
                                            <select type="text" name="active" id="active" class="form-control" >
                                                <option value=""></option>';
                                                @foreach($droplist_active as $item)
                                                        <option value="{{$item['value']}}">{{$item['value']}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <!-- <tr> -->
                                        <!-- <td class="col-md-1 control-label" style="text-align: left;"><b>Contact</b></td> -->
                                        <!-- <td class="col-md-1 control-label" style="text-align: left;"><b>IP Address</b></td> -->
                                        <!-- <td class="col-md-1 control-label" style="text-align: left;"><b>Location</b></td> -->
                                        <!-- <td class="col-md-1 control-label" style="text-align: left;"><b>Status</b></td> -->
                                        <!-- <td class="col-md-1 control-label" style="text-align: left;"><b>Service Type</b></td> -->
                                    <!-- </tr> -->
                                    <!-- <tr> -->
                                        <!-- <td class="col-md-2"><input type="text" name="contact" id="contact" class="form-control" ></td> -->
                                        <!-- <td class="col-md-2"><input type="text" name="ip_address" id="ip_address" class="form-control" ></td> -->
                                        <!-- <td class="col-md-2"><input type="text" name="location" id="location" class="form-control" ></td> -->
                                        <!-- <td class="col-md-2"><input type="text" name="status" id="status" class="form-control" ></td> -->
                                        <!-- <td class="col-md-2"><input type="text" name="service_type" id="service_type" class="form-control" ></td> -->
                                    <!-- </tr> -->
                                    <!-- <tr> -->
                                        <!-- <td class="col-md-1 control-label" style="text-align: left;"><b>Asset</b></td> -->
                                        <!-- <td class="col-md-1 control-label" style="text-align: left;"><b>CI</b></td> -->
                                    <!-- </tr> -->
                                    <!-- <tr> -->
                                        <!-- <td class="col-md-2"><input type="text" name="asset" id="asset" class="form-control" ></td> -->
                                        <!-- <td class="col-md-2"><input type="text" name="ci" id="ci" class="form-control" ></td> -->
                                    <!-- </tr> -->
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
