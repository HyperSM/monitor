@extends('/layout')
@section('content')
@include('casvd.menu')

<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>CA Service Desk | All Requests</h3>
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
							<th>Request#</th>
							<th>Summary</th>
							<th>Priority</th>
							<th>Category</th>
							<th>Status</th>
							<th>Group</th>
							<th>Assigned To</th>
							<th>Main Assignee</th>
							<th>Open Date</th>
							<th>Last Modified Date</th>
							<th>SLA Violation</th>
						</thead>
						<tbody>
							<?php 
								// $alink = @Config::get('app.url');
								// $atag = '<a href="'.@Config::get('app.url').'/admin/dashboard/users/ena/{{$item->userid}}" class="bs-tooltip" title="Enable">';
								// dd($atag);
								foreach ($tmpstr as $item) {
									echo '<tr>';
									echo '<td>'.$item->{'ID'}.'</td>';
									echo '<td><a href="'.@Config::get('app.url').'/admin/casvd/allrequests/edit/'.$item->{'Request#'}.'">'.$item->{'Request#'}.'</a></td>';
									echo '<td>'.$item->{'Summary'}.'</td>';
									echo '<td>'.$item->{'Priority'}.'</td>';
									echo '<td>'.$item->{'Category'}.'</td>';
									echo '<td>'.$item->{'Status'}.'</td>';
									echo '<td>'.$item->{'Group'}.'</td>';
									echo '<td>'.$item->{'Assigned To'}.'</td>';
									echo '<td>'.$item->{'Main Assignee'}.'</td>';
									echo '<td>'.$item->{'Open Date'}.'</td>';
									echo '<td>'.$item->{'Last Modified Date'}.'</td>';
									echo '<td>'.$item->{'SLA Violation'}.'</td>';
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
</script>

@endsection
