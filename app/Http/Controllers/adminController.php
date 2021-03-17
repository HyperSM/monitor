<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Crypt;
use Session;

class adminController extends Controller
{
    public function dashboard(){

        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }

    	$err_msg = '';
    	$dm=Crypt::decryptString(session('mymonitor_md'));

    	$domain = DB::table('tbl_domains')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        // get info centreonserver
        $centreonserver = DB::table('tbl_centreonservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();
        
        if (isset($centreonserver)) {
            $authen_key = "";
            $client = new \GuzzleHttp\Client(['cookies' => true]);
            try {
                $res = $client->request("POST",  $centreonserver->hostname."/centreon/api/index.php?action=authenticate", [
                    'form_params' => [
                        'username' => $centreonserver->user,
                        'password' => $centreonserver->password
                    ],
                    //"verify" => false
                ]);

                $authen_key = json_decode($res->getBody())->authToken;
            } catch (RequestException $e) {
                $authen_key = "";
            }
            //endregion

            //region get hosts centreon
            if ($authen_key != "") {
                $res = $client->request("GET", $centreonserver->hostname."/centreon/api/index.php?object=centreon_realtime_hosts&action=list", [
                    "headers" => [
                        "Content-Type" => "application/json",
                        "centreon-auth-token" => $authen_key
                    ],
                ]);
                $hosts = json_decode($res->getBody());
                for ($i = 1 ; $i < count($hosts);++$i){
                    if ($hosts[$i]->name == $hosts[$i - 1]->name) {
                        unset($hosts[$i]);
                    }
                }
                // dd($hosts);
            }
            //endregion
        } else {
            $hosts = NULL;
        }

        return view('admin.dashboard',compact('err_msg','domain','user','hosts'));
    }

    function ajaxgetservicebyhost(Request $request){
        //region auth
        if (!Session::has('Monitor')) {
            $url = url('/');
            return redirect($url);
        }

        $centreonserver = DB::table('tbl_centreonservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();
        $authen_key = "";
        $client = new \GuzzleHttp\Client(['cookies' => true]);
        try {
            $res = $client->request("POST", $centreonserver->hostname."/centreon/api/index.php?action=authenticate", [
                'form_params' => [
                    'username' => $centreonserver->user,
                    'password' => $centreonserver->password
                ]
            ]);

            $authen_key = json_decode($res->getBody())->authToken;
        } catch (RequestException $e) {

        }
        //endregion

        $html = "";
        //region load data
        $services = array();
        if ($authen_key != "") {
            if(!empty($request->name)) {
                $res = $client->request("GET", $centreonserver->hostname."/centreon/api/index.php?object=centreon_realtime_services&action=list&fields=host_name,host_state,output,description,host_last_check,scheduled_downtime_depth&searchHost=" . $request->name, [
                    "headers" => [
                        "Content-Type" => "application/json",
                        "centreon-auth-token" => $authen_key
                    ],
                ]);
                $services = json_decode($res->getBody());


                foreach ($services as $service){
                    //region get service downtime
                    $rs = $client->request("POST", $centreonserver->hostname."/centreon/api/index.php?action=action&object=centreon_clapi", [
                        "headers" => [
                            "Content-Type" => "application/json",
                            "centreon-auth-token" => $authen_key
                        ],
                        'json'=>[
                            "object"=>"RTDOWNTIME",
                            "action"=>"show",
                            "values"=>"SVC;".$service->host_name.",".$service->description
                        ]
                    ]);
                    $data = json_decode($rs->getBody());
                    if(isset($data)){
                        $service->service_downtimes = "0";
                    }
                    else{
                        $duration = $this->secondsToTime($data->duration);
                        $service->service_downtimes = $duration['days'].'D'.$duration['hours'].'H'.$duration['minutes'].'M'.$duration['seconds'].'S';
                    }
                    //endregion

                    //region get host downtime
                    $rs_host = $client->request("POST", $centreonserver->hostname."/centreon/api/index.php?action=action&object=centreon_clapi", [
                        "headers" => [
                            "Content-Type" => "application/json",
                            "centreon-auth-token" => $authen_key
                        ],
                        'json'=>[
                            "object"=>"RTDOWNTIME",
                            "action"=>"show",
                            "values"=>"HOST;".$service->host_name
                        ]
                    ]);
                    $data1 = json_decode($rs_host->getBody());
                    if(isset($data1)){
                        $service->host_downtimes = "0";
                    }
                    else{
                        $duration = $this->secondsToTime($data->duration);
                        $service->host_downtimes = $duration['days'].'D'.$duration['hours'].'H'.$duration['minutes'].'M'.$duration['seconds'].'S';
                    }
                    //endregion
                }
            }
        }

        //endregion
        return response()->json(['services' => $services]);

    }

    public function timequeryfunction(){
    	echo "Server time: " . date("H:i:s", time()+3600*7);// . date("h:i:sa");
    }

    public function users() {

        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }

      $err_msg='';
      $dm=Crypt::decryptString(session('mymonitor_md'));

      $user = DB::table('tbl_accounts')
      ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
      ->where([
          ['tbl_accounts.username', '=', session('mymonitor_userid')]
      ])->first();

      $users = DB::table('tbl_accounts')
      ->where([
        ['domainid','=',$dm]
      ])->get();

      if ($user->accountconfig == '1') {
        return view('admin.users',compact('user','users','err_msg'));
      } else {
        $url = url('/') . '/admin/dashboard';
        return redirect($url);
      }
    }

    public function adduser() {
        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }

      $dm=Crypt::decryptString(session('mymonitor_md'));

      $user = DB::table('tbl_accounts')
      ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
      ->where([
          ['tbl_accounts.username', '=', session('mymonitor_userid')]
      ])->first();

      $err_msg ='';

      if ($user->accountconfig == '1') {
        return view('admin.adduser',compact('user','err_msg'));
      } else {
        $url = url('/') . '/admin/dashboard';
        return redirect($url);
      }
    }

    public function addusersubmit() {
        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }

      $err_msg = '';
      $dm=Crypt::decryptString(session('mymonitor_md'));

      $user = DB::table('tbl_accounts')
      ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
      ->where([
          ['tbl_accounts.username', '=', session('mymonitor_userid')]
      ])->first();

      $domainname = DB::table('tbl_accounts')
      ->leftJoin('tbl_domains', 'tbl_accounts.domainid', '=', 'tbl_domains.domainid')
      ->where([
          ['tbl_domains.domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
      ])->first()->domainname;

      $selecteduser = DB::table('tbl_accounts')
      ->where([
          ['tbl_accounts.username', '=', Request('username')]
      ])->first();

      if (is_null($selecteduser)) {
        if (isset($_POST['active'])) {
          $active=1;
        } else {
          $active=0;
        }
        $id=DB::table('tbl_accounts')
            ->insertGetId([
                'domainid' => Crypt::decryptString(session('mymonitor_md')),
                'username' => Request('username').'@'.$domainname,
                'fullname' => Request('fullname'),
                'email'    => Request('email'),
                'password' => md5(Request('password')),
                'active'   => $active
            ]);
        if (isset($_POST['accountconfig'])) {
          $accountconfig=1;
        } else {
          $accountconfig=0;
        }
        if (isset($_POST['slwnpmconfig'])) {
          $slwnpmconfig=1;
        } else {
          $slwnpmconfig=0;
        }
        if (isset($_POST['slwnpmuse'])) {
          $slwnpmuse=1;
        } else {
          $slwnpmuse=0;
        }
        if (isset($_POST['casvduse'])) {
          $casvduse=1;
        } else {
          $casvduse=0;
        }
        if (isset($_POST['casvdconfig'])) {
          $casvdconfig=1;
        } else {
          $casvdconfig=0;
        }
        if (isset($_POST['centreonuse'])) {
          $centreonuse=1;
        } else {
          $centreonuse=0;
        }
        if (isset($_POST['centreonconfig'])) {
          $centreonconfig=1;
        } else {
          $centreonconfig=0;
        }
        if (isset($_POST['ciscosdwanuse'])) {
          $ciscosdwanuse=1;
        } else {
          $ciscosdwanuse=0;
        }
        if (isset($_POST['ciscosdwanconfig'])) {
          $ciscosdwanconfig=1;
        } else {
          $ciscosdwanconfig=0;
        }
        DB::table('tbl_rights')
            ->insertGetId([
                'userid'       => $id,
                'accountconfig' => $accountconfig,
                'slwnpmconfig' => $slwnpmconfig,
                'slwnpmuse'    => $slwnpmuse,
                'casvdconfig'    => $casvdconfig,
                'casvduse'       => $casvduse,
                'centreonconfig'    => $centreonconfig,
                'centreonuse'       => $centreonuse,
                'ciscosdwanconfig'    => $ciscosdwanconfig,
                'ciscosdwanuse'       => $ciscosdwanuse
            ]);
        return $this->users();
      }else{
        $err_msg ='User exist!!!';
        return view('admin.adduser',compact('user','err_msg'));
      }
    }

    public function checkuserexist(Request $request) {
        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }

      $err_msg = '';
      $dm=Crypt::decryptString(session('mymonitor_md'));

      $user = DB::table('tbl_accounts')
      ->where([
          ['tbl_accounts.username', '=', $request->username]
      ])->first();

      if (is_null($user)) {
        echo "notexist";
      }else{
        echo "exist";
      }
    }

    public function disableuser($userid) {
        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }

      $err_msg = '';
      $dm=Crypt::decryptString(session('mymonitor_md'));

      $user = DB::table('tbl_accounts')
      ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
      ->where([
          ['tbl_accounts.username', '=', session('mymonitor_userid')]
      ])->first();

      if ($user->accountconfig==1) {
        DB::table('tbl_accounts')
          ->where('userid','=',Request('userid'))
          ->update([
            'active' => 0,
          ]);
      }
      return $this->users();
    }

    public function enableuser($userid) {
        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }

      $err_msg = '';
      $dm=Crypt::decryptString(session('mymonitor_md'));

      $user = DB::table('tbl_accounts')
      ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
      ->where([
          ['tbl_accounts.username', '=', session('mymonitor_userid')]
      ])->first();

      if ($user->accountconfig==1) {
        DB::table('tbl_accounts')
          ->where('userid','=',Request('userid'))
          ->update([
            'active' => 1,
          ]);
      }
      return $this->users();
    }

    public function edituser($userid) {
      if (!Session::has('Monitor')){
          $url = url('/');
          return redirect($url);
      }

      $dm=Crypt::decryptString(session('mymonitor_md'));

      $user = DB::table('tbl_accounts')
      ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
      ->where([
          ['tbl_accounts.username', '=', session('mymonitor_userid')]
      ])->first();

      $err_msg ='';

      $selecteduser = DB::table('tbl_accounts')
      ->where([
        ['domainid','=',$dm],
        ['userid','=',Request('userid')]
      ])->first();

      $selecteduserrights = DB::table('tbl_rights')
      ->where([
        ['userid','=',Request('userid')]
      ])->first();

      if ($user->accountconfig == '1') {
        return view('admin.edituser',compact('user','selecteduser','selecteduserrights','err_msg'));
      } else {
        $url = url('/') . '/admin/dashboard';
        return redirect($url);
      }
    }

    public function editusersubmit($userid) {
      if (!Session::has('Monitor')){
          $url = url('/');
          return redirect($url);
      }

      $err_msg = '';
      $dm=Crypt::decryptString(session('mymonitor_md'));

      $user = DB::table('tbl_accounts')
      ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
      ->where([
          ['tbl_accounts.username', '=', session('mymonitor_userid')]
      ])->first();

      if (isset($_POST['active'])) {
        $active=1;
      } else {
        $active=0;
      }
      if (isset($_POST['accountconfig'])) {
        $accountconfig=1;
      } else {
        $accountconfig=0;
      }
      if (isset($_POST['slwnpmconfig'])) {
        $slwnpmconfig=1;
      } else {
        $slwnpmconfig=0;
      }
      if (isset($_POST['slwnpmuse'])) {
        $slwnpmuse=1;
      } else {
        $slwnpmuse=0;
      }
      if (isset($_POST['casvduse'])) {
        $casvduse=1;
      } else {
        $casvduse=0;
      }
      if (isset($_POST['casvdconfig'])) {
        $casvdconfig=1;
      } else {
        $casvdconfig=0;
      }
      if (isset($_POST['centreonuse'])) {
        $centreonuse=1;
      } else {
        $centreonuse=0;
      }
      if (isset($_POST['centreonconfig'])) {
        $centreonconfig=1;
      } else {
        $centreonconfig=0;
      }
      if (isset($_POST['ciscosdwanuse'])) {
        $ciscosdwanuse=1;
      } else {
        $ciscosdwanuse=0;
      }
      if (isset($_POST['ciscosdwanconfig'])) {
        $ciscosdwanconfig=1;
      } else {
        $ciscosdwanconfig=0;
      }

      DB::table('tbl_accounts')
          ->where('userid',Request('userid'))
          ->update([
              'username' => Request('username'),
              'fullname' => Request('fullname'),
              'email'    => Request('email'),
              'active'   => $active
          ]);

      DB::table('tbl_rights')
          ->where('userid',Request('userid'))
          ->update([
              'accountconfig' => $accountconfig,
              'slwnpmconfig' => $slwnpmconfig,
              'slwnpmuse'    => $slwnpmuse,
              'casvdconfig'  => $casvdconfig,
              'casvduse'     => $casvduse,
              'centreonuse'     => $centreonuse,
              'centreonconfig'     => $centreonconfig,
              'ciscosdwanuse'     => $ciscosdwanuse,
              'ciscosdwanconfig'     => $ciscosdwanconfig
          ]);

      return $this->users();
    }

    public function deleteuser($userid) {
      if (!Session::has('Monitor')){
          $url = url('/');
          return redirect($url);
      }

      $dm=Crypt::decryptString(session('mymonitor_md'));
      $err_msg = '';

      $user = DB::table('tbl_accounts')
      ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
      ->where([
          ['tbl_accounts.username', '=', session('mymonitor_userid')]
      ])->first();

      $selecteduser = DB::table('tbl_accounts')
      ->where([
        ['domainid','=',$dm],
        ['userid','=',Request('userid')]
      ])->first();

      if ($user->accountconfig == '1') {
        return view('admin.deleteuser',compact('user','selecteduser','err_msg'));
      } else {
        $url = url('/') . '/admin/dashboard';
        return redirect($url);
      }
    }

    public function deleteusersubmit($userid) {
      if (!Session::has('Monitor')){
          $url = url('/');
          return redirect($url);
      }

      $err_msg = '';
      $dm=Crypt::decryptString(session('mymonitor_md'));

      $user = DB::table('tbl_accounts')
      ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
      ->where([
          ['tbl_accounts.username', '=', session('mymonitor_userid')]
      ])->first();

      $selecteduser = DB::table('tbl_accounts')
      ->where([
        ['domainid','=',$dm],
        ['userid','=',Request('userid')]
      ])->first();

      if ($selecteduser->username==Request('username')) {
        DB::table('tbl_accounts')
            ->where('userid',Request('userid'))
            ->delete();

        DB::table('tbl_rights')
            ->where('userid',Request('userid'))
            ->delete();

        return $this->users();
      }else {
        $err_msg ='Wrong username! User was not deleted.';
        return view('admin.deleteuser',compact('user','selecteduser','err_msg'));
      }
    }
}
