@extends('/layout')

@section('content')

	@include('slwnpm.menu')

<div style="height: 10px;"></div>
<h4>
	<b>
	Events 
	</b>
</h4>
<div style="height: 10px;"></div>
<form action="{{@Config::get('app.url')}}/admin/slwnpm/events" method="post">
@csrf
{{csrf_field()}}
<table border="0" cellpadding="5" cellspacing="5" width="100%">
	<tr>
		<td style="width:10px;"></td>
		<td style="width:150px;">FILTER DEVICES: </td>
		<td>
			<table style="background-color:#dfe0e1; width:100%">
				<tr>
					<td style="padding: 10px;">Network Object</td>
				</tr>
				<tr>
					<td style="padding-left: 10px; padding-bottom: 10px;">
						<select style="width: 250px;" name="selectednode">
							<option selected value=''>All network objects</option>
							@if (isset($nodes))
							@foreach ($nodes as $node)
							<option value="{{$node['NodeID']}}" <?php if((isset($selectednode)) && $node['NodeID']==$selectednode){echo 'selected';} ?>>{{$node['DisplayName']}}</option>
							@endforeach
							@endif
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="width:10px;"></td>
		<td style="width:150px;">FILTER EVENTS: </td>
		<td>
			<table style="background-color:#dfe0e1; width:100%" border="0">
				<tr>
					<td style="padding: 10px; width: 100px;" colspan="7">Event Type</td>
				</tr>
				<tr>
					<td style="padding-left: 10px; padding-bottom: 10px;" colspan="7">
						<select style="width: 350px;" name="selectedeventtype">
							<option value='All events' selected>All events</option>
							@if (isset($eventtypes))
							@foreach ($eventtypes as $eventtype)
							<option value="{{$eventtype['EventType']}}" <?php if((isset($selectedeventtype)) && $eventtype['EventType']==$selectedeventtype){echo 'selected';} ?>>{{$eventtype['Name']}}</option>
							@endforeach
							@endif					
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="7"></td>
				</tr>
				<tr>
					<td style="padding-left: 10px;">Time Period:</td>
					<td></td>
				</tr>
				<tr>
					<td style="padding-left: 10px; width: 150px; padding-right: 10px;">
						<select style="width: 150px;" onchange="displaydate()" id="showdate" name="selectedtime">
							<option value="4" <?php if (isset($selectedtime) && $selectedtime==4){echo 'selected';}?>>Today</option>
							<option value="1" <?php if (isset($selectedtime) && $selectedtime==1){echo 'selected';}?>>Past Hour</option>
							<option value="2" <?php if (isset($selectedtime) && $selectedtime==2){echo 'selected';}?>>Last 2 Hours</option>
							<option value="3" <?php if (isset($selectedtime) && $selectedtime==3){echo 'selected';}?>>Last 24 Hours</option>
							<option value="5" <?php if (isset($selectedtime) && $selectedtime==5){echo 'selected';}?>>Yesterday</option>
							<option value="6" <?php if (isset($selectedtime) && $selectedtime==6){echo 'selected';}?>>Last 7 Days</option>
							<option value="7" <?php if (isset($selectedtime) && $selectedtime==7){echo 'selected';}?>>This Month</option>
							<option value="8" <?php if (isset($selectedtime) && $selectedtime==8){echo 'selected';}?>>Last Month</option>
							<option value="9" <?php if (isset($selectedtime) && $selectedtime==9){echo 'selected';}?>>Last 30 Days</option>
							<option value="10" <?php if (isset($selectedtime) && $selectedtime==10){echo 'selected';}?>>Last 3 Months</option>
							<option value="11" <?php if (isset($selectedtime) && $selectedtime==11){echo 'selected';}?>>This Year</option>
							<option value="12" <?php if (isset($selectedtime) && $selectedtime==12){echo 'selected';}?>>Last 12 Months</option>
							<option value="13" <?php if (isset($selectedtime) && $selectedtime==13){echo 'selected';}?>>Custom</option>
						</select>						
					</td>
					<td align="left" style="width: 10px; ">
						<span id="labelfrom">FROM:</span>
					</td>
					<td style="width: 10px; ">
						<input type="text" id="fromdate" name="fromdate" class="form-control datepicker-fullscreen" style="width:150px; height: 23px;" value="<?php if (isset($fromdate)){echo $fromdate;}else{echo date('d F, Y');} ?>">
					</td>
					<td style="width: 10px; ">
						<input type="text" id="fromtime" name="fromtime" class="form-control timepicker-fullscreen" style="width:90px; height: 23px;" value="<?php if (isset($fromtime)){echo $fromtime;}else{echo '12:00 AM';} ?>">
					</td style="width: 10px; ">
					<td align="left" style="width: 10px; ">
						<span id="labelto">TO:</span>
					</td>
					<td style="width: 10px;">
						<input type="text" id="todate" name="todate" class="form-control datepicker-fullscreen" style="width:150px; height: 23px;" value="<?php if (isset($todate)){echo $todate;}else{echo date('d F, Y');} ?>">
					</td>
					<td>
						<input type="text" id="totime" name="totime" class="form-control timepicker-fullscreen" style="width:90px; height: 23px;" value="<?php if (isset($totime)){echo $totime;}else{echo '11:59 PM';} ?>">
					</td>
				</tr>
				<tr style="height: 10px;"><td colspan="7"></td></tr>
				<tr>
					<td colspan="7" style="padding-left: 10px;">
						Number of displayed events:&nbsp;&nbsp;
						<input name="limit" type="number" value="<?php if(isset($limit)){echo $limit;}else{echo '250';} ?>" style="height: 23px; width: 100px; border: 1px solid #ccc; box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);"/>
					</td>
				</tr>
				<tr style="height: 20px;"><td colspan="7"></td></tr>
			</table>
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
				<h4><i class="icon-reorder"></i> Results</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<table class="table table-striped table-bordered table-hover table-checkable datatable" data-display-length="50">
					<thead>
						<tr>
							<th></th>
							<th style="width: 250px;">TIME OF EVENT</th>
							<th style="width: 70px;"></th>
							<th>TYPE</th>
							<th>MESSAGE</th>
						</tr>
					</thead>
					<tbody>
						@if (isset($events) && count($events)>0)
						@foreach ($events as $event)
						<tr>
							<td class="checkbox-column">
								<input type="checkbox" class="uniform">
							</td>
							<td>
								<?php
									$utc = $event['EventTime'];
						            $dt = new DateTime($utc);

						            $tz = new DateTimeZone('Asia/Ho_Chi_Minh');
						            $dt->setTimezone($tz);
							    	echo $dt->format('M d, Y H:i A');
								?>
							</td>
							<td align="center">
								@if (strpos($event['Name'], 'Up') !== false)
				                <img src="{{@Config::get('app.url')}}/images/slwnpm/small/up.gif" style="width:14px;">
					            @elseif (strpos($event['Name'], 'Down') !== false)
					            	<img src="{{@Config::get('app.url')}}/images/slwnpm/small/down.gif" style="width:14px;">
					            @elseif (strpos($event['Name'], 'Critical') !== false)
					            	<img src="{{@Config::get('app.url')}}/images/slwnpm/small/critical.gif" style="width:14px;">
					            @elseif (strpos($event['Name'], 'Alert') !== false)
					                <img src="{{@Config::get('app.url')}}/images/slwnpm/small/alert.gif" style="width:14px;">
					            @elseif (strpos($event['Name'], 'Warning') !== false)
					                <img src="{{@Config::get('app.url')}}/images/slwnpm/small/alert.gif" style="width:14px;">
					            @elseif (strpos($event['Name'], 'Fail') !== false)
					                <img src="{{@Config::get('app.url')}}/images/slwnpm/small/failed.gif" style="width:14px;">
					            @elseif (strpos($event['Name'], 'Remove') !== false)
					                <img src="{{@Config::get('app.url')}}/images/slwnpm/small/remove.gif" style="width:14px;">
					            @elseif (strpos($event['Name'], 'Remapped') !== false)
					                <img src="{{@Config::get('app.url')}}/images/slwnpm/small/remap.gif" style="width:14px;">
					            @elseif (strpos($event['Name'], 'Rebooted') !== false)
					                <img src="{{@Config::get('app.url')}}/images/slwnpm/small/reboot.gif" style="width:14px;">
					            @else
					                <img src="{{@Config::get('app.url')}}/images/slwnpm/small/Info.gif" style="width:14px;">
								@endif
							</td>
							<td style="width: 200px;">
								<?php 
								switch ($event['NetObjectType']) {
					                case 'N':
					                case 'HWHS':
					                case 'HWHT':
					                case 'HWH':
					                    $link = '<a href="'.url('/').'/admin/slwnpm/nodesummary/'. $event['NetObjectID'].'" style="text-decoration:none;">'.$event['Name'].'</a>';
					                    break;
					                case 'I':
					                    $link = '<a href="'.url('/').'/admin/slwnpm/interfacedetail/'. $event['NetObjectID'].'" style="text-decoration:none;">'.$event['Name'].'</a>';
					                    break;
					                default:
					                    $link = '<a href="#" style="text-decoration:none;">'.$event['Name'].'</a>'; 
					                    break;
					            }
					            echo $link;
								?>								
							</td>
							<td>
								<?php 
								switch ($event['NetObjectType']) {
					                case 'N':
					                case 'HWHS':
					                case 'HWHT':
					                case 'HWH':
					                    $link = '<a href="'.url('/').'/admin/slwnpm/nodesummary/'. $event['NetObjectID'].'" style="text-decoration:none;">'.$event['Message'].'</a>';
					                    break;
					                case 'I':
					                    $link = '<a href="'.url('/').'/admin/slwnpm/interfacedetail/'. $event['NetObjectID'].'" style="text-decoration:none;">'.$event['Message'].'</a>';
					                    break;
					                default:
					                    $link = '<a href="#" style="text-decoration:none;">'.$event['Message'].'</a>'; 
					                    break;
					            }
					            echo $link;
								?>
							</td>
						</tr>
						@endforeach
						@endif									
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script>
	function displaydate() {
		var e = document.getElementById("showdate");
		//var value = e.options[e.selectedIndex].value;
		var text = e.options[e.selectedIndex].text;
		
		if (text=='Custom'){
			$("#fromdate").show();
			$("#fromtime").show();
			$("#todate").show();
			$("#totime").show();
			$("#labelfrom").show();
			$("#labelto").show();
		}else{
			$("#fromdate").hide();
			$("#fromtime").hide();
			$("#todate").hide();
			$("#totime").hide();
			$("#labelfrom").hide();
			$("#labelto").hide();
		}
	}

	//===== Date Pickers & Time Pickers & Color Pickers =====//
	$( ".datepicker" ).datepicker({
		defaultDate: +7,
		showOtherMonths:true,
		autoSize: true,
		appendText: '<span class="help-block">(dd-mm-yyyy)</span>',
		dateFormat: 'dd-mm-yy'
		});

	$('.inlinepicker').datepicker({
		inline: true,
		showOtherMonths:true
	});

	$('.datepicker-fullscreen').pickadate();
	$('.timepicker-fullscreen').pickatime();

	$(document).ready(function(){
		var e = document.getElementById("showdate");
		//var value = e.options[e.selectedIndex].value;
		var text = e.options[e.selectedIndex].text;
		
		if (text=='Custom'){
			$("#fromdate").show();
			$("#fromtime").show();
			$("#todate").show();
			$("#totime").show();
			$("#labelfrom").show();
			$("#labelto").show();
		}else{
			$("#fromdate").hide();
			$("#fromtime").hide();
			$("#todate").hide();
			$("#totime").hide();
			$("#labelfrom").hide();
			$("#labelto").hide();
		}
	});
</script>

@endsection

