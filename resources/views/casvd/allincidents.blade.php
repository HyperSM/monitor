@extends('/layout')
@section('content')
@include('casvd.menu')

<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>CA Service Desk | All Incidents</h3>
	</div>
    <div class="page-title" style="padding-left: 100px">
        <form action="{{@Config::get('app.url')}}/admin/slwnpm/events" method="post">
            @csrf
            <table border="0" cellpadding="5" cellspacing="5" >
                <tr>
                    <td style="width: 10px;"></td>
                    <td style="">INCIDENT FILTER: </td>
                    <td>
                        <div class="widget-header" style="display: grid; grid-template-columns: 1fr">
                            <div id="incidentrange" style="cursor: pointer; grid-column: 2/3; ">
                                <span style="display: inline" class="selectdate"></span> &nbsp;
                                <span style="display: inline"><i class="fa fa-calendar"></i>&nbsp;</span>
                                <span style="display: inline"><i class="fa fa-caret-down"></i></span>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

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
				<div class="ct-control-status" >
					<table class="table table-striped table-bordered table-hover" id="incidents" style="width: 100%" >
						<thead>
                            <tr>
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
                            </tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
	    //default get tickets today
       var start = moment().startOf('day');
       var end = moment().endOf('day');
        // var info = $('#incidents').DataTable().page.info();
        // var currentpage = info.page +1;


        function loadpage(start,end) {
            if ($.fn.DataTable.isDataTable("#incidents")) {
                $('#incidents').DataTable().clear().destroy();
            }

            $('#incidents').on('xhr.dt', function (e, settings, json, xhr) {
            })
            .DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": '<?php echo URL::route("ajaxcasvdallincidents") ?>',
                    "type": "POST",
                    "data": function (d) {
                        //d.myKey = "myValue";
                        d._token = '{{ csrf_token() }}';
                        d.startindex = d.start;
                        d.pagesize = d.length;
                        d.startdate = start;
                        d.enddate = end;
                    }
                },
                "columns": [
                    {"data": "id"},
                    {"data": "ref_num"},
                    {"data": "summary"},
                    {"data": "priority"},
                    {"data": "category"},
                    {"data": "affected_resource"},
                    {"data": "status"},
                    {"data": "group_name"},
                    {"data": "assignee_name"},
                    {"data": "main_assignee"},
                    {"data": "open_date"},
                    {"data": "last_modified_date"},
                    {"data": "sla_violation"}
                ]
            });
        }

        function adddatepicker(){
            var start = moment().startOf('day');
            var end = moment().startOf('day');
            $('#incidentrange').daterangepicker(
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
                    $('#incidentrange span.selectdate').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
                    //console.log(start.unix() + ' ' + end.unix());
                    loadpage(start.unix(),end.unix());
                }
            );

            $('#incidentrange span.selectdate').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
        }

        adddatepicker();
        loadpage(start.unix(),end.unix());



    });

</script>

@endsection
