@extends('/layout')

@section('content')

@include('slwnpm.menu')

<div class="page-header">
	<div class="page-title">
		<h3>Solarwinds NPM</h3>
		<span>Company: {{$domain->company}} / Domain: {{$domain->domainname}}</span>
	</div>

	<!-- Page Stats -->
	<ul class="page-stats">
		<li>
			<div class="summary">
				<span>Total Nodes</span>
				<h3><div id="ajaxtotalnodes">N/A</h3>
			</div>
			<!-- Use instead of sparkline e.g. this:
			<div class="graph circular-chart" data-percent="73">73%</div>
			-->
		</li>
		<li>
			<div class="summary">
				<span>Total Interfaces</span>
				<h3><div id="ajaxtotalint">N/A</h3>
			</div>
		</li>
	</ul>
	<!-- /Page Stats -->
</div>
<!-- /Page Header -->

<!--=== Page Content ===-->
<!--=== Statboxes ===-->
<div class="row row-bg"> <!-- .row-bg -->

	<div class="col-md-2">
		<div class="statbox widget box box-shadow">
			<div class="widget-content">
				<div class="visual cyan">
					<div><i class="fas fa-server"></i></div>
				</div>
				<div class="title">Nodes Up</div>
				<div class="value" id="ajaxnodeup">N/A</div>
			</div>
		</div> <!-- /.smallstat -->
	</div> <!-- /.col-md-3 -->

	<div class="col-md-2">
		<div class="statbox widget box box-shadow">
			<div class="widget-content">
				<div class="visual red">
					<div><i class="fas fa-server"></i></div>
				</div>
				<div class="title">Nodes Down</div>
				<div class="value" id="ajaxnodedown">N/A</div>
			</div>
		</div> <!-- /.smallstat -->
	</div> <!-- /.col-md-3 -->

	<div class="col-md-2 hidden-xs">
		<div class="statbox widget box box-shadow">
			<div class="widget-content">
				<div class="visual cyan">
					<i class="fas fa-ethernet"></i>
				</div>
				<div class="title">Interfaces Up</div>
				<div class="value" id="ajaxintup">N/A</div>
			</div>
		</div> <!-- /.smallstat -->
	</div> <!-- /.col-md-3 -->

	<div class="col-md-2">
		<div class="statbox widget box box-shadow">
			<div class="widget-content">
				<div class="visual red">
					<i class="fas fa-ethernet"></i>
				</div>
				<div class="title">Interfaces Down</div>
				<div class="value" id="ajaxintdown">N/A</div>
			</div>
		</div> <!-- /.smallstat -->
	</div> <!-- /.col-md-3 -->

	<!--=== Condensed Table ===-->
	<div class="col-md-4">
		<div class="widget box">
			<div class="widget-content">
				<form action="{{@Config::get('app.url')}}/admin/slwnpm/search" method="post">
				@csrf
				<table border="0" width="100%">
					<tr>
						<td style="width: 20px;"></td>
						<td style="width: 250px;">Seach for Node</td>
						<td style="width: 10px;"></td>
						<td style="width: 150px;">Search By</td>
						<td style="width: 10px;"></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td style="padding-top:5px;padding-bottom:5px;">
							<input name="searchtext" type="text" class="form-control" value="" style="height: 20px;" required/>
						</td>
						<td></td>
						<td>
							<select class="form-control" name="searchtype" style="height: 20px;">
								<option value="Node Name">Node Name</option>
								<option value="IP Address">IP Address</option>
								<option value="Machine Type">Machine Type</option>
								<option value="Vendor">Vendor</option>
								<option value="Description">Description</option>
							</select>
						</td>
						<td></td>
						<td align="left">
							<input type="Submit" value="OK" style="width: 50px; height: 20px; padding: 0px; border-color: gray; border-width: 1px;"/>
						</td>
					</tr>
					<tr>
						<td></td>
						<td colspan="5">Examples: Cisco*, 10.15.*.*, W?ndows, Site-*, *.SolarWinds.Net</td>
					</tr>
				</table>
				</form>
			</div>
		</div>
	</div>
	<!-- /Condensed Table -->
</div> <!-- /.row -->
<!-- /Statboxes -->

<!--=== Blue Chart ===-->
<div class="row">

	<div class="col-md-4">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> All Nodes</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<div class="ct-control-status" style="height:370px; overflow: overlay; border:none;">
            		<!-- <div id="mytree"></div> -->
            		<div id="nodestree"></div>
          		</div>
			</div>

		</div>
	</div>

	<div class="col-md-8">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> Map</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>

			<div class="widget-content" style="height: 390px;">
				<!--div id="gmap_markers" class="gmaps"></div-->
				<div id="world-map-markers" style="height: 325px; width: 100%"></div>
			</div>

			<div class="divider"></div>
		</div>
	</div> <!-- /.col-md-12 -->
</div> <!-- /.row -->
<!-- /Blue Chart -->

<div class="row">
	<div class="col-md-4">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>Top 5 Utilization Interfaces</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content" style="height: 300px;">
				<div id="ajaxnpmutilization"></div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>Hardware Health Overview</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content" style="height: 300px;">
				<div id="chart_pie" class="chart"></div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>Chat</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content" style="height: 300px;">
				<form id="ajaxform">
					@csrf
		            <div class="form-group" style="height: 230px; overflow:overlay; border:none;">
		                <div id="chat_content"></div>
		            </div>
		            <div class="form-group">
		            	<table border="0" width="100%">
		            		<tr>
		            			<td>
		            				<input type="text" name="message" id="message" class="form-control" placeholder="Enter Your Message" required>
		            			</td>
		            			<td style="width:10px;">
		            			<td>
		            				<input type="Submit" value="Send" class="save-data" style="width: 100px; height: 30px; padding: 0px; border-color: gray; border-width: 1px;"/>
		            			</td>
		            		</tr>
		            	</table>
		            </div>
		        </form>
		    </div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-4">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>Last 10 Unacknowledged Alerts</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content" style="height: 650px; overflow:overlay; border:none;">
				<div id="ajaxnpmunack">
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>Event Summary</h4>
				<span>TODAY</span>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content" style="height: 650px; overflow:overlay; border:none;">
				<div id="ajaxnpmeventsum"></div>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>Last 10 Events</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content" style="height: 650px; overflow:overlay; border:none;">
				<div id="ajaxnpmlast10event"></div>
			</div>
		</div>
	</div>
</div>

<div id="tmpup" style="display: none;"></div>
<div id="tmpwarning" style="display: none;"></div>
<div id="tmpcritical" style="display: none;"></div>
<div id="tmpunknown" style="display: none;"></div>
<div id="tmpnodejstree" style="display: none; !important;"></div>

<!-- /Page Content -->

<script>

	setInterval(function(){
	    var totalnodesquery = '<?php echo URL::route('totalnodesquery') ?>';
	    $('#ajaxtotalnodes').load(totalnodesquery).fadeIn("slow");
	},5000);

	setInterval(function(){
	    var totalintquery = '<?php echo URL::route('totalintquery') ?>';
	    $('#ajaxtotalint').load(totalintquery).fadeIn("slow");
	},5000);

	setInterval(function(){
	    var nodeupquery = '<?php echo URL::route('nodeupquery') ?>';
	    $('#ajaxnodeup').load(nodeupquery).fadeIn("slow");
	},5000);

	setInterval(function(){
	    var nodedownquery = '<?php echo URL::route('nodedownquery') ?>';
	    $('#ajaxnodedown').load(nodedownquery).fadeIn("slow");
	},5000);

	setInterval(function(){
	    var intupquery = '<?php echo URL::route('intupquery') ?>';
	    $('#ajaxintup').load(intupquery).fadeIn("slow");
	},5000);

	setInterval(function(){
	    var intdownquery = '<?php echo URL::route('intdownquery') ?>';
	    $('#ajaxintdown').load(intdownquery).fadeIn("slow");
	},5000);

    /*setInterval(function(){
	    var npmnodetree = '<?php echo URL::route('npmnodetree') ?>';
	    $('#mytree').load(npmnodetree).fadeIn("slow");
	},5000);*/
	setInterval(function(){
	    var url = '<?php echo URL::route('npmnodejstree') ?>';
	    $('#tmpnodejstree').load(url);
	    //$('#clgt').load(url).fadeIn("slow");
		$('#nodestree').jstree(true).settings.core.data = JSON.parse(tmpnodejstree.innerText);
		$('#nodestree').jstree(true).refresh(true);
	},5000);

	setInterval(function(){
	    var ajaxnpmutilization = '<?php echo URL::route('ajaxnpmutilization') ?>';
	    $('#ajaxnpmutilization').load(ajaxnpmutilization).fadeIn("slow");
	},5000);

	setInterval(function(){
	    var ajaxnpmlast10event = '<?php echo URL::route('ajaxnpmlast10event') ?>';
	    $('#ajaxnpmlast10event').load(ajaxnpmlast10event).fadeIn("slow");
	},5000);

	setInterval(function(){
	    var ajaxnpmeventsum = '<?php echo URL::route('ajaxnpmeventsum') ?>';
	    $('#ajaxnpmeventsum').load(ajaxnpmeventsum).fadeIn("slow");
	},5000);

	setInterval(function(){
	    var ajaxnpmunack = '<?php echo URL::route('ajaxnpmunack') ?>';
	    $('#ajaxnpmunack').load(ajaxnpmunack).fadeIn("slow");
	},5000);

	setInterval(function(){
	    var get = '<?php echo URL::route('slwnpm.chat') ?>';
		$('#chat_content').load(get);
	},5000);

	$(function(){
        $('#world-map-markers').vectorMap({
          map: 'world_mill',
          scaleColors: ['#C8EEFF', '#0071A4'],
          normalizeFunction: 'polynomial',
          hoverOpacity: 0.7,
          hoverColor: false,
          markerStyle: {
            initial: {
              fill: '#F8E23B',
              stroke: '#383f47'
            }
          },
          backgroundColor: '#383f47',
          markers: [
            {latLng: [41.90, 12.45], name: 'Vatican City'},
            {latLng: [43.73, 7.41], name: 'Monaco'},
          ]
        });

        //////////////////////////
        /*var data = [
			{ label: "Warning",  data: [100]},
			{ label: "Undefined",  data: [200]},
			{ label: "Critical",  data: [20]},
			{ label: "Up",  data: [56]}
		];

		// DEFAULT
	    $.plot($("#chart_pie"), data,
		{
			series: {
				pie: {
					show: true
				}
			}
		});*/

        //////////////////////////
    });

    setInterval(function(){
	    var ajaxhwhealthup = '<?php echo URL::route('ajaxhwhealthup') ?>';
	    $('#tmpup').load(ajaxhwhealthup);

	    var ajaxhwhealthunknown = '<?php echo URL::route('ajaxhwhealthunknown') ?>';
	    $('#tmpunknown').load(ajaxhwhealthunknown);

	    var ajaxhwhealthcritical = '<?php echo URL::route('ajaxhwhealthcritical') ?>';
	    $('#tmpcritical').load(ajaxhwhealthcritical);

	    var ajaxhwhealthwarning = '<?php echo URL::route('ajaxhwhealthwarning') ?>';
	    $('#tmphwarning').load(ajaxhwhealthwarning);
	    //alert(tmp.innerText);

	    //
	    var data = [
			{ label: "Warning",  data: [tmpwarning.innerText]},
			{ label: "Undefined",  data: [tmpunknown.innerText]},
			{ label: "Critical",  data: [tmpcritical.innerText]},
			{ label: "Up",  data: [tmpup.innerText]}
		];

		// DEFAULT
	    $.plot($("#chart_pie"), data,
		{
			series: {
				pie: {
	                show: true,
	                radius: 1,
	                label: {
	                    show: true,
	                    radius: 2 / 3,
	                    formatter: function (label, series) {
	                        return '<div style="font-size:9pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + series.data[0][1] + '</div>';

	                    },
	                    threshold: 0.1
	                }
	            }
			}
		});
	    //
	},5000);

	$( document ).ready(function() {

		var get = '<?php echo URL::route('slwnpm.chat') ?>';
		$('#chat_content').load(get);

	    var totalnodesquery = '<?php echo URL::route('totalnodesquery') ?>';
	    $('#ajaxtotalnodes').load(totalnodesquery).fadeIn("slow");

	    var totalintquery = '<?php echo URL::route('totalintquery') ?>';
	    $('#ajaxtotalint').load(totalintquery).fadeIn("slow");

	    var nodeupquery = '<?php echo URL::route('nodeupquery') ?>';
	    $('#ajaxnodeup').load(nodeupquery).fadeIn("slow");

	    var nodedownquery = '<?php echo URL::route('nodedownquery') ?>';
	    $('#ajaxnodedown').load(nodedownquery).fadeIn("slow");

	    var intupquery = '<?php echo URL::route('intupquery') ?>';
	    $('#ajaxintup').load(intupquery).fadeIn("slow");

	    var intdownquery = '<?php echo URL::route('intdownquery') ?>';
	    $('#ajaxintdown').load(intdownquery).fadeIn("slow");

	    $("#nodestree").jstree().bind("select_node.jstree", function (e, data) {
		     var href = data.node.a_attr.href;
		     document.location.href = href;
		});


	    $('#nodestree').jstree();
	    	//var imgup = "{{url('/')}}" + "/images/slwnpm/StatusIcons/small-up.gif";
	    	//var imdown = "{{url('/')}}" + "/images/slwnpm/StatusIcons/small-down.gif";
			//var tmpstr = '[{"id":1,"text":"UP","icon":"fa fa-circle text-success"},{"id":2,"text":"DOWN","icon":"fa fa-circle text-danger"}]';
			var tmpstr = '[{"id":1,"text":"UP","icon":"fa fa-circle text-success"},{"id":2,"text":"DOWN","icon":"fa fa-circle text-danger"}]';
	    //$('#nodestree').jstree(true).settings.core.data = JSON.parse('[{"id":0,"text":"Root node","children":[{"id":2,"text":"Child node 1"},{"id":3,"text":"Child node 2"}]}]');
		//$('#nodestree').jstree(true).refresh();
		$('#nodestree').jstree(true).settings.core.data = JSON.parse(tmpstr);
		$('#nodestree').jstree(true).refresh(true);

	    var ajaxnpmutilization = '<?php echo URL::route('ajaxnpmutilization') ?>';
	    $('#ajaxnpmutilization').load(ajaxnpmutilization).fadeIn("slow");

	    var ajaxnpmlast10event = '<?php echo URL::route('ajaxnpmlast10event') ?>';
	    $('#ajaxnpmlast10event').load(ajaxnpmlast10event).fadeIn("slow");

	    var ajaxnpmeventsum = '<?php echo URL::route('ajaxnpmeventsum') ?>';
	    $('#ajaxnpmeventsum').load(ajaxnpmeventsum).fadeIn("slow");

	    var ajaxnpmunack = '<?php echo URL::route('ajaxnpmunack') ?>';
	    $('#ajaxnpmunack').load(ajaxnpmunack).fadeIn("slow");


	});

	$(".save-data").click(function(event){
	    event.preventDefault();
	    let message = $("input[name=message]").val();
	    let _token   = $('meta[name="csrf-token"]').attr('content');
	    let userid = <?php echo $user->userid;?>;

	    if(message==''){

	    }else{
	    	//
	    	$.ajax({
		        url: '<?php echo URL::route('slwnpm.chat') ?>',
		        type:"POST",
		        data:{
		            message:message,
		            userid: userid,
		            //_token: _token,
		            _token: "{{ csrf_token() }}"
		        },
		        success:function(response){
		        	//document.getElementById('message').value = "";
		            if(response) {
		          	    //alert(response.success);
		                //$("#ajaxform")[0].reset();
		                //alert('OK');
		                var get = '<?php echo URL::route('slwnpm.chat') ?>';
		    			$('#chat_content').load(get);

		    			document.getElementById('message').value = "";

		            }
		        },
		    });
	    	//
	    }

	});


</script>
@endsection

