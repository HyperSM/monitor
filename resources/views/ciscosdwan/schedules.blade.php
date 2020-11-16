@extends('/layout')

@section('content')

	@include('ciscosdwan.menu')	
<div style="height: 10px;"></div>
<h4>
	<b>
	SCHEDULES
	</b>
</h4>
<div style="height: 10px;"></div>

<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> All schedules</h4>&nbsp;&nbsp;
				@if ($user->ciscosdwanconfig==1)
				<span id="addnew">
					<a style="text-decoration: none;" href="#"><i class="icon-plus"></i> Add New </a>
				</span>
				@endif
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
							<th>Name</th>
							<th>Time Everyday</th>
							<th>Device ID</th>
							<th>Template ID</th>
							<th>Last run</th>
							<th style="text-align: center;">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=0; ?>
						@if (isset($schedules))
							@foreach ($schedules as $schedule)
								<tr>
									<td>
										@if(isset($schedule->name))
										{{$schedule->name}}
										@endif
									</td>
									<td>
										@if(isset($schedule->time))
										{{$schedule->time}}
										@endif
									</td>
									<td>
										@if(isset($schedule->deviceid))
										{{$schedule->deviceid}}
										@endif
									</td>
									<td>
										@if(isset($schedule->templateid))
										{{$schedule->templateid}}
										@endif
									</td>
									<td>
										@if(isset($schedule->lastrun))
										{{ date("d M, Y h:i A", $schedule->lastrun + 25200) }}
										@endif
									</td>
									<td class="align-center" style="width: 100px;">
										@if ($user->ciscosdwanconfig==1)
										<ul class="table-controls">
											<li><a href="javascript:void(0);" id="delete" name={{$schedule->id}} class="bs-tooltip" title="Delete" style="text-decoration: none;"><i class="icon-trash"></i></a> </li>
										</ul>
										@endif
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
<!-- Modal -->

<!-- end Modal -->

<div id="modal" data-izimodal-title="Add new schedule">
	<div class="iziModal-content" style="overflow: auto;">		
	    <div class="widget box" style="margin-bottom: 10px;">
			
			<div class="widget-content">
				<form class="form-horizontal row-border" action="#">
					<div class="form-group">
						<label class="col-md-2 control-label">Name:</label>
						<div class="col-md-4"><input type="text" class="form-control" id="txtname"></div>
					</div>				
					
					<div class="form-group">
						<label class="col-md-2 control-label">Run at</label>
						<div class="col-md-10">
							<div class="row next-row">
								<div class="col-md-4">
									<input type="text" id="runat" name="runat" class="form-control timepicker-fullscreen" value="12:00 AM" style="width:90px;">
								</div>
							</div> <!--.row -->			
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label">Device:</label>
						<div class="col-md-4">
							<select class="form-control" name="devices" id="devices">								
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label">Template:</label>
						<div class="col-md-4">
							<select class="form-control" name="templates" id="templates">
								
							</select>
						</div>
					</div>
				</form>			
			</div>
		</div>
		<div align="center" style="padding: 0px; margin: 0px;">
			<button data-izimodal-close="" class="btn btn-primary" id="Cancel" style="width:100px;">Cancel</button>
			<button class="btn btn-primary" id="OK" style="width:100px;">OK</button>
		</div>
	</div>
</div>

<script>
	$('#modal').iziModal({
        headerColor: '#4d7496', 
        width: '70%', 
        overlayColor: 'rgba(0, 0, 0, 0.5)', 
        fullscreen: true, 
        transitionIn: 'fadeInUp', 
        transitionOut: 'fadeOutDown', 
        bodyOverflow: true,
        padding: 10
    });

    $(document).on('closed', '#modal', function (e) {
        $("#wrapper").css({
              'zIndex' : 'unset'
        })
        //location.reload();
    });

    $(document).on('opening', '#modal', function (e) {
        $("#wrapper").css({
              'zIndex' : -1
        })
    });

    $(document).on('click', '#addnew', function(event) {
    	$('#modal').iziModal('open');
        $('#modal').iziModal('setZindex', 99999999);
        jQuery.ajax({ 
	         headers: {
	         },
	         url: "{{ url('/admin/ciscosdwan/schedules/getdeviceandtemplate') }}",
	         method: 'get',
	         success: function(result,response){
	            var json = JSON.parse(result);
	            //console.log(json['templates']);
	            var seldevice = document.getElementById('devices');
	            var seltemplate = document.getElementById('templates');
	            $("#devices").empty();
	            $("#templates").empty();

	            var i=0;
	            for(i=0;i<json['templates'].length;i++){
	            	var opt = document.createElement('option');
					// create text node to add to option element (opt)
					opt.appendChild( document.createTextNode(json['templates'][i]) );
					// set value property of opt
					opt.value = json['templates'][i]; 
					// add opt to end of select box (sel)
					seltemplate.appendChild(opt); 
	            }

	            for(i=0;i<json['devices'].length;i++){
	            	var opt = document.createElement('option');
					opt.appendChild( document.createTextNode(json['devices'][i]) );
					opt.value = json['devices'][i]; 
					seldevice.appendChild(opt); 
	            }
	         },
	         error: function (xhr, textStatus, errorThrown) {  
	            console.log('Error in Operation');  
	         }  
	    });
    });

    $(document).on('click', '#Cancel', function(event) {
    	//alert('OK');
    });

    $(document).on('click', '#delete', function(event) {
    	var answer = window.confirm("Delete this schedule?");
		if (answer) {
		    //some code
		    //alert(this.name);
		    jQuery.ajax({ 
	            headers: {
	            },
	            url: "{{ url('/admin/ciscosdwan/schedules/dodelete') }}",
	            method: 'post',
	            data: {
	                _token: '{{ csrf_token() }}',
	                id:this.name
	            },
	            success: function(result,response){
	            	//alert('Record has been deleted');     
	            },
	            error: function (xhr, textStatus, errorThrown) {  
	                
	            }  
	        });

	    	location.reload();
	    	location.reload();
	    	location.reload();
		}
    	
    });

    $(document).on('click', '#OK', function(event) {
    	var seldevice = document.getElementById('devices').value;
        var seltemplate = document.getElementById('templates').value;
    	jQuery.ajax({ 
             headers: {
             },
             url: "{{ url('/admin/ciscosdwan/schedules/doaddnew') }}",
             method: 'post',
             data: {
                _token: '{{ csrf_token() }}',
                name: txtname.value,
                time: runat.value,
                deviceid: seldevice,
                templateid: seltemplate
             },
             success: function(result,response){
             	//alert('Record has been inserted');     
             },
             error: function (xhr, textStatus, errorThrown) {  
                
             }  
        });
    	location.reload();
    	location.reload();
    	location.reload();
    });


	$('.inlinepicker').datepicker({
		inline: true,
		showOtherMonths:true
	});

	$('.timepicker-fullscreen').pickatime();
	
</script>

@endsection

