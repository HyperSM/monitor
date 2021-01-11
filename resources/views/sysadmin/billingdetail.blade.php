@extends('sysadmin/layout')
@section('content')
@include('sysadmin.menu')

<!-- Page header -->
<div style="height: 10px;"></div>
<h4>
	<b>
	BILLING DETAIL
	</b>
</h4>

<div style="height: 10px;"></div>

<div id="timerange" name="timerange" style="cursor: pointer; text-align: left;">
	<span style="display: inline;"></span> &nbsp;
	<i class="fa fa-calendar"></i>&nbsp;
	<i class="fa fa-caret-down"></i>
</div>

<div style="height: 30px;"></div>

<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> All domains</h4>&nbsp;&nbsp;
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
							<th style="width: 14%">Domain Name</th>
							<th style="width: 14%">Company</th>
							<th style="width: 14%">CA Service Desk ($)</th>
							<th style="width: 14%">Centreon ($)</th>
							<th style="width: 14%">Solarwinds NPM ($)</th>
							<th style="width: 14%">Cisco SDWAN ($)</th>
							<th style="width: 14%">Total price ($)</th>
						</tr>
					</thead>
					<tbody>
            @if(isset($domains))
              @foreach($domains as $item)
			  	<?php
					$casvd = "casvd".$item->domainid;
					$centreon = "centreon".$item->domainid;
					$slwnpm = "slwnpm".$item->domainid;
					$sdwan = "sdwan".$item->domainid;
					$total = "total".$item->domainid;
				?>
			  	
                <tr>
					<td><a href="{{@Config::get('app.url')}}/sysadmin/billing/detail/{{$item->domainid}}">{{$item->domainname}}</a></td>
					<td>{{$item->company}}</td>
					<td><span style="display: inline;" id="{{$casvd}}"></span><span class="casvdGIF"><img src="{{@Config::get('app.url')}}/images/casvd/loading.gif" style="width: 30px;"></span></td>
					<td><span style="display: inline;" id="{{$centreon}}"></span><span class="centreonGIF"><img src="{{@Config::get('app.url')}}/images/casvd/loading.gif" style="width: 30px;"></span></td>
					<td><span style="display: inline;" id="{{$slwnpm}}"></span><span class="slwnpmGIF"><img src="{{@Config::get('app.url')}}/images/casvd/loading.gif" style="width: 30px;"></span></td>
					<td><span style="display: inline;" id="{{$sdwan}}"></span><span class="sdwanGIF"><img src="{{@Config::get('app.url')}}/images/casvd/loading.gif" style="width: 30px;"></span></td>
					<td id="{{$total}}"></td>
                </tr>
              @endforeach
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
    var rangestart = moment().startOf('day');
    var rangeend = moment().startOf('day');
	var domains = @json($domains);

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
                ajaxloading();
            }
        );

        $('#timerange span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
        ajaxloading();
    });

    $(document).ready(function () {
		domains.forEach(domain => {
			document.getElementById('total'+domain.domainid).innerHTML = 0;
		});

		domains.forEach(domain => {
			var centreon = "centreon" + domain.domainid;
			var slwnpm = "slwnpm" + domain.domainid;
			var sdwan = "sdwan" + domain.domainid;

			//Centreon
			var ajaxbillingcentreon = "<?php echo @Config::get('app.url') ?>";
			ajaxbillingcentreon += ('/ajaxbillingcentreon/' + domain.domainid);
			$.ajax({url: ajaxbillingcentreon, success: function(result){
				$('.centreonGIF').hide();
				$("#"+centreon).html(result["price"]);
				if (result["price"]=="N/A") {
					centreonPrice = 0;
				} else {
					document.getElementById('total'+domain.domainid).innerHTML = parseInt(document.getElementById('total'+domain.domainid).innerHTML) + parseInt(result["price"]);
				}
			}});

			//Solarwinds NPM
			var ajaxbillingslwnpm = "<?php echo @Config::get('app.url') ?>";
			ajaxbillingslwnpm += ('/ajaxbillingslwnpm/' + domain.domainid);
			$.ajax({url: ajaxbillingslwnpm, success: function(result){
				$('.slwnpmGIF').hide();
				$("#"+slwnpm).html(result["price"]);
				if (result["price"]=="N/A") {
					slwnpmPrice = 0;
				} else {
					document.getElementById('total'+domain.domainid).innerHTML = parseInt(document.getElementById('total'+domain.domainid).innerHTML) + parseInt(result["price"]);
				}
			}});

			//Cisco SDWAN
			var ajaxbillingciscosdwan = "<?php echo @Config::get('app.url') ?>";
			ajaxbillingciscosdwan += ('/ajaxbillingciscosdwan/' + domain.domainid);
			$.ajax({url: ajaxbillingciscosdwan, success: function(result){
				$('.sdwanGIF').hide();
				$("#"+sdwan).html(result["price"]);
				if (result["price"]=="N/A") {
					sdwanPrice = 0;
				} else {
					document.getElementById('total'+domain.domainid).innerHTML = parseInt(document.getElementById('total'+domain.domainid).innerHTML) + parseInt(result["price"]);
				}
			}});
		});
    });

    function ajaxloading() {
		domains.forEach(domain => {
			var casvd = "casvd" + domain.domainid;

			//CA Service Desk
			$('.casvdGIF').show();
			var ajaxbillingcasvd = "<?php echo @Config::get('app.url') ?>";
			ajaxbillingcasvd += ('/ajaxbillingcasvd/' + domain.domainid + '/' + rangestart + '/' + rangeend);
			$.ajax({url: ajaxbillingcasvd, success: function(result){
				$('.casvdGIF').hide();
				$("#"+casvd).html(result["price"]);
				if (result["price"]=="N/A") {
					casvdPrice = 0;
				} else {
					document.getElementById('total'+domain.domainid).innerHTML = parseInt(document.getElementById('total'+domain.domainid).innerHTML) + parseInt(result["price"]);
				}
			}});
		});
    }
</script>

@endsection
