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
				<h4><i class="icon-reorder"></i>All Results | <a href="{{@Config::get('app.url')}}/admin/casvd/popup/ci/{{$id}}">Back to filter</a></h4>
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
							<th>Class</th>
							<th>Family</th>
							<th>Serial Number</th>
							<th>Contact</th>
							<th>Location</th>
							<th>Last Change</th>
							<th>Asset Number</th>
							<th>Asset</th>
							<th>CI</th>
							<th>Active</th>
						</thead>
						<tbody>
							<?php 
								if($tmpstr!='') {
									foreach ($tmpstr as $item) {
										echo '<tr>';
										echo '<td id="ciid">'.$item->{'ID'}.'</td>';
										echo '<td><a href="'.@Config::get('app.url').'/admin/casvd/popup/ci/'.$item->{'ID'}.'" onclick="sendData(this)">'.$item->{'Name'}.'</a></td>';
										echo '<td>'.$item->{'Class'}.'</td>';
										echo '<td>'.$item->{'Family'}.'</td>';
										echo '<td>'.$item->{'Serial Number'}.'</td>';
										echo '<td>'.$item->{'Contact'}.'</td>';
										echo '<td>'.$item->{'Location'}.'</td>';
										echo '<td>'.$item->{'Last Change'}.'</td>';
										echo '<td>'.$item->{'Asset Number'}.'</td>';
										echo '<td>'.$item->{'Asset'}.'</td>';
										echo '<td>'.$item->{'CI'}.'</td>';
										echo '<td>'.$item->{'Active'}.'</td>';
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
		window.opener.document.getElementById("<?php echo $id; ?>").id = window.document.getElementById("ciid").innerHTML;
		window.close();
	};
</script>

@endsection