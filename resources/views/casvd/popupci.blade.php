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
								if($tmpstr!='') {
									foreach ($tmpstr as $item) {
										echo '<tr>';
										echo '<td id="userid">'.$item->{'ID'}.'</td>';
										echo '<td><a href="'.@Config::get('app.url').'/admin/casvd/popup/person/requester/'.$item->{'ID'}.'" onclick="sendData(this)">'.$item->{'Name'}.'</a></td>';
										echo '<td>'.$item->{'User ID'}.'</td>';
										echo '<td>'.$item->{'Area'}.'</td>';
										echo '<td>'.$item->{'Email Address'}.'</td>';
										echo '<td>'.$item->{'Contact Type'}.'</td>';
										echo '<td>'.$item->{'Access Type'}.'</td>';
										echo '<td>'.$item->{'Workshift'}.'</td>';
										echo '<td>'.$item->{'Status'}.'</td>';
										echo '</tr>';
									};
								};
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
			"aaSorting": [[ 1, "asc" ]],
			"iDisplayLength": 10,
			"aLengthMenu": [5, 10, 15, 25, 50, "All"]
		});
    });
    
	function sendData(e) {
		var tmparr = window.location.href.split("/");
		var type = tmparr[tmparr.length-3];
		window.opener.document.getElementById("<?php echo $id; ?>").value = e.innerHTML;
		window.opener.document.getElementById("<?php echo $id; ?>").id = window.document.getElementById("userid").innerHTML;
		window.close();
	};
</script>

@endsection