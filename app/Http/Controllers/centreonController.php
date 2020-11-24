<?php

namespace App\Http\Controllers;
use DB;
// use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;

use Illuminate\Support\Facades\Crypt;
use Session;


date_default_timezone_set('Asia/Ho_Chi_Minh');


class centreonController extends Controller
{
    /*
  Page: Dashboard
  */
  public function dashboard() {

    $dm = Crypt::decryptString(session('mymonitor_md'));
    $domain = DB::table('tbl_domains')
      ->where([
        ['domainid', '=', $dm]
      ])->first();

    $user = DB::table('tbl_accounts')
    ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
    ->where([
      ['tbl_accounts.username', '=', session('mymonitor_userid')]
    ])->first();
    // Check refresh rate
    $refreshrate = DB::table('tbl_refreshrate')
    ->leftJoin('tbl_accounts', 'tbl_refreshrate.userid', '=', 'tbl_accounts.userid')
    ->where([
      ['tbl_accounts.username', '=', session('mymonitor_userid')]
    ])->first();
    if (empty($refreshrate)) {
    $refreshrate = 5000;
    // Insert refreshrate to DB
    DB::table('tbl_refreshrate')
      ->insert(
        [
          'userid' => $user->userid,
          'product' => 'casvd',
          'refreshrate' => $refreshrate
        ]
      );
    } else {
    $refreshrate = $refreshrate->refreshrate;
    }
      return view('centreon.dashboard', compact('domain', 'user', 'refreshrate'));
  }

   /*
  Page: Hosts -> List Hosts
  Section: Main view
  */
  public function Hosts() {
    if (!Session::has('Monitor')) {
      $url = url('/');
      return redirect($url);
    }

    $dm = Crypt::decryptString(session('mymonitor_md'));
    $domain = DB::table('tbl_domains')
      ->where([
        ['domainid', '=', $dm]
      ])->first();
    $user = DB::table('tbl_accounts')
      ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
      ->where([
        ['tbl_accounts.username', '=', session('mymonitor_userid')]
      ])->first();

    $centreonserver = DB::table('tbl_centreonservers')        
    ->where([
        ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
    ])->first();
    // dd($centreonserver);
    $authen_key = "";
    $client = new \GuzzleHttp\Client(['cookies' => true]);
    try {
      $res = $client->request("POST", $centreonserver->hostname."/centreon/api/index.php?action=authenticate" , [
        'form_params' => [
            'username' => $centreonserver-> user , 
            'password' => $centreonserver -> password
            ],
              //"verify" => false
      ]);

      $authen_key = json_decode($res->getBody())->authToken;
    } catch (RequestException $e) {
      $authen_key ="";
    }

    if ($authen_key !=""){
      $res = $client->request("GET",$centreonserver->hostname."/centreon/api/index.php?object=centreon_realtime_hosts&action=list",[
        "headers" => [
          "Content-Type" => "application/json",
          "centreon-auth-token" => $authen_key
        ]
      ]);
      $hosts = json_decode($res->getBody());
      // dd($hosts);
    }
    return view('centreon.hosts', compact('domain', 'user', 'hosts'));
  }

     /*
  Page: Server config
  */
  public function serverconfig() {
    if (!Session::has('Monitor')) {
      $url = url('/');
      return redirect($url);
    }

    $dm = Crypt::decryptString(session('mymonitor_md'));

    $centreonserver = DB::table('tbl_centreonservers')
      ->where([
        ['domainid', '=', $dm]
      ])->first();

    $user = DB::table('tbl_accounts')
      ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
      ->where([
        ['tbl_accounts.username', '=', session('mymonitor_userid')]
      ])->first();

    return view('centreon.serverconfig', compact('centreonserver', 'user'));
  }

    /*
    Page: Submit Config server
    Section: Submit form
    */
    public function centreonserversubmit(){
      $server = DB::table('tbl_centreonservers')
      ->where('domainid','=', Crypt::decryptString(session('mymonitor_md')))
      ->first();
      if (empty($server)){
          //Create
          DB::table('tbl_centreonservers')
          ->insert(
          [
              'domainid' => Crypt::decryptString(session('mymonitor_md')),
              'displayname' => Request('displayname'),
              'hostname' => Request('hostname'),
              'secures' => Request('secures'),
              'port' => Request('port'),
              'basestring' => Request('basestring'),
              'user' => Request('user'),
              'password' => Request('password')
          ]
          );

      }else{
          //Update
          DB::table('tbl_centreonservers')
          ->where('domainid', '=', Crypt::decryptString(session('mymonitor_md')))
          ->update([
              'displayname' => Request('displayname'),
              'hostname' => Request('hostname'),
              'secures' => Request('secures'),
              'port' => Request('port'),
              'basestring' => Request('basestring'),
              'user' => Request('user'),
              'password' => Request('password')
              ]);
      }
      $url = url('/').'/admin/centreon';
      return redirect($url);
  }

  /*
  Add Host 
  */
  public function addHost() {
    if (!Session::has('Monitor')){
        $url = url('/');
        return redirect($url);
    }

  $dm=Crypt::decryptString(session('mymonitor_md'));
  $centreonserver = DB::table('tbl_centreonservers')        
  ->where([
      ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
  ])->first();
  $user = DB::table('tbl_accounts')
  ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
  ->where([
      ['tbl_accounts.username', '=', session('mymonitor_userid')]
  ])->first();

  $authen_key = "";
    $client = new \GuzzleHttp\Client(['cookies' => true]);
    try {
      $res = $client->request("POST", $centreonserver->hostname."/centreon/api/index.php?action=authenticate" , [
        'form_params' => [
            'username' => $centreonserver-> user , 
            'password' => $centreonserver -> password
            ],
              //"verify" => false
      ]);

      $authen_key = json_decode($res->getBody())->authToken;
    } catch (RequestException $e) {
      $authen_key ="";
    }
 

  $client = new \GuzzleHttp\Client(['cookies' => true]);

  $res = $client->request("POST",$centreonserver->hostname."/centreon/api/index.php",[
    "headers" => [
      "Content-Type" => "application/json",
      "centreon-auth-token" => $authen_key
    ], "form_params" => [
      "action" => "show",
      "object" => "INSTANCE"
    ]
  ]);
  $hosts = json_decode($res->getBody());
  dd($hosts);
  $err_msg ='';

  return view('centreon.addhost',compact('user','err_msg'));
}

public function addhostsubmit() {
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
    return view('centreon.addhost',compact('user','err_msg'));
  }
}
}