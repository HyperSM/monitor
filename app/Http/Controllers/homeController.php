<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class homeController extends Controller
{
    //
    //
    public function index(){

    	//Kiểm tra xem đã login chưa, nếu đã login thì session('FPTMonitor') sẽ là md5('admin')
        //Nếu chưa thì redirect sang trang đăng nhập
        switch (session('Monitor')) {
            case md5('admin'):
                return redirect("/admin/ciscosdwan");
                break;
            default:
                $err_msg = '';
                return view('login',compact('err_msg'));
                break;
        }        
    }

    //login
    public function doLogin(){        
        $username = Request('username');
        $username = str_replace('\'','',$username);
        $username = str_replace('=','',$username);
        $username = str_replace(' or ','',$username);

        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_domains', 'tbl_accounts.domainid', '=', 'tbl_domains.domainid')
        ->where([
            ['tbl_accounts.active', '=', 1],
            ['tbl_accounts.username', '=', $username]
        ])->first();

        //Nhập nhưng không có username trong database
        if (empty($user)){
        $err_msg = 'Error: Username does not exist';
            return view('login',compact('err_msg'));
        }else{
            //Có user trong db
            //Domain is inactive or acitve
            if($user->domainactive==1){

                //Chưa nhập user/password
                if(Request('username')==''||Request('username')==''){
                    $err_msg = 'Error: Enter username or password';
                    return view('login',compact('err_msg'));
                    die();
                }

                //
                //Tạm thời bỏ qua xác thực ldap
                $password = md5(Request('password'));
                if ($user->password == $password){
                    //session(['AppID' => strlen($user->userid).$user->userid.md5($user->userid)]);
                    session(['Monitor' => md5('admin')]);
                    session(['mymonitor_userid' => $username]);
                    session(['mymonitor_md' => Crypt::encryptString($user->domainid)]);                    
                    return redirect("/admin/ciscosdwan");                            

                }else{
                    $err_msg = 'Error: Wrong password';
                    return view('login',compact('err_msg'));
                }
                //          

            }else{
                //Domain chưa kích hoạt
                $err_msg = 'Error: Domain is inactive';
                return view('login',compact('err_msg'));
                die();
            }
            //End of có user trong db
        }
    }


    //logout
    public function doLogout(){   
        Auth::logout();

        Session()->invalidate();
        Session()->regenerateToken();

        return redirect('/');
    }
}
