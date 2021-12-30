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
				<h4><i class="icon-reorder"></i>Top 10 open requests</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content" style="vertical-align: middle;">
				<div class="ct-control-status" style="overflow-x: overlay; border:none;" align="center">
					<!-- <div id="mytree"></div> -->
					<div id="ajaxcasvdallrequests"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
		// Set interval
		setInterval(function(){
			var ajaxcasvdallrequests = '<?php echo URL::route('ajaxcasvdallrequests') ?>';
            $('#ajaxcasvdallrequests').load(ajaxcasvdallrequests).fadeIn("slow");
		},{{$refreshrate}});
		// Refresh ajax
		$( document ).ready(function() {
			var ajaxcasvdallrequests = '<?php echo URL::route('ajaxcasvdallrequests') ?>';
			$('#ajaxcasvdallrequests').load(ajaxcasvdallrequests).fadeIn("slow");
			//Sort
			$('#ajaxcasvdallrequeststable').DataTable( {
					"order": [[ 0, "desc" ]]
				} );
		});
</script>

@endsection
