<!-- Menu -->

<div class="crumbs">

  <!-- CASVD Dashboard button -->
  <ul id="breadcrumbs" class="breadcrumb">
		<li>
			<i class="icon-home"></i>
			<a href="{{@Config::get('app.url')}}/admin/casvd">CA Service Desk Dashboard</a>
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

    <!-- Incident menu -->
    <li class="dropdown"><a href="#" title="" data-toggle="dropdown"><i class="fas fa-user"></i><span>Incident </span><i class="icon-angle-down left-padding"></i></a>
      <ul class="dropdown-menu pull-right">
      <li><a href="{{@Config::get('app.url')}}/admin/casvd/addincident" title=""><i class="icon-plus"></i>Add new Incident</a></li>
      <li><a href="{{@Config::get('app.url')}}/admin/casvd/allincidents" title=""><i class="icon-reorder"></i>All Incidents</a></li>
      </ul>
    </li>

    <!-- Request menu -->
    <li class="dropdown"><a href="#" title="" data-toggle="dropdown"><i class="fas fa-user"></i><span>Request </span><i class="icon-angle-down left-padding"></i></a>
			<ul class="dropdown-menu pull-right">
			<li><a href="{{@Config::get('app.url')}}/admin/casvd/allrequests/create" title=""><i class="icon-plus"></i>Add new Request</a></li>
			<li><a href="{{@Config::get('app.url')}}/admin/casvd/allrequests" title=""><i class="icon-reorder"></i>All Requests</a></li>
			</ul>
		</li>

    <!-- Change menu -->
    <li class="dropdown"><a href="#" title="" data-toggle="dropdown"><i class="fas fa-user"></i><span>Change </span><i class="icon-angle-down left-padding"></i></a>
			<ul class="dropdown-menu pull-right">
			<li><a href="#" title=""><i class="icon-plus"></i>Add new Change</a></li>
			<li><a href="{{@Config::get('app.url')}}/admin/casvd/allchanges" title=""><i class="icon-reorder"></i>All Changes </a></li>
			</ul>
		</li>

    <!-- Settings menu -->
    <li class="dropdown"><a href="#" title="" data-toggle="dropdown"><i class="icon-cog"></i><span>Settings </span><i class="icon-angle-down left-padding"></i></a>
			<ul class="dropdown-menu pull-right">
			<li><a href="{{@Config::get('app.url')}}/admin/casvd/serverconfig" title=""></i>CA Service Desk Servers</a></li>
			</ul>
		</li>
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
           url: "{{ url('/admin/casvd/getrefreshrate') }}",
           method: 'get',
           success: function(result,response){
               document.getElementById('refreshrate').value = result;
               //refreshrate.value = result;
           },
           error: function (xhr, textStatus, errorThrown) {

           }
    });
  });

  // Set refresh rate to DB and reload page
  $(document).on('click', '#btn_submit', function(event) {
      var refreshrate = document.getElementById('refreshrate').value;
    jQuery.ajax({
           headers: {
           },
           url: "{{ url('/admin/casvd/setrefreshrate') }}",
           method: 'post',
           data: {
              _token: '{{ csrf_token() }}',
              refreshrate: refreshrate
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
