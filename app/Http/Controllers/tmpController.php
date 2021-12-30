<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use DB;
use Illuminate\Support\Facades\Crypt;
use DateTime;
use DateTimeZone;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;


class tmpController extends Controller
{
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
                $apihost = $ciscosdwanserver->secures."://". $ciscosdwanserver->hostname.":". $ciscosdwanserver->port;
                //$query = "/j_security_check";
                //$response = Http::withBasicAuth($ciscosdwanserver->user,$ciscosdwanserver->password)->Get($apihost . "/j_security_check");
                //$cookieJar = $response->cookies;
                //$data = $cookieJar->toArray($cookieJar);
                //dd($data[0]['Value']);
                //$j_ssesion = $data[0]['Value'];
                $client = new \GuzzleHttp\Client(['cookies' => true]);
		        try {
		           $res = $client->request('POST', "https://sandboxsdwan.cisco.com:8443/j_security_check", [
		              'form_params' => [
		                  'j_username' => 'devnetuser' , 
		                  'j_password' => 'Cisco123!'
		              ],
		              "verify" => false
		           ]);
		        } catch (RequestException $e) {
		            dd('There are somthing wrong with your api server');
		        }
		        $cookieJar = $client->getConfig('cookies');
		        $infoArray = $cookieJar->toArray();
		        $cookie    = $infoArray[0]["Value"];

		        echo($cookie);
		        echo '<br>';

              	/*
				Chạy thống kê
              	*/
				$query1 = [
	                "query"=>[
	                  "condition"=>"AND",
	                  "rules"=>[
	                    [
	                      "value"=>[
	                        "600"
	                      ],
	                      "field"=>"entry_time",
	                      "type"=>"date",
	                      "operator"=>"last_n_hours"
	                    ]
	                  ]
	                ],
	                "size"=>10
	              ];

	               

	              $client1= new \GuzzleHttp\Client(['cookies' => true]);
	              $response1 = $client1->request('POST', 'https://sandboxsdwan.cisco.com:8443/dataservice/alarms', [
	                    'headers'        => [
	                       'Cookie'       => "JSESSIONID=".$cookie,
	                       'Content-Type' => 'application/json',
	                       'Accept' => 'application/json',
	                    ],
	                    'json'    => $query1,
	                    "verify" => false
	              ]);


	            dd($response1->getBody()->getContents());
              	/*Kết thúc thống kê*/
            } 
        }
    }
}
