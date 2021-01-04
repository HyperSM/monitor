<?php

namespace App\Http\Controllers;

//use CodeDredd\Soap\Client\Request;
use DateTime;
use DB;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Session;
use function Clue\StreamFilter\append;


date_default_timezone_set('Asia/Ho_Chi_Minh');


class centreonController extends Controller
{
    public function dashboard()
    {
        if (!Session::has('Monitor') || !Session::has('mymonitor_md')) {
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

        // get info centreonserver
        $centreonserver = DB::table('tbl_centreonservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

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


        $refreshrate = $this->getrefreshrate();
        return view('centreon.dashboard', compact('domain', 'user','hosts','refreshrate'));
    }

    //region Host Screen
    /*
   Page: Hosts -> List Hosts
   Section: Main view
   */
    public function Hosts()
    {
        //region auth
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

        // get info centreonserver
        $centreonserver = DB::table('tbl_centreonservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

        $authen_key = "";
        $client = new \GuzzleHttp\Client(['cookies' => true]);
        try {

            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=authenticate", [
                'form_params' => [
                    'username' => $centreonserver->user,
                    'password' => $centreonserver->password
                ],
                //"verify" => false
            ]);

            $authen_key = json_decode($res->getBody())->authToken;

        } catch (RequestException $e) {
            return view('centreon.errorpage',compact('user'));
        }
        //endregion
        $json = [
            "action" => "show",
            "object" => "host"
        ];
        if ($authen_key != "") {
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                "headers" => [
                    "Content-Type" => "application/json",
                    "centreon-auth-token" => $authen_key
                ],
                'json' => $json,
                "verify" => false
            ]);

            $hosts = json_decode($res->getBody());
            $hosts = $hosts->result;
            //dd($hosts);
            return view('centreon.hosts', compact('user', 'hosts'));
        }


    }

    public function serverconfig()
    {
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

    public function centreonserversubmit()
    {
        $server = DB::table('tbl_centreonservers')
            ->where('domainid', '=', Crypt::decryptString(session('mymonitor_md')))
            ->first();
        if (empty($server)) {
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

        } else {
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
        $url = url('/') . '/admin/centreon';
        return redirect($url);
    }

    public function addHost()
    {
        if (!Session::has('Monitor')) {
            $url = url('/');
            return redirect($url);
        }

        $dm = Crypt::decryptString(session('mymonitor_md'));
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
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=authenticate", [
                'form_params' => [
                    'username' => $centreonserver->user,
                    'password' => $centreonserver->password
                ],
                //"verify" => false
            ]);

            $authen_key = json_decode($res->getBody())->authToken;
        } catch (RequestException $e) {
            return view('centreon.errorpage',compact('user'));
        }

        $json = [
            "action" => "show",
            "object" => "INSTANCE"
        ];


        $client = new \GuzzleHttp\Client(['cookies' => true]);

        $response = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
            'headers' => [
                'centreon-auth-token' => $authen_key,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => $json,
            "verify" => false
        ]);

        $pollers = json_decode($response->getBody());
        $pollers = $pollers->result;
        $err_msg = '';
        return view('centreon.addhost', compact('user', 'err_msg', 'pollers'));
    }

    public function addhostsubmit()
    {
        if (!Session::has('Monitor')) {
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';
        $dm = Crypt::decryptString(session('mymonitor_md'));

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
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=authenticate", [
                'form_params' => [
                    'username' => $centreonserver->user,
                    'password' => $centreonserver->password
                ],
                //"verify" => false
            ]);

            $authen_key = json_decode($res->getBody())->authToken;
        } catch (RequestException $e) {
            return view('centreon.errorpage',compact('user'));
        }

        $hostname = Request("hostname");
        $alias = Request("alias");
        $address = Request("address");
        $templates = "";
        $poller = Request("poller");

        try {
            $json = [
                "action" => "add",
                "object" => "host",
                "values" => $hostname . ";" . $alias . ";" . $address . ";" . $templates . ";" . $poller . ";"
            ];
            $response = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                'headers' => [
                    'centreon-auth-token' => $authen_key,
                    'Content-Type' => 'application/json',
                ],
                'json' => $json,
                "verify" => false
            ]);

            // get param
            $max_check_item = Request("max_check_item");
            $check_interval = Request("check_interval");
            $retry_check_interval = Request("retry_check_interval");

            if ($max_check_item != "") {
                $param1 = [
                    "action" => "setparam",
                    "object" => "host",
                    "values" => $hostname . ";" . "max_check_item" . ";" . $max_check_item
                ];

                $result1 = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                    'headers' => [
                        'centreon-auth-token' => $authen_key,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                    'json' => $param1,
                    "verify" => false
                ]);


            }

            if ($check_interval != "") {
                $param2 = [
                    "action" => "setparam",
                    "object" => "host",
                    "values" => $hostname . ";" . "check_interval" . ";" . $check_interval
                ];
                $result2 = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                    'headers' => [
                        'centreon-auth-token' => $authen_key,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                    'json' => $param2,
                    "verify" => false
                ]);
            }

            if ($retry_check_interval != "") {
                $param3 = [
                    "action" => "setparam",
                    "object" => "host",
                    "values" => $hostname . ";" . "retry_check_interval" . ";" . $retry_check_interval
                ];
                $result3 = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                    'headers' => [
                        'centreon-auth-token' => $authen_key,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                    'json' => $param3,
                    "verify" => false
                ]);
            }
            $err_msg = "insert new host successfull";

        } catch (RequestException $e) {
            $err_msg = "Error while insert host";
        }

        return redirect()->action('centreonController@hosts');
    }

    public function deletehost($name)
    {

        if (!Session::has('Monitor')) {
            $url = url('/');
            return redirect($url);
        }

        $dm = Crypt::decryptString(session('mymonitor_md'));
        $err_msg = '';

        $user = DB::table('tbl_accounts')
            ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
            ->where([
                ['tbl_accounts.username', '=', session('mymonitor_userid')]
            ])->first();

        return view('centreon.deletehost', compact('user', 'name', 'err_msg'));

    }

    public function deletehostsubmit($name)
    {
        $err_msg = '';
        if (!Session::has('Monitor')) {
            $url = url('/');
            return redirect($url);
        }

        $dm = Crypt::decryptString(session('mymonitor_md'));
        $centreonserver = DB::table('tbl_centreonservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

        $authen_key = "";
        $client = new \GuzzleHttp\Client(['cookies' => true]);

        try {
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=authenticate", [
                'form_params' => [
                    'username' => $centreonserver->user,
                    'password' => $centreonserver->password
                ],
                //"verify" => false
            ]);

            $authen_key = json_decode($res->getBody())->authToken;
        } catch (RequestException $e) {
            return view('centreon.errorpage',compact('user'));
        }

        $param = Request('name');

        if ($authen_key != "") {
            $json = [
                "action" => "del",
                "object" => "host",
                "values" => $name
            ];


            try {
                $response = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                    'headers' => [
                        'centreon-auth-token' => $authen_key,
                        'Content-Type' => 'application/json',
                    ],
                    'json' => $json,
                    "verify" => false
                ]);

            } catch (RequestException $e) {
                $err_msg = "Cannot delete host";
            }
        }

        $json1 = [
            "action" => "show",
            "object" => "host"
        ];

        if ($authen_key != "") {
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                "headers" => [
                    "Content-Type" => "application/json",
                    "centreon-auth-token" => $authen_key
                ],
                'json' => $json1,
                "verify" => false
            ]);

            $user = DB::table('tbl_accounts')
                ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
                ->where([
                    ['tbl_accounts.username', '=', session('mymonitor_userid')]
                ])->first();

            $hosts = json_decode($res->getBody());
            $hosts = $hosts->result;
            //dd($hosts);
        }
        return view('centreon.hosts', compact('hosts', 'user', 'err_msg'));
    }

    public function edithost($id)
    {
        //region auth
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
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=authenticate", [
                'form_params' => [
                    'username' => $centreonserver->user,
                    'password' => $centreonserver->password
                ],
                //"verify" => false
            ]);

            $authen_key = json_decode($res->getBody())->authToken;
        } catch (RequestException $e) {
            return view('centreon.errorpage',compact('user'));
        }
        //endregion

        if ($authen_key != "") {
            $json = [
                "action" => "show",
                "object" => "host"
            ];
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                "headers" => [
                    "Content-Type" => "application/json",
                    "centreon-auth-token" => $authen_key
                ],
                'json' =>$json,
                "verify" => false
            ]);

            $hosts = json_decode($res->getBody());
            $hosts = $hosts->result;
        }
        $id = Request("id");

        $err_msg = "";
        // get one item
        foreach ($hosts as $k => $val) {
            if ($val->id != $id) {
                unset($hosts[$k]);
            }
        }
        $host = reset($hosts);

        return view("centreon.edithost", compact("host", "user", "err_msg"));
    }

    public function edithostsubmit()
    {
        $err_msg = '';
        if (!Session::has('Monitor')) {
            $url = url('/');
            return redirect($url);
        }

        $dm = Crypt::decryptString(session('mymonitor_md'));
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
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=authenticate", [
                'form_params' => [
                    'username' => $centreonserver->user,
                    'password' => $centreonserver->password
                ],
                //"verify" => false
            ]);

            $authen_key = json_decode($res->getBody())->authToken;
        } catch (RequestException $e) {
            return view('centreon.errorpage',compact('user'));
        }

        if ($authen_key != "") {
            //region update field
            // get param
            $hostname = Request('hidden_hostname');

            $name = Request("name");
            $alias = Request("alias");
            $address = Request("address");
            $poller = Request("poller");
            $max_check_item = Request("max_check_item");
            $check_interval = Request("check_interval");
            $retry_check_interval = Request("retry_check_interval");

            $param1 = [
                "action" => "setparam",
                "object" => "host",
                "values" => $hostname . ";" . "max_check_attempts" . ";" . $max_check_item
            ];

            $result1 = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                'headers' => [
                    'centreon-auth-token' => $authen_key,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $param1,
                "verify" => false
            ]);

            $param2 = [
                "action" => "setparam",
                "object" => "host",
                "values" => $hostname . ";" . "check_interval" . ";" . $check_interval
            ];
            $result2 = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                'headers' => [
                    'centreon-auth-token' => $authen_key,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $param2,
                "verify" => false
            ]);

            $param3 = [
                "action" => "setparam",
                "object" => "host",
                "values" => $hostname . ";" . "retry_check_interval" . ";" . $retry_check_interval
            ];
            $result3 = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                'headers' => [
                    'centreon-auth-token' => $authen_key,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $param3,
                "verify" => false
            ]);

            $param4 = [
                "action" => "setparam",
                "object" => "host",
                "values" => $hostname . ";" . "alias" . ";" . $alias
            ];

            $result4 = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                'headers' => [
                    'centreon-auth-token' => $authen_key,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $param4,
                "verify" => false
            ]);

            $param5 = [
                "action" => "setparam",
                "object" => "host",
                "values" => $hostname . ";" . "address" . ";" . $address
            ];
            $result6 = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                'headers' => [
                    'centreon-auth-token' => $authen_key,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $param5,
                "verify" => false
            ]);

            $param6 = [
                "action" => "setparam",
                "object" => "host",
                "values" => $hostname . ";" . "name" . ";" . $name
            ];

            $result5 = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                'headers' => [
                    'centreon-auth-token' => $authen_key,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $param6,
                "verify" => false
            ]);

            // $results = \GuzzleHttp\Promise\settle([$result1,$result2,$result3,$result4, $result5,$result6])->wait();
            //sleep(3);
            //return redirect()->action('centreonController@hosts');

            $err_msg = "update success";
            //endregion

            //region reload hosts
            $json = [
                "action" => "show",
                "object" => "host"
            ];

            if ($authen_key != "") {
                $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                    "headers" => [
                        "Content-Type" => "application/json",
                        "centreon-auth-token" => $authen_key
                    ],
                    'json' => $json,
                    "verify" => false
                ]);

                $hosts = json_decode($res->getBody());
                $hosts = $hosts->result;
            }
            //endregion
            return view('centreon.hosts', compact('user', 'hosts', 'err_msg'));
        }
    }

    //endregion

    //region Host Group Screen
    public function HostGroup()
    {
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

        // get info centreonserver
        $centreonserver = DB::table('tbl_centreonservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

        $authen_key = "";
        $client = new \GuzzleHttp\Client(['cookies' => true]);
        try {
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=authenticate", [
                'form_params' => [
                    'username' => $centreonserver->user,
                    'password' => $centreonserver->password
                ],
                //"verify" => false
            ]);

            $authen_key = json_decode($res->getBody())->authToken;
        } catch (RequestException $e) {
            return view('centreon.errorpage',compact('user'));
        }

        $json = [
            "action" => "show",
            "object" => "HG"
        ];

        if ($authen_key != "") {
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                "headers" => [
                    "Content-Type" => "application/json",
                    "centreon-auth-token" => $authen_key
                ],
                'json' => $json,
                "verify" => false
            ]);

            $hostgroups = json_decode($res->getBody());
            $hostgroups = $hostgroups->result;
        }
        return view('centreon.hostgroup', compact('user', 'hostgroups'));
    }

    public function AddHostGroup()
    {
        if (!Session::has('Monitor')) {
            $url = url('/');
            return redirect($url);
        }

        $centreonserver = DB::table('tbl_centreonservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();
        $user = DB::table('tbl_accounts')
            ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
            ->where([
                ['tbl_accounts.username', '=', session('mymonitor_userid')]
            ])->first();


        $err_msg = '';
        return view('centreon.addhostgroup', compact('user', 'err_msg'));
    }

    public function addhostgroupsubmit()
    {
        if (!Session::has('Monitor')) {
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';
        $dm = Crypt::decryptString(session('mymonitor_md'));

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
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=authenticate", [
                'form_params' => [
                    'username' => $centreonserver->user,
                    'password' => $centreonserver->password
                ]
            ]);

            $authen_key = json_decode($res->getBody())->authToken;
        } catch (RequestException $e) {
            return view('centreon.errorpage',compact('user'));
        }


        if ($authen_key != "") {
            $hostgroupname = Request("hostgroupname");
            $alias = Request("alias");

            $param = [
                "action" => "add",
                "object" => "HG",
                "values" => $hostgroupname . ";" . $alias
            ];

            try {
                $response = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                    'headers' => [
                        'centreon-auth-token' => $authen_key,
                        'Content-Type' => 'application/json',
                    ],
                    'json' => $param
                ]);

                // set message when done
                $err_msg = "insert successfully";

            } catch (RequestException $e) {
                $err_msg = "Error";
            }
        }

        return view('centreon.addhostgroup', compact('user', 'err_msg'));
    }

    public function deletehostgroup($name)
    {
        if (!Session::has('Monitor')) {
            $url = url('/');
            return redirect($url);
        }

        $dm = Crypt::decryptString(session('mymonitor_md'));
        $err_msg = '';

        $user = DB::table('tbl_accounts')
            ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
            ->where([
                ['tbl_accounts.username', '=', session('mymonitor_userid')]
            ])->first();

        return view('centreon.deletehostgroup', compact('user', 'name', 'err_msg'));

    }

    public function deletehostgroupsubmit($name)
    {
        $err_msg = '';
        if (!Session::has('Monitor')) {
            $url = url('/');
            return redirect($url);
        }

        $dm = Crypt::decryptString(session('mymonitor_md'));
        $centreonserver = DB::table('tbl_centreonservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

        $authen_key = "";
        $client = new \GuzzleHttp\Client(['cookies' => true]);

        try {
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=authenticate", [
                'form_params' => [
                    'username' => $centreonserver->user,
                    'password' => $centreonserver->password
                ],
                //"verify" => false
            ]);

            $authen_key = json_decode($res->getBody())->authToken;
        } catch (RequestException $e) {
            return view('centreon.errorpage',compact('user'));
        }

        $param = Request('name');

        if ($authen_key != "") {
            $json = [
                "action" => "del",
                "object" => "HG",
                "values" => $name
            ];

            try {
                $response = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                    'headers' => [
                        'centreon-auth-token' => $authen_key,
                        'Content-Type' => 'application/json',
                    ],
                    'json' => $json,
                    "verify" => false
                ]);
                $err_msg = "delete successfully";
            } catch (RequestException $e) {
                $err_msg = "Error";
            }
        }

        $user = DB::table('tbl_accounts')
            ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
            ->where([
                ['tbl_accounts.username', '=', session('mymonitor_userid')]
            ])->first();

        if ($authen_key != "") {
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                "headers" => [
                    "Content-Type" => "application/json",
                    "centreon-auth-token" => $authen_key
                ],
                'json' => $json,
                "verify" => false
            ]);

            $hostgroup = json_decode($res->getBody());
            $hostgroup = $hostgroup->result;
        }

        return view('centreon.hostgroup', compact('user', 'hostgroup'));
    }

    public function edithostgroup($id)
    {
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
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=authenticate", [
                'form_params' => [
                    'username' => $centreonserver->user,
                    'password' => $centreonserver->password
                ],
                //"verify" => false
            ]);

            $authen_key = json_decode($res->getBody())->authToken;
        } catch (RequestException $e) {
            return view('centreon.errorpage',compact('user'));
        }

        $json = [
            "action" => "show",
            "object" => "HG"
        ];

        if ($authen_key != "") {
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                "headers" => [
                    "Content-Type" => "application/json",
                    "centreon-auth-token" => $authen_key
                ],
                'json' => $json,
                "verify" => false
            ]);

            $hostgroups = json_decode($res->getBody());
            $hostgroups = $hostgroups->result;
        }

        $id = Request("id");
        $err_msg = "";
        // search item
        foreach ($hostgroups as $k => $val) {
            if ($val->id != $id) {
                unset($hostgroups[$k]);
            }
        }
        $hostgroup = reset($hostgroups);
        return view("centreon.edithostgroup", compact("hostgroup", "user", "err_msg"));
    }

    public function edithostgroupsubmit()
    {
        $err_msg = '';
        if (!Session::has('Monitor')) {
            $url = url('/');
            return redirect($url);
        }

        $dm = Crypt::decryptString(session('mymonitor_md'));
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
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=authenticate", [
                'form_params' => [
                    'username' => $centreonserver->user,
                    'password' => $centreonserver->password
                ],
                //"verify" => false
            ]);

            $authen_key = json_decode($res->getBody())->authToken;
        } catch (RequestException $e) {
            return view('centreon.errorpage',compact('user'));
        }

        $name = Request('hostgroupname');

        if ($authen_key != "") {
            // update field
            $this->setparamHG($name, "name", $client, $authen_key, $centreonserver);
            $this->setparamHG($name, "alias", $client, $authen_key, $centreonserver);
        }

        return redirect()->action('centreonController@hostgroup');
    }

    function setparamHG($name, $field, $client, $authen_key, $centreonserver)
    {
        $value = Request($field);
        if ($value != "" && !is_null($value)) {

            $params = [
                "action" => "setparam",
                "object" => "HG",
                "values" => $name . ";" . $field . ";" . $value
            ];

            try {
                $response = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                    'headers' => [
                        'centreon-auth-token' => $authen_key,
                        'Content-Type' => 'application/json',
                    ],
                    'json' => $params,
                    "verify" => false
                ]);

            } catch (RequestException $e) {
                $err_msg = "Cannot update";
            }
        }
    }
    //endregion

    //region Service group
    public function srvgroup()
    {
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

        // get info centreonserver
        $centreonserver = DB::table('tbl_centreonservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

        $authen_key = "";
        $client = new \GuzzleHttp\Client(['cookies' => true]);
        try {
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=authenticate", [
                'form_params' => [
                    'username' => $centreonserver->user,
                    'password' => $centreonserver->password
                ],
                //"verify" => false
            ]);

            $authen_key = json_decode($res->getBody())->authToken;
        } catch (RequestException $e) {
            return view('centreon.errorpage',compact('user'));
        }

        $json = [
            "action" => "show",
            "object" => "SG"
        ];

        if ($authen_key != "") {
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                "headers" => [
                    "Content-Type" => "application/json",
                    "centreon-auth-token" => $authen_key
                ],
                'json' => $json,
                "verify" => false
            ]);

            $servicegroup = json_decode($res->getBody());
            $servicegroup = $servicegroup->result;
        }
        return view('centreon.servicegroup', compact('user', 'servicegroup'));
    }

    public function addservicegroup()
    {
        if (!Session::has('Monitor')) {
            $url = url('/');
            return redirect($url);
        }

        $centreonserver = DB::table('tbl_centreonservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();


        $err_msg = '';
        return view('centreon.addservicegroup', compact('user', 'err_msg'));
    }

    public function addservicegroupsubmit()
    {
        if (!Session::has('Monitor')) {
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';
        $dm = Crypt::decryptString(session('mymonitor_md'));

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
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=authenticate", [
                'form_params' => [
                    'username' => $centreonserver->user,
                    'password' => $centreonserver->password
                ]
            ]);

            $authen_key = json_decode($res->getBody())->authToken;
        } catch (RequestException $e) {
            $err_msg = "UnAuthorized";
        }


        if ($authen_key != "") {
            $servicegroupname = Request("name");
            $alias = Request("alias");

            $param = [
                "action" => "ADD",
                "object" => "SG",
                "values" => $servicegroupname . ";" . $alias
            ];

            try {
                $response = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                    'headers' => [
                        'centreon-auth-token' => $authen_key,
                        'Content-Type' => 'application/json',
                    ],
                    'json' => $param
                ]);

                // set message when done
                $err_msg = "insert successfully";

            } catch (RequestException $e) {
                $err_msg = "Error";
            }
        }

        $json = [
            "action" => "show",
            "object" => "SG"
        ];

        if ($authen_key != "") {
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                "headers" => [
                    "Content-Type" => "application/json",
                    "centreon-auth-token" => $authen_key
                ],
                'json' => $json,
                "verify" => false
            ]);

            $servicegroup = json_decode($res->getBody());
            $servicegroup = $servicegroup->result;
        }
        return view('centreon.servicegroup', compact('user','servicegroup', 'err_msg'));
    }

    public function deleteservicegroup($name)
    {

        if (!Session::has('Monitor')) {
            $url = url('/');
            return redirect($url);
        }

        $dm = Crypt::decryptString(session('mymonitor_md'));
        $err_msg = '';

        $user = DB::table('tbl_accounts')
            ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
            ->where([
                ['tbl_accounts.username', '=', session('mymonitor_userid')]
            ])->first();

        return view('centreon.deleteservicegroup', compact('user', 'name', 'err_msg'));

    }

    public function deleteservicegroupsubmit($name)
    {
        $err_msg = '';
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
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=authenticate", [
                'form_params' => [
                    'username' => $centreonserver->user,
                    'password' => $centreonserver->password
                ],
                //"verify" => false
            ]);

            $authen_key = json_decode($res->getBody())->authToken;
        } catch (RequestException $e) {
            $err_msg = "UnAuthorized";
        }

        $param = Request('name');

        if ($authen_key != "") {
            $json = [
                "action" => "del",
                "object" => "SG",
                "values" => $name
            ];
            try {
                $response = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                    'headers' => [
                        'centreon-auth-token' => $authen_key,
                        'Content-Type' => 'application/json',
                    ],
                    'json' => $json,
                    "verify" => false
                ]);

            } catch (RequestException $e) {
                $err_msg = "Cannot delete ";
            }
        }

        if ($authen_key != "") {
            $json1 = [
                "action" => "show",
                "object" => "SG"
            ];
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                "headers" => [
                    "Content-Type" => "application/json",
                    "centreon-auth-token" => $authen_key
                ],
                'json' => $json1,
                "verify" => false
            ]);

            $user = DB::table('tbl_accounts')
                ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
                ->where([
                    ['tbl_accounts.username', '=', session('mymonitor_userid')]
                ])->first();

            $servicegroup = json_decode($res->getBody());
            $servicegroup = $servicegroup->result;
        }
        return view('centreon.servicegroup', compact('servicegroup', 'user', 'err_msg'));
    }

    public function editservicegroup($id)
    {
        //region auth
        if (!Session::has('Monitor')) {
            $url = url('/');
            return redirect($url);
        }

        $centreonserver = DB::table('tbl_centreonservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();
        $user = DB::table('tbl_accounts')
            ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
            ->where([
                ['tbl_accounts.username', '=', session('mymonitor_userid')]
            ])->first();

        $err_msg = '';

        $json = [
            "action" => "show",
            "object" => "SG"
        ];

        $client = new \GuzzleHttp\Client(['cookies' => true]);
        try {
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=authenticate", [
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

        if ($authen_key != "") {
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                "headers" => [
                    "Content-Type" => "application/json",
                    "centreon-auth-token" => $authen_key
                ],
                'json' => $json,
                "verify" => false
            ]);

            $servicegroup = json_decode($res->getBody());
            $servicegroup = $servicegroup->result;
        }

        // get one
        foreach ($servicegroup as $k => $val) {
            if ($val->id != $id) {
                unset($servicegroup[$k]);
            }
        }
        $servicegroup = reset($servicegroup);

        return view('centreon.editservicegroup', compact('user','servicegroup', 'err_msg'));
    }

    public function editservicegroupsubmit()
    {
        //region auth
        if (!Session::has('Monitor')) {
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';
        $dm = Crypt::decryptString(session('mymonitor_md'));

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
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=authenticate", [
                'form_params' => [
                    'username' => $centreonserver->user,
                    'password' => $centreonserver->password
                ]
            ]);

            $authen_key = json_decode($res->getBody())->authToken;
        } catch (RequestException $e) {
            $err_msg = "UnAuthorized";
        }
        //endregion

        //get param
        $srvname = Request("servicegroup_name");
        $name = Request("name");
        $alias = Request("alias");

        if ($authen_key != "") {
            $param = [
                "action" => "setparam",
                "object" => "SG",
                "values" => $srvname . ";" ."name".";". $name
            ];


            $response = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                'headers' => [
                    'centreon-auth-token' => $authen_key,
                    'Content-Type' => 'application/json',
                ],
                'json' => $param
            ]);
        }

        if ($authen_key != "") {
            if(strcmp($srvname,$name) != 0){
                $param1 = [
                    "action" => "setparam",
                    "object" => "SG",
                    "values" => $name . ";" ."alias".";". $alias
                ];
            }
            else{
                $param1 = [
                    "action" => "setparam",
                    "object" => "SG",
                    "values" => $srvname . ";" ."alias".";". $alias
                ];
            }

            $response = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                'headers' => [
                    'centreon-auth-token' => $authen_key,
                    'Content-Type' => 'application/json',
                ],
                'json' => $param1
            ]);
        }

        //region reload data
        $json = [
            "action" => "show",
            "object" => "SG"
        ];

        if ($authen_key != "") {
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                "headers" => [
                    "Content-Type" => "application/json",
                    "centreon-auth-token" => $authen_key
                ],
                'json' => $json,
                "verify" => false
            ]);

            $servicegroup = json_decode($res->getBody());
            $servicegroup = $servicegroup->result;
        }
        //endregion

        return view('centreon.servicegroup', compact('user','servicegroup', 'err_msg'));
    }
    //endregion

    //region Service by host
    public function services()
    {
        //region auth
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

        $client = new \GuzzleHttp\Client(['cookies' => true]);

        try {
            $res = $client->request("POST", $centreonserver->hostname."/centreon/api/index.php?action=authenticate", [
                'form_params' => [
                    'username' => $centreonserver->user,
                    'password' => $centreonserver->password
                ],
                //"verify" => false
            ]);

            $authen_key = json_decode($res->getBody())->authToken;

        } catch (RequestException $e) {
            return view('centreon.errorpage',compact('user'));
        }
        //endregion

        if ($authen_key != "") {
            $response = $client->request("POST", $centreonserver->hostname."/centreon/api/index.php?action=action&object=centreon_clapi", [
                "headers" => [
                    "Content-Type" => "application/json",
                    "centreon-auth-token" => $authen_key
                ],
                "json" =>["action"=>"show","object"=>"service"],
                "verify" => false
            ]);

            $services = json_decode($response->getBody());
            $services = $services->result;
        }

        //region get template
        $rs_templ = $client->request("POST", $centreonserver->hostname."/centreon/api/index.php?action=action&object=centreon_clapi", [
            "headers" => [
                "Content-Type" => "application/json",
                "centreon-auth-token" => $authen_key
            ],
            'json' => [
                "action"=>"show",
                "object"=>"STPL"
            ]
        ]);
        $rs_templ = json_decode($rs_templ->getBody());
        $rs_templ = $rs_templ->result;


        foreach ($services as $ser){
            // get template for service
            $template= array_search($ser->id, array_column($rs_templ, 'id'));
            if(empty($template)){
                $ser->template ="";
            }
            else
            {
                $ser->template = $template->description;
            }

        }
        //endregion
        return view('centreon.servicebyhost', compact('user', 'services'));
    }


    public function addservice()
    {
        $user = DB::table('tbl_accounts')
            ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
            ->where([
                ['tbl_accounts.username', '=', session('mymonitor_userid')]
            ])->first();
        $err_msg = "";

        //region load data
        // get token
        $authen_key = "";
        $client = new \GuzzleHttp\Client(['cookies' => true]);
        $centreonserver = DB::table('tbl_centreonservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();
        try {
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=authenticate", [
                'form_params' => [
                    'username' => $centreonserver->user,
                    'password' => $centreonserver->password
                ],
                //"verify" => false
            ]);

            $authen_key = json_decode($res->getBody())->authToken;
        } catch (RequestException $e) {
            return view('centreon.errorpage',compact('user'));
        }

        // linked to host
        if ($authen_key != "") {
            $json = [
                "action" => "show",
                "object" => "host"
            ];
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                "headers" => [
                    "Content-Type" => "application/json",
                    "centreon-auth-token" => $authen_key
                ],
                'json' => $json,
                "verify" => false
            ]);
            $hosts = json_decode($res->getBody());
            $hosts = $hosts->result;
        }

        //templates
        if ($authen_key != "") {
            $param1 = [
                "action" => "show",
                "object" => "STPL"
            ];
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                "headers" => [
                    "Content-Type" => "application/json",
                    "centreon-auth-token" => $authen_key
                ],
                'json' => $param1,
                "verify" => false
            ]);

            $templates = json_decode($res->getBody());
            $templates = $templates->result;

        }

        // check command
        if ($authen_key != "") {
            $param2 = [
                "action" => "show",
                "object" => "CMD"
            ];
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                "headers" => [
                    "Content-Type" => "application/json",
                    "centreon-auth-token" => $authen_key
                ],
                'json' => $param2,
                "verify" => false
            ]);
            $commands = json_decode($res->getBody());
            $commands = $commands->result;
            // get 10 items
            $commands = array_slice($commands, 0, 9);

        }

        //endregion
        return view("centreon.addservicebyhost", compact('user', 'hosts', 'commands', 'templates', 'err_msg'));
    }

    public function addservicesubmit()
    {

        //region auth
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
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=authenticate", [
                'form_params' => [
                    'username' => $centreonserver->user,
                    'password' => $centreonserver->password
                ],
                //"verify" => false
            ]);

            $authen_key = json_decode($res->getBody())->authToken;
        } catch (RequestException $e) {
            return view('centreon.errorpage',compact('user'));
        }
        //endregion

        //get param
        $des = Request("description");
        $host = Request("host");
        $template = Request("template");
        $macro_name = Request("macro_name");
        $macro_val = Request("macro_value");
        $check_command = Request("command");
        $max_check_item = Request("max_check_attempts");
        $check_interval = Request("normal_check_interval");
        $retry_check_interval = Request("retry_check_interval");

        // add
        if ($authen_key != "") {
            $json = [
                "action" => "add",
                "object" => "service",
                "values" => $host . ";" . $des . ";" . $template
            ];
            try {
                $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                    "headers" => [
                        "Content-Type" => "application/json",
                        "centreon-auth-token" => $authen_key
                    ],
                    'json' => $json,
                    "verify" => false
                ]);
                $err_msg = "done";
            } catch (RequestException $exception) {
                $err_msg = "error";
            }

            //region set param
            // command
            $p1 = [
                "action" => "setparam",
                "object" => "service",
                "values" => $host . ";" . $des . ";" . "check_command" . ";" . $check_command
            ];
            if ($check_command != "") {
                $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                    "headers" => [
                        "Content-Type" => "application/json",
                        "centreon-auth-token" => $authen_key
                    ],
                    'json' => $p1,
                    "verify" => false
                ]);
            }

            //macro
            $p2 = [
                "action" => "setmacro",
                "object" => "host",
                "values" => $host . ";" . $macro_name . ";" . $macro_val
            ];
            if ($macro_val != "" && $macro_name != "") {
                $rs_macro = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                    'headers' => [
                        'centreon-auth-token' => $authen_key,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                    'json' => $p2,
                    "verify" => false
                ]);
            }

            // check attemps , retry interval , check interval
            if ($max_check_item != "") {
                $param1 = [
                    "action" => "setparam",
                    "object" => "service",
                    "values" => $host . ";" . $des . ";" . "max_check_attempts" . ";" . $max_check_item
                ];

                $result1 = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                    'headers' => [
                        'centreon-auth-token' => $authen_key,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                    'json' => $param1,
                    "verify" => false
                ]);

            }

            if ($check_interval != "") {
                $param2 = [
                    "action" => "setparam",
                    "object" => "service",
                    "values" => $host . ";" . $des . ";" . "normal_check_interval" . ";" . $check_interval
                ];
                $result2 = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                    'headers' => [
                        'centreon-auth-token' => $authen_key,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                    'json' => $param2,
                    "verify" => false
                ]);
            }

            if ($retry_check_interval != "") {
                $param3 = [
                    "action" => "setparam",
                    "object" => "service",
                    "values" => $host . ";" . $des . ";" . "retry_check_interval" . ";" . $retry_check_interval
                ];
                $result3 = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                    'headers' => [
                        'centreon-auth-token' => $authen_key,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                    'json' => $param3,
                    "verify" => false
                ]);
            }

            // reload data
            if ($authen_key != "") {
                $rs_services = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                    "headers" => [
                        "Content-Type" => "application/json",
                        "centreon-auth-token" => $authen_key
                    ],
                    'json' => $json,
                    "verify" => false
                ]);

                $services = json_decode($rs_services->getBody());
                $services = $services->result;
            }

            //endregion
        }
        return view('centreon.service', compact('user', 'services'));
    }

    public function deleteservice($hostname, $servicename)
    {
        if (!Session::has('Monitor')) {
            $url = url('/');
            return redirect($url);
        }
        $dm = Crypt::decryptString(session('mymonitor_md'));
        $err_msg = '';

        $user = DB::table('tbl_accounts')
            ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
            ->where([
                ['tbl_accounts.username', '=', session('mymonitor_userid')]
            ])->first();

        return view('centreon.deleteservice', compact('user', 'hostname', 'servicename', 'err_msg'));
    }

    public function deleteservicesubmit()
    {
        $err_msg = '';
        //region auth
        if (!Session::has('Monitor')) {
            $url = url('/');
            return redirect($url);
        }
        $user = DB::table('tbl_accounts')
            ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
            ->where([
                ['tbl_accounts.username', '=', session('mymonitor_userid')]
            ])->first();


        $dm = Crypt::decryptString(session('mymonitor_md'));
        $centreonserver = DB::table('tbl_centreonservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

        $authen_key = "";
        $client = new \GuzzleHttp\Client(['cookies' => true]);

        try {
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=authenticate", [
                'form_params' => [
                    'username' => $centreonserver->user,
                    'password' => $centreonserver->password
                ],
                //"verify" => false
            ]);

            $authen_key = json_decode($res->getBody())->authToken;
        } catch (RequestException $e) {
            return view('centreon.errorpage',compact('user'));
        }
        //endregion

        $param = Request('name');
        $hostname = Request("hostname");
        $servicename = Request("servicename");
        if ($authen_key != "") {
            $json = [
                "action" => "del",
                "object" => "service",
                "values" => $hostname . ";" . $servicename
            ];


            try {
                $response = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                    'headers' => [
                        'centreon-auth-token' => $authen_key,
                        'Content-Type' => 'application/json',
                    ],
                    'json' => $json,
                    "verify" => false
                ]);

            } catch (RequestException $e) {
                $err_msg = "Cannot delete ";
            }
        }

        // reload data
        $json1 = [
            "action" => "show",
            "object" => "service"
        ];

        if ($authen_key != "") {
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                "headers" => [
                    "Content-Type" => "application/json",
                    "centreon-auth-token" => $authen_key
                ],
                'json' => $json1,
                "verify" => false
            ]);

            $services = json_decode($res->getBody());
            $services = $services->result;
        }

        return view('centreon.servicebyhost', compact('services', 'user', 'err_msg'));
    }

    public function editservice($id)
    {
        //region auth
        $user = DB::table('tbl_accounts')
            ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
            ->where([
                ['tbl_accounts.username', '=', session('mymonitor_userid')]
            ])->first();
        $err_msg = "";
        $authen_key = "";
        $client = new \GuzzleHttp\Client(['cookies' => true]);
        $centreonserver = DB::table('tbl_centreonservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

        try {
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=authenticate", [
                'form_params' => [
                    'username' => $centreonserver->user,
                    'password' => $centreonserver->password
                ],
                //"verify" => false
            ]);

            $authen_key = json_decode($res->getBody())->authToken;
        } catch (RequestException $e) {
            return view('centreon.errorpage',compact('user'));
        }
        //endregion

        //region load data
        $json = [
            "action" => "show",
            "object" => "service"
        ];

        if ($authen_key != "") {
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                "headers" => [
                    "Content-Type" => "application/json",
                    "centreon-auth-token" => $authen_key
                ],
                'json' => $json,
                "verify" => false
            ]);

            $services = json_decode($res->getBody());
            $services = $services->result;
        }
        $err_msg = "";
        // get one item
        foreach ($services as $k => $val) {
            if ($val->id != $id) {
                unset($services[$k]);
            }
        }
        $service = reset($services);

        // linked to host
        if ($authen_key != "") {
            $json = [
                "action" => "show",
                "object" => "host"
            ];
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                "headers" => [
                    "Content-Type" => "application/json",
                    "centreon-auth-token" => $authen_key
                ],
                'json' => $json,
                "verify" => false
            ]);
            $hosts = json_decode($res->getBody());
            $hosts = $hosts->result;
        }

        //templates
        if ($authen_key != "") {
            $param1 = [
                "action" => "show",
                "object" => "STPL"
            ];
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                "headers" => [
                    "Content-Type" => "application/json",
                    "centreon-auth-token" => $authen_key
                ],
                'json' => $param1,
                "verify" => false
            ]);

            $templates = json_decode($res->getBody());
            $templates = $templates->result;

        }

        // check command
        if ($authen_key != "") {
            $param2 = [
                "action" => "show",
                "object" => "CMD"
            ];
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                "headers" => [
                    "Content-Type" => "application/json",
                    "centreon-auth-token" => $authen_key
                ],
                'json' => $param2,
                "verify" => false
            ]);
            $commands = json_decode($res->getBody());
            $commands = $commands->result;
            $commands = array_filter($commands);

        }

        //endregion
        return view("centreon.editservice", compact('user', 'hosts', 'commands', 'templates', 'service', 'err_msg'));
    }

    public function editservicesubmit()
    {
        //region auth
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
            $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=authenticate", [
                'form_params' => [
                    'username' => $centreonserver->user,
                    'password' => $centreonserver->password
                ],
                //"verify" => false
            ]);

            $authen_key = json_decode($res->getBody())->authToken;
        } catch (RequestException $e) {
            return view('centreon.errorpage',compact('user'));
        }
        //endregion

        //get param
        $servicename = Request("servicename");
        $des = Request("description");
        $host = Request("host");
        $template = Request("template");
        $macro_name = Request("macro_name");
        $macro_val = Request("macro_value");
        $check_command = Request("command");
        $max_check_item = Request("max_check_attempts");
        $check_interval = Request("normal_check_interval");
        $retry_check_interval = Request("retry_check_interval");

        //region service name
        if ($authen_key != "") {
            $json = [
                "action" => "setparam",
                "object" => "service",
                "values" => $host . ";" . $servicename . ";" . "description" . ";" . $des
            ];
            if ($servicename != $des) {
                $res = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                    "headers" => [
                        "Content-Type" => "application/json",
                        "centreon-auth-token" => $authen_key
                    ],
                    'json' => $json,
                    "verify" => false
                ]);
            }
        }
        //endregion

        //region check attemps , retry interval , check interval
        if ($authen_key != "") {
            $param1 = [
                "action" => "setparam",
                "object" => "service",
                "values" => $host . ";" . $des . ";" . "max_check_attempts" . ";" . $max_check_item
            ];

            $result1 = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                'headers' => [
                    'centreon-auth-token' => $authen_key,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $param1,
                "verify" => false
            ]);

        }

        if ($authen_key != "") {
            $param2 = [
                "action" => "setparam",
                "object" => "service",
                "values" => $host . ";" . $des . ";" . "normal_check_interval" . ";" . $check_interval
            ];
            $result2 = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                'headers' => [
                    'centreon-auth-token' => $authen_key,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $param2,
                "verify" => false
            ]);
        }

        if ($authen_key != "") {
            $param3 = [
                "action" => "setparam",
                "object" => "service",
                "values" => $host . ";" . $des . ";" . "retry_check_interval" . ";" . $retry_check_interval
            ];
            $result3 = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                'headers' => [
                    'centreon-auth-token' => $authen_key,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $param3,
                "verify" => false
            ]);
        }
        //endregion

        //region template
        if ($authen_key != "") {
            $param4 = [
                "action" => "setparam",
                "object" => "service",
                "values" => $host . ";" . $des . ";" . "template" . ";" . $template
            ];

            $result4 = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                'headers' => [
                    'centreon-auth-token' => $authen_key,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $param4,
                "verify" => false
            ]);

        }
        //endregion

        //region check_command
        if ($authen_key != "") {
            $param5 = [
                "action" => "setparam",
                "object" => "service",
                "values" => $host . ";" . $des . ";" . "check_command" . ";" . $check_command
            ];

            $result5 = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                'headers' => [
                    'centreon-auth-token' => $authen_key,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $param5,
                "verify" => false
            ]);

        }
        //endregion

        //region macro
        $p2 = [
            "action" => "setmacro",
            "object" => "STPL",
            "values" => $servicename . ";" . $macro_name . ";" . $macro_val
        ];
        if ($macro_val != "" && $macro_name != "") {
            $rs_macro = $client->request('POST', $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                'headers' => [
                    'centreon-auth-token' => $authen_key,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $p2,
                "verify" => false
            ]);
        }
        //endregion

        //region reload data
        if ($authen_key != "") {
            $rs_services = $client->request("POST", $centreonserver->hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
                "headers" => [
                    "Content-Type" => "application/json",
                    "centreon-auth-token" => $authen_key
                ],
                'json' => ["action"=>"show","object"=>"service"],
                "verify" => false
            ]);

            $services = json_decode($rs_services->getBody());
            $services = $services->result;

            $rs_templ = $client->request("POST", $centreonserver->hostname."/centreon/api/index.php?action=action&object=centreon_clapi", [
                "headers" => [
                    "Content-Type" => "application/json",
                    "centreon-auth-token" => $authen_key
                ],
                'json' => [
                    "action"=>"show",
                    "object"=>"STPL"
                ]
            ]);

            $rs_templ = json_decode($rs_templ->getBody());
            $rs_templ = $rs_templ->result;
            foreach ($services as $ser){
                // add template
                $template= array_search($ser->id, array_column($rs_templ, 'id'));
                if(empty($template)){
                    $ser->template ="";
                }
                else
                {
                    $ser->template = $template->description;
                }

            }
        }
        //endregion

        return view('centreon.servicebyhost', compact('user', 'services'));
}


//endregion

    //region Monitoring

    public function monitoring()
    {
        //region auth
        if (!Session::has('Monitor')) {
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_accounts')
            ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
            ->where([
                ['tbl_accounts.username', '=', session('mymonitor_userid')]
            ])->first();
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
                ],
                //"verify" => false
            ]);

            $authen_key = json_decode($res->getBody())->authToken;
        } catch (RequestException $e) {
            return view('centreon.errorpage',compact('user'));
        }
        //endregion

        $refreshrate = $this->getrefreshrate();
        return view('centreon.monitoring', compact('user','refreshrate'));
    }

    function  ajaxmonitors(){
        $html = "";
        //region auth
        if (!Session::has('Monitor')) {
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_accounts')
            ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
            ->where([
                ['tbl_accounts.username', '=', session('mymonitor_userid')]
            ])->first();
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
                ],
                //"verify" => false
            ]);

            $authen_key = json_decode($res->getBody())->authToken;
        } catch (RequestException $e) {
            return view('centreon.errorpage',compact('user'));
        }
        //endregion
        // load host
        if ($authen_key != "") {
                $res = $client->request("GET", $centreonserver->hostname."/centreon/api/index.php?object=centreon_realtime_services&action=list", [
                "headers" => [
                    "Content-Type" => "application/json",
                    "centreon-auth-token" => $authen_key
                ],
                "verify" => false
            ]);

            $monitors = json_decode($res->getBody());
            foreach($monitors as $monitor) {
                $last_hard_state_change = $this->secondsToTime($monitor->last_hard_state_change);
                $last_check = $this->secondsToTime($monitor-> last_check);
                $monitor->last_hard_state_change = $last_hard_state_change['days'] ."D ".$last_hard_state_change['days'] ."H";
                $monitor->host_last_check = $last_check['minutes'] ."M ".$last_check['seconds'] ."S";
                if($monitor -> check_attempt != "" && $monitor->max_check_attempts != "")
                    $monitor->tries = $monitor ->check_attempt."/". $monitor->max_check_attempts;
                else
                    $monitor->tries = "";
            }
        }
        return response()->json($monitors);

    }

    function  ajaxgetdetailhost(Request $request){
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
       $hostname = $centreonserver->hostname;
       $username = $centreonserver->user;
       $password = $centreonserver->password;
       $client = new \GuzzleHttp\Client(['cookies' => true]);
       $seletedid = $request->id;

        try {
            $res = $client->request("POST",  $hostname. "/centreon/api/index.php?action=authenticate", [
                'form_params' => [
                    'username' => $username,
                    'password' => $password
                ]
            ]);

            $authen_key = json_decode($res->getBody())->authToken;
        } catch (RequestException $e) {
            return view('centreon.errorpage',compact('user'));
        }
        //endregion

        // load host
      if ($authen_key != "") {
          $res = $client->request("POST", $hostname . "/centreon/api/index.php?action=action&object=centreon_clapi", [
              "headers" => [
                  "Content-Type" => "application/json",
                  "centreon-auth-token" => $authen_key
              ],
              'json' => ["action" => "show", "object" => "host"],
          ]);
          $monitors = json_decode($res->getBody());
          $monitors =  $monitors->result;
          //$monitor = $monitors[0];

      }
        // get one
        foreach ($monitors as $k => $val) {
            if ($val->id != $seletedid) {
                unset($monitors[$k]);
            }
        }
        $monitor = reset($monitors);

       //region gen html
        $html = "";
        $html.= "<table cellpadding='5' cellspacing='0' border='0' style='padding-left:50px;'>";
        $html.=     "<tr>";
        $html.=         "<td>Host Name:</td>";
        $html.=         "<td>".$monitor->name."</td>";
        $html.=     "</tr>";

        $html.=     "<tr>";
        $html.=         "<td>Alias:</td>";
        $html.=         "<td>".$monitor->alias."</td>";
        $html.=     "</tr>";

        $html.=     "<tr>";
        $html.=         "<td>Address:</td>";
        $html.=         "<td>".$monitor->address."</td>";
        $html.=     "</tr>";

        $html.=     "<tr>";
        $html.=         "<td>Status:</td>";
        $html.=         "<td>";
        if($monitor->activate == 1)
            $html.= "Enabled";
        else
            $html.= "Disabled" ;
        $html.=         "</td>";
        $html.=     "</tr>";
        $html.="</table>";
        //endregion*/

        echo $html;
    }


//endregion

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
            return view('centreon.errorpage',compact('user'));
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

    public function secondsToTime($inputSeconds) {
        $then = new DateTime(date('Y-m-d H:i:s', $inputSeconds));
        $now = new DateTime(date('Y-m-d H:i:s', time()));
        $diff = $then->diff($now);
        return array('years' => $diff->y, 'months' => $diff->m, 'days' => $diff->d, 'hours' => $diff->h, 'minutes' => $diff->i, 'seconds' => $diff->s);
    }

    public function  secondsToDate($inputSeconds){
        return date('d/m/Y', $inputSeconds);
    }

    //region refresh rate
    public function getrefreshrate() {
        $user = DB::table('tbl_accounts')
            ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
            ->where([
                ['tbl_accounts.username', '=', session('mymonitor_userid')]
            ])->first();
        $userid = $user->userid;

        $refreshrate = DB::table('tbl_refreshrate')
            ->where([
                ['userid', '=', $userid],
                ['product', '=', 'centreon']
            ])->first();

        if ($refreshrate == NULL) {
            //set default
            $this->setrefreshrate(5000);
            return 5000;
        } else {
            return $refreshrate->refreshrate;
        }
    }


    public function setrefreshrate($value) {
        $user = DB::table('tbl_accounts')
            ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
            ->where([
                ['tbl_accounts.username', '=', session('mymonitor_userid')]
            ])->first();
        $userid = $user->userid;

        $refreshrate = DB::table('tbl_refreshrate')
            ->where([
                ['userid', '=', $userid],
                ['product', '=', 'centreon']
            ])->first();



        if (empty($refreshrate)) {
            //Create
            DB::table('tbl_refreshrate')
                ->insert(
                    [
                        'userid' => $userid,
                        'product' => 'centreon',
                        'refreshrate' => intval($value)
                    ]
                );
        } else {
            //Update
            DB::table('tbl_refreshrate')
                ->where([
                    ['userid', '=', $userid],
                    ['product', '=', 'centreon']
                ])
                ->update([
                    'refreshrate' => intval($value)
                ]);
        }
    }
    //endregion
}
