<?php

namespace App\Http\Controllers;

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

        return view('admin.dashboard',compact('err_msg','domain','user'));
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

      return view('admin.users',compact('user','users','err_msg'));
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

      return view('admin.adduser',compact('user','err_msg'));
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
                'username' => Request('username'),
                'fullname' => Request('fullname'),
                'email'    => Request('email'),
                'password' => md5(Request('password')),
                'active'   => $active
            ]);
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
        DB::table('tbl_rights')
            ->insertGetId([
                'userid'       => $id,
                'slwnpmconfig' => $slwnpmconfig,
                'slwnpmuse'    => $slwnpmuse,
                'casvdconfig'    => $casvdconfig,
                'casvduse'       => $casvduse
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

      return view('admin.edituser',compact('user','selecteduser','selecteduserrights','err_msg'));
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
              'slwnpmconfig' => $slwnpmconfig,
              'slwnpmuse'    => $slwnpmuse,
              'casvdconfig'  => $casvdconfig,
              'casvduse'     => $casvduse
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

      return view('admin.deleteuser',compact('user','selecteduser','err_msg'));
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

    public function test() {
      $user = DB::table('tbl_accounts')
      ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
      ->where([
          ['tbl_accounts.username', '=', session('mymonitor_userid')]
      ])->first();
      return view('test',compact('user'));
    }
}
