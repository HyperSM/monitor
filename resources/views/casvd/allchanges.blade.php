@extends('/layout')
@section('content')
@include('casvd.menu')

<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>CA Service Desk | All Changes</h3>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>All Changes</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>

			<div class="widget-content" style="vertical-align: middle;">
				<div class="ct-control-status" style="overflow-x: hidden; border:none;" align="center">
					<table class="table table-striped table-bordered table-hover" id="changestable">
						<thead>
							<th>Change Order #</th>
							<th>Summary</th>
							<th>Priority</th>
							<th>Category</th>
							<th>Status</th>
							<th>Assigned To</th>
							<th>Change Type</th>
						</thead>
						<tbody>
							<?php 
								foreach ($result as $item) {
									echo '<tr>';
									echo '<td><a href="'.@Config::get('app.url').'/admin/casvd/allchanges/edit/'.$item['Change Order #'].'">'.$item['Change Order #'].'</a></td>';
									echo '<td>'.$item['Summary'].'</td>';
									echo '<td>'.$item['Priority'].'</td>';
									echo '<td>'.$item['Category'].'</td>';
									echo '<td>'.$item['Status'].'</td>';
									echo '<td>'.$item['Assigned To'].'</td>';
									echo '<td>'.$item['Change Type'].'</td>';
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
		$('#changestable').dataTable({
			"aaSorting": [[ 0, "desc" ]],
			"iDisplayLength": 10,
            "aLengthMenu": [5, 10, 15, 25, 50, "All"]
            // "columnDefs": [
            //     { "width": "9vw", "targets": 0 },
            //     { "width": "45vw", "targets": 1 },
            //     { "width": "5vw", "targets": 2 },
            //     { "width": "16vw", "targets": 3 },
            //     { "width": "8vw", "targets": 4 },
            //     { "width": "10vw", "targets": 5 },
            //     { "width": "7vw", "targets": 6 }
            // ]
		});
	});
</script>

@endsection
