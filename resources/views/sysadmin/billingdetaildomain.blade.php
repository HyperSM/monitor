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

<!-- <form class="form-horizontal row-border" action="{{@Config::get('app.url')}}/sysadmin/billing/detail/{{$domain->domainid}}" name="dateform" method="POST"> -->
	<!-- @csrf -->
	<!-- <input id="start" name="start" style="display: none;"></input>
	<input id="end" name="end" style="display: none;"></input> -->
<!-- </form> -->


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
                        <tr>
                            <td>CA Service Desk</td>
                            <td><span style="display: inline;" id="casvdCount"></span><span class="casvdGIF"><img src="{{@Config::get('app.url')}}/images/casvd/loading.gif" style="width: 30px;"></span></td>
                            <td><span style="display: inline;" id="casvdUP"></span><span class="casvdGIF"><img src="{{@Config::get('app.url')}}/images/casvd/loading.gif" style="width: 30px;"></span></td>
                            <td><span style="display: inline;" id="casvdPrice"></span><span class="casvdGIF"><img src="{{@Config::get('app.url')}}/images/casvd/loading.gif" style="width: 30px;"></span></td>
                        </tr>
                        <tr>
                            <td>Centreon</td>
                            <td><span style="display: inline;" id="centreonCount"></span><span class="centreonGIF"><img src="{{@Config::get('app.url')}}/images/casvd/loading.gif" style="width: 30px;"></span></td>
                            <td><span style="display: inline;" id="centreonUP"></span><span class="centreonGIF"><img src="{{@Config::get('app.url')}}/images/casvd/loading.gif" style="width: 30px;"></span></td>
                            <td><span style="display: inline;" id="centreonPrice"></span><span class="centreonGIF"><img src="{{@Config::get('app.url')}}/images/casvd/loading.gif" style="width: 30px;"></span></td>
                        </tr>
                        <tr>
                            <td>Solarwinds NPM</td>
                            <td><span style="display: inline;" id="slwnpmCount"></span><span class="slwnpmGIF"><img src="{{@Config::get('app.url')}}/images/casvd/loading.gif" style="width: 30px;"></span></td>
                            <td><span style="display: inline;" id="slwnpmUP"></span><span class="slwnpmGIF"><img src="{{@Config::get('app.url')}}/images/casvd/loading.gif" style="width: 30px;"></span></td>
                            <td><span style="display: inline;" id="slwnpmPrice"></span><span class="slwnpmGIF"><img src="{{@Config::get('app.url')}}/images/casvd/loading.gif" style="width: 30px;"></span></td>
                        </tr>
                        <tr>
                            <td>Cisco SDWAN</td>
                            <td><span style="display: inline;" id="sdwanCount"></span><span class="sdwanGIF"><img src="{{@Config::get('app.url')}}/images/casvd/loading.gif" style="width: 30px;"></span></td>
                            <td><span style="display: inline;" id="sdwanUP"></span><span class="sdwanGIF"><img src="{{@Config::get('app.url')}}/images/casvd/loading.gif" style="width: 30px;"></span></td>
                            <td><span style="display: inline;" id="sdwanPrice"></span><span class="sdwanGIF"><img src="{{@Config::get('app.url')}}/images/casvd/loading.gif" style="width: 30px;"></span></td>
                        </tr>
                        <tr>
                            <td><b>Total</b></td>
                            <td><span style="display: inline;" id="total"></span><span class="sdwanGIF"><img src="{{@Config::get('app.url')}}/images/casvd/loading.gif" style="width: 30px;"></span></td>
                            <td></td>
                            <td></td>
                        </tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->

<!-- end Modal -->

<script>
    var rangestart = moment().startOf('day');
    var rangeend = moment().startOf('day');

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
                rangestart = start.unix();
                rangeend = end.unix();
                ajaxcasvdloading();
            }
        );

        $('#timerange span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
        ajaxcasvdloading();
    });

    $(document).ready(function () {
        var centreon = 0;
        //Centreon
        var ajaxbillingcentreon = "<?php echo @Config::get('app.url') ?>";
        ajaxbillingcentreon += ('/ajaxbillingcentreon/' + '{{$domain->domainid}}');
        $.ajax({url: ajaxbillingcentreon, success: function(result){
            $('.centreonGIF').hide();
            $("#centreonCount").html(result["count"]);
            $("#centreonUP").html(result["up"]);
            $("#centreonPrice").html(result["price"]);
            centreon = result["price"];
        }});

        //Solarwinds NPM
        var ajaxbillingslwnpm = "<?php echo @Config::get('app.url') ?>";
        ajaxbillingslwnpm += ('/ajaxbillingslwnpm/' + '{{$domain->domainid}}');
        $.ajax({url: ajaxbillingslwnpm, success: function(result){
            $('.slwnpmGIF').hide();
            $("#slwnpmCount").html(result["count"]);
            $("#slwnpmUP").html(result["up"]);
            $("#slwnpmPrice").html(result["price"]);
        }});

        //Cisco SDWAN
        var ajaxbillingciscosdwan = "<?php echo @Config::get('app.url') ?>";
        ajaxbillingciscosdwan += ('/ajaxbillingciscosdwan/' + '{{$domain->domainid}}');
        $.ajax({url: ajaxbillingciscosdwan, success: function(result){
            $('.sdwanGIF').hide();
            $("#sdwanCount").html(result["count"]);
            $("#sdwanUP").html(result["up"]);
            $("#sdwanPrice").html(result["price"]);
        }});

        $("#total").html(centreon);
    });

    function ajaxcasvdloading() {
        //CA Service Desk
        $('.casvdGIF').show();
        var ajaxbillingcasvd = "<?php echo @Config::get('app.url') ?>";
        ajaxbillingcasvd += ('/ajaxbillingcasvd/' + '{{$domain->domainid}}' + '/' + rangestart + '/' + rangeend);
        $.ajax({url: ajaxbillingcasvd, success: function(result){
            $('.casvdGIF').hide();
            $("#casvdCount").html(result["count"]);
            $("#casvdUP").html(result["up"]);
            $("#casvdPrice").html(result["price"]);
        }});
    }

</script>

@endsection
