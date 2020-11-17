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
				<h4><i class="icon-reorder"></i>All Requests</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>

			<div class="widget-content" style="vertical-align: middle;">
				<div class="ct-control-status" style="overflow-x: overlay; border:none;" align="center">
					<!-- <div id="ajaxcasvdallincidents"></div> -->
					<table class="table table-striped table-bordered table-hover" id="requeststable">
						<thead>
							<th>ID</th>
							<th>Name</th>
							<th>User ID</th>
							<th>Area</th>
							<th>Email Address</th>
							<th>Contact Type</th>
							<th>Access Type</th>
							<th>Workshift</th>
							<th>Status</th>
						</thead>
						<tbody>
							<?php 
								// $alink = @Config::get('app.url');
								// $atag = '<a href="'.@Config::get('app.url').'/admin/dashboard/users/ena/{{$item->userid}}" class="bs-tooltip" title="Enable">';
								// dd($atag);
								foreach ($tmpstr as $item) {
									echo '<tr>';
									echo '<td>'.$item->{'ID'}.'</td>';
									echo '<td><a href="" onclick="sendData(this)">'.$item->{'Name'}.'</a></td>';
									echo '<td>'.$item->{'User ID'}.'</td>';
									echo '<td>'.$item->{'Area'}.'</td>';
									echo '<td>'.$item->{'Email Address'}.'</td>';
									echo '<td>'.$item->{'Contact Type'}.'</td>';
									echo '<td>'.$item->{'Access Type'}.'</td>';
									echo '<td>'.$item->{'Workshift'}.'</td>';
									echo '<td>'.$item->{'Status'}.'</td>';
									echo '</tr>';
								}
							?>
					</table>
					
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('#requeststable').DataTable({
			aaSorting: [[ 0, "desc" ]]
		});
    });
    
    function sendData(el) {
        var origin = window.opener;
        var elval = el.innerHTML;
        origin.postMessage(elval,origin);
        window.close();
    }
</script>

@endsection