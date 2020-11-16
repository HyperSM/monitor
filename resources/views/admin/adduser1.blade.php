@extends('/layout')

@section('content')

	@include('admin.menu')
  <div class="row">
    <div class="col-md-12">
      <div class="widget box">
        <div class="widget-header">
          <h4><i class="icon-reorder"></i> Add new user</h4>
        </div>
        <div class="widget-content">
          <form class="form-horizontal row-border" action="{{@Config::get('app.url')}}/admin/dashboard/users/adduser" method="POST" onsubmit="return validateMyForm();">
            @csrf
            <div class="form-group">
              <label class="col-md-5 control-label">Username:</label>
              <div class="col-md-2"><input type="text" name="username" id="username" class="form-control" required></div>
            </div>

            <div class="form-group">
              <label class="col-md-5 control-label">Full name:</label>
              <div class="col-md-3"><input type="text" name="fullname" class="form-control" ></div>
            </div>

            <div class="form-group">
              <label class="col-md-5 control-label">Password:</label>
              <div class="col-md-3"><input type="password" name="pass" class="form-control" ></div>
            </div>

            <div class="form-group align-center">
              <label class="col-md-5 control-label">Email:</label>
              <div class="col-md-3"><input type="text" name="readonly" class="form-control" ></div>
            </div>

            <div class="form-actions align-center">
              <button type="button" id="btn_cancel" class="back btn btn-primary">Cancel</button>
              <input type="submit" id="btn_submit" value="Submit" class="btn btn-primary">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script>

    var pass="";

    $('#btn_cancel').click(function() {
      document.location.href="{{@Config::get('app.url')}}/admin/dashboard/users";
    });

    function validateMyForm()
    {
      //
      // jQuery.ajax({
      //        headers: {
      //        },
      //        url: "{{@Config::get('app.url')}}/admin/dashboard/users/checkuserexist",
      //        method: 'post',
      //        data: {
      //           _token: '{{ csrf_token() }}',
      //           username: username.value
      //        },
      //        success: function(response){
      //            // if (response=="notexist") {
      //            //     tmp = response;
      //            // }else{
      //            //     alert("Username exist!!!");
      //            //     tmp = response;
      //            // }
      //            tmp = response;
      //
      //        },
      //        error: function (xhr, textStatus, errorThrown) {
      //
      //        }
      //   });

      pass = a();
      alert(pass);
      return false;
    }

    function a() {
      var tmp;
      jQuery.ajax({
             headers: {
             },
             url: "{{@Config::get('app.url')}}/admin/dashboard/users/checkuserexist",
             method: 'post',
             data: {
                _token: '{{ csrf_token() }}',
                username: username.value
             },
             success: function(response){
                 // if (response=="notexist") {
                 //     tmp = response;
                 // }else{
                 //     alert("Username exist!!!");
                 //     tmp = response;
                 // }
                 tmp = response;

             },
             error: function (xhr, textStatus, errorThrown) {

             }
        })
        return tmp;
    };

    $(document).ajaxComplete(function(event,xhr,settings){
      alert (tmp);
    });
  </script>
  @endsection
