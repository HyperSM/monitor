@extends('/layout')
@section('content')
@include('slwnpm.menu')

<div style="height: 10px;"></div>

<h4> <b> <a href="{{@Config::get('app.url')}}/admin/slwnpm/nodes">NODES</a> | DELETE NODE </b> </h4>

<div style="height: 10px;"></div>

<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-trash"></i> Delete node</h4>&nbsp;&nbsp;
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
            <div id="err_msg" style="display: none;">{{$err_msg}}</div>
            <div class="widget-content">
                <form class="form-horizontal row-border" action="{{@Config::get('app.url')}}/admin/slwnpm/deletenode/{{$selectednode['NodeID']}}" method="POST">
                    @csrf
                    <input type="text" name="URI" id="URI" value="{{$selectednode['URI']}}" style="display: none;">
                    <div class="form-group">
                        <label class="col-md-9 control-label">Are you sure you want to delete this node? This action CANNOT be undone. This will permanently delete the node <font size="+0.5"><b>{{$selectednode['DisplayName']}}</b></font>.</label>
                        <label class="col-md-7 control-label"><br>Please type the node name to confirm:&nbsp;&nbsp;</label>
                    </div>

                    <div class="form-group">
                        <label class="col-md-5 control-label"> </label>
                        <div class="col-md-2"><input type="text" name="nodename" class="form-control" placeholder="Type the node name" required></div>
                    </div>

                    <div class="form-actions align-center">
                        <button type="button" id="btn_cancel" class="back btn btn-default">Cancel</button>
                        <input type="submit" id="btn_submit" value="Delete" class="btn btn-primary">
                    </div>
                </form>
			</div>
		</div>
	</div>
</div>

<script>
  $('#btn_cancel').click(function() {
    document.location.href="{{@Config::get('app.url')}}/admin/slwnpm/nodes";
  });
  $( document ).ready(function() {
    if (err_msg.innerText !=''){
      alert(err_msg.innerText);
    }
  });
</script>
@endsection
