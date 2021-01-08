@extends('sysadmin/layout')
@section('content')
@include('sysadmin.menu')

<!-- Page header -->
<div style="height: 10px;"></div>
	<div class="page-header">
	<h4> <b> <a href="{{@Config::get('app.url')}}/sysadmin/billing/detail">BILLING DETAIL</a> | DOMAIN PRICE SUMMARY </b> </h4>
	<div class="page-title">
		<span>Company: {{$domain->company}} / Domain: {{$domain->domainname}}</span>
	</div>
</div>
<!-- /Page Header -->

<div style="height: 10px;"></div>

<div id="timerange" name="timerange" style="cursor: pointer; text-align: left;">
	<span style="display: inline;"></span> &nbsp;
	<i class="fa fa-calendar"></i>&nbsp;
	<i class="fa fa-caret-down"></i>
</div>

<div style="height: 30px;"></div>

<form class="form-horizontal row-border" action="{{@Config::get('app.url')}}/sysadmin/billing/detail/{{$domain->domainid}}" name="dateform" method="POST">
	@csrf
	<input id="start" name="start" style="display: none;"></input>
	<input id="end" name="end" style="display: none;"></input>
</form>


<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> Domain users</h4>&nbsp;&nbsp;
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<table class="table table-striped table-bordered table-hover table-checkable datatable" data-display-length="10">
					<thead>
						<tr>
							<th>Product</th>
							<th>Count</th>
							<th>Unit Price</th>
							<th>Total Price</th>
						</tr>
					</thead>
					<tbody>
			<?php 
				foreach ($pricelist as $key) {
					switch ($key->product) {
						case 'casvd':
							$casvdUP = $key->price;
							break;
						case 'centreon':
							$centreonUP = $key->price;
							break;
						case 'slwnpm':
							$slwnpmUP = $key->price;
							break;
						case 'sdwan':
							$sdwanUP = $key->price;
							break;
					}
				}
			?>
            @if(isset($pricelist))
                <tr>
					<td>CA Service Desk</td>
					<td>{{$casvd->count}}</td>
					<td>{{$casvdUP}}</td>
					<td>{{$casvd->price}}</td>
                </tr>
				<tr>
					<td>Centreon</td>
					<td></td>
					<td></td>
					<td></td>
                </tr>
				<tr>
					<td>Solarwinds NPM</td>
					<td>{{$casvd->count}}</td>
					<td></td>
					<td></td>
                </tr>
				
            @endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->

<!-- end Modal -->

<script>
	//Date Range Picker
	$(function() {
        var start = moment().startOf('day');
        var end = moment().startOf('day');

        $('#timerange').daterangepicker(
            {
                startDate: start,
                endDate: end,
                alwaysShowCalendars: true,
                ranges: {
                    'None': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            },

            function (start, end) {
                $('#timerange span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
				document.getElementById("start").value = start.unix();
				document.getElementById("end").value = end.unix();
				document.dateform.submit();
            }
        );

        $('#timerange span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
		document.getElementById("start").value = start.unix();
		document.getElementById("end").value = end.unix();
    });

</script>

@endsection
