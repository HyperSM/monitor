@extends('/layout')
@section('content')
@include('casvd.menu')

<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>CA Service Desk | All Changes</h3>
	</div>
{{--    <div class="page-title" style="padding-left: 100px">--}}
{{--        <form action="{{@Config::get('app.url')}}/admin/slwnpm/events" method="post">--}}
{{--            @csrf--}}
{{--            <table border="0" cellpadding="5" cellspacing="5" >--}}
{{--                <tr>--}}
{{--                    <td style="width: 10px;"></td>--}}
{{--                    <td style="">INCIDENT FILTER: </td>--}}
{{--                    <td>--}}
{{--                        <div class="widget-header" style="display: grid; grid-template-columns: 1fr">--}}
{{--                            <div id="selectrange" style="cursor: pointer; grid-column: 2/3; ">--}}
{{--                                <span style="display: inline" class="selectdate"></span> &nbsp;--}}
{{--                                <span style="display: inline"><i class="fa fa-calendar"></i>&nbsp;</span>--}}
{{--                                <span style="display: inline"><i class="fa fa-caret-down"></i></span>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--            </table>--}}
{{--        </form>--}}
{{--    </div>--}}
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
					<table class="table table-striped table-bordered table-hover" data-display-length="5" id="allchanges" style="width: 100%;">
						<thead>
							<th>Change Order #</th>
							<th>Summary</th>
							<th>Priority</th>
							<th>Category</th>
							<th>Status</th>
							<th>Assigned To</th>
							<th>Change Type</th>
						</thead>
					</table>

				</div>
			</div>
		</div>
	</div>
</div>

<script>
    $(document).ready(function(){
        var start = moment().startOf('day');
        var end = moment().endOf('day');
        function init(){
            if ($.fn.DataTable.isDataTable("#allchanges")) {
                $('#allchanges').DataTable().clear().destroy();
            }

            $('#allchanges').DataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": 25,
                "ajax": {
                    "url": '<?php echo URL::route("ajaxcasvdallchanges") ?>',
                    "type": "POST",
                    "data": function (d) {
                        //d.myKey = "myValue";
                        d._token = '{{ csrf_token() }}';
                        d.startindex = d.start;
                        d.pagesize = d.length;
                    }
                },
                "columns":[
                    {"data":"ref_num"},
                    {"data":"summary"},
                    {"data":"priority"},
                    {"data":"category"},
                    {"data":"status"},
                    {"data":"assignee_name"},
                    {"data":"change_type"},
                ]
            });

        }

        init();


    });

</script>

@endsection
