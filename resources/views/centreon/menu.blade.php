<!-- Menu -->

<div class="crumbs">

  <!-- CASVD Dashboard button -->
  <ul id="breadcrumbs" class="breadcrumb">
		<li>
			<i class="icon-home"></i>
			<a href="{{@Config::get('app.url')}}/admin/centreon">Centreon Dashboard</a>
		</li>
	</ul>

	<ul class="crumb-buttons">

    <!-- Refresh rate menu -->
    <li class="manualdropdown" id="refreshmenu"><a href="#" title="" ><i class="icon-cog"></i><span>Refresh rate </span><i class="icon-angle-down left-padding"></i></a>
			<ul class="dropdown-menu pull-right">
        <li>
          <div id="dropdowncontent" style="width:200px; height:100px; padding: 5px;">
            <div style="background-color: #4d7496; padding-left:5px; color:white" align="center">Refresh rate time</div>
            <div style="height:10px;"></div>
            <table width=100% border="0" padding:5px>
              <tr>
                <td>Time (ms)</td>
                <td align="right"> <input id="refreshrate" type="text" style="width:100px; height:25px;" class="form-control"></td>
              </tr>
              <tr style="height:5px"></tr>
              <tr>
                <td colspan="2">
                  <button id="btn_submit" type="button" style="width:100%" value="Submit" class="btn btn-primary">Submit</button>
                </td>
              </tr>
            </table>
          </div>
        </li>
			</ul>
		</li>

    <!-- Monitoring menu -->
    <li class="dropdown">
        <a href="#" title="" data-toggle="dropdown"><i class="icon-cog"></i><span>Monitoring</span><i class="icon-angle-down left-padding"></i></a>
        <ul class="dropdown-menu pull-right">
            <li><a href="{{@Config::get('app.url')}}/admin/centreon/monitoring" title=""></i>Status</a></li>
            <li><a href="{{@Config::get('app.url')}}/admin/centreon/monitoring" title=""></i>Details</a></li>
        </ul>
    </li>
    <!-- Host menu -->
    <li class="dropdown"><a href="#" title="" data-toggle="dropdown"><i class="icon-cog"></i><span>Hosts</span><i class="icon-angle-down left-padding"></i></a>
      <ul class="dropdown-menu pull-right">
      <li><a href="{{@Config::get('app.url')}}/admin/centreon/hosts" title=""></i>Hosts</a></li>
			<li><a href="{{@Config::get('app.url')}}/admin/centreon/hostgroup" title=""></i>Host Groups</a></li>
			</ul>
		</li>

    <!-- Service menu -->
    <li class="dropdown"><a href="#" title="" data-toggle="dropdown"><i class="icon-cog"></i><span>Services</span><i class="icon-angle-down left-padding"></i></a>
      <ul class="dropdown-menu pull-right">
      <li><a href="{{@Config::get('app.url')}}/admin/centreon/service" title=""></i>Service by Host</a></li>
			<li><a href="{{@Config::get('app.url')}}/admin/centreon/srvgroup" title=""></i>Service Groups</a></li>
			</ul>
		</li>


    <!-- Report menu -->
    <li class="dropdown"><a href="#" title="" data-toggle="dropdown"><i class="icon-cog"></i><span>Report </span><i class="icon-angle-down left-padding"></i></a>
        <ul class="dropdown-menu pull-right">
            <li><a href="{{@Config::get('app.url')}}/admin/centreon/report" title="View Total Host" id="frame-report"></i>View Total Host</a></li>
            <li  ><a href="{{@Config::get('app.url')}}/admin/centreon/reportdetail" title="View Detail Host" id="frame-report"></i>View Detail Host</a></li>
        </ul>
    </li>

    <!-- Settings menu -->
    <li class="dropdown"><a href="#" title="" data-toggle="dropdown"><i class="icon-cog"></i><span>Settings </span><i class="icon-angle-down left-padding"></i></a>
			<ul class="dropdown-menu pull-right">
			<li><a href="{{@Config::get('app.url')}}/admin/centreon/serverconfig" title=""></i>Centreon Server</a></li>
			</ul>
		</li>

	</ul>



    </ul>

</div>

<script>

  // Manually open dropdown menu (Refresh rate)
  $('li.manualdropdown a').on('click', function (event) {
    $(this).parent().toggleClass('open');
  });

  // Prevent dropdown menu close when click inside
  $('body').on('click', function (e) {
    if (!$('li.manualdropdown').is(e.target)
        && $('li.manualdropdown').has(e.target).length === 0
        && $('.open').has(e.target).length === 0
    ){
        $('li.manualdropdown').removeClass('open');
    }
  });

  // Get refresh rate from DB
  $(document).on('click', '#refreshmenu', function(event) {
    jQuery.ajax({
           headers: {
           },
           url: "{{ url('/admin/centreon/getrefreshrate') }}",
           method: 'get',
           success: function(result,response){
            refreshrate.value = result;
           },
           error: function (xhr, textStatus, errorThrown) {

           }
    });
  });

  // Set refresh rate to DB and reload page
  $(document).on('click', '#btn_submit', function(event) {
    jQuery.ajax({
           headers: {
           },
           url: "{{ url('/admin/centreon/setrefreshrate') }}",
           method: 'post',
           data: {
              _token: '{{ csrf_token() }}',
              refreshrate: refreshrate.value
           },
           success: function(result,response){
            location.reload();
           },
           error: function (xhr, textStatus, errorThrown) {

           }
    });
  });

</script>
<!-- /End of menu -->
