@extends('/layout')

@section('content')

	@include('ciscosdwan.menu')

<div style="height: 10px;"></div>
<h4>
	<b>ATTACH TEMPLATE</b>
</h4>
<div style="height: 10px;"></div>

<div class="row row-bg"> 
	<div class="col-md-3">
        <div class="statbox widget box box-shadow">
            <div class="widget-content">
                <div class="visual cyan">
                    <i class="fas fa-id-badge"></i>
                </div>
                <div class="title">Template ID</div>
                <div style="font-size: 10; font-weight: 100%; text-align: right; padding-top: 8px;" id="selectedtemplate"><b><?php if (isset($selectedtemplate)){echo $selectedtemplate->templateId;}?></b></div>
                <a class="more" href="javascript:void(0);">View More <i class="pull-right icon-angle-right"></i></a>
            </div>
        </div> <!-- /.smallstat -->
    </div> <!-- /.col-md-3 -->
	
    <div class="col-md-3">
        <div class="statbox widget box box-shadow">
            <div class="widget-content">
                <div class="visual cyan">
                    <i class="fas fa-file-signature"></i>
                </div>
                <div class="title">Template Name</div>
                <div style="font-size: 10; font-weight: 100%; text-align: right; padding-top: 8px;"><b><?php if (isset($selectedtemplate)){echo $selectedtemplate->templateName;}?></b></div>
                <a class="more" href="javascript:void(0);">View More <i class="pull-right icon-angle-right"></i></a>
            </div>
        </div> <!-- /.smallstat -->
    </div> <!-- /.col-md-3 -->

    <div class="col-md-3">
        <div class="statbox widget box box-shadow">
            <div class="widget-content">
                <div class="visual cyan">
                    <i class="fas fa-paperclip"></i>
                </div>
                <div class="title">Device Attached</div>
                <div style="font-size: 10; font-weight: 100%; text-align: right; padding-top: 8px;"><b><?php if (isset($selectedtemplate)){echo $selectedtemplate->devicesAttached;}?></b></div>
                <a class="more" href="javascript:void(0);">View More <i class="pull-right icon-angle-right"></i></a>
            </div>
        </div> <!-- /.smallstat -->
    </div> <!-- /.col-md-3 -->

	<div class="col-md-3">
        <div class="statbox widget box box-shadow">
            <div class="widget-content">
                <div class="visual cyan">
                    <i class="fas fa-binoculars"></i>
                </div>
                <div class="title">Device Type</div>
                <div style="font-size: 10; font-weight: 100%; text-align: right; padding-top: 8px;"><b><?php if (isset($selectedtemplate)){echo $selectedtemplate->deviceType;}?></b></div>
                <a class="more" href="javascript:void(0);">View More <i class="pull-right icon-angle-right"></i></a>
            </div>
        </div> <!-- /.smallstat -->
    </div> <!-- /.col-md-3 -->
</div> 
<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i>Select device to attach</h4>
            </div>
            <div class="widget-content clearfix">
                <!-- Left box -->
                <div class="left-box">
                    <input type="text" id="box1Filter" class="form-control box-filter" placeholder="Filter entries..."><button type="button" id="box1Clear" class="filter">x</button>
                    <select id="box1View" multiple="multiple" class="multiple">
                        @if (isset($AvailableDevices))
                            @foreach ($AvailableDevices as $AvailableDevice)
                            <option value="{{$AvailableDevice}}">{{$AvailableDevice}}</option>
                            @endforeach
                        @endif
                    </select>
                    <span id="box1Counter" class="count-label"></span>
                    <select id="box1Storage"></select>
                </div>
                <!--left-box -->

                <!-- Control buttons -->
                <div class="dual-control">
                    <button id="to2" type="button" class="btn">&nbsp;&gt;&nbsp;</button>
                    <button id="allTo2" type="button" class="btn">&nbsp;&gt;&gt;&nbsp;</button><br>
                    <button id="to1" type="button" class="btn">&nbsp;&lt;&nbsp;</button>
                    <button id="allTo1" type="button" class="btn">&nbsp;&lt;&lt;&nbsp;</button>
                </div>
                <!--control buttons -->

                <!-- Right box -->
                <div class="right-box">
                    <input type="text" id="box2Filter" class="form-control box-filter" placeholder="Filter entries..."><button type="button" id="box2Clear" class="filter">x</button>
                    <select id="box2View" multiple="multiple" class="multiple">
                    </select>
                    <span id="box2Counter" class="count-label"></span>
                    <select id="box2Storage"></select>
                </div>
                <!--right box -->
            </div>
        </div>
    </div>
</div>

<div class="row" align="center">
    <input type="submit" value="Attach Now" id="attachnow" class="btn btn-primary" style="width: 150px;">
</div>

<div id="modal" data-izimodal-group="group1" data-izimodal-loop="" data-izimodal-title="Cisco SDWAN">
    <span><img src="{{@Config::get('app.url')}}/images/ajax.jpg" style="width: 70px;" id="ajax"></span><span id="message"></span>
    <table class="table table-hover table-striped table-bordered table-highlight-head" id='showDetailDevices'>
        <thead>
            <tr>
                <th>Status</th>
                <th>Activity</th>
                <th>Device</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script>
     $(document).on('click', '#attachnow', function(event) {
        var x = document.getElementById("box2View");
        var txt = "All options: ";
        var i;
        var selectedDevices=[];
        for (i = 0; i < x.length; i++) {
            selectedDevices.push(x.options[i].value);
        }
        var template = selectedtemplate.innerText;

        if (selectedDevices.length==0){
            alert('Please select the device you want to attach this template');
        }else{
            ////
            $('#modal').iziModal('open');
            $('#modal').iziModal('setZindex', 99999999);
            message.innerText = 'Please be patient while templates are doing attach. Do not close this window.';
            jQuery.ajax({ 
                 headers: {
                 },
                 url: "{{ url('/admin/ciscosdwan/templates/attachcheck') }}",
                 method: 'post',
                 data: {
                    _token: '{{ csrf_token() }}',
                    templateId : "{{ $selectedtemplate->templateId }}",
                    Devices : selectedDevices
                 },
                 success: function(result,response){
                    //console.log("===== KET QUA TRA VE =======");
                    //console.log(result);
                    if (result==-1){
                        message.innerText = 'Please select the device you want to attach this template';
                    }else{
                        
                        var data = JSON.parse(result);
                        var i = 0;
                        var html = '';                        
                        for(i=0; i<data.length; i++){
                            var tmpstr = '';
                            tmpstr = (data[i]["deviceIp"]==''?selectedDevices[i]:data[i]["deviceIp"]);
                            html = html + '<tr>'+
                            '<td>'+data[i]["status"]+'</td>'+
                            '<td>'+data[i]["currentActivity"]+'</td>'+
                            '<td>'+ tmpstr + '</td>'+
                            '</tr>';
                        }
                        message.innerText ='';
                        document.getElementById("ajax").style.display = "none";
                        $("#showDetailDevices tbody tr").remove();
                        $("#showDetailDevices tbody").html(html);
                    }  
                    //console.log(result);
                 },
                 error: function (xhr, textStatus, errorThrown) {  
                    console.log('Error in Operation');  
                 }  
            });
            ////
        }

    });

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
        location.reload();
    });

    $(document).on('opening', '#modal', function (e) {
        $("#wrapper").css({
              'zIndex' : -1
        })
    });
</script>

@endsection

