<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use Session;
use SoapClient;

class sysadminController extends Controller
{
    public function index(){

    	//Kiểm tra xem đã login chưa, nếu đã login thì session('FPTMonitor') sẽ là md5('admin')
        //Nếu chưa thì redirect sang trang đăng nhập
        switch (session('Monitor')) {
            case md5('admin'):
                return redirect("/sysadmin/dashboard");
                break;
            default:
                $err_msg = '';
                return view('adminlogin',compact('err_msg'));
                break;
        }        
    }

    public function doLogin(){        
        $username = Request('username');
        $username = str_replace('\'','',$username);
        $username = str_replace('=','',$username);
        $username = str_replace(' or ','',$username);

        $user = DB::table('tbl_admins')
        //->leftJoin('tbl_domains', 'tbl_accounts.domainid', '=', 'tbl_domains.domainid')
        ->where([
            //['tbl_accounts.active', '=', 1],
            ['tbl_admins.username', '=', $username]
        ])->first();

        //Nhập nhưng không có username trong database
        if (empty($user)){
        $err_msg = 'Error: Username does not exist';
            return view('adminlogin',compact('err_msg'));
        }else{
            //Có user trong db
                //Chưa nhập user/password
                if(Request('username')==''||Request('username')==''){
                    $err_msg = 'Error: Enter username or password';
                    return view('adminlogin',compact('err_msg'));
                    die();
                }

                //
                //Tạm thời bỏ qua xác thực ldap
                $password = md5(Request('password'));
                if ($user->password == $password){
                    session(['Monitor' => md5('sysadmin')]);
                    session(['mymonitor_userid' => $username]);
                    //session(['mymonitor_md' => Crypt::encryptString($user->domainid)]);                    
                    return redirect("/sysadmin/dashboard");                            

                }else{
                    $err_msg = 'Error: Wrong password';
                    return view('adminlogin',compact('err_msg'));
                }
        }
    }

    public function doLogout(){
        Session()->flush();
        return redirect('/sysadmin');
    }

    public function dashboard(){
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', session('mymonitor_userid')]
        ])->first();

        return view('sysadmin.dashboard', compact('user'));
    }

    public function users() {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', session('mymonitor_userid')]
        ])->first();

        $users = DB::table('tbl_admins')->get();

        return view('sysadmin.users',compact('user','users'));
    }

    public function adduser() {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';

        $user = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', session('mymonitor_userid')]
        ])->first();

        return view('sysadmin.adduser',compact('user','err_msg'));
    }

    public function addusersubmit() {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';

        $user = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', session('mymonitor_userid')]
        ])->first();

        $selecteduser = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', Request('username')]
        ])->first();

        if (is_null($selecteduser)) {
            $id=DB::table('tbl_admins')
                ->insertGetId([
                    'username' => Request('username'),
                    'fullname' => Request('fullname'),
                    'password' => md5(Request('password')),
                ]);
        
            $url = url('/sysadmin/users');
            return redirect($url);
        }else{
            $err_msg ='User exist!!!';
            return view('sysadmin.adduser',compact('user','err_msg'));
        }
    }

    public function edituser($userid) {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', session('mymonitor_userid')]
        ])->first();

        $err_msg ='';

        $selecteduser = DB::table('tbl_admins')
        ->where([
            ['userid','=',Request('userid')]
        ])->first();
        
        return view('sysadmin.edituser',compact('user','selecteduser','err_msg'));
    }

    public function editusersubmit($userid) {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';

        $user = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', session('mymonitor_userid')]
        ])->first();

        DB::table('tbl_admins')
            ->where('userid',Request('userid'))
            ->update([
                'username' => Request('username'),
                'fullname' => Request('fullname')
            ]);

        $url = url('/sysadmin/users');
        return redirect($url);
    }

    public function deleteuser($userid) {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';

        $user = DB::table('tbl_admins')
            ->where([
                ['tbl_admins.username', '=', session('mymonitor_userid')]
            ])->first();

        $selecteduser = DB::table('tbl_admins')
        ->where([
            ['userid','=',Request('userid')]
        ])->first();

        return view('sysadmin.deleteuser',compact('user','selecteduser','err_msg'));
    }

    public function deleteusersubmit($userid) {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';

        $user = DB::table('tbl_admins')
            ->where([
                ['tbl_admins.username', '=', session('mymonitor_userid')]
            ])->first();

        $selecteduser = DB::table('tbl_admins')
        ->where([
            ['userid','=',Request('userid')]
        ])->first();

        if ($selecteduser->username==Request('username')) {
            DB::table('tbl_admins')
                ->where('userid',Request('userid'))
                ->delete();

        $url = url('/sysadmin/users');
        return redirect($url);
        }else {
            $err_msg ='Wrong username! User was not deleted.';
            return view('sysadmin.deleteuser',compact('user','selecteduser','err_msg'));
        }
    }

    public function domains() {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', session('mymonitor_userid')]
        ])->first();

        $domains = DB::table('tbl_domains')->get();
        
        return view('sysadmin.domains',compact('user','domains'));
    }

    public function adddomain() {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';

        $user = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', session('mymonitor_userid')]
        ])->first();

        return view('sysadmin.adddomain',compact('user','err_msg'));
    }

    public function adddomainsubmit() {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';

        $user = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', session('mymonitor_userid')]
        ])->first();

        $selecteddomain = DB::table('tbl_domains')
        ->where([
            ['tbl_domains.domainname', '=', Request('domainname')]
        ])->first();

        if (is_null($selecteddomain)) {
            if (isset($_POST['active'])) {
                $active=1;
            } else {
                $active=0;
            }

            $id=DB::table('tbl_domains')
                ->insertGetId([
                    'domainname' => Request('domainname'),
                    'company' => Request('company'),
                    'address' => Request('address'),
                    'tel' => Request('tel'),
                    'domainactive' => $active
                ]);
        
            $url = url('/sysadmin/domains');
            return redirect($url);
        }else{
            $err_msg ='User exist!!!';
            return view('sysadmin.adddomain',compact('user','err_msg'));
        }
    }

    public function editdomain($domainid) {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', session('mymonitor_userid')]
        ])->first();

        $err_msg ='';

        $selecteddomain = DB::table('tbl_domains')
        ->where([
            ['domainid','=',Request('domainid')]
        ])->first();
        
        return view('sysadmin.editdomain',compact('user','selecteddomain','err_msg'));
    }

    public function editdomainsubmit($domainid) {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';

        $user = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', session('mymonitor_userid')]
        ])->first();

        if (isset($_POST['active'])) {
            $active=1;
        } else {
            $active=0;
        }

        DB::table('tbl_domains')
            ->where('domainid',Request('domainid'))
            ->update([
                'domainname' => Request('domainname'),
                'company' => Request('company'),
                'address' => Request('address'),
                'tel' => Request('tel'),
                'domainactive' => $active
            ]);

        $url = url('/sysadmin/domains');
        return redirect($url);
    }

    public function deletedomain($domainid) {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';

        $user = DB::table('tbl_admins')
            ->where([
                ['tbl_admins.username', '=', session('mymonitor_userid')]
            ])->first();

        $selecteddomain = DB::table('tbl_domains')
        ->where([
            ['domainid','=',Request('domainid')]
        ])->first();

        return view('sysadmin.deletedomain',compact('user','selecteddomain','err_msg'));
    }

    public function deletedomainsubmit($domainid) {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';

        $user = DB::table('tbl_admins')
            ->where([
                ['tbl_admins.username', '=', session('mymonitor_userid')]
            ])->first();

        $selecteddomain = DB::table('tbl_domains')
        ->where([
            ['domainid','=',Request('domainid')]
        ])->first();

        if ($selecteddomain->domainname==Request('domainname')) {
            DB::table('tbl_domains')
                ->where('domainid',Request('domainid'))
                ->delete();

        $url = url('/sysadmin/domains');
        return redirect($url);
        }else {
            $err_msg ='Wrong domain name! Domain was not deleted.';
            return view('sysadmin.deletedomain',compact('user','selecteddomain','err_msg'));
        }
    }

    public function disabledomain($domainid) {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';

        DB::table('tbl_domains')
        ->where('domainid','=',Request('domainid'))
        ->update([
            'domainactive' => 0,
        ]);

        $url = url('/sysadmin/domains');
        return redirect($url);
    }

    public function enabledomain($domainid) {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';

        DB::table('tbl_domains')
        ->where('domainid','=',Request('domainid'))
        ->update([
            'domainactive' => 1,
        ]);

        $url = url('/sysadmin/domains');
        return redirect($url);
    }

    public function domainusers($domainid) {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', session('mymonitor_userid')]
        ])->first();

        $domain = DB::table('tbl_domains')
        ->where([
            ['domainid','=',$domainid]
        ])->first();

        $users = DB::table('tbl_accounts')
        ->where([
            ['domainid','=',$domainid]
        ])->get();

        return view('sysadmin.domainusers',compact('user','users','domain'));
    }

    public function adddomainuser($domainid) {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';

        $user = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', session('mymonitor_userid')]
        ])->first();

        return view('sysadmin.adddomainuser',compact('user','err_msg','domainid'));
    }

    public function adddomainusersubmit($domainid) {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';

        $user = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', session('mymonitor_userid')]
        ])->first();

        $domain = DB::table('tbl_domains')
        ->where([
            ['domainid','=',$domainid]
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
                    'domainid' => $domainid,
                    'username' => Request('username'),
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
            $url = url('/sysadmin/domains/'.$domainid.'/users');
            return redirect($url);
        }else{
            $err_msg ='User exist!!!';
            return view('sysadmin.adddomainuser',compact('user','err_msg','domainid'));
        }
    }

    public function editdomainuser($domainid, $userid) {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', session('mymonitor_userid')]
        ])->first();

        $err_msg ='';

        $selecteduser = DB::table('tbl_accounts')
        ->where([
            ['domainid','=',$domainid],
            ['userid','=',Request('userid')]
        ])->first();

        $selecteduserrights = DB::table('tbl_rights')
        ->where([
            ['userid','=',Request('userid')]
        ])->first();
        
        return view('sysadmin.editdomainuser',compact('user','selecteduser','selecteduserrights','err_msg','domainid'));
    }

    public function editdomainusersubmit($domainid, $userid) {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';

        $user = DB::table('tbl_admins')
            ->where([
                ['tbl_admins.username', '=', session('mymonitor_userid')]
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

        $url = url('/sysadmin/domains/'.$domainid.'/users');
        return redirect($url);
    }

    public function deletedomainuser($domainid, $userid) {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', session('mymonitor_userid')]
        ])->first();
  
        $err_msg = '';
  
        $selecteduser = DB::table('tbl_accounts')
        ->where([
          ['domainid','=',$domainid],
          ['userid','=',Request('userid')]
        ])->first();
  
        return view('sysadmin.deletedomainuser',compact('user','selecteduser','err_msg','domainid'));
    }
  
    public function deletedomainusersubmit($domainid, $userid) {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', session('mymonitor_userid')]
        ])->first();

        $err_msg = '';

        $selecteduser = DB::table('tbl_accounts')
        ->where([
            ['domainid','=',$domainid],
            ['userid','=',Request('userid')]
        ])->first();

        if ($selecteduser->username==Request('username')) {
            DB::table('tbl_accounts')
                ->where('userid',Request('userid'))
                ->delete();

            DB::table('tbl_rights')
                ->where('userid',Request('userid'))
                ->delete();

            $url = url('/sysadmin/domains/'.$domainid.'/users');
            return redirect($url);
        }else {
            $err_msg ='Wrong username! User was not deleted.';
            return view('sysadmin.deletedomainuser',compact('user','selecteduser','err_msg','domainid'));
        }
    }

    public function disabledomainuser($domainid, $userid) {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';

        DB::table('tbl_accounts')
        ->where('userid','=',Request('userid'))
        ->update([
            'active' => 0,
        ]);

        $url = url('/sysadmin/domains/'.$domainid.'/users');
        return redirect($url);
    }

    public function enabledomainuser($domainid, $userid) {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';

        DB::table('tbl_accounts')
        ->where('userid','=',Request('userid'))
        ->update([
            'active' => 1,
        ]);

        $url = url('/sysadmin/domains/'.$domainid.'/users');
        return redirect($url);
    }

    public function billingprices() {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', session('mymonitor_userid')]
        ])->first();

        $billingprices = DB::table('tbl_billingprices')->get();

        return view('sysadmin.billingprices',compact('user','billingprices'));
    }

    public function addbillingprice() {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';

        $user = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', session('mymonitor_userid')]
        ])->first();

        return view('sysadmin.addbillingprice',compact('user','err_msg'));
    }

    public function addbillingpricesubmit() {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';

        $user = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', session('mymonitor_userid')]
        ])->first();

        $selectedprice = DB::table('tbl_billingprices')
        ->where([
            ['tbl_billingprices.product', '=', Request('product')]
        ])->first();

        if (is_null($selectedprice)) {
            $id=DB::table('tbl_billingprices')
                ->insertGetId([
                    'product' => Request('product'),
                    'price' => Request('price'),
                ]);
        
            $url = url('/sysadmin/billing/prices');
            return redirect($url);
        }else{
            $err_msg ='Product price exist!!!';
            return view('sysadmin.addbillingprice',compact('user','err_msg'));
        }
    }

    public function editbillingprice($product) {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', session('mymonitor_userid')]
        ])->first();

        $err_msg ='';

        $selectedprice = DB::table('tbl_billingprices')
        ->where([
            ['product','=',Request('product')]
        ])->first();
        
        return view('sysadmin.editbillingprice',compact('user','selectedprice','err_msg'));
    }

    public function editbillingpricesubmit($product) {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';

        $user = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', session('mymonitor_userid')]
        ])->first();

        DB::table('tbl_billingprices')
            ->where('product',Request('product'))
            ->update([
                'product' => Request('product'),
                'price' => Request('price')
            ]);

        $url = url('/sysadmin/billing/prices');
        return redirect($url);
    }

    public function deletebillingprice($product) {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';

        $user = DB::table('tbl_admins')
            ->where([
                ['tbl_admins.username', '=', session('mymonitor_userid')]
            ])->first();

        $selectedprice = DB::table('tbl_billingprices')
        ->where([
            ['product','=',Request('product')]
        ])->first();

        return view('sysadmin.deletebillingprice',compact('user','selectedprice','err_msg'));
    }

    public function deletebillingpricesubmit($product) {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';

        $user = DB::table('tbl_admins')
            ->where([
                ['tbl_admins.username', '=', session('mymonitor_userid')]
            ])->first();

        $selectedprice = DB::table('tbl_billingprices')
        ->where([
            ['product','=',$product]
        ])->first();

        if ($selectedprice->product==Request('productname')) {
            DB::table('tbl_billingprices')
                ->where('product',Request('productname'))
                ->delete();

        $url = url('/sysadmin/billing/prices');
        return redirect($url);
        }else {
            $err_msg ='Wrong product name! Product was not deleted.';
            return view('sysadmin.deletebillingprice',compact('user','selectedprice','err_msg'));
        }
    }

    public function billingdetail() {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', session('mymonitor_userid')]
        ])->first();

        $domains = DB::table('tbl_domains')->get();

        return view('sysadmin.billingdetail',compact('user','domains'));
    }

    public function billingdetaildomain($domainid) {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', session('mymonitor_userid')]
        ])->first();

        $domain = DB::table('tbl_domains')
        ->where([
            ['domainid','=',$domainid]
        ])->first();

        $pricelist = DB::table('tbl_billingprices')->get();

        $start = time();
        $end = time();

        $casvd = $this->billingcasvdcount($domainid,$start,$end);
        $slwnpm = $this->slwnpmtotalnodes($domainid);
        
        return view('sysadmin.billingdetaildomain',compact('user','pricelist','domain','start','end','casvd','slwnpm'));
    }

    public function billingdetaildomainreload($domainid) {
        if (!Session::has('Monitor') || Session('Monitor') != md5('sysadmin')){
            $url = url('/');
            return redirect($url);
        }
        
        $start = Request('start');
        $end = Request('end');

        $user = DB::table('tbl_admins')
        ->where([
            ['tbl_admins.username', '=', session('mymonitor_userid')]
        ])->first();

        $domain = DB::table('tbl_domains')
        ->where([
            ['domainid','=',$domainid]
        ])->first();

        $pricelist = DB::table('tbl_billingprices')->get();
        
        $casvd = $this->billingcasvdcount($domainid,$start,$end);

        return view('sysadmin.billingdetaildomain',compact('user','pricelist','domain','start','end','casvd'));
    }

    public function billingcasvdcount($domainid, $start, $end) {
        $casvdIncident = $this->billingtotalincidents($domainid,$start,$end);
        $casvdRequest = $this->billingtotalrequests($domainid,$start,$end);
        $casvdChange = $this->billingtotalchanges($domainid,$start,$end);
        
        $casvdCount = $casvdIncident + $casvdRequest + $casvdChange;
        $price = DB::table('tbl_billingprices')
        ->where([
            ['product','=','casvd']
        ])->get();
        
        $casvdPrice = $casvdCount * $price[0]->price;

        $casvdPrice = number_format($casvdPrice,0,',','.');
        $casvdPrice = "$".$casvdPrice;

        $result = (object) array('count'=>$casvdCount,'price'=>$casvdPrice);

        return $result;
    }

    public function billingcentreoncount($domainid, $start, $end) {
        $casvdIncident = $this->billingtotalincidents($domainid,$start,$end);
        $casvdRequest = $this->billingtotalrequests($domainid,$start,$end);
        $casvdChange = $this->billingtotalchanges($domainid,$start,$end);
        
        $casvdCount = $casvdIncident + $casvdRequest + $casvdChange;

        return $casvdCount;
    }

    public function billingslwnpmcount($domainid) {
        $slwnpmCount = $this->slwnpmtotalnodes($domainid);
        
        $price = DB::table('tbl_billingprices')
        ->where([
            ['product','=','slwnpm']
        ])->get();
        
        $slwnpmPrice = $slwnpmCount * $price[0]->price;

        $slwnpmPrice = number_format($slwnpmPrice,0,',','.');
        $slwnpmPrice = "$".$slwnpmPrice;

        $result = (object) array('count'=>$slwnpmCount,'price'=>$slwnpmPrice);

        return $result;
    }

    public function billingsdwancount($domainid, $start, $end) {
        $casvdIncident = $this->billingtotalincidents($domainid,$start,$end);
        $casvdRequest = $this->billingtotalrequests($domainid,$start,$end);
        $casvdChange = $this->billingtotalchanges($domainid,$start,$end);
        
        $casvdCount = $casvdIncident + $casvdRequest + $casvdChange;

        return $casvdCount;
    }

    public function billingtotalincidents($domainid, $start, $end){
        $casvdserver = DB::table('tbl_casvdservers')
            ->where([
                ['domainid', '=', $domainid],
            ])->first();

        if ($casvdserver->hostname == '') {
            return 'N/A';
        } else {
            $client = new SoapClient($casvdserver->secures . "://" . $casvdserver->hostname . ":" . $casvdserver->port . $casvdserver->basestring, array('trace' => 1));
            // Login to CASVD
            $ap_param = array(
                'username' => $casvdserver->user,
                'password' => $casvdserver->password,
                // 'username' => $casvdserver->user,
                // 'password' => $casvdserver->password
            );
            $sid = $client->__call("login", array($ap_param))->loginReturn;
            $whereParam = "type = 'I' AND open_date >= " . $start . "AND open_date <= " . ($end + 86400);

            // Get list handle
            $ap_param = array(
                'sid' => $sid,
                'objectType' => 'cr',
                'whereClause' => $whereParam,
            );
            $listHandle = $client->__call("doQuery", array($ap_param))->doQueryReturn;
            $listHandleID = $listHandle->listHandle;
            $listHandleLength = $listHandle->listLength;

            // Free List hanlde
            $ap_param = array(
                'sid' => $sid,
                'handles' => $listHandleID,
            );
            $client->__call("freeListHandles", array($ap_param));
        }
        return $listHandleLength;
    }

    public function billingtotalrequests($domainid, $start, $end){
        $casvdserver = DB::table('tbl_casvdservers')
            ->where([
                ['domainid', '=', $domainid],
            ])->first();

        if ($casvdserver->hostname == '') {
            return 'N/A';
        } else {
            $client = new SoapClient($casvdserver->secures . "://" . $casvdserver->hostname . ":" . $casvdserver->port . $casvdserver->basestring, array('trace' => 1));
            // Login to CASVD
            $ap_param = array(
                'username' => $casvdserver->user,
                'password' => $casvdserver->password,
            );
            $sid = $client->__call("login", array($ap_param))->loginReturn;
            $whereParam = "type = 'R' AND open_date >= " . $start . "AND open_date <= " . ($end + 86400);

            // Get list handle
            $ap_param = array(
                'sid' => $sid,
                'objectType' => 'cr',
                'whereClause' => $whereParam,
            );
            $listHandle = $client->__call("doQuery", array($ap_param))->doQueryReturn;
            $listHandleID = $listHandle->listHandle;
            $listHandleLength = $listHandle->listLength;

            // Free List hanlde
            $ap_param = array(
                'sid' => $sid,
                'handles' => $listHandleID,
            );
            $client->__call("freeListHandles", array($ap_param));
        }
        return $listHandleLength;
    }

    public function billingtotalchanges($domainid, $start, $end){
        $casvdserver = DB::table('tbl_casvdservers')
            ->where([
                ['domainid', '=', $domainid],
            ])->first();

        if ($casvdserver->hostname == '') {
            return 'N/A';
        } else {
            $client = new SoapClient($casvdserver->secures . "://" . $casvdserver->hostname . ":" . $casvdserver->port . $casvdserver->basestring, array('trace' => 1));
            // Login to CASVD
            $ap_param = array(
                'username' => $casvdserver->user,
                'password' => $casvdserver->password,
            );
            $sid = $client->__call("login", array($ap_param))->loginReturn;
            $whereParam = "open_date >= " . $start . "AND open_date <= " . ($end + 86400);

            // Get list handle
            $ap_param = array(
                'sid' => $sid,
                'objectType' => 'chg',
                'whereClause' => $whereParam,
            );
            $listHandle = $client->__call("doQuery", array($ap_param))->doQueryReturn;
            $listHandleID = $listHandle->listHandle;
            $listHandleLength = $listHandle->listLength;

            // Free List hanlde
            $ap_param = array(
                'sid' => $sid,
                'handles' => $listHandleID,
            );
            $client->__call("freeListHandles", array($ap_param));
        }
        return $listHandleLength;
    }

    public function slwnpmtotalnodes($domainid){
        $slwnpmserver = DB::table('tbl_slwnpmservers')        
        ->where([
            ['domainid', '=', $domainid]
        ])->first();
        
        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT+COUNT(NodeId) AS NodesCount+FROM+ORION.Nodes";

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        
        $data = json_decode($response, TRUE);
        
        return array_values( $data )[0][0]['NodesCount'];
    }
}