@extends('/layout')
@section('content')
@include('casvd.menu')

<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>CA Service Desk | All Incidents</h3>
	</div>
</div>

<div style="height: 10px;"></div>
<form action="{{@Config::get('app.url')}}/admin/slwnpm/events" method="post">
@csrf
{{csrf_field()}}
<table border="0" cellpadding="5" cellspacing="5" width="100%">
	<tr>
		<td style="width: 10px;"></td>
		<td style="width: 100px;">INCIDENT FILTER: </td>
		<td>
            <div style="background-color:#dfe0e1; display: flex;">
                <div id="daterange" style="cursor: pointer; text-align: right;">
                    <span></span> &nbsp;
                    <i class="fa fa-calendar"></i>&nbsp;
                    <i class="fa fa-caret-down"></i>
                </div>
            </div>
			<!-- <table style="background-color:#dfe0e1; width:100%">
				<tr>
					<td style="padding: 10px;">Network Object</td>
				</tr>
				<tr>
					<div id="incidentrange" style="cursor: pointer; grid-column: 2/3; text-align: right;">
                        <span></span> &nbsp;
                        <i class="fa fa-calendar"></i>&nbsp;
                        <i class="fa fa-caret-down"></i>
                    </div>
				</tr>
			</table> -->
		</td>
	</tr>
	<tr>
		<td style="width:10px;"></td>
		<td style="width:150px;"></td>
		<td>
			<input type="Submit" value="OK" style="width: 100px; height: 25px; padding: 0px; color: white; border-color: gray; border-width: 1px; background-color: #297994;"/>
		</td>
	</tr>
</table>
</form>

<div style="height: 20px;"></div>

<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>All Incidents</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>

			<div class="widget-content" style="vertical-align: middle;">
				<div class="ct-control-status" style="overflow-x: hidden; border:none;" align="center">
					<table class="table table-striped table-bordered table-hover" id="incidentstable">
						<!-- <thead>
							<th>ID</th>
							<th>Request#</th>
							<th>Summary</th>
							<th>Priority</th>
							<th>Category</th>
							<th>CI</th>
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
								foreach ($result as $item) {
									echo '<tr>';
									echo '<td>'.$item['ID'].'</td>';
									echo '<td><a href="'.@Config::get('app.url').'/admin/casvd/allincidents/edit/'.$item['Request#'].'">'.$item['Request#'].'</a></td>';
									echo '<td>'.$item['Summary'].'</td>';
									echo '<td>'.$item['Priority'].'</td>';
									echo '<td>'.$item['Category'].'</td>';
									echo '<td>'.$item['CI'].'</td>';
									echo '<td>'.$item['Status'].'</td>';
									echo '<td>'.$item['Group'].'</td>';
									echo '<td>'.$item['Assigned To'].'</td>';
									echo '<td>'.$item['Main Assignee'].'</td>';
									echo '<td>'.$item['Open Date'].'</td>';
									echo '<td>'.$item['Last Modified Date'].'</td>';
									echo '<td>'.$item['SLA Violation'].'</td>';
									echo '</tr>';
								}
                            ?>
                        </tbody> -->
                        <thead>
                            <th>Test</th>
                        </thead>
					</table>
					<div id="incidentstable"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
    var ajaxcasvddashboardincidents = '<?php echo @Config::get('app.url') ?>';
    ajaxcasvddashboardincidents += ('/ajaxcasvddashboardincidents');
	$(document).ready(function(){
		$('#incidentstable').dataTable({
			"aaSorting": [[ 0, "desc" ]],
			"iDisplayLength": 10,
            "aLengthMenu": [5, 10, 15, 25, 50, "All"],
            "ajax" : ajaxcasvddashboardincidents
		});
    });
    
    $(function() {
        var start = moment().startOf('day');
        var end = moment().startOf('day');

        $('#daterange').daterangepicker(
            {
                startDate: start,
                endDate: end,
                alwaysShowCalendars: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, 
        
            function (start, end) {
                $('#daterange span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
                // ajaxGetTotal('incident',start.unix(),end.unix());
            }
        );

        $('#daterange span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
        // ajaxGetTotal('incident',start.unix(),end.unix());
    });
</script>

@endsection
