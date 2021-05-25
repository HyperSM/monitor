<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use DateTime;
use DateTimeZone;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;


class ciscosdwanController extends Controller
{

    /*
    Category: Cisco SDWAN
    Page: Dashboard
    Section: Main view
    */
    public function dashboard(){

        if (!Session::has('Monitor')||!Session::has('mymonitor_md')){
            $url = url('/');
            return redirect($url);
        }

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

        if($user->ciscosdwanuse=='1'){
            return view('ciscosdwan.dashboard',compact('domain','user'));
        }else{
            $url = url('/').'/admin/dashboard';
            return redirect($url);
        }
    }

    /*
    Category: Cisco SDWAN
    Page: Config vmanage information (config server)
    Section: Main view
    */
    public function configserver(){

        if (!Session::has('Monitor')||!Session::has('mymonitor_md')){
            $url = url('/');
            return redirect($url);
        }

        $dm=Crypt::decryptString(session('mymonitor_md'));

        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        return view('ciscosdwan.serverconfig',compact('ciscosdwanserver','user'));
    }

    /*
    Category: Cisco SDWAN
    Page: Submit vmanage information (config server)
    Section: Submit
    */
    public function ciscosdwanserversubmit(){
        $server = DB::table('tbl_ciscosdwanservers')
        ->where('domainid','=', Crypt::decryptString(session('mymonitor_md')))
        ->first();
        if (empty($server)){
            //Create
            DB::table('tbl_ciscosdwanservers')
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
            DB::table('tbl_ciscosdwanservers')
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
        $url = url('/').'/admin/ciscosdwan';
        return redirect($url);
    }

    /*
    Category: Cisco SDWAN
    Page: Dashboard
    Section: Function to all devices
    Return value to div in dashboard
    */
    public function dashboardalldevices(){
        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            return 'N/A';
        }else{
            /////////////////////////////////////
            //Get cookie first
            $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

            if($ciscosdwanserver->hostname==''){
                return 'N/A';
            }else{
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
                $query = "/j_security_check";
                $response = Http::withBasicAuth($ciscosdwanserver->user,$ciscosdwanserver->password)->Get($apihost . $query);
                $cookieJar = $response->cookies;
                $data = $cookieJar->toArray($cookieJar);
                //dd($data[0]['Value']);
                $j_ssesion = $data[0]['Value'];
            }
            ////////////////////////////////////////////
            $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
            $query = "device";
            $response = Http::withBasicAuth($ciscosdwanserver->user,$ciscosdwanserver->password,[
              'form_params' => [
                  'j_ssesion' => $j_ssesion
              ]])->Get($apihost . $query);

            $myJSON = json_decode($response->getBody()->getContents());
            if ($myJSON!=null){
                $dataArray = $myJSON->data;
                echo(json_encode($dataArray));
            }else{
                echo('');
            }
        }
    }

    /*
    Category: Cisco SDWAN
    Page: Dashboard
    Section: Widget reboot count
    Return value to div in dashboard
    */
    public function dashboardrebootcount(){
        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            return 'N/A';
        }else{
            /////////////////////////////////////
            //Get cookie first
            $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

            if($ciscosdwanserver->hostname==''){
                return 'N/A';
            }else{
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
                $query = "/j_security_check";
                $response = Http::withBasicAuth($ciscosdwanserver->user,$ciscosdwanserver->password)->Get($apihost . $query);
                $cookieJar = $response->cookies;
                $data = $cookieJar->toArray($cookieJar);
                //dd($data[0]['Value']);
                $j_ssesion = $data[0]['Value'];
            }
            ////////////////////////////////////////////
            $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
            $query = "network/issues/rebootcount";
            $response = Http::withBasicAuth($ciscosdwanserver->user,$ciscosdwanserver->password,[
              'form_params' => [
                  'j_ssesion' => $j_ssesion
              ]])->Get($apihost . $query);

            $myJSON = json_decode($response->getBody()->getContents());
            if ($myJSON!=null){
                $dataArray = $myJSON->data;
                echo($dataArray[0]->count);
            }else{
                $dataArray = null;
                echo('N/A');
            }
        }
    }

    /*
    Category: Cisco SDWAN
    Page: Dashboard
    Section: Widget warning count
    Return value to div in dashboard
    */
    public function dashboardwarningcount(){
        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            return 'N/A';
        }else{
            /////////////////////////////////////
            //Get cookie first
            $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

            if($ciscosdwanserver->hostname==''){
                return 'N/A';
            }else{
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
                $query = "/j_security_check";
                $response = Http::withBasicAuth($ciscosdwanserver->user,$ciscosdwanserver->password)->Get($apihost . $query);
                $cookieJar = $response->cookies;
                $data = $cookieJar->toArray($cookieJar);
                //dd($data[0]['Value']);
                $j_ssesion = $data[0]['Value'];
            }
            ////////////////////////////////////////////
            $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
            $query = "certificate/stats/summary";
            $response = Http::withBasicAuth($ciscosdwanserver->user,$ciscosdwanserver->password,[
              'form_params' => [
                  'j_ssesion' => $j_ssesion
              ]])->Get($apihost . $query);

            $myJSON = json_decode($response->getBody()->getContents());
            if ($myJSON!=null){
                $dataArray = $myJSON->data;
                echo('WARNING ' . $dataArray[0]->warning);
            }else{
                $dataArray = null;
                echo('WARNING N/A');
            }
        }
    }

    /*
    Category: Cisco SDWAN
    Page: Dashboard
    Section: Widget invalid count
    Return value to div in dashboard
    */
    public function dashboardinvalidcount(){
        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            return 'N/A';
        }else{
            /////////////////////////////////////
            //Get cookie first
            $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

            if($ciscosdwanserver->hostname==''){
                return 'N/A';
            }else{
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
                $query = "/j_security_check";
                $response = Http::withBasicAuth($ciscosdwanserver->user,$ciscosdwanserver->password)->Get($apihost . $query);
                $cookieJar = $response->cookies;
                $data = $cookieJar->toArray($cookieJar);
                //dd($data[0]['Value']);
                $j_ssesion = $data[0]['Value'];
            }
            ////////////////////////////////////////////
            $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
            $query = "certificate/stats/summary";
            $response = Http::withBasicAuth($ciscosdwanserver->user,$ciscosdwanserver->password,[
              'form_params' => [
                  'j_ssesion' => $j_ssesion
              ]])->Get($apihost . $query);

            $myJSON = json_decode($response->getBody()->getContents());
            if ($myJSON!=null){
                $dataArray = $myJSON->data;
                echo('INVALID ' . $dataArray[0]->invalid);
            }else{
                $dataArray = null;
                echo('INVALID N/A');
            }
        }
    }

    /*
    Category: Cisco SDWAN
    Page: Dashboard
    Section: Widget wan edge health
    Return value to div in dashboard
    */
    public function dashboardwanedgehealth(){
        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            return 'N/A';
        }else{
            /////////////////////////////////////
            //Get cookie first
            $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

            if($ciscosdwanserver->hostname==''){
                return 'N/A';
            }else{
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
                $query = "/j_security_check";
                $response = Http::withBasicAuth($ciscosdwanserver->user,$ciscosdwanserver->password)->Get($apihost . $query);
                $cookieJar = $response->cookies;
                $data = $cookieJar->toArray($cookieJar);
                //dd($data[0]['Value']);
                $j_ssesion = $data[0]['Value'];
            }
            ////////////////////////////////////////////
            $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
            $query = "device/hardwarehealth/summary";
            $response = Http::withBasicAuth($ciscosdwanserver->user,$ciscosdwanserver->password,[
              'form_params' => [
                  'j_ssesion' => $j_ssesion
              ]])->Get($apihost . $query);

            $myJSON = json_decode($response->getBody()->getContents());
            if ($myJSON!=null){
                $dataArray = $myJSON->data[0]->statusList;
                echo(json_encode($dataArray));
            }else{
                $dataArray = null;
                echo('');
            }
        }
    }

    /*
    Category: Cisco SDWAN
    Page: Dashboard
    Section: Widget control status
    Return value to div in dashboard
    */
    public function dashboardcontrolstatus(){
        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            echo 'DATA IS NOT AVAILABLE';
        }else{
            /////////////////////////////////////
            $tmpstr =
            '<table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>';

            $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

            if($ciscosdwanserver->hostname==''){
                echo 'DATA IS NOT AVAILABLE';
            }else{
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
                $query = "/j_security_check";
                $response = Http::withBasicAuth($ciscosdwanserver->user,$ciscosdwanserver->password)->Get($apihost . $query);
                $cookieJar = $response->cookies;
                $data = $cookieJar->toArray($cookieJar);
                //dd($data[0]['Value']);
                $j_ssesion = $data[0]['Value'];
            }
            ////////////////////////////////////////////
            $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
            $query = "device/control/count";
            $response = Http::withBasicAuth($ciscosdwanserver->user,$ciscosdwanserver->password,[
              'form_params' => [
                  'j_ssesion' => $j_ssesion
              ]])->Get($apihost . $query);

            $myJSON = json_decode($response->getBody()->getContents());
            if ($myJSON!=null){
                $dataArray = $myJSON->data[0]->statusList;
                $total = 0;
                foreach($dataArray as $data){
                    $total = $total + $data->count;
                }
                
                foreach($dataArray as $data){
                    if ($total == 0) {
                        $tmp = 0;
                    } else {
                        $tmp = ($data->count/$total) * 100;
                    }
                    
                    ////
                    $tmpstr = $tmpstr .
                        '<tr>
                                <td style="vertical-align: middle;"><a id="'.$data->name.'" class="click_devices open-options" style="text-decoration:none;" href="#">' .$data->name.'</a></td>
                                <td style="vertical-align: middle;">' .$data->status.'</td>
                                <td style="vertical-align: middle;">'.$data->count.'<div class="progress progress-striped active" style="height:10px;">
                                    <div style="width: '.$tmp.'%; height:10px;" class="progress-bar progress-bar-success"></div>
                                    </div>
                                </td>                  
                        </tr>';
                    /////
                }
            }else{
                echo('There are somthing wrong with your data');
            }
            $tmpstr = $tmpstr.' 
                </tbody>
            </table>';
            echo $tmpstr;
        }
    }

    /*
    Category: Cisco SDWAN
    Page: Dashboard
    Section: Widget wan edge inventory
    Return value to div in dashboard
    */
    public function dashboardwanedgeinventory(){
        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            echo 'DATA IS NOT AVAILABLE';
        }else{
            /////////////////////////////////////
            $tmpstr =
            '<table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>List</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>';

            $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

            if($ciscosdwanserver->hostname==''){
                echo 'DATA IS NOT AVAILABLE';
            }else{
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
                $query = "/j_security_check";
                $response = Http::withBasicAuth($ciscosdwanserver->user,$ciscosdwanserver->password)->Get($apihost . $query);
                $cookieJar = $response->cookies;
                $data = $cookieJar->toArray($cookieJar);
                //dd($data[0]['Value']);
                $j_ssesion = $data[0]['Value'];
            }
            ////////////////////////////////////////////
            $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
            $query = "device/vedgeinventory/summary";
            $response = Http::withBasicAuth($ciscosdwanserver->user,$ciscosdwanserver->password,[
              'form_params' => [
                  'j_ssesion' => $j_ssesion
              ]])->Get($apihost . $query);

            $myJSON = json_decode($response->getBody()->getContents());

            if ($myJSON!=null){
                $dataArray = $myJSON->data;

                foreach($dataArray as $data){
                    ////
                    $tmpstr = $tmpstr .
                        '<tr>
                                <td style="vertical-align: middle;"><a id="'.$data->name.'" class="click_devices open-options" style="text-decoration:none;" href="#">' .$data->name.'</a></td>
                                <td style="vertical-align: middle;">' .$data->list.'</td>
                                <td style="vertical-align: middle;">' .$data->value.'</td>                  
                        </tr>';
                    //////
                }
            }else{
                echo('There are somthing wrong with your data');
            }
            $tmpstr = $tmpstr.' 
                </tbody>
            </table>';
            echo $tmpstr;
        }
    }


    /*
    Category: Cisco SDWAN
    Page: Dashboard
    Section: Widget Site health
    Return value to div in dashboard
    */
    public function dashboardsitehealth(){
        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            echo 'DATA IS NOT AVAILABLE';
        }else{
            /////////////////////////////////////
            $tmpstr =
            '<table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th align="center">Status</th>
                        <th>Name</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>';

            $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

            if($ciscosdwanserver->hostname==''){
                echo 'DATA IS NOT AVAILABLE';
            }else{
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
                $query = "/j_security_check";
                $response = Http::withBasicAuth($ciscosdwanserver->user,$ciscosdwanserver->password)->Get($apihost . $query);
                $cookieJar = $response->cookies;
                $data = $cookieJar->toArray($cookieJar);
                //dd($data[0]['Value']);
                $j_ssesion = $data[0]['Value'];
            }
            ////////////////////////////////////////////
            $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
            $query = "device/bfd/sites/summary";
            $response = Http::withBasicAuth($ciscosdwanserver->user,$ciscosdwanserver->password,[
              'form_params' => [
                  'j_ssesion' => $j_ssesion
              ]])->Get($apihost . $query);

            $myJSON = json_decode($response->getBody()->getContents());

            if ($myJSON!=null){
                $dataArray = $myJSON->data[0]->statusList;

                foreach($dataArray as $data){
                    ////
                    $i = '';
                    switch ($data->status) {
                        case 'up':
                            $i = '<span class="fa fa-check-circle text-primary"></i>';
                            break;
                        case 'warning':
                            $i = '<i class="fa fa-exclamation-circle text-warning"></i>';
                            break;
                        case 'down':
                            $i = '<i class="fa fa-times-circle text-danger"></i>';
                            break;
                        default:
                            # code...
                            break;
                    }
                    $tmpstr = $tmpstr .
                        '<tr>
                                <td style="vertical-align: middle;" align="center">' .$i. '</td>
                                <td style="vertical-align: middle;"><a id="'.$data->name.'" class="click_devices open-options" style="text-decoration:none;" href="#">' .$data->name.'</a></td>
                                <td style="vertical-align: middle;">' .$data->count.' sites</td>                  
                        </tr>';
                    /////
                }
            }else{
                echo('There are somthing wrong with your data');
            }
            $tmpstr = $tmpstr.' 
                </tbody>
            </table>';
            echo $tmpstr;
        }
    }

    /*
    Category: Cisco SDWAN
    Page: Dashboard
    Section: Widget Transport Interface Distribution
    Return value to div in dashboard
    */
    public function dashboardstransportinterface(){
        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            echo 'DATA IS NOT AVAILABLE';
        }else{
            /////////////////////////////////////
            $tmpstr =
            '<table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>';

            $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

            if($ciscosdwanserver->hostname==''){
                echo 'DATA IS NOT AVAILABLE';
            }else{
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
                $query = "/j_security_check";
                $response = Http::withBasicAuth($ciscosdwanserver->user,$ciscosdwanserver->password)->Get($apihost . $query);
                $cookieJar = $response->cookies;
                $data = $cookieJar->toArray($cookieJar);
                //dd($data[0]['Value']);
                $j_ssesion = $data[0]['Value'];
            }
            ////////////////////////////////////////////
            $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
            $query = "device/tlocutil";
            $response = Http::withBasicAuth($ciscosdwanserver->user,$ciscosdwanserver->password,[
              'form_params' => [
                  'j_ssesion' => $j_ssesion
              ]])->Get($apihost . $query);

            $myJSON = json_decode($response->getBody()->getContents());

            if ($myJSON!=null){
                $dataArray = $myJSON->data;

                foreach($dataArray as $data){
                    ////
                    $tmpstr = $tmpstr .
                        '<tr>
                                <td style="vertical-align: middle;"><a id="'.$data->name.'" class="click_devices open-options" style="text-decoration:none;" href="#">' .$data->percentageDistribution.'</a></td>
                                <td style="vertical-align: middle;">' .$data->value.' sites</td>                  
                        </tr>';
                    /////
                }
            }else{
                echo('There are somthing wrong with your data');
            }
            $tmpstr = $tmpstr.' 
                </tbody>
            </table>';
            echo $tmpstr;

        }
    }

    /*
    Category: Cisco SDWAN
    Page: Dashboard
    Section: Widget Transport Health
    Return value to div in dashboard
    */
    public function dashboardtransporthealth(){
        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            echo 'DATA IS NOT AVAILABLE';
        }else{
            /////////////////////////////////////
            $tmpstr = '';

            $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

            if($ciscosdwanserver->hostname==''){
                echo 'DATA IS NOT AVAILABLE';
            }else{
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
                $query = "/j_security_check";
                $response = Http::withBasicAuth($ciscosdwanserver->user,$ciscosdwanserver->password)->Get($apihost . $query);
                $cookieJar = $response->cookies;
                $data = $cookieJar->toArray($cookieJar);
                //dd($data[0]['Value']);
                $j_ssesion = $data[0]['Value'];
            }
            ////////////////////////////////////////////
            $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
            $query = "device/bfd/sites/summary";
            $response = Http::withBasicAuth($ciscosdwanserver->user,$ciscosdwanserver->password,[
              'form_params' => [
                  'j_ssesion' => $j_ssesion
              ]])->Get($apihost . $query);

            $myJSON = json_decode($response->getBody()->getContents());

            if ($myJSON!=null){
                $dataArray = $myJSON->data;

                echo json_encode($dataArray);
            }else{
                echo('There are somthing wrong with your data');
            }
        }
    }

    /*
    Category: Cisco SDWAN
    Page: Dashboard
    Section: Alarms in last 24 hours
    Return value to div in dashboard
    */
    public function dashboardalarms(){
        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            echo 'DATA IS NOT AVAILABLE';
        }else{
            /////////////////////////////////////
            $tmpstr = '';

            $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

            if($ciscosdwanserver->hostname==''){
                echo 'DATA IS NOT AVAILABLE';
            }else{
                $client = new \GuzzleHttp\Client(['cookies' => true]);
                try {
                    $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
                    $res = $client->request('POST', $apihost . "/j_security_check", [
                      'form_params' => [
                          'j_username' => $ciscosdwanserver->user ,
                          'j_password' => $ciscosdwanserver->password
                      ],
                      "verify" => false
                   ]);
                } catch (RequestException $e) {
                    dd('There are somthing wrong with your api server');
                }

                $cookieJar = $client->getConfig('cookies');
                $infoArray = $cookieJar->toArray();
                $cookie    = $infoArray[0]["Value"];


                /*
                Chạy thống kê
                */
                $json = [
                    "query"=>[
                      "condition"=>"AND",
                      "rules"=>[
                        [
                          "value"=>[
                            "24"
                          ],
                          "field"=>"entry_time",
                          "type"=>"date",
                          "operator"=>"last_n_hours"
                        ]
                      ]
                    ],
                    "size"=>10
                  ];


                  $client= new \GuzzleHttp\Client(['cookies' => true]);
                  $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
                  $query = 'alarms';
                  $response = $client->request('POST', $apihost . $query, [
                        'headers'        => [
                           'Cookie'       => "JSESSIONID=".$cookie,
                           'Content-Type' => 'application/json',
                           'Accept' => 'application/json',
                        ],
                        'json'    => $json,
                        "verify" => false
                  ]);


                //dd($response->getBody()->getContents());
                $jsondata = json_decode($response->getBody()->getContents());
                if ($jsondata==null){
                    dd('There are something wrong with your data');
                }else{
                    $alarms = $jsondata->data;
                    $tmpstr =
                    '<table class="table table-hover table-condensed">
                        <thead>
                            <tr>
                                <th>Component</th>
                                <th>Message</th>
                                <th>Severity</th>
                                <th>Receive Time (in local)</th>
                            </tr>
                        </thead>
                        <tbody>';
                    foreach ($alarms as $alarm) {
                        # code...
                        //$dt = new DateTime('@'.substr($alarm->entry_time,1,strlen($alarm->entry_time)-3));
                        //$dt->setTimeZone(new DateTimeZone('Asia/Ho_Chi_Minh'));

                        $milliseconds =  $alarm->entry_time;
                        $timestamp = ($milliseconds/1000) + 25200;

                        //echo $dt->format('F j, Y, g:i a');
                        $tmpstr= $tmpstr . '<tr>
                            <td>' . $alarm->component . '</td>
                            <td>' . $alarm->message . '</td>
                            <td>' . $alarm->severity . '</td>
                            <td>' . date("d F, Y H:i A", $timestamp) . '</td>
                        </tr>';
                    }
                    $tmpstr = $tmpstr .
                    '   </tbody>
                    </table>';

                    echo $tmpstr;
                }

                /*Kết thúc thống kê*/
            }
        }
    }

    /*
    Category: Cisco SDWAN
    Page: Dashboard
    Section: Server Detail
    Return value to div in dashboard
    */
    public function dashboardserverdetail(){
        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            echo 'DATA IS NOT AVAILABLE';
        }else{
            /////////////////////////////////////
            $tmpstr = '';

            $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

            if($ciscosdwanserver->hostname==''){
                echo 'DATA IS NOT AVAILABLE';
            }else{
                $client = new \GuzzleHttp\Client(['cookies' => true]);
                try {
                    $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
                    $res = $client->request('POST', $apihost . "/j_security_check", [
                      'form_params' => [
                          'j_username' => $ciscosdwanserver->user ,
                          'j_password' => $ciscosdwanserver->password
                      ],
                      "verify" => false
                   ]);
                } catch (RequestException $e) {
                    dd('There are somthing wrong with your api server');
                }

                $cookieJar = $client->getConfig('cookies');
                $infoArray = $cookieJar->toArray();
                $cookie    = $infoArray[0]["Value"];


                /*
                Chạy thống kê
                */

                $client= new \GuzzleHttp\Client(['cookies' => true]);
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
                $query = 'client/server';
                $response = $client->request('GET', $apihost . $query, [
                        'headers'        => [
                           'Cookie'       => "JSESSIONID=".$cookie,
                        ],
                        "verify" => false
                ]);


                //dd($response->getBody()->getContents());
                $jsondata = json_decode($response->getBody()->getContents());
                if ($jsondata==null){
                    dd('There are something wrong with your data');
                }else{
                    $server = $jsondata->data;
                    $tmpstr =
                    '<table class="table table-hover table-condensed">
                        <tbody>';
                        # code...
                        $tmpstr= $tmpstr . '<tr><td>Server</td><td>' . $server->server . '</td></tr>
                            <td>Tenancy Mode</td><td>' . $server->tenancyMode . '</td></tr>
                            <td>Platform Version</td><td>' . $server->platformVersion . '</td></tr>
                            <td>General Template</td><td>' . ($server->generalTemplate==true?'True':'False') . '</td>
                        </tr>';
                    $tmpstr = $tmpstr .
                    '   </tbody>
                    </table>';

                    echo $tmpstr;
                }

                /*Kết thúc thống kê*/
            }
        }
    }

    /*
    Category: Cisco SDWAN
    Page: Dashboard
    Section: Click vào Control up/down/partial trên widget Control Status
    Return value to ajax call back
    */
    public function dashboardajaxcontrol($type){
        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            echo 'DATA IS NOT AVAILABLE';
        }else{
            /////////////////////////////////////
            $tmpstr = '';

            $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

            if($ciscosdwanserver->hostname==''){
                echo 'DATA IS NOT AVAILABLE';
            }else{
                $client = new \GuzzleHttp\Client(['cookies' => true]);
                try {
                    $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
                    $res = $client->request('POST', $apihost . "/j_security_check", [
                      'form_params' => [
                          'j_username' => $ciscosdwanserver->user ,
                          'j_password' => $ciscosdwanserver->password
                      ],
                      "verify" => false
                   ]);
                } catch (RequestException $e) {
                    dd('There are somthing wrong with your api server');
                }

                $cookieJar = $client->getConfig('cookies');
                $infoArray = $cookieJar->toArray();
                $cookie    = $infoArray[0]["Value"];

                $client= new \GuzzleHttp\Client(['cookies' => true]);
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
                $query = 'device/control/networksummary?state=' . $type;
                $response = $client->request('GET', $apihost . $query, [
                        'headers'        => [
                           'Cookie'       => "JSESSIONID=".$cookie,
                        ],
                        "verify" => false
                ]);

                $jsondata = json_decode($response->getBody()->getContents());
                if ($jsondata==null){
                    echo ('There are something wrong with your data');
                }else{
                    echo(json_encode($jsondata->data));
                }


            }
        }
    }

    /*
    Category: Cisco SDWAN
    Page: Dashboard
    Section: Click widget Vedge inventory
    Return value to ajax call back
    */
    public function dashboardajaxinventory(Request $request){
        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            echo 'DATA IS NOT AVAILABLE';
        }else{
            /////////////////////////////////////
            $tmpstr = '';

            $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

            if($ciscosdwanserver->hostname==''){
                echo 'DATA IS NOT AVAILABLE';
            }else{
                $client = new \GuzzleHttp\Client(['cookies' => true]);
                try {
                    $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
                    $res = $client->request('POST', $apihost . "/j_security_check", [
                      'form_params' => [
                          'j_username' => $ciscosdwanserver->user ,
                          'j_password' => $ciscosdwanserver->password
                      ],
                      "verify" => false
                   ]);
                } catch (RequestException $e) {
                    dd('There are somthing wrong with your api server');
                }

                $cookieJar = $client->getConfig('cookies');
                $infoArray = $cookieJar->toArray();
                $cookie    = $infoArray[0]["Value"];

                $client= new \GuzzleHttp\Client(['cookies' => true]);
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
                $query = 'device/vedgeinventory/' . $request->data;
                $response = $client->request('GET', $apihost . $query, [
                        'headers'        => [
                           'Cookie'       => "JSESSIONID=".$cookie,
                        ],
                        "verify" => false
                ]);

                $jsondata = json_decode($response->getBody()->getContents());
                if ($jsondata==null){
                    echo ('There are something wrong with your data');
                }else{
                    echo(json_encode($jsondata->data));
                }


            }
        }
    }

    /*
    Category: Cisco SDWAN
    Page: Dashboard
    Section: Click widget Site Health
    Return value to ajax call back
    */
    public function dashboardajaxsitehealth(Request $request){
        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            echo 'DATA IS NOT AVAILABLE';
        }else{
            /////////////////////////////////////
            $tmpstr = '';

            $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

            if($ciscosdwanserver->hostname==''){
                echo 'DATA IS NOT AVAILABLE';
            }else{
                $client = new \GuzzleHttp\Client(['cookies' => true]);
                try {
                    $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
                    $res = $client->request('POST', $apihost . "/j_security_check", [
                      'form_params' => [
                          'j_username' => $ciscosdwanserver->user ,
                          'j_password' => $ciscosdwanserver->password
                      ],
                      "verify" => false
                   ]);
                } catch (RequestException $e) {
                    dd('There are somthing wrong with your api server');
                }

                $cookieJar = $client->getConfig('cookies');
                $infoArray = $cookieJar->toArray();
                $cookie    = $infoArray[0]["Value"];

                $client= new \GuzzleHttp\Client(['cookies' => true]);
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
                $query = 'device/bfd/sites/' . $request->data;
                $response = $client->request('GET', $apihost . $query, [
                        'headers'        => [
                           'Cookie'       => "JSESSIONID=".$cookie,
                        ],
                        "verify" => false
                ]);

                $jsondata = json_decode($response->getBody()->getContents());
                if ($jsondata==null){
                    echo ('There are something wrong with your data');
                }else{
                    echo(json_encode($jsondata->data));
                }


            }
        }
    }

    /*
    Category: Cisco SDWAN
    Page: Dashboard
    Section: Click widget Site Interface
    Return value to ajax call back
    */
    public function dashboardajaxsiteinterface(Request $request){
        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            echo 'DATA IS NOT AVAILABLE';
        }else{
            /////////////////////////////////////
            $tmpstr = '';

            $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

            if($ciscosdwanserver->hostname==''){
                echo 'DATA IS NOT AVAILABLE';
            }else{
                $client = new \GuzzleHttp\Client(['cookies' => true]);
                try {
                    $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
                    $res = $client->request('POST', $apihost . "/j_security_check", [
                      'form_params' => [
                          'j_username' => $ciscosdwanserver->user ,
                          'j_password' => $ciscosdwanserver->password
                      ],
                      "verify" => false
                   ]);
                } catch (RequestException $e) {
                    dd('There are somthing wrong with your api server');
                }

                $cookieJar = $client->getConfig('cookies');
                $infoArray = $cookieJar->toArray();
                $cookie    = $infoArray[0]["Value"];

                $client= new \GuzzleHttp\Client(['cookies' => true]);
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
                $query = 'device/tlocutil/' . str_replace("_","",$request->data);
                $response = $client->request('GET', $apihost . $query, [
                        'headers'        => [
                           'Cookie'       => "JSESSIONID=".$cookie,
                        ],
                        "verify" => false
                ]);

                $jsondata = json_decode($response->getBody()->getContents());


                if ($jsondata==null){
                    echo ('There are something wrong with your data');
                }else{
                    echo(json_encode($jsondata->data));
                }
            }
        }
    }

    /*
    Category: Cisco SDWAN
    Page: Network -> Device list
    Section: Main view
    */
    public function network(){

        if (!Session::has('Monitor')||!Session::has('mymonitor_md')){
            $url = url('/');
            return redirect($url);
        }

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

        if($user->ciscosdwanuse!='1'){
            $url = url('/').'/admin/dashboard';
            return redirect($url);
        }

        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            $url = url('/').'/admin/ciscosdwan';
            return redirect($url);
        }else{
            //Get jsessionid
            $client = new \GuzzleHttp\Client(['cookies' => true]);
            try {
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
                $res = $client->request('POST', $apihost . "/j_security_check", [
                  'form_params' => [
                      'j_username' => $ciscosdwanserver->user ,
                      'j_password' => $ciscosdwanserver->password
                  ],
                  "verify" => false
               ]);
            } catch(\GuzzleHttp\Exception\GuzzleException $e) {
                $url = url('/').'/admin/ciscosdwan';
                return redirect($url);
            }
            //End of jsession id

            $cookieJar = $client->getConfig('cookies');
            $infoArray = $cookieJar->toArray();
            $cookie    = $infoArray[0]["Value"];

            try{
                $client= new \GuzzleHttp\Client(['cookies' => true]);
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
                $query = 'device';
                $response = $client->request('GET', $apihost . $query, [
                        'headers'        => [
                           'Cookie'       => "JSESSIONID=".$cookie,
                        ],
                        "verify" => false
                ]);
            } catch(\GuzzleHttp\Exception\GuzzleException $e) {
                $url = url('/').'/admin/ciscosdwan';
                return redirect($url);
            }

            $jsondata = json_decode($response->getBody()->getContents());

            if ($jsondata==null){
                $devices = null;
                return view('ciscosdwan.network',compact('user','ciscosdwanserver','devices'));
            }else{
                $devices = $jsondata->data;
                return view('ciscosdwan.network',compact('user','ciscosdwanserver','devices'));
            }
        }
    }

    /*
    Category: Cisco SDWAN
    Page: Network Detail System Status, One device
    Section: Main view
    */
    public function systemstatus($deviceid){
        if (!Session::has('Monitor')||!Session::has('mymonitor_md')){
            $url = url('/');
            return redirect($url);
        }

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

        if($user->ciscosdwanuse!='1'){
            $url = url('/').'/admin/dashboard';
            return redirect($url);
        }

        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            $url = url('/').'/admin/ciscosdwan';
            return redirect($url);
        }else{
            //device/reboothistory/synced?deviceId='.$id
            return view('ciscosdwan.systemstatus',compact('user','ciscosdwanserver','deviceid'));
        }
    }

    /*
    Category: Cisco SDWAN
    Page: Network Detail System Status
    Section: Function Return reboot count to ajax call
    */
    public function networkajaxreboot(Request $request){

        $dm=Crypt::decryptString(session('mymonitor_md'));

        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        //Get jsessionid
        $client = new \GuzzleHttp\Client(['cookies' => true]);
        $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
        $res = $client->request('POST', $apihost . "/j_security_check", [
            'form_params' => [
                'j_username' => $ciscosdwanserver->user ,
                'j_password' => $ciscosdwanserver->password
            ],
            "verify" => false
        ]);

        //End of jsession id

        $cookieJar = $client->getConfig('cookies');
        $infoArray = $cookieJar->toArray();
        $cookie    = $infoArray[0]["Value"];

        $client= new \GuzzleHttp\Client(['cookies' => true]);
        $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
        $query = 'device/reboothistory/synced?deviceId='.$request->data;
        $response = $client->request('GET', $apihost . $query, [
                'headers'        => [
                   'Cookie'       => "JSESSIONID=".$cookie,
                ],
                "verify" => false
        ]);

        $jsondata = json_decode($response->getBody()->getContents());
        $rebootdata = $jsondata->data;
        $rebootcount = count($rebootdata);
        echo $rebootcount;
    }

    /*
    Category: Cisco SDWAN
    Page: Network Detail System Status
    Section: Function Return crash count to ajax call
    */
    public function networkajaxcrash(Request $request){

        $dm=Crypt::decryptString(session('mymonitor_md'));

        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        //Get jsessionid
        $client = new \GuzzleHttp\Client(['cookies' => true]);
        $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
        $res = $client->request('POST', $apihost . "/j_security_check", [
            'form_params' => [
                'j_username' => $ciscosdwanserver->user ,
                'j_password' => $ciscosdwanserver->password
            ],
            "verify" => false
        ]);

        //End of jsession id

        $cookieJar = $client->getConfig('cookies');
        $infoArray = $cookieJar->toArray();
        $cookie    = $infoArray[0]["Value"];

        $client= new \GuzzleHttp\Client(['cookies' => true]);
        $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
        $query = 'device/crashlog/synced?deviceId='.$request->data;
        $response = $client->request('GET', $apihost . $query, [
                'headers'        => [
                   'Cookie'       => "JSESSIONID=".$cookie,
                ],
                "verify" => false
        ]);

        $jsondata = json_decode($response->getBody()->getContents());
        $crashdata = $jsondata->data;
        $crashcount = count($crashdata);
        echo $crashcount;
    }

    /*
    Category: Cisco SDWAN
    Page: Network Detail System Status
    Section: Function Return device's summary information to ajax call back
    */
    public function networkajaxsummary(Request $request){

        $dm=Crypt::decryptString(session('mymonitor_md'));

        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        //Get jsessionid
        $client = new \GuzzleHttp\Client(['cookies' => true]);
        $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
        $res = $client->request('POST', $apihost . "/j_security_check", [
            'form_params' => [
                'j_username' => $ciscosdwanserver->user ,
                'j_password' => $ciscosdwanserver->password
            ],
            "verify" => false
        ]);

        //End of jsession id

        $cookieJar = $client->getConfig('cookies');
        $infoArray = $cookieJar->toArray();
        $cookie    = $infoArray[0]["Value"];

        $client= new \GuzzleHttp\Client(['cookies' => true]);
        $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
        $query = 'device?deviceId='.$request->data;
        $response = $client->request('GET', $apihost . $query, [
                'headers'        => [
                   'Cookie'       => "JSESSIONID=".$cookie,
                ],
                "verify" => false
        ]);

        $jsondata = json_decode($response->getBody()->getContents());
        $summary = json_encode($jsondata->data);
        echo $summary;
    }

    /*
    Category: Cisco SDWAN
    Page: Network Detail System Status
    Section: Function Return all devices to ajax call back
    */
    public function networkajaxalldevices(Request $request){

        $dm=Crypt::decryptString(session('mymonitor_md'));

        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        //Get jsessionid
        $client = new \GuzzleHttp\Client(['cookies' => true]);
        $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
        $res = $client->request('POST', $apihost . "/j_security_check", [
            'form_params' => [
                'j_username' => $ciscosdwanserver->user ,
                'j_password' => $ciscosdwanserver->password
            ],
            "verify" => false
        ]);

        //End of jsession id

        $cookieJar = $client->getConfig('cookies');
        $infoArray = $cookieJar->toArray();
        $cookie    = $infoArray[0]["Value"];

        $client= new \GuzzleHttp\Client(['cookies' => true]);
        $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
        $query = 'device';
        $response = $client->request('GET', $apihost . $query, [
                'headers'        => [
                   'Cookie'       => "JSESSIONID=".$cookie,
                ],
                "verify" => false
        ]);

        $jsondata = json_decode($response->getBody()->getContents());
        $summary = json_encode($jsondata->data);
        echo $summary;
    }

    /*
    Category: Cisco SDWAN
    Page: Network Detail System Status
    Section: load cpu & ram
    */
    public function networkloadcpumemory(Request $request){

        $dm=Crypt::decryptString(session('mymonitor_md'));

        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        //Get jsessionid
        $client = new \GuzzleHttp\Client(['cookies' => true]);
        $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
        $res = $client->request('POST', $apihost . "/j_security_check", [
            'form_params' => [
                'j_username' => $ciscosdwanserver->user ,
                'j_password' => $ciscosdwanserver->password
            ],
            "verify" => false
        ]);

        //End of jsession id

        $time     = $request->time;
        $deviceIP = $request->deviceIP;

        $cookieJar = $client->getConfig('cookies');
        $infoArray = $cookieJar->toArray();
        $cookie    = $infoArray[0]["Value"];

        $rawdata = [
            "query"=>[
                "condition"=>"AND",
                "rules"=>[
                    [
                        "value"=>[
                            "$time"
                        ],
                        "field"=>"entry_time",
                        "type"=>"date",
                        "operator"=>"last_n_hours"
                    ],
                    [
                        "field"=>"system_ip",
                        "operator"=>"in",
                        "type"=>"string",
                        "value"=>[
                            "$deviceIP"
                        ]
                    ]
                ]
            ],
            "aggregation" => [
            "histogram" => [
                "property" => "entry_time",
                "type" =>  "minute",
                "interval" =>  60,
                "order" => "asc"
                ]
            ],
            "size"=>2000
        ];

        $client= new \GuzzleHttp\Client(['cookies' => true]);
        $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
        $query = 'statistics/system';
        $response = $client->request('POST', $apihost . $query, [
                'headers'        => [
                   'Cookie'       => "JSESSIONID=".$cookie,
                   'Content-Type' => 'application/json'
                ],
                'json'    => $rawdata,
                "verify" => false
        ]);

        $myJSON = json_decode($response->getBody()->getContents());
        $devicesSpecs = $myJSON->data;
        $countRecords = count($devicesSpecs);
        $totalCpuPercentage = 0;
        $totalMemPercentage = 0;
        $cpuPercentage = [];
        $memPercentage = [];
        foreach($devicesSpecs as $devicesSpec){
            $totalCpuPercentage += $devicesSpec->cpu_user_new;
            $totalMemPercentage += $devicesSpec->mem_util;
            //convert time
            //$timestamp=$devicesSpec->entry_time;
            //echo gmdate("Y-m-d\TH:i:s\Z", $timestamp);
            //
            //$milliseconds = $devicesSpec->entry_time;
            //$timestamp = ($milliseconds/1000) + 25200;
            array_push($cpuPercentage,[
                //'x'=> $devicesSpec->entry_time,
                'x'=> $devicesSpec->entry_time + 25200000,
                'y'=> $devicesSpec->cpu_user_new,
            ]);
            array_push($memPercentage,[
                'x'=> $devicesSpec->entry_time + 25200000,
                'y'=> round($devicesSpec->mem_util * 100),
            ]);
        }
        if($countRecords == 0){
          $resArr = [
                'resTotalCpuPer' => 0,
                'resTotalMemPer' => 0,
          ];
        }else{
            $resTotalCpuPer = $totalCpuPercentage / $countRecords ;
            $resTotalMemPer = $totalMemPercentage / $countRecords * 100;
            $resArr = [
                'resTotalCpuPer' => Round($resTotalCpuPer,2),
                'resTotalMemPer' => Round($resTotalMemPer,2),
                'cpuPercentage' => $cpuPercentage,
                'memPercentage' => $memPercentage,
            ];
        }
        echo (json_encode($resArr));
    }

    /*
    Category: Cisco SDWAN
    Page: Network Detail Application DPI, One device
    Section: Main view
    */
    public function applicationdpi($deviceid){
        if (!Session::has('Monitor')||!Session::has('mymonitor_md')){
            $url = url('/');
            return redirect($url);
        }

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

        if($user->ciscosdwanuse!='1'){
            $url = url('/').'/admin/dashboard';
            return redirect($url);
        }

        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            $url = url('/').'/admin/ciscosdwan';
            return redirect($url);
        }else{
            //device/reboothistory/synced?deviceId='.$id
            return view('ciscosdwan.applicationdpi',compact('user','ciscosdwanserver','deviceid'));
        }
    }


    /*
    Category: Cisco SDWAN
    Page: Network Detail System Status
    Section: load application dpi
    */
    public function networkloadapplicationdpi(Request $request){

        $dm=Crypt::decryptString(session('mymonitor_md'));

        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        //Get jsessionid
        $client = new \GuzzleHttp\Client(['cookies' => true]);
        $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
        $res = $client->request('POST', $apihost . "/j_security_check", [
            'form_params' => [
                'j_username' => $ciscosdwanserver->user ,
                'j_password' => $ciscosdwanserver->password
            ],
            "verify" => false
        ]);

        //End of jsession id


        $cookieJar = $client->getConfig('cookies');
        $infoArray = $cookieJar->toArray();
        $cookie    = $infoArray[0]["Value"];

        $deviceId = $request->deviceId;
        $lastTime = $request->lastTime;
        $interval = (int)$request->interval;

        $rawdata = [
            "query" => [
              "condition" => "AND",
              "rules" => [
                [
                  "value" => [
                    "$lastTime"
                  ],
                  "field" => "entry_time",
                  "type" => "date",
                  "operator" => "last_n_hours"
                ],
                [
                  "value" => [
                    "$deviceId"
                  ],
                  "field" => "vdevice_name",
                  "type" => "string",
                  "operator" =>"in"
                  ]
                ]
              ],
              "aggregation" => [
              "field" => [
                [
                  "property" => "family",
                  "size" => 200,
                  "sequence" => 1
                ]
              ],
              "metrics" => [
                [
                  "property" => "octets",
                  "type" => "sum",
                  "order" => "desc"
                ]
              ],
              "histogram" => [
                "property" => "entry_time",
                "type" =>  "minute",
                "interval" =>  $interval,
                "order" => "asc"
              ]
            ]
        ];

        $client= new \GuzzleHttp\Client(['cookies' => true]);
        $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
        $query = 'statistics/dpi/aggregation';
        $response = $client->request('POST', $apihost . $query, [
                'headers'        => [
                   'Cookie'       => "JSESSIONID=".$cookie,
                   'Content-Type' => 'application/json'
                ],
                'json'    => $rawdata,
                "verify" => false
        ]);

        $myJSON = json_decode($response->getBody()->getContents());

        $dataArray = $myJSON->data;
        if(count($dataArray) == 0){
            return 'No data';
        }
        // Application name
        $applicationName = [$dataArray[0]->family];
        foreach ($dataArray as $data) {
            if(!in_array($data->family, $applicationName) ){
                array_push($applicationName, $data->family);
            }
        }

        $chartDataArray = [];
        $totalUsage = 0;
        foreach ($applicationName as $value => $name) {
            $abc = [];
            foreach($dataArray as $data){
                if($data->family == $name){
                    if(!in_array($name, $abc)){
                        array_push($abc,$name);
                    }
                  array_push($abc,[$data->entry_time,round($data->octets/1024,2)]);
                }
            }
            array_push($chartDataArray,$abc);
        }
      echo json_encode($chartDataArray);
    }


    /*
    Category: Cisco SDWAN
    Page: Network Detail events
    Section: Main view
    */
    public function networkevents($deviceid){

        if (!Session::has('Monitor')||!Session::has('mymonitor_md')){
            $url = url('/');
            return redirect($url);
        }

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

        if($user->ciscosdwanuse!='1'){
            $url = url('/').'/admin/dashboard';
            return redirect($url);
        }

        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            $url = url('/').'/admin/ciscosdwan';
            return redirect($url);
        }else{
            //Get jsessionid
            $client = new \GuzzleHttp\Client(['cookies' => true]);
            try {
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
                $res = $client->request('POST', $apihost . "/j_security_check", [
                  'form_params' => [
                      'j_username' => $ciscosdwanserver->user ,
                      'j_password' => $ciscosdwanserver->password
                  ],
                  "verify" => false
               ]);
            } catch(\GuzzleHttp\Exception\GuzzleException $e) {
                $url = url('/').'/admin/ciscosdwan';
                return redirect($url);
            }
            //End of jsession id

            $cookieJar = $client->getConfig('cookies');
            $infoArray = $cookieJar->toArray();
            $cookie    = $infoArray[0]["Value"];

            $rawdata = [
                "size" => 1000,
                "query" => [
                    "condition" => "AND",
                    "rules" => [
                        [
                            "value" => [
                                "24"
                            ],
                            "field" => "entry_time",
                            "type" => "date",
                            "operator" => "last_n_hours"
                        ],
                        [
                            "value" => [
                                "$deviceid"
                            ],
                            "field" => "system_ip",
                            "type"  => "string",
                            "operator" => "in"
                        ]
                    ]
                ]
            ];

            try{
                $client= new \GuzzleHttp\Client(['cookies' => true]);
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
                $query = 'event';
                // $response = $client->request('POST', $apihost . $query, [
                //     'headers'        => [
                //       'Cookie'       => $cookie,
                //       'Content-Type' => 'application/json',
                //     ],
                //     'json'    => $rawdata,
                //     "verify" => false
                // ]);

                $response = $client->request('POST', $apihost . $query, [
                    'headers'        => [
                        'Cookie'       => "JSESSIONID=".$cookie,
                        'Content-Type' => 'application/json',
                    ],
                    'json'    => $rawdata,
                    "verify" => false
                ]);

                //echo $cookie;
                //echo '<br>';
                //echo $apihost . $query;
                //echo '<br>';
                //dd($response->getBody()->getContents());

            } catch(\GuzzleHttp\Exception\GuzzleException $e) {
                $url = url('/').'/admin/ciscosdwan';
                return redirect($url);
            }

            $jsondata = json_decode($response->getBody()->getContents());

            if ($jsondata==null){
                $events = null;
                return view('ciscosdwan.events',compact('user','ciscosdwanserver','events','deviceid'));
            }else{
                $events = $jsondata->data;
                return view('ciscosdwan.events',compact('user','ciscosdwanserver','events','deviceid'));
            }
        }
    }

    /*
    Category: Cisco SDWAN
    Page: Network Detail connections
    Section: Main view
    */
    public function networkconnections($deviceid){

        if (!Session::has('Monitor')||!Session::has('mymonitor_md')){
            $url = url('/');
            return redirect($url);
        }

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

        if($user->ciscosdwanuse!='1'){
            $url = url('/').'/admin/dashboard';
            return redirect($url);
        }

        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            $url = url('/').'/admin/ciscosdwan';
            return redirect($url);
        }else{
            //Get jsessionid
            $client = new \GuzzleHttp\Client(['cookies' => true]);
            try {
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
                $res = $client->request('POST', $apihost . "/j_security_check", [
                  'form_params' => [
                      'j_username' => $ciscosdwanserver->user ,
                      'j_password' => $ciscosdwanserver->password
                  ],
                  "verify" => false
               ]);
            } catch(\GuzzleHttp\Exception\GuzzleException $e) {
                $url = url('/').'/admin/ciscosdwan';
                return redirect($url);
            }
            //End of jsession id

            $cookieJar = $client->getConfig('cookies');
            $infoArray = $cookieJar->toArray();
            $cookie    = $infoArray[0]["Value"];

            try{
                $client= new \GuzzleHttp\Client(['cookies' => true]);
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
                $query = "device/control/connections?deviceId=" . $deviceid;
                // $response = $client->request('GET', $apihost . $query, [
                //     'headers'        => [
                //       'Cookie'       => $cookie
                //     ],
                //     "verify" => false
                // ]);

                $response = $client->request('GET', $apihost . $query, [
                    'headers'        => [
                        'Cookie'       => "JSESSIONID=".$cookie,
                        'Content-Type' => 'application/json',
                    ],
                    "verify" => false
                ]);
            } catch(\GuzzleHttp\Exception\GuzzleException $e) {
                $url = url('/').'/admin/ciscosdwan';
                return redirect($url);
            }

            $jsondata = json_decode($response->getBody()->getContents());

            if ($jsondata==null){
                $connections = null;
                return view('ciscosdwan.connections',compact('user','ciscosdwanserver','connections','deviceid'));
            }else{
                $connections = $jsondata->data;
                return view('ciscosdwan.connections',compact('user','ciscosdwanserver','connections','deviceid'));
            }
        }
    }

    /*
    Category: Cisco SDWAN
    Page: Template
    Section: Main view
    */
    public function templates(){

        if (!Session::has('Monitor')||!Session::has('mymonitor_md')){
            $url = url('/');
            return redirect($url);
        }

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

        if($user->ciscosdwanuse!='1'){
            $url = url('/').'/admin/dashboard';
            return redirect($url);
        }

        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            $url = url('/').'/admin/ciscosdwan';
            return redirect($url);
        }else{
            //Get jsessionid
            $client = new \GuzzleHttp\Client(['cookies' => true]);
            try {
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
                $res = $client->request('POST', $apihost . "/j_security_check", [
                  'form_params' => [
                      'j_username' => $ciscosdwanserver->user ,
                      'j_password' => $ciscosdwanserver->password
                  ],
                  "verify" => false
               ]);
            } catch(\GuzzleHttp\Exception\GuzzleException $e) {
                $url = url('/').'/admin/ciscosdwan';
                return redirect($url);
            }
            //End of jsession id

            $cookieJar = $client->getConfig('cookies');
            $infoArray = $cookieJar->toArray();
            $cookie    = $infoArray[0]["Value"];

            try{
                $client= new \GuzzleHttp\Client(['cookies' => true]);
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
                $query = "template/device";
                $response = $client->request('GET', $apihost . $query, [
                    'headers'        => [
                        'Cookie'       => "JSESSIONID=".$cookie,
                        'Content-Type' => 'application/json',
                    ],
                    "verify" => false
                ]);
            } catch(\GuzzleHttp\Exception\GuzzleException $e) {
                $url = url('/').'/admin/ciscosdwan';
                return redirect($url);
            }

            $jsondata = json_decode($response->getBody()->getContents());

            if ($jsondata==null){
                $templates= null;
                return view('ciscosdwan.templates',compact('user'));
            }else{
                $templates = $jsondata->data;
                return view('ciscosdwan.templates',compact('user','templates'));
            }
        }
    }

    /*
    Category: Cisco SDWAN
    Page: Template Attach
    Section: Main view
    */
    public function templatesattach($templateid){

        if (!Session::has('Monitor')||!Session::has('mymonitor_md')){
            $url = url('/');
            return redirect($url);
        }

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

        if($user->ciscosdwanuse!='1'){
            $url = url('/').'/admin/dashboard';
            return redirect($url);
        }

        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            $url = url('/').'/admin/ciscosdwan/teamplates';
            return redirect($url);
        }else{
            $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
            //Get jsessionid
            $client = new \GuzzleHttp\Client(['cookies' => true]);
            try {

                $res = $client->request('POST', $apihost . "/j_security_check", [
                  'form_params' => [
                      'j_username' => $ciscosdwanserver->user ,
                      'j_password' => $ciscosdwanserver->password
                  ],
                  "verify" => false
               ]);
            } catch(\GuzzleHttp\Exception\GuzzleException $e) {
                $url = url('/').'/admin/ciscosdwan/templates';
                return redirect($url);
            }
            //End of jsession id

            $cookieJar = $client->getConfig('cookies');
            $infoArray = $cookieJar->toArray();
            $cookie    = $infoArray[0]["Value"];

            //
            $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
            $query = "template/device";
            $response = $client->request('GET', $apihost . $query, [
                'headers'        => ['Cookie' => "JSESSIONID=".$cookie],
                "verify" => false
            ]);

            $myJSON = json_decode($response->getBody()->getContents());
            if($myJSON == null){
                $url = url('/').'/admin/ciscosdwan/templates';
                return redirect($url);
            }
            // Lấy ra thông tin template đã chọn
            $datas = $myJSON->data;
            $selectedtemplate = null;
            foreach ($datas as $data) {
                if($data->templateId == $templateid){
                    $selectedtemplate = $data;
                }
            }

            //echo 'template đã chọn<br>';
            //print_r($selectedtemplate);

            // Lấy ra thông tin thiết bị đã nhúng template
            $query = "template/device/config/attached/" . $templateid;
            $response = $client->request('GET', $apihost . $query, [
               'headers'        => ['Cookie' => "JSESSIONID=".$cookie],
               "verify" => false
            ]);

            $myJSON = json_decode($response->getBody()->getContents());
            if($myJSON == null){
                $tmpAttachedDevices = null;
            }else{
                $tmpAttachedDevices = $myJSON->data;
            }

            //dd($tmpAttachedDevices);

            $AttachedDevices = [];
            if (count($tmpAttachedDevices)>0){
                foreach ($tmpAttachedDevices as $tmpAttachedDevice) {
                    //Nhét deviceid vào mảng
                    array_push($AttachedDevices, $tmpAttachedDevice->deviceIP);
                }
            }

            //echo '<br><br>Thiết bị đã attach template này <br>';
            //print_r($AttachedDevices);

            //Lấy toàn bộ thiết bị
            $query = 'device';
            $response = $client->request('GET', $apihost . $query, [
               'headers'        => ['Cookie' => "JSESSIONID=".$cookie],
               "verify" => false
            ]);

            $myJSON = json_decode($response->getBody()->getContents());
            if($myJSON == null){
                $tmmpAllDevices = null;
            }else{
                $tmpAllDevices = $myJSON->data;
            }


            $AllDevices = [];
            if (count($tmpAllDevices)>0){
                foreach ($tmpAllDevices as $tmpAllDevice) {
                    //Nhét deviceid vào mảng
                    array_push($AllDevices, $tmpAllDevice->deviceId);
                }
            }
            //echo '<br><br>Toàn bộ thiết bị<br>';
            //print_r($AllDevices);


            //echo '-----';
            //dd($AttachedDevices[0]['deviceid']);

            $AvailableDevices = [];
            $AvailableDevices=array_diff($AllDevices,$AttachedDevices);
            //echo '<br><br>Có thể attach<br>';
            //print_r($AvailableDevices);

            return view('ciscosdwan.attach',compact('user','selectedtemplate','AvailableDevices'));

        }
    }

    /*
    Category: Cisco SDWAN
    Page: Template Attach check
    Section: Function submit thiết bị muốn attach template và kiểm tra trạng thái
    */
    public function templatesattachcheck(Request $request){
        //echo 'ok';
        //$selectedDevices = $_POST['selectedDevices'];
        //$post = file_get_contents('php://input');
        //dd($post);
        $selectedtemplate = $request->templateId;
        $selectedDevices = $request->Devices;
        //echo $selectedDevices[0];
        if (!Session::has('Monitor')||!Session::has('mymonitor_md')){
            $url = url('/');
            return redirect($url);
        }

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

        if($user->ciscosdwanuse!='1'){
            $url = url('/').'/admin/dashboard';
            return redirect($url);
        }

        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        $client = new \GuzzleHttp\Client(['cookies' => true]);
        try {
            $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
            $res = $client->request('POST', $apihost . "/j_security_check", [
              'form_params' => [
                  'j_username' => $ciscosdwanserver->user ,
                  'j_password' => $ciscosdwanserver->password
              ],
              "verify" => false
           ]);
        } catch(\GuzzleHttp\Exception\GuzzleException $e) {
            $url = url('/').'/admin/ciscosdwan/teamplates';
            return redirect($url);
        }
        //End of jsession id

        $cookieJar = $client->getConfig('cookies');
        $infoArray = $cookieJar->toArray();
        $cookie    = $infoArray[0]["Value"];

        $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;

        //Bắt đầu xử lý
        if(empty($selectedDevices[0])){
            return -1; //Chưa chọn thiết bị để attach
        }

        $statusReturnArr = [];
        foreach ($selectedDevices as $item) {
            // Get UUID devices
            $response = $client->request('GET', $apihost . "device", [
                'headers'        => ['Cookie' => "JSESSIONID=".$cookie],
                "verify" => false
            ]);
            $myJSON = json_decode($response->getBody()->getContents());

            $arrDevice = $myJSON->data;
            foreach ($arrDevice as $device) {
                if($device->deviceId == $item){
                    $deviceIds = $device->uuid;
                }
            }

            // Lấy ra Input Device
            $deviceTemplateJSON = [
                "templateId" => $selectedtemplate,
                "deviceIds" =>[
                    $deviceIds,
                ],
                "isEdited" => false,
                "isMasterEdited" => false
            ];
            $response = $client->request('POST', $apihost . "template/device/config/input", [
               'headers'        => [
                    'Cookie'       => "JSESSIONID=".$cookie,
                    'Content-Type' => 'application/json',
                ],
                'json'    => $deviceTemplateJSON,
                "verify" => false
            ]);

            // Dữ liệu dạng json
            $myJSON = json_decode($response->getBody()->getContents());
            $deviceInput = $myJSON->data;
            // Attach Template Device
            $attachTemplateJson = [
                "deviceTemplateList" => [
                    [
                        "templateId" =>  $selectedtemplate,
                        "device" =>  $deviceInput,
                        "isEdited" => false,
                        "isMasterEdited" => false
                    ]
                ]
            ];

            $response = $client->request('POST', $apihost. "template/device/config/attachfeature", [
                'headers'        => [
                    'Cookie'       => "JSESSIONID=".$cookie,
                    'Content-Type' => 'application/json',
                ],
                'json'    => $attachTemplateJson,
                "verify" => false
            ]);

            $myJSON = json_decode($response->getBody()->getContents());
            $statusId = $myJSON->id;
            array_push($statusReturnArr, $statusId);
        }
        //dd($statusReturnArr);

        // Thực hiện push template
        $resultArray = [];
        foreach ($statusReturnArr as $statusId) {
            try{
                ////
                $response = $client->request('GET', $apihost . "/device/action/status/" . $statusId, [
                    "headers"        => ['Cookie'       => "JSESSIONID=".$cookie],
                    "verify" => false
                ]);
                $myJSON = json_decode($response->getBody()->getContents());

                if(isset($myJSON->data[0])){
                    $array = (array) $myJSON->data[0];
                    $status  = $array['status'];
                    $currentActivity = $array['currentActivity'];
                    $deviceIp      = $array['system-ip'];
                    array_push($resultArray, ['status' => $status,'currentActivity' => $currentActivity,'deviceIp' => $deviceIp]);
                }
                ////
            }catch(\GuzzleHttp\Exception\GuzzleException $e){
                array_push($resultArray,['status' => 'Can not attach to device','currentActivity' => 'Attach Failed','deviceIp' => '']);
            }
        }
        return json_encode($resultArray);

        //print_r($resultArray);
    }

    /*
    Category: Cisco SDWAN
    Page: Template Detach
    Section: Main view
    */
    public function templatesdetach($templateid){

        if (!Session::has('Monitor')||!Session::has('mymonitor_md')){
            $url = url('/');
            return redirect($url);
        }

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

        if($user->ciscosdwanuse!='1'){
            $url = url('/').'/admin/dashboard';
            return redirect($url);
        }

        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            $url = url('/').'/admin/ciscosdwan/teamplates';
            return redirect($url);
        }else{
            $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
            //Get jsessionid
            $client = new \GuzzleHttp\Client(['cookies' => true]);
            try {

                $res = $client->request('POST', $apihost . "/j_security_check", [
                  'form_params' => [
                      'j_username' => $ciscosdwanserver->user ,
                      'j_password' => $ciscosdwanserver->password
                  ],
                  "verify" => false
               ]);
            } catch(\GuzzleHttp\Exception\GuzzleException $e) {
                $url = url('/').'/admin/ciscosdwan/templates';
                return redirect($url);
            }
            //End of jsession id

            $cookieJar = $client->getConfig('cookies');
            $infoArray = $cookieJar->toArray();
            $cookie    = $infoArray[0]["Value"];

            //
            $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
            $query = "template/device";
            $response = $client->request('GET', $apihost . $query, [
                'headers'        => ['Cookie' => "JSESSIONID=".$cookie],
                "verify" => false
            ]);

            $myJSON = json_decode($response->getBody()->getContents());
            if($myJSON == null){
                $url = url('/').'/admin/ciscosdwan/templates';
                return redirect($url);
            }
            // Lấy ra thông tin template đã chọn
            $datas = $myJSON->data;
            $selectedtemplate = null;
            foreach ($datas as $data) {
                if($data->templateId == $templateid){
                    $selectedtemplate = $data;
                }
            }

            //echo 'template đã chọn<br>';
            //print_r($selectedtemplate);

            // Lấy ra thông tin thiết bị đã nhúng template
            $query = "template/device/config/attached/" . $templateid;
            $response = $client->request('GET', $apihost . $query, [
               'headers'        => ['Cookie' => "JSESSIONID=".$cookie],
               "verify" => false
            ]);

            $myJSON = json_decode($response->getBody()->getContents());
            if($myJSON == null){
                $tmpAttachedDevices = null;
            }else{
                $tmpAttachedDevices = $myJSON->data;
            }

            //dd($tmpAttachedDevices);

            $AvailableDevices = [];
            if (count($tmpAttachedDevices)>0){
                foreach ($AvailableDevices as $tmpAttachedDevice) {
                    //Nhét deviceid vào mảng
                    array_push($AvailableDevices, $tmpAttachedDevice->deviceIP);
                }
            }

            return view('ciscosdwan.detach',compact('user','selectedtemplate','AvailableDevices'));

        }
    }

    /*
    Category: Cisco SDWAN
    Page: Template detach check
    Section: Function submit thiết bị muốn detach template và kiểm tra trạng thái
    */
    public function templatesdetachcheck(Request $request){
        //echo 'ok';
        //$selectedDevices = $_POST['selectedDevices'];
        //$post = file_get_contents('php://input');
        //dd($post);
        $selectedtemplate = $request->templateId;
        $selectedDevices = $request->Devices;
        //echo $selectedDevices[0];
        if (!Session::has('Monitor')||!Session::has('mymonitor_md')){
            $url = url('/');
            return redirect($url);
        }

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

        if($user->ciscosdwanuse!='1'){
            $url = url('/').'/admin/dashboard';
            return redirect($url);
        }

        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        $client = new \GuzzleHttp\Client(['cookies' => true]);
        try {
            $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
            $res = $client->request('POST', $apihost . "/j_security_check", [
              'form_params' => [
                  'j_username' => $ciscosdwanserver->user ,
                  'j_password' => $ciscosdwanserver->password
              ],
              "verify" => false
           ]);
        } catch(\GuzzleHttp\Exception\GuzzleException $e) {
            $url = url('/').'/admin/ciscosdwan/teamplates';
            return redirect($url);
        }
        //End of jsession id

        $cookieJar = $client->getConfig('cookies');
        $infoArray = $cookieJar->toArray();
        $cookie    = $infoArray[0]["Value"];

        $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;

        //Bắt đầu xử lý
        if(empty($selectedDevices[0])){
            return -1; //Chưa chọn thiết bị để attach
        }

        $statusReturnArr = [];
        foreach ($selectedDevices as $item) {
            $response = $client->request('GET', $apihost . "device", [
                'headers'        => ['Cookie' => "JSESSIONID=".$cookie],
                "verify" => false
            ]);
            $myJSON = json_decode($response->getBody()->getContents());

            $arrDevice = $myJSON->data;
            foreach ($arrDevice as $device) {
                if($device->deviceId == $item){
                    $deviceIds = $device->uuid;
                    $deviceType = $device->personality;
                    $deviceId = $device->deviceId;
                }
            }
            // return $deviceType;
            $detachTemplateJson = [
                "deviceType" => $deviceType,
                "devices" => [
                    [
                        "deviceId" => $deviceIds,
                        "deviceIP" => $deviceId,
                    ]
                ]
            ];

            $response = $client->request('POST', $apihost . "template/config/device/mode/cli", [
                'headers'        => [
                    'Cookie'       => "JSESSIONID=".$cookie,
                    'Content-Type' => 'application/json',
                ],
                'json'    => $detachTemplateJson,
                "verify" => false
            ]);

            $myJSON = json_decode($response->getBody()->getContents());
            $statusId = $myJSON->id;
            array_push($statusReturnArr, $statusId);
        }
        //dd($statusReturnArr);

        // Thực hiện push template
        $resultArray = [];
        foreach ($statusReturnArr as $statusId) {
            try{
                $response = $client->request('GET', $apihost . "/device/action/status/".$statusId, [
                    "headers"        => ['Cookie'       => "JSESSIONID=".$cookie],
                    "verify" => false
                ]);
                $myJSON = json_decode($response->getBody()->getContents());
                if(isset($myJSON->data[0])){
                    $array = (array) $myJSON->data[0];
                    $status  = $array['status'];
                }
            }catch(\GuzzleHttp\Exception\GuzzleException $e){
                array_push($resultArray,['status' => 'Can not detach the template from device']);
            }
        }

        return json_encode($resultArray);
    }

    /*
    Category: Cisco SDWAN
    Page: Schedules
    Section: Main view
    */
    public function schedules(){

        if (!Session::has('Monitor')||!Session::has('mymonitor_md')){
            $url = url('/');
            return redirect($url);
        }

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

        if($user->ciscosdwanuse!='1'){
            $url = url('/').'/admin/dashboard';
            return redirect($url);
        }

        $schedules = DB::table('tbl_ciscosdwanschedules')
        ->where([
            ['domainid', '=', $dm]
        ])->get();

        return view('ciscosdwan.schedules',compact('user','schedules'));
    }


    /*
    Category: Cisco SDWAN
    Page: Schedules
    Section: Function to return devices and templates to ajax call back from add new button click
    */
    public function getdeviceandtemplate(){

        if (!Session::has('Monitor')||!Session::has('mymonitor_md')){
            $url = url('/');
            return redirect($url);
        }

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

        if($user->ciscosdwanuse!='1'){
            $url = url('/').'/admin/dashboard';
            return redirect($url);
        }

        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        if($ciscosdwanserver->hostname==''){
        }else{
            //Get jsessionid
            $client = new \GuzzleHttp\Client(['cookies' => true]);
            try {
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
                $res = $client->request('POST', $apihost . "/j_security_check", [
                  'form_params' => [
                      'j_username' => $ciscosdwanserver->user ,
                      'j_password' => $ciscosdwanserver->password
                  ],
                  "verify" => false
               ]);
            } catch(\GuzzleHttp\Exception\GuzzleException $e) {
            }
            //End of jsession id

            $cookieJar = $client->getConfig('cookies');
            $infoArray = $cookieJar->toArray();
            $cookie    = $infoArray[0]["Value"];

            $jsonarray = [];

            try{
                $client= new \GuzzleHttp\Client(['cookies' => true]);
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
                $query = "template/device";
                $response = $client->request('GET', $apihost . $query, [
                    'headers'        => [
                        'Cookie'       => "JSESSIONID=".$cookie,
                        'Content-Type' => 'application/json',
                    ],
                    "verify" => false
                ]);
                ///
                $jsondata = json_decode($response->getBody()->getContents());

                $i = 0;
                if ($jsondata==null){
                    $templates= null;
                }else{
                    $templates = $jsondata->data;
                    foreach ($templates as $template){
                        //$jsonarray['templates'][$i++] = $template->templateId;
                        $jsonarray['templates'][$i++] = $template->templateName;
                    }
                }
                ///
            } catch(\GuzzleHttp\Exception\GuzzleException $e) {
            }

            try{
                $client= new \GuzzleHttp\Client(['cookies' => true]);
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
                $query = "device";
                $response = $client->request('GET', $apihost . $query, [
                    'headers'        => [
                        'Cookie'       => "JSESSIONID=".$cookie,
                        'Content-Type' => 'application/json',
                    ],
                    "verify" => false
                ]);
                ///
                $jsondata = json_decode($response->getBody()->getContents());

                $i = 0;
                if ($jsondata==null){
                    $devices= null;
                }else{
                    $devices = $jsondata->data;
                    foreach ($devices as $device){
                        //$jsonarray['devices'][$i++] = $device->deviceId;
                        $tmp = 'host-name';
                        $jsonarray['devices'][$i++] = $device->$tmp;
                    }
                }
                ///
            } catch(\GuzzleHttp\Exception\GuzzleException $e) {
            }

            $json = json_encode($jsonarray);
            echo $json;
        }
    }

    /*
    Category: Cisco SDWAN
    Page: Schedules
    Section: Function insert schedule into db
    */
    public function scheduledoaddnew(Request $request){

        if (!Session::has('Monitor')||!Session::has('mymonitor_md')){
            $url = url('/');
            return redirect($url);
        }

        $dm=Crypt::decryptString(session('mymonitor_md'));
        DB::table('tbl_ciscosdwanschedules')
        ->insert(
            [
                'name' => $request->name,
                'time' => $request->time,
                'deviceid' => $request->deviceid,
                'templateid' => $request->templateid,
                'domainid' => $dm
            ]
        );
    }

    /*
    Category: Cisco SDWAN
    Page: Schedules
    Section: Function delete schedule from db
    */
    public function scheduledodelete(Request $request){

        if (!Session::has('Monitor')||!Session::has('mymonitor_md')){
            $url = url('/');
            return redirect($url);
        }

        $dm=Crypt::decryptString(session('mymonitor_md'));
        DB::table('tbl_ciscosdwanschedules')
        ->where('id', $request->id)
        ->delete();
    }


    /*
    Category: Cisco SDWAN
    Page: Bandwidth Forecasting
    Section: Main view
    */
    public function forecast(){

        if (!Session::has('Monitor')||!Session::has('mymonitor_md')){
            $url = url('/');
            return redirect($url);
        }

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

        if($user->ciscosdwanuse!='1'){
            $url = url('/').'/admin/dashboard';
            return redirect($url);
        }

        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            $url = url('/').'/admin/ciscosdwan';
            return redirect($url);
        }else{
            //Get jsessionid
            $client = new \GuzzleHttp\Client(['cookies' => true]);
            try {
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
                $res = $client->request('POST', $apihost . "/j_security_check", [
                  'form_params' => [
                      'j_username' => $ciscosdwanserver->user ,
                      'j_password' => $ciscosdwanserver->password
                  ],
                  "verify" => false
               ]);
            } catch(\GuzzleHttp\Exception\GuzzleException $e) {
                $url = url('/').'/admin/ciscosdwan';
                return redirect($url);
            }
            //End of jsession id

            $cookieJar = $client->getConfig('cookies');
            $infoArray = $cookieJar->toArray();
            $cookie    = $infoArray[0]["Value"];

            try{
                $client= new \GuzzleHttp\Client(['cookies' => true]);
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
                $query = 'device';
                $response = $client->request('GET', $apihost . $query, [
                        'headers'        => [
                           'Cookie'       => "JSESSIONID=".$cookie,
                        ],
                        "verify" => false
                ]);
            } catch(\GuzzleHttp\Exception\GuzzleException $e) {
                $url = url('/').'/admin/ciscosdwan';
                return redirect($url);
            }

            $jsondata = json_decode($response->getBody()->getContents());

            if ($jsondata==null){
                $devices = null;
                return view('ciscosdwan.forecast',compact('user','devices'));
            }else{
                $devices = $jsondata->data;
                return view('ciscosdwan.forecast',compact('user','devices'));
            }
        }
    }

    /*
    Category: Cisco SDWAN
    Page: Bandwidth Forecasting
    Section: Function to return data to ajax call back
    */
    public function bandwidthvalue(Request $request){

        if (!Session::has('Monitor')||!Session::has('mymonitor_md')){
            $url = url('/');
            return redirect($url);
        }

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

        if($user->ciscosdwanuse!='1'){
            $url = url('/').'/admin/dashboard';
            return redirect($url);
        }

        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        if($ciscosdwanserver->hostname==''){
            $url = url('/').'/admin/ciscosdwan';
            return redirect($url);
        }else{
            //Get jsessionid
            $client = new \GuzzleHttp\Client(['cookies' => true]);
            try {
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
                $res = $client->request('POST', $apihost . "/j_security_check", [
                  'form_params' => [
                      'j_username' => $ciscosdwanserver->user ,
                      'j_password' => $ciscosdwanserver->password
                  ],
                  "verify" => false
               ]);
            } catch(\GuzzleHttp\Exception\GuzzleException $e) {
                $url = url('/').'/admin/ciscosdwan';
                return redirect($url);
            }
            //End of jsession id

            $cookieJar = $client->getConfig('cookies');
            $infoArray = $cookieJar->toArray();
            $cookie    = $infoArray[0]["Value"];

            $totaldata = [];
            $totaldatajson = '';
            $heso = $request->range; //Nếu tommorow thì lấy 11 ngày trở về trước

            for($i= $heso; $i<=0; $i++){
                //
                try{
                    ////Đổi date time ra timestamp
                    $d = strtotime($i.' day', time());

                    $query = 'data/device/statistics/interfacestatistics?startDate=' . date('Y-m-d', strtotime($i.' day', time())).'T00:00:00&endDate=' . date('Y-m-d', strtotime($i. ' day', time())).'T23:59:59';

                    $client= new \GuzzleHttp\Client(['cookies' => true]);
                    $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port . $ciscosdwanserver->basestring;
                    $response = $client->request('GET', $apihost . $query, [
                            'headers'        => [
                               'Cookie'       => "JSESSIONID=".$cookie,
                            ],
                            "verify" => false
                    ]);
                    $jsondata = json_decode($response->getBody()->getContents());
                    $data = $jsondata->data;

                    $rxcounter = 0;
                    $txcounter = 0;
                    $rxbandwidth = 0;
                    $txbandwidth = 0;
                    $rxavgbandwidth = 0;
                    $txavgbandwidth = 0;

                    foreach ($data as $item) {
                      $array = (array) $item;
                      if (($array['vmanage_system_ip']=='1.1.1.81')){
                        if ($array['rx_kbps']!='0'){
                            $rxcounter = $rxcounter + 1;
                            $rxbandwidth = $rxbandwidth + $array['rx_kbps'];
                        }
                        if ($array['tx_kbps']!='0'){
                            $txcounter = $txcounter + 1;
                            $txbandwidth = $txbandwidth + $array['tx_kbps'];
                        }
                      }
                    }

                    $rxcounter = ($rxcounter==0?1:$rxcounter);
                    $rxavgbandwidth = round($rxbandwidth/$rxcounter,2);
                    $txcounter = ($txcounter==0?1:$txcounter);
                    $txavgbandwidth = round($txbandwidth/$txcounter,2);

                    $totaldata['travg'][$i + abs($heso)] = $rxavgbandwidth;
                    $totaldata['txavg'][$i + abs($heso)] = $txavgbandwidth;
                    $totaldata['time'][$i + abs($heso)] = $d;

                } catch(\GuzzleHttp\Exception\GuzzleException $e) {
                }
                //
            }

            $trforecast = trader_tsf($totaldata['travg'],2);
            $txforecast = trader_tsf($totaldata['txavg'],2);
            try{
                $totaldata['trforecast'] = $trforecast;
                $totaldata['txforecast'] = $txforecast;
            }catch (Exception $e) {

            }


            $totaldatajson = json_encode($totaldata);
            echo $totaldatajson;

        }
    }

    public function reporttotal(){
        $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

        if($ciscosdwanserver->hostname==''){
            return 'N/A';
        }else{
            /////////////////////////////////////
            //Get cookie first
            $ciscosdwanserver = DB::table('tbl_ciscosdwanservers')
                ->where([
                    ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
                ])->first();

            if($ciscosdwanserver->hostname==''){
                return 'N/A';
            }else {
                $apihost = $ciscosdwanserver->secures . "://" . $ciscosdwanserver->hostname . ":" . $ciscosdwanserver->port;
                $query = "/j_security_check";
                $response = Http::withBasicAuth($ciscosdwanserver->user, $ciscosdwanserver->password)->Get($apihost . $query);
                $cookieJar = $response->cookies;
                $data = $cookieJar->toArray($cookieJar);
                //dd($data[0]['Value']);
                $j_ssesion = $data[0]['Value'];

                //region collect data
                // get device
                $vsmart = 0;
                $vedge = 0;
                $vbond = 0;
                $vmanage = 0;
                $apihost = $ciscosdwanserver->secures . "://" . $ciscosdwanserver->hostname . ":" . $ciscosdwanserver->port . $ciscosdwanserver->basestring;
                $query = "device";
                $response = Http::withBasicAuth($ciscosdwanserver->user, $ciscosdwanserver->password, [
                    'form_params' => [
                        'j_ssesion' => $j_ssesion
                    ]])->Get($apihost . $query);

                $myJSON = json_decode($response->getBody()->getContents());
                if ($myJSON != null) {
                    $dataArray = $myJSON->data;
                    //echo(json_encode($dataArray));
                    //dd($dataArray);
                    foreach ($dataArray as $item) {
                        if (strcmp($item->personality, 'vsmart') == 0) {
                            $vsmart += 1;
                        }
                        if (strcmp($item->personality, 'vbond') == 0) {
                            $vbond += 1;
                        }
                        if (strcmp($item->personality, 'vmanage') == 0) {
                            $vmanage += 1;
                        }
                        if (strcmp($item->personality, 'vedge') == 0) {
                            $vedge += 1;
                        }
                    }
                }

                $dataDevice = array(
                    array("category" => "Device", "vsmart" => $vsmart, "vbond" => $vbond, "vmanage" => $vmanage, 'vedge' => $vedge),
                );

                // wan edge
                $total = 0;
                $authorized = 0;
                $deployed = 0;
                $staging = 0;
                $apihost = $ciscosdwanserver->secures . "://" . $ciscosdwanserver->hostname . ":" . $ciscosdwanserver->port . $ciscosdwanserver->basestring;
                $query = "device/vedgeinventory/summary";
                $response = Http::withBasicAuth($ciscosdwanserver->user, $ciscosdwanserver->password, [
                    'form_params' => [
                        'j_ssesion' => $j_ssesion
                    ]])->Get($apihost . $query);

                $myJSON = json_decode($response->getBody()->getContents());
                $data = $myJSON->data;
                if ($myJSON != null) {
                    foreach ($data as $item) {
                        if (strcmp($item->name, 'Total') == 0) {
                            $total = $item->value;
                        }
                        if (strcmp($item->name, 'Authorized') == 0) {
                            $authorized = $item->value;
                        }
                        if (strcmp($item->name, 'Deployed') == 0) {
                            $deployed = $item->value;
                        }
                        if (strcmp($item->name, 'Staging') == 0) {
                            $staging = $item->value;
                        }
                    }
                }
                $dataWanEdge = array(
                    array("category" => "WanEdge", "total" => $total, "authorized" => $authorized, "deployed" => $deployed, 'staging' => $staging),
                );

                //dd($dataWanEdge);

                // Site Health
                $upcount = 0;
                $warningcount = 0;
                $downcount = 0;
                $apihost = $ciscosdwanserver->secures . "://" . $ciscosdwanserver->hostname . ":" . $ciscosdwanserver->port . $ciscosdwanserver->basestring;
                $query = "device/bfd/sites/summary";
                $response = Http::withBasicAuth($ciscosdwanserver->user, $ciscosdwanserver->password, [
                    'form_params' => [
                        'j_ssesion' => $j_ssesion
                    ]])->Get($apihost . $query);

                $myJSON = json_decode($response->getBody()->getContents());
                if ($myJSON != null) {
                    $data = $myJSON->data;
                    foreach ($data[0]->statusList as $item) {
                        if (strcmp($item->status, 'up') == 0) {
                            $upcount = $item->count;
                        }
                        if (strcmp($item->status, 'warning') == 0) {
                            $warningcount = $item->count;
                        }
                        if (strcmp($item->status, 'down') == 0) {
                            $downcount = $item->count;
                        }
                    }
                }
                $dataSiteHealth = array(
                    array("category" => "SiteHealth", "up" => $upcount, "warning" => $warningcount, "down" => $downcount),
                );

                //Transport Interface
                $less_than_10_mbps = 0;
                $from10_mbps_100_mbps = 0;
                $from100_mbps_500_mbps = 0;
                $greater_than_500_mbps = 0;
                $apihost = $ciscosdwanserver->secures . "://" . $ciscosdwanserver->hostname . ":" . $ciscosdwanserver->port . $ciscosdwanserver->basestring;
                $query = "device/tlocutil";
                $response = Http::withBasicAuth($ciscosdwanserver->user, $ciscosdwanserver->password, [
                    'form_params' => [
                        'j_ssesion' => $j_ssesion
                    ]])->Get($apihost . $query);

                $myJSON = json_decode($response->getBody()->getContents());
                if ($myJSON != null) {
                    $data = $myJSON->data;
                    foreach ($data as $item) {
                        if (strcmp($item->name, 'less_than_10_mbps') == 0) {
                            $less_than_10_mbps = $item->value;
                        }
                        if (strcmp($item->name, '10_mbps_100_mbps') == 0) {
                            $from10_mbps_100_mbps = $item->value;
                        }
                        if (strcmp($item->name, '100_mbps_500_mbps') == 0) {
                            $from100_mbps_500_mbps = $item->value;
                        }
                        if (strcmp($item->name, 'greater_than_500_mbps') == 0) {
                            $greater_than_500_mbps = $item->value;
                        }
                    }
                }
                $dataTransportInt = array(
                    array("category" => "TransportInt", "less_than_10_mbps" => $less_than_10_mbps, "10_mbps_100_mbps" => $from10_mbps_100_mbps, "100_mbps_500_mbps" => $from100_mbps_500_mbps, 'greater_than_500_mbps' => $greater_than_500_mbps),
                );

                //dd($myJSON);

                //endregion

            }
            return view('ciscosdwan.report',compact('dataDevice','dataWanEdge','dataSiteHealth','dataTransportInt'));
        }


    }

}
