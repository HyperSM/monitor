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

class slwnpmController extends Controller
{
    //
    //
    public function dashboard(){

        if (!Session::has('Monitor')){
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

        if($user->slwnpmuse=='1'){
            return view('slwnpm.dashboard',compact('domain','user'));
        }else{
            $url = url('/').'/admin/dashboard';
            return redirect($url);
        }
    }

    //Get total nodes for slwnpm dashboard
    public function totalnodesqueryfunction(){
    	//echo "Server time: " . date("H:i:s", time()+3600*7);// . date("h:i:sa");

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT+COUNT(NodeId) AS NodesCount+FROM+ORION.Nodes";

    	$response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
    	$data = json_decode($response, TRUE);
        echo '<p align=center>'. array_values( $data )[0][0]['NodesCount'] . '</p>';
    }


    //Get total interfaces for slwnpm dashboard
    public function totalintqueryfunction(){
        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT+COUNT(InterfaceID) AS IntCount+FROM+Orion.NPM.Interfaces";

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $data = json_decode($response, TRUE);
        echo '<p align=center>'. array_values( $data )[0][0]['IntCount'] . '</p>';
    }

    //Get total nodes with status is up for slwnpm dashboard
    public function nodeupqueryfunction(){
        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT+COUNT(NodeId) AS NodesCount+FROM+ORION.Nodes+WHERE+Status=1";

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $data = json_decode($response, TRUE);
        echo array_values( $data )[0][0]['NodesCount'];
    }

    //Get total nodes with status is down for slwnpm dashboard
    public function nodedownqueryfunction(){
        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT+COUNT(NodeId) AS NodesCount+FROM+ORION.Nodes+WHERE+Status=2";

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $data = json_decode($response, TRUE);
        echo array_values( $data )[0][0]['NodesCount'];
    }

    //Get total interfaces up for slwnpm dashboard
    public function intupqueryfunction(){
        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT+COUNT(InterfaceID) AS IntCount+FROM+Orion.NPM.Interfaces+WHERE+OperStatus=1";

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $data = json_decode($response, TRUE);
        echo array_values( $data )[0][0]['IntCount'];
    }

    //Get total interfaces down and unreachable for slwnpm dashboard
    public function intdownqueryfunction(){
        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT+COUNT(InterfaceID) AS IntCount+FROM+Orion.NPM.Interfaces+WHERE+OperStatus=0+OR+OperStatus=2";
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);

        $data = json_decode($response, TRUE);
        echo array_values( $data )[0][0]['IntCount'];
    }

    /*
    Page hiển thị dashboard slw npm
    Nạp ajax widget top 5 utilization interfaces
    */
    public function ajaxnpmutilization(){
        $tmpstr =
        '<table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th>Node</th>
                    <th>Interface</th>
                    <th>Receive</th>
                    <th>Transmit</th>
                </tr>
            </thead>
            <tbody>';

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT TOP 5 I.InterfaceID, I.Name,I.InPercentUtil,I.OutPercentUtil,N.Caption, I.NodeID+FROM Orion.NPM.Interfaces+I+JOIN+Orion.Nodes+N+ON+I.NodeId=N.NodeID+WHERE+I.InPercentUtil<>0 OR I.OutPercentUtil<>0 +ORDER BY+I.InPercentUtil+DESC";

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $data = json_decode($response, TRUE);
        foreach (array_values( $data )[0] as $item) {
            //echo $item['InPercentUtil'];
            $tmpstr = $tmpstr .
            '<tr>
                    <td style="vertical-align: middle;"><a href="'. url('/'). '/admin/slwnpm/nodesummary/' .$item['NodeID'].'" style="text-decoration:none;">'.$item['Caption'].'</a></td>
                    <td style="vertical-align: middle;"><a href="'. url('/'). '/admin/slwnpm/interfacedetail/' .$item['InterfaceID'].'" style="text-decoration:none;">'.$item['Name'].'</a></td>
                    <td style="vertical-align: middle;">'.$item['InPercentUtil'].'%<div class="progress progress-striped active" style="height:10px;">
                        <div style="width: '.$item['InPercentUtil'].'%; height:10px;" class="progress-bar progress-bar-success"></div>
                        </div>
                    </td>
                    <td style="vertical-align: middle;">'.$item['OutPercentUtil'].'%<div class="progress progress-striped active" style="height:10px;">
                        <div style="width: '.$item['OutPercentUtil'].'%; height:10px;" class="progress-bar progress-bar-success"></div>
                        </div>
                    </td>                    
            </tr>';
        }
        //dd( array_values( $data )[0][2]['InPercentUtil']);
        $tmpstr = $tmpstr.' 
            </tbody>
        </table>';
        echo $tmpstr;
    }


    public function npmnodetreefunction(){
        /*$response = Http::withBasicAuth('admin','Cisco@1234')->Get('https://172.16.0.10:17778/SolarWinds/InformationService/v3/Json/Query?query=SELECT+I.Name AS InterfaceName ,I.OperStatus AS InterfaceStatus, N.Caption AS NodeCaption, N.IPAddress AS NodeIP, N.Vendor AS NodeVendor, N.Status AS NodeStatus FROM+Orion.NPM.Interfaces+I+JOIN+Orion.Nodes+N+ON+I.NodeId=N.NodeID+ORDER BY+NodeVendor,NodeCaption');
        $data = json_decode($response,TRUE);


        //echo array_values( $data )[0][0][0];
        //echo array_values( $data )[0][0][1];

        //var_dump(array_values( $data )[0][0]);
        //var_dump(array_values( $data )[0][1]);

        //echo  count(array_values( $data )[0]);

        $lastnode = '';

        $tmpstr = '
            <ul id="myUL">
                <li><span class="mycaret">Vietlott</span>
                    <ul class="mynested">';
        $i = 0;
        foreach (array_values( $data )[0] as $key) {
            if ($lastnode != $key['NodeCaption']){
                if ($i<>0){$tmpstr = $tmpstr.'</ul>';}

                if($key['InterfaceStatus']=='1'){
                    $tmpstr = $tmpstr .
                    '<li><span class="mycaret"><i class="fas fa-circle text-success" style="padding-right:10px;"></i>'.$key['NodeCaption'].'</span>
                    <ul class="mynested">
                      <li><i class="fas fa-network-wired text-success" style="padding-right:10px;"></i>'.$key['InterfaceName'].'</li>';
                }else{
                    $tmpstr = $tmpstr .
                    '<li><span class="mycaret"><i class="fas fa-circle text-success" style="padding-right:10px;"></i>'.$key['NodeCaption'].'</span>
                    <ul class="mynested">
                      <li><i class="fas fa-network-wired text-danger" style="padding-right:10px;"></i>'.$key['InterfaceName'].'</li>';
                }

            }else{

                if($key['InterfaceStatus']=='1'){
                    $tmpstr = $tmpstr . '<li><i class="fas fa-network-wired text-success" style="padding-right:10px;"></i>' . $key['InterfaceName']. '</li>';
                }else{
                    $tmpstr = $tmpstr . '<li><i class="fas fa-network-wired text-danger" style="padding-right:10px;"></i>' . $key['InterfaceName']. '</li>';
                }
            }

            $lastnode = $key['NodeCaption'];
            $i++;
        }
        $tmpstr = $tmpstr . '
                    </ul>
                </li>
            </ul>';
        echo $tmpstr;

        /*echo '
        <ul id="myUL">
          <li><span class="mycaret">Vietlott</span>
            <ul class="mynested">

              <li>Water</li>
              <li>Coffee</li>
              <li><span class="mycaret">Tea</span>
                <ul class="mynested">
                  <li>Black Tea</li>
                  <li>White Tea</li>
                  <li><span class="mycaret">Green Tea</span>
                    <ul class="mynested">
                      <li>Sencha</li>
                      <li>Gyokuro</li>
                      <li>Matcha</li>
                      <li>Pi Lo Chun</li>
                    </ul>
                  </li>
                </ul>
              </li>
            </ul>
          </li>
        </ul>
        ';*/

        $tmpstr = '
            <ul id="myUL">
                <li><span class="mycaret">Vietlott</span>
                    <ul>';
        /////
        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT+NodeId,DisplayName,Status,StatusIcon,IPAddress,Vendor+FROM+ORION.Nodes ORDER BY DisplayName";
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $data = json_decode($response, TRUE);

        foreach (array_values( $data )[0] as $item) {
            /*if($item['Status']=='1'){
                $tmpstr = $tmpstr.'<li style="padding-top:5px;"><i class="fas fa-circle text-success" style="padding-right:10px; color:#00cc00;"><a href="'.url('/').'/admin/slwnpm/nodedetail/'.$item['NodeId'].'" title="Vendor: '.$item['Vendor'].' - Management IP: '.$item['IPAddress'].'" style="text-decoration:none;"></i>'.$item['DisplayName'].'</a></li>';
            }else{
                $tmpstr = $tmpstr.'<li style="padding-top:5px;"><i class="fas fa-circle text-danger" style="padding-right:10px; color:Red;"><a href="'.url('/').'/admin/slwnpm/nodedetail/'.$item['NodeId'].'" title="Vendor: '.$item['Vendor'].' - Management IP: '.$item['IPAddress'].'" style="text-decoration:none;"></i>'.$item['DisplayName'].'</a></li>';
            }*/

            $slwnpmserver = DB::table('tbl_slwnpmservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();

            $tmpstr = $tmpstr.'<li style="padding-top:5px;"><img src="http://'.$slwnpmserver->hostname.'/Orion/images/StatusIcons/small-'.$item['StatusIcon'].'"/><a href="'.url('/').'/admin/slwnpm/nodesummary/'.$item['NodeId'].'" title="Vendor: '.$item['Vendor'].' - Management IP: '.$item['IPAddress'].'" style="text-decoration:none;"></i>'.$item['DisplayName'].'</a></li>';

        }

        /////
        $tmpstr = $tmpstr . '
                    </ul>
                </li>
            </ul>';
        echo $tmpstr;
    }

    //Get total nodes with status is up for slwnpm dashboard
    public function ajaxhwhealthup(){
        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT+COUNT(HardwareInfoID) AS Total+FROM Orion.HardwareHealth.HardwareCategoryStatusBase+WHERE+StatusDescription='Up'";
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);

        $data = json_decode($response, TRUE);
        echo array_values( $data )[0][0]['Total'];
    }

    public function ajaxhwhealthunknown(){
        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT+COUNT(HardwareInfoID) AS Total+FROM Orion.HardwareHealth.HardwareCategoryStatusBase+WHERE+StatusDescription='Unknown'";
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);

        $data = json_decode($response, TRUE);
        echo array_values( $data )[0][0]['Total'];
    }

    public function ajaxhwhealthcritical(){
        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT+COUNT(HardwareInfoID) AS Total+FROM Orion.HardwareHealth.HardwareCategoryStatusBase+WHERE+StatusDescription='Critical'";
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);

        $data = json_decode($response, TRUE);
        echo array_values( $data )[0][0]['Total'];
    }

    public function ajaxhwhealthwarning(){
        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT+COUNT(HardwareInfoID) AS Total+FROM Orion.HardwareHealth.HardwareCategoryStatusBase+WHERE+StatusDescription='Warning'";

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $data = json_decode($response, TRUE);
        echo array_values( $data )[0][0]['Total'];
    }

    /*
    Trang dashboard slw npm
    ajax load last 10 event cho widget
    */
    public function ajaxnpmlast10event(){
        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT+TOP 10 E.EventTime,E.Message,T.Name,E.EventType,E.NetObjectType,E.NetObjectID+FROM+Orion.Events AS E+LEFT JOIN+Orion.EventTypes AS T+ON+E.EventType=T.EventType+ORDER BY+E.EventTime DESC";

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);

        $data = json_decode($response, TRUE);
        //dd($data);
        //echo array_values( $data )[0][0]['Total'];
        $tmpstr =
        '<table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th>DATE TIME</th>
                    <th></th>
                    <th>EVENT</th>
                </tr>
            </thead>
            <tbody>';
        foreach (array_values( $data )[0] as $item) {
            $utc = $item['EventTime'];
            $dt = new DateTime($utc);
            //echo 'Original: ', $dt->format('r'), PHP_EOL;
            $tz = new DateTimeZone('Asia/Ho_Chi_Minh');
            $dt->setTimezone($tz);
            //echo 'After setting timezone: '. $dt->format('r');
            //echo '<br>';

            //Generating link upon network object type
            switch ($item['NetObjectType']) {
                case 'N':
                case 'HWHS':
                case 'HWHT':
                case 'HWH':
                    $link = '<a href="'.url('/').'/admin/slwnpm/nodesummary/'. $item['NetObjectID'].'" style="text-decoration:none;">'.$item['Message'].'</a>';
                    break;
                case 'I':
                    $link = '<a href="'.url('/').'/admin/slwnpm/interfacedetail/'. $item['NetObjectID'].'" style="text-decoration:none;">'.$item['Message'].'</a>';
                    break;
                default:
                    $link = '<a href="#" style="text-decoration:none;">'.$item['Message'].'</a>';
                    break;
            }

            //End of generating link

            //Generating image depends on Name
            if (strpos($item['Name'], 'Up') !== false) {
                $image= '<img src="'.url('/'). '/images/slwnpm/small/up.gif" style="width:14px;"/>';
            }elseif (strpos($item['Name'], 'Down') !== false) {
                $image= '<img src="'.url('/'). '/images/slwnpm/small/down.gif" style="width:14px;"/>';
            }elseif (strpos($item['Message'], 'Down') !== false) {
                $image= '<img src="'.url('/') . '/images/slwnpm/small/down.gif" style="width:14px;"/>';
            }elseif (strpos($item['Name'], 'Critical') !== false) {
                $image= '<img src="'.url('/'). '/images/slwnpm/small/critical.gif" style="width:14px;"/>';
            }elseif (strpos($item['Name'], 'Alert') !== false) {
                $image= '<img src="'.url('/') . '/images/slwnpm/small/alert.gif" style="width:14px;"/>';
            }elseif (strpos($item['Name'], 'Fail') !== false) {
                $image= '<img src="'.url('/') . '/images/slwnpm/small/failed.gif" style="width:14px;"/>';
            }elseif (strpos($item['Name'], 'Remove') !== false) {
                $image= '<img src="'.url('/') . '/images/slwnpm/small/remove.gif" style="width:14px;"/>';
            }else{
                $image= '<img src="'.url('/'). '/images/slwnpm/small/Info.gif" style="width:14px;"/>';
            }
            //End of image gen

            $tmpstr = $tmpstr .
                '<tr>
                        <td style="vertical-align: middle; width:100px; text-align: right;">'.$dt->format('M d, yy H:i A').'</td>
                        <td style="vertical-align: middle; width:40px; text-align: center;">'.$image.'</td>
                        <td style="vertical-align: middle;">'.$link.'</td>
                </tr>';
        }

        $tmpstr = $tmpstr.
        '   </tbody>
        </table>';

        echo $tmpstr;

    }

    /*
    Page: Dashboard solarwinds NPM
    Ajax div event summary to day
    */
    public function ajaxnpmeventsum(){
        $sdate = gmdate("Y-m-d\TH:i:s\Z");
        $edate = gmdate("Y-m-d\TH:i:s\Z",strtotime('- 24 hours'));

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        //$query = "query=SELECT+COUNT(EventID) AS Total, T.Name, E.EventType+FROM+Orion.Events AS E+LEFT JOIN+Orion.EventTypes AS T+ON+E.EventType=T.EventType+WHERE+E.EventTime >'". $edate . "' GROUP BY+E.EventType, T.Name";

        //$query = "query=SELECT COUNT(E.EventID) AS Total, T.Name FROM Orion.Events AS E LEFT JOIN Orion.EventTypes AS T ON E.EventType=T.EventType WHERE hourdiff(EventTime,GetUtcDate())<=24 GROUP BY T.Name";
        $query = "query=SELECT COUNT(E.EventID) AS Total, T.Name FROM Orion.Events AS E LEFT JOIN Orion.EventTypes AS T ON E.EventType=T.EventType WHERE (YEAR(ToLocal(EVENTTIME)) = ".date("Y")." AND MONTH(ToLocal(EVENTTIME))=".date("m")." AND DAY(ToLocal(EVENTTIME))=".date("d"). ") GROUP BY T.Name";

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);

        $data = json_decode($response, TRUE);
        //dd($data);
        //echo array_values( $data )[0][0]['Total'];
        $tmpstr =
        '<table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th></th>
                    <th style="text-align: center;">COUNT</th>
                    <th>EVENT</th>
                </tr>
            </thead>
            <tbody>';
        foreach (array_values( $data )[0] as $item) {
            $tmpstr = $tmpstr .
                '<tr>
                        <td style="vertical-align: middle; width:40px; text-align: center;">';
            if (strpos($item['Name'], 'Up') !== false) {
                $tmpstr = $tmpstr. '<img src="'.url('/').'/images/slwnpm/small/up.gif" style="width:14px;"/>';
            }elseif (strpos($item['Name'], 'Down') !== false) {
                $tmpstr = $tmpstr. '<img src="'.url('/').'/images/slwnpm/small/down.gif" style="width:14px;"/>';
            }elseif (strpos($item['Name'], 'Critical') !== false) {
                $tmpstr = $tmpstr. '<img src="'.url('/').'/images/slwnpm/small/critical" style="width:14px;"/>';
            }elseif (strpos($item['Name'], 'Alert') !== false) {
                $tmpstr = $tmpstr. '<img src="'.url('/').'/images/slwnpm/small/alert.gif" style="width:14px;"/>';
            }elseif (strpos($item['Name'], 'Warning') !== false) {
                $tmpstr = $tmpstr. '<img src="'.url('/').'/images/slwnpm/small/alert.gif" style="width:14px;"/>';
            }elseif (strpos($item['Name'], 'Fail') !== false) {
                $tmpstr = $tmpstr. '<img src="'.url('/').'/images/slwnpm/small/failed.gif" style="width:14px;"/>';
            }elseif (strpos($item['Name'], 'Remove') !== false) {
                $tmpstr = $tmpstr. '<img src="'.url('/').'/images/slwnpm/small/remove.gif" style="width:14px;"/>';
            }elseif (strpos($item['Name'], 'Remapped') !== false) {
                $tmpstr = $tmpstr. '<img src="'.url('/').'/images/slwnpm/small/remap.gif" style="width:14px;"/>';
            }elseif (strpos($item['Name'], 'Rebooted') !== false) {
                $tmpstr = $tmpstr. '<img src="'.url('/').'/images/slwnpm/small/reboot.gif" style="width:14px;"/>';
            }else{
                $tmpstr = $tmpstr. '<img src="'.url('/').'/images/slwnpm/small/Info.gif" style="width:14px;"/>';
            }
            //$tmpstr = $tmpstr. '<img src="'.url('/images/slwnpm/info.gif'). '" style="width:14px;"></img>';
            $tmpstr = $tmpstr.
                        '</td>
                        <td style="vertical-align: middle; text-align: center;"><a href="#" style="text-decoration:none;">'.$item['Total'].'</a></td>
                        <td style="vertical-align: middle;"><a href="#" style="text-decoration:none;">'.$item['Name'].'</a></td>
                </tr>';
        }

        $tmpstr = $tmpstr.
        '   </tbody>
        </table>';

        echo $tmpstr;

    }

    /*
    Page: All Nodes
    */
    public function nodes(){

        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }

        $dm=Crypt::decryptString(session('mymonitor_md'));

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT NodeID, DisplayName, IPAddress, StatusDescription, Unmanaged, UnmanageUntil, URI FROM Orion.Nodes ORDER BY DisplayName ASC";

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $data = json_decode($response, TRUE);
        $data = $data["results"];

        return view('slwnpm.nodes',compact('user','slwnpmserver','data'));
    }

    /*
    Page: Add Node
    */
    public function addnode(){

        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }

        $dm=Crypt::decryptString(session('mymonitor_md'));

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        return view('slwnpm.addnode',compact('user','slwnpmserver'));
    }

    /*
    Page: Add Node Submit
    */
    public function addnodesubmit(){
        $url = url('/');

        if (!Session::has('Monitor')){
            return redirect($url);
        }

        $dm=Crypt::decryptString(session('mymonitor_md'));

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port."/SolarWinds/InformationService/v3/Json/Create/Orion.Nodes";
        $client = new \GuzzleHttp\Client([
            'auth' => [$slwnpmserver->user,$slwnpmserver->password]
        ]);
        
        $client->request('POST', $apihost, [
            'headers' => ['Content-Type' => 'application/json'],
             'json' => [
                'ObjectSubType' => 'SNMP',
                'IPAddress' => Request('ipaddress'),
                'DynamicIP' => 'False',
                'Caption' => Request('nodename'),
                'NodeDescription' => '',
                'SysName' => Request('nodename'),
                'Location' => '',
                'Contact' => '',
                'IOSImage' => '',
                'IOSVersion' => '',
                'UnManaged' => 'False',
                'Allow64BitCounters' => 'True',
                'Community' => '',
                'Status' => 0,
                'EngineID' => 1,
                'SNMPVersion' => 2,
                'EntityType' => 'Orion.Nodes'
             ]
        ]);

        return redirect('/admin/slwnpm/nodes');
    }

    /*
    Page: Delete Node
    */
    public function deletenode($nodeid){

        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }

        $err_msg = '';
        $dm=Crypt::decryptString(session('mymonitor_md'));

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT NodeID, DisplayName, URI FROM Orion.Nodes WHERE NodeID='".$nodeid."'";

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $selectednode = json_decode($response, TRUE);
        $selectednode = $selectednode["results"][0];
            
        return view('slwnpm.deletenode',compact('user','selectednode','err_msg'));
    }

    /*
    Page: Delete Node Submit
    */
    public function deletenodesubmit($nodeid){
        $url = url('/');

        if (!Session::has('Monitor')){
            return redirect($url);
        }

        $err_msg = '';
        $dm=Crypt::decryptString(session('mymonitor_md'));

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();
            
        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT NodeID, DisplayName, URI FROM Orion.Nodes WHERE NodeID='".$nodeid."'";

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $selectednode = json_decode($response, TRUE);
        $selectednode = $selectednode["results"][0];

        if ($selectednode["DisplayName"]==Request('nodename')) {
            $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port."/SolarWinds/InformationService/v3/Json/".$selectednode["URI"];
            $client = new \GuzzleHttp\Client([
                'auth' => [$slwnpmserver->user,$slwnpmserver->password]
            ]);
            $client->request('DELETE', $apihost);
            return redirect('/admin/slwnpm/nodes');
        }else {
            $err_msg ='Wrong username! User was not deleted.';
            return view('slwnpm.deletenode',compact('user','selectednode','err_msg'));
        }
    }

    /*
    Page: Config server
    Section: Main view
    */
    public function configserver(){

        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }

        $dm=Crypt::decryptString(session('mymonitor_md'));

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();


        return view('slwnpm.serverconfig',compact('slwnpmserver','user'));
    }

    /*
    Page: Submit Config server
    Section: Submit form
    */
    public function slwnpmserversubmit(){

        $server = DB::table('tbl_slwnpmservers')
        ->where('domainid','=', Crypt::decryptString(session('mymonitor_md')))
        ->first();
        if (empty($server)){
            //Create
            DB::table('tbl_slwnpmservers')
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
            DB::table('tbl_slwnpmservers')
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
        $url = url('/').'/admin/slwnpm';
        return redirect($url);
    }

    /*
    Page: Dashboard solarwinds npm
    Widget Unacknowledge alerts
    Ajax
    */
    public function ajaxnpmunack(){

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT+TOP 10 D.Name, ObjectName, isnull(TriggerTimeStamp,'') AS TriggerTimeStamp, C.Severity, O.RelatedNodeId+FROM+Orion.AlertDefinitions D + LEFT JOIN Orion.AlertStatus S ON D.AlertDefID = S.AlertDefID+LEFT JOIN Orion.AlertConfigurations C ON C.AlertRefID = S.AlertDefID+LEFT JOIN Orion.AlertObjects O ON S.AlertObjectID = O.AlertObjectID+WHERE+S.Acknowledged=0+ORDER BY TriggerTimeStamp DESC";

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);

        $data = json_decode($response, TRUE);
        //dd($data);
        //echo array_values( $data )[0][0]['Total'];
        $tmpstr =' 
        <table class="table table-hover table-condensed">
            <tbody>';
        foreach (array_values( $data )[0] as $item) {
            $sdate = strtotime($item['TriggerTimeStamp']);
            $edate = strtotime(gmdate("Y-m-d\TH:i:s\Z"));
            $phut = round(($edate - $sdate)/60);

            if ($phut>60*24){
                //$days = round($phut/(60*24));
                //$hours = $phut - $phut/(60*24)*$days;
                //$time = $days . ' days '. $hours . 'hours';
                $time = '> 1d';
            }elseif ($phut>60) {
                $hours = floor($phut/60);
                $mins = $phut - 60*$hours;
                $time = $hours . 'h ' . $mins . 'm';
            }else{
                $time = $phut . 'm';
            }

            $utc = $item['TriggerTimeStamp'];
            $dt = new DateTime($utc);
            //echo 'Original: ', $dt->format('r'), PHP_EOL;
            $tz = new DateTimeZone('Asia/Ho_Chi_Minh');
            $dt->setTimezone($tz);

            $tmpstr = $tmpstr .
                '<tr>
                        <td style="vertical-align: middle; width:40px; text-align: center;">';
            if ($item['Severity']=='0') {
                $tmpstr = $tmpstr. '<img src="'.url('/').'/images/slwnpm/small/Info.gif" style="width:14px;"/>';
            }elseif ($item['Severity']=='1') {
                $tmpstr = $tmpstr. '<img src="'.url('/').'/images/slwnpm/small/alert.gif" style="width:14px;"/>';
            }elseif ($item['Severity']=='2') {
                $tmpstr = $tmpstr. '<img src="'.url('/').'/images/slwnpm/small/critical.gif" style="width:14px;"/>';
            }elseif ($item['Severity']=='3') {
                $tmpstr = $tmpstr. '<img src="'.url('/').'/images/slwnpm/small/critical.gif" style="width:14px;"/>';
            }else{
                $tmpstr = $tmpstr. '<img src="'.url('/').'/images/slwnpm/small/Info.gif" style="width:14px;"/>';
            }
            //$tmpstr = $tmpstr. '<img src="'.url('/images/slwnpm/info.gif'). '" style="width:14px;"></img>';
            $tmpstr = $tmpstr.
                        '</td>
                        <td style="vertical-align: middle; text-align: left;">
                            <div style="padding-top:5px;">
                            <a href="'.url('/').'/admin/slwnpm/nodesummary/'.$item['RelatedNodeId'].'" style="text-decoration:none;"><b><font color="#006699">'
                                .$item['Name'].'</font></b>
                            </a>
                            </div>
                            <div style="padding-top:5px; padding-bottom:5px;">
                            On <font color="#006699"><a href="'.url('/').'/admin/slwnpm/nodesummary/'.$item['RelatedNodeId'].'">'.$item['ObjectName'].'</a></font>
                            </div>
                        </td>
                        <td style="vertical-align: middle;" align="right"><a href="#" style="text-decoration:none;" title="From '.$dt->format('M d, yy h:i A').'">'.$time.'</a></td>
                        <td style="width:40px;"></td>
                </tr>';
        }

        $tmpstr = $tmpstr.
        '   </tbody>
        </table>';


        echo($tmpstr);

    }

    /*
    Page hiển thị chi tiết 1 node phần summary
    Nạp view (sẽ bỏ sau khi xong)
    */
    public function slwnpmnodedetail($nodeid){
        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT NodeID, ObjectSubType, N.IPAddress, IPAddressType, DynamicIP, Caption, NodeDescription, N.Description, DNS, SysName, Vendor, SysObjectID, Location, Contact, VendorIcon, Icon, Status, StatusLED, StatusDescription, CustomStatus, IOSImage, IOSVersion, GroupStatus, N.StatusIcon, LastBoot, SystemUpTime, LastSync, LastSystemUpTimePollUtc, MachineType, IsServer, Severity, Allow64BitCounters, PollInterval, RediscoveryInterval, NextPoll, NextRediscovery, StatCollection, External,N.IP, IP_Address, IPAddressGUID, NodeName, BlockUntil, EntityType, DetailsUrl, N.DisplayName, Category, IsOrionServer, CPUCount, E.DisplayName AS PoolDisplayName, E.IP as PoolIP FROM Orion.Nodes N left join (SELECT count(NodeID) as [CPUCount], nodeid FROM Orion.CPUMultiLoadCurrent group by nodeid) cpu on cpu.nodeid=N.nodeid LEFT JOIN Orion.Engines E on N.EngineID = E.EngineID WHERE NodeID =" . $nodeid;

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $data = json_decode($response, TRUE);
        if (isset(array_values( $data )[0][0])){
            $device = array_values( $data )[0][0];
        }else{
            $device = null;
        }

        $query = "query= SELECT I.StatusIcon, I.STATUS, I.Icon, I.FullName,I.InPercentUtil,I.OutPercentUtil, V.DisplayName as Vlan FROM Orion.NPM.Interfaces I JOIN Orion.NodeVlans V ON I.NodeID = V.NodeID where NodeID =" . $nodeid;
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $interfacedata = json_decode($response, TRUE);
        if (isset(array_values( $interfacedata )[0])){
            $interfaces = array_values( $interfacedata )[0];
        }else{
            $interfaces = null;
        }

        $query = "query= SELECT IPAddress, IPAddressType, SubnetMask FROM Orion.NodeIPAddresses where NodeID=" . $nodeid;
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $ipsdata = json_decode($response, TRUE);
        if (isset(array_values( $ipsdata )[0])){
            $ips = array_values( $ipsdata )[0];
        }else{
            $ips = null;
        }

        $edate = gmdate("Y-m-d\TH:i:s\Z",strtotime('- 7 days'));
        $query = "query= SELECT avg(MinLoad) as MinCPU, avg(MaxLoad) as MaxCPU, avg(AvgLoad) as AvgCPU, avg(MinMemoryUsed/TotalMemory*100) as MinMEM, avg(MaxMemoryUsed/TotalMemory*100) as MaxMEM, avg(AvgPercentMemoryUsed) as AvgMEM FROM Orion.CPULoad where NodeID = ".$nodeid." and daydiff(datetime,getdate())<7 group by NodeID";
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $cpuloaddata = json_decode($response, TRUE);
        if (isset(array_values( $cpuloaddata )[0][0])){
            $cpuload = array_values( $cpuloaddata )[0][0];
        }else{
            $cpuload = null;
        }

        $query = "query=SELECT COUNT(E.EventID) AS Total, T.Name FROM Orion.Events AS E LEFT JOIN Orion.EventTypes AS T ON E.EventType=T.EventType WHERE hourdiff(EventTime,GetUtcDate())<=24 AND NetworkNode=". $nodeid ." GROUP BY T.Name";
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $eventsumsdata = json_decode($response, TRUE);
        if(isset(array_values( $eventsumsdata )[0])){
            $eventsums = array_values( $eventsumsdata )[0];
        }else{
            $eventsums = null;
        }

        $query = "query=SELECT D.Name, ObjectName, isnull(TriggerTimeStamp,'') AS TriggerTimeStamp, C.Severity, S.AlertMessage FROM Orion.AlertDefinitions D LEFT JOIN Orion.AlertStatus S ON D.AlertDefID = S.AlertDefID LEFT JOIN Orion.AlertConfigurations C ON C.AlertRefID = S.AlertDefID LEFT JOIN Orion.AlertObjects O ON S.AlertObjectID = O.AlertObjectID WHERE O.RelatedNodeID = ".$nodeid." ORDER BY TriggerTimeStamp DESC";
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $alertsdata = json_decode($response, TRUE);
        if (isset(array_values( $alertsdata )[0])){
            $alerts = array_values( $alertsdata )[0];
        }else{
            $alerts = null;
        }

        return view('slwnpm.slwnpmnodedetail',compact('user','device','slwnpmserver','ips','cpuload','nodeid','interfaces','eventsums','alerts'));
    }

    /*
    Page hiển thị chi tiết 1 node phần network
    Nạp view
    */
    public function slwnpmnodenetwork($nodeid){
        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;

        //device
        $query = "query=SELECT SysName,StatusIcon,BufferNoMemThisHour, BufferNoMemToday, BufferSmMissThisHour, BufferSmMissToday, BufferMdMissThisHour, BufferMdMissToday, BufferBgMissThisHour, BufferBgMissToday, BufferLgMissThisHour, BufferLgMissToday, BufferHgMissThisHour, BufferHgMissToday FROM Orion.Nodes WHERE NodeID = " . $nodeid;

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $devicedata = json_decode($response, TRUE);
        if (isset(array_values( $devicedata )[0][0])){
            $device = array_values( $devicedata )[0][0];
        }else{
            $device = null;
        }

        //hardware health
        $query = "query=SELECT StatusDescription, Manufacturer, Model, ServiceTag, LastPollTime FROM Orion.HardwareHealth.HardwareInfo WHERE NodeID = " . $nodeid;

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $hwhealthdata = json_decode($response, TRUE);
        if (isset(array_values( $hwhealthdata )[0][0])){
            $hwhealth = array_values( $hwhealthdata )[0][0];
        }else{
            $hwhealth = null;
        }

        //flapping route
        $query = "query=SELECT TOP 10 RouteDestination,CIDR, NodeId, DateTime, RouteNextHop FROM Orion.Routing.RoutingTableFlap where nodeid = ".$nodeid. " ORDER BY DATETIME DESC";

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $flappingdata = json_decode($response, TRUE);
        if (isset(array_values( $flappingdata )[0])){
            $flappings = array_values( $flappingdata )[0];
        }else{
            $flappings = null;
        }

        //acive vlans
        $query = "query=SELECT VlanId, VlanName, VlanTag FROM Orion.NodeVlans where NodeID = ".$nodeid." AND VlanStatus =1";

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $vlansdata = json_decode($response, TRUE);
        if (isset(array_values( $vlansdata )[0])){
            $vlans = array_values( $vlansdata )[0];
        }else{
            $vlans = null;
        }

        //vrfs
        $query = "query=SELECT Status, RouteDistinguisher, Name, Description FROM Orion.Routing.VRF WHERE NodeID = ".$nodeid;

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $vrfsdata = json_decode($response, TRUE);
        if (isset(array_values( $vrfsdata )[0])){
            $vrfs = array_values( $vrfsdata )[0];
        }else{
            $vrfs = null;
        }

        //routing neighbors
        $query = "query=SELECT  DISTINCT NeighborIP, rp.DisplayName, rpsm.displayname as status,rn.lastchange FROM orion.routing.Neighbors rn LEFT JOIN orion.routing.RoutingProtocolStateMapping rpsm  ON rn.ProtocolID=rpsm.ProtocolID  AND rn.ProtocolStatus=rpsm.ProtocolStatus left join orion.routing.RoutingProtocol rp on rn.ProtocolID=rp.ProtocolID left join orion.Nodes  on rn.NodeID=nodes.NodeID where nodes.NodeID = ".$nodeid;

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $r_neighborsdata = json_decode($response, TRUE);
        if (isset(array_values( $r_neighborsdata )[0])){
            $r_neighbors = array_values( $r_neighborsdata )[0];
        }else{
            $r_neighbors = null;
        }

        //Utilization
        $query = "query= SELECT I.InterfaceID, I.StatusIcon, I.STATUS, I.Icon, I.FullName,I.InPercentUtil,I.OutPercentUtil, V.DisplayName as Vlan FROM Orion.NPM.Interfaces I JOIN Orion.NodeVlans V ON I.NodeID = V.NodeID where NodeID =" . $nodeid;
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $interfacedata = json_decode($response, TRUE);
        if (isset(array_values( $interfacedata )[0])){
            $interfaces = array_values( $interfacedata )[0];
        }else{
            $interfaces = null;
        }

        //Routing Table
        $query = "query= SELECT DISTINCT R.RouteDestination, R.RouteNextHop, R.Metric, I.Caption, I.StatusIcon, R.ProtocolName FROM Orion.Routing.RoutingTable R LEFT JOIN Orion.NPM.Interfaces I ON R.InterfaceIndex = I.InterfaceIndex AND I.NodeID = I.NodeID WHERE R.NodeID = ".$nodeid." AND I.NodeID = ".$nodeid." ORDER BY R.RouteDestination";
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $routingtabledata = json_decode($response, TRUE);
        if (isset(array_values( $routingtabledata )[0])){
            $routingtables = array_values( $routingtabledata )[0];
        }else{
            $routingtables = null;
        }

        return view('slwnpm.slwnpmnodenetwork',compact('user','nodeid','slwnpmserver','hwhealth','flappings','vlans', 'vrfs','r_neighbors','interfaces','device', 'routingtables'));
    }

    /*
    Page dashboard solarwinds npm
    Nạp ajax treeview các nodes
    */
    public function npmnodejstree(){
        /////
        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();


        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $i = 0;

        //Nếu đã cấu hình group by

        if ($slwnpmserver->nodegroupby=='Default'){

            $query = "query=SELECT+NodeId,DisplayName,Status,StatusIcon,IPAddress,Vendor+FROM+ORION.Nodes ORDER BY Status";
            $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
            $data = json_decode($response, TRUE);
            //Group theo up down
            $i++;
            $tmpstr = '[';

            //Up device
            //$img = 'http://' . $slwnpmserver->hostname . '/Orion/images/StatusIcons/small-up.gif';
            $img = url('/') . '/images/slwnpm/StatusIcons/small-up.gif';
            $tmpstr = $tmpstr . '{"id":1,"text":"UP","icon":"'.$img.'","children":';
            $tmpstr = $tmpstr. '[';
            foreach (array_values( $data )[0] as $item) {

                if ($item['Status']==1){
                    $i++;
                    $href = url('/') . '/admin/slwnpm/nodedsummary/' . $item['NodeId'];
                    //$img = 'http://' . $slwnpmserver->hostname . '/Orion/images/StatusIcons/small-' . $item['StatusIcon'];
                    $img = url('/') . '/images/slwnpm/StatusIcons/small-' . $item['StatusIcon'];
                    $tmpstr = $tmpstr. '{"id":"'. $i. '","text":"' . $item['DisplayName'] . '","icon":"'.$img.'","a_attr":{"href":"'.$href.'"}},';
                    //$tmpstr = $tmpstr. '{"id":"'. $i. '","text":"' . $item['DisplayName'] . '","icon":"'.$img.'"},';
                }
            }
            $tmpstr = substr($tmpstr, 0, -1);
            $tmpstr = $tmpstr. ']}';
            //End of Up device

            //Down device
            //$img = 'http://' . $slwnpmserver->hostname . '/Orion/images/StatusIcons/small-down.gif';
            $img = url('/') . '/images/slwnpm/StatusIcons/small-down.gif';
            $i++;
            $tmpstr = $tmpstr . ',{"id":'.$i.',"text":"DOWN","icon":"'.$img.'","children":';
            $tmpstr = $tmpstr. '[';
            foreach (array_values( $data )[0] as $item) {
                if ($item['Status']!=1){
                    $i++;
                    $href = url('/') . '/admin/slwnpm/nodesummary/' . $item['NodeId'];
                    //$img = 'http://' . $slwnpmserver->hostname . '/Orion/images/StatusIcons/small-' . $item['StatusIcon'];
                    $img = url('/') . '/images/slwnpm/StatusIcons/small-' . $item['StatusIcon'];
                    $tmpstr = $tmpstr. '{"id":"'. $i. '","text":"' . $item['DisplayName'] . '","icon":"'.$img.'","a_attr":{"href":"'.$href.'"}},';
                }
            }
            $tmpstr = substr($tmpstr, 0, -1);
            $tmpstr = $tmpstr. ']}';
            //End of down

            $tmpstr = $tmpstr. ']';
            //Kết thúc group theo up/down

        }else{
            //Sap xep theo custom category
            $query = "query=SELECT N.NodeId, N.DisplayName, N.Status, N.StatusIcon, N.IPAddress, N.Vendor, P.".$slwnpmserver->nodegroupby." FROM ORION.Nodes N Left join Orion.NodesCustomProperties P ON N.NodeID = P.NodeID ORDER BY Status";
            $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
            $data = json_decode($response, TRUE);

            $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
            $query = "query=SELECT DISTINCT ". $slwnpmserver->nodegroupby. " FROM Orion.NodesCustomProperties";
            $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
            $catogories = json_decode($response, TRUE);

            //Root
            $i=1;
            $tmpstr = '[';

            foreach (array_values( $catogories)[0] as $category) {
                $i++;
                $tmpstr = $tmpstr. '{"id":"'.$i.'","text":"' . $category[$slwnpmserver->nodegroupby] . '","icon":"fas fa-exclamation-triangle text-warning","children":';
                //Group theo up down
                $i++;
                $tmpstr = $tmpstr.'[';

                //
                //$img = 'http://' . $slwnpmserver->hostname . '/Orion/images/StatusIcons/small-up.gif';
                foreach (array_values( $data )[0] as $item) {

                    if ($item[$slwnpmserver->nodegroupby]==$category[$slwnpmserver->nodegroupby]){
                        $i++;
                        $href = url('/') . '/admin/slwnpm/nodesummary/' . $item['NodeId'];
                        //$img = 'http://' . $slwnpmserver->hostname . '/Orion/images/StatusIcons/small-' . $item['StatusIcon'];
                        $img = url('/') . '/images/slwnpm/StatusIcons/small-' . $item['StatusIcon'];

                        $tmpstr = $tmpstr. '{"id":"'. $i. '","text":"' . $item['DisplayName'] . '","icon":"'.$img.'","a_attr":{"href":"'.$href.'"}},';
                        //$tmpstr = $tmpstr. '{"id":"'. $i. '","text":"' . $item['DisplayName'] . '","icon":"'.$img.'"},';
                    }
                }
                $tmpstr = substr($tmpstr, 0, -1);
                $tmpstr = $tmpstr. ']},';
                //
            }

            //End of root
            $tmpstr = substr($tmpstr, 0, -1);
            $tmpstr = $tmpstr . ']';

        }


        echo($tmpstr);
    }

    /*
    Page hiển thị trang cấu hình group by
    Nạp view
    */
    public function configgroup(){
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

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', $dm]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT Field FROM Orion.CustomPropertyUsage WHERE Table = 'NodesCustomProperties'";
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);

        $groupsdata = json_decode($response, TRUE);

        if (isset(array_values( $groupsdata )[0])){
            $groups = array_values( $groupsdata )[0];
        }else{
            $groups = null;
        }

        return view('slwnpm.configgroup',compact('slwnpmserver','user','groups'));
    }

    /*
    Hàm submit lưu cấu hình groupby
    */
    public function configgroupsubmit(){
        $server = DB::table('tbl_slwnpmservers')
        ->where('domainid','=', Crypt::decryptString(session('mymonitor_md')))
        ->first();
        if (empty($server)){
            //Create
            DB::table('tbl_slwnpmservers')
            ->insert(
            [
                'domainid' => Crypt::decryptString(session('mymonitor_md')),
                'nodegroupby' => Request('nodegroupby')
            ]
            );

        }else{
            //Update
            DB::table('tbl_slwnpmservers')
            ->where('domainid', '=', Crypt::decryptString(session('mymonitor_md')))
            ->update([
                'nodegroupby' => Request('nodegroupby')
                ]);
        }
        $url = url('/').'/admin/slwnpm';
        return redirect($url);
    }

    /*
    Page hiển thị chi tiết 1 node phần summary
    Nạp view chính của node summary
    */
    public function nodesummary($nodeid){
        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();


        return view('slwnpm.slwnpmnodesummary',compact('user','slwnpmserver','nodeid'));
    }

    /*
    Page hiển thị chi tiết 1 node phần summary
    Nạp ajax thông tin chung về node
    */
    public function nodesummarydevice($nodeid){
        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT NodeID, ObjectSubType, N.IPAddress, IPAddressType, DynamicIP, Caption, NodeDescription, N.Description, DNS, SysName, Vendor, SysObjectID, Location, Contact, VendorIcon, Icon, Status, StatusLED, StatusDescription, CustomStatus, IOSImage, IOSVersion, GroupStatus, N.StatusIcon, LastBoot, SystemUpTime, LastSync, LastSystemUpTimePollUtc, MachineType, IsServer, Severity, Allow64BitCounters, PollInterval, RediscoveryInterval, NextPoll, NextRediscovery, StatCollection, External,N.IP, IP_Address, IPAddressGUID, NodeName, BlockUntil, EntityType, DetailsUrl, N.DisplayName, Category, IsOrionServer, CPUCount, E.DisplayName AS PoolDisplayName, E.IP as PoolIP FROM Orion.Nodes N left join (SELECT count(NodeID) as [CPUCount], nodeid FROM Orion.CPUMultiLoadCurrent group by nodeid) cpu on cpu.nodeid=N.nodeid LEFT JOIN Orion.Engines E on N.EngineID = E.EngineID WHERE NodeID =" . $nodeid;

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $data = json_decode($response, TRUE);
        if (isset(array_values( $data )[0][0])){
            $device = array_values( $data )[0][0];
        }else{
            $device = null;
        }
        return $device;
    }

    /*
    Page hiển thị chi tiết 1 node phần summary
    Nạp ajax danh sách utilization interfaces
    */
    public function nodesummaryinterfaces($nodeid){
        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query= SELECT I.InterfaceID, I.StatusIcon, I.STATUS, I.Icon, I.FullName,I.InPercentUtil,I.OutPercentUtil, V.DisplayName as Vlan FROM Orion.NPM.Interfaces I JOIN Orion.NodeVlans V ON I.NodeID = V.NodeID where NodeID =" . $nodeid;
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $interfacedata = json_decode($response, TRUE);
        if (isset(array_values( $interfacedata )[0])){
            $interfaces = array_values( $interfacedata )[0];
        }else{
            $interfaces = null;
        }
        return $interfaces;
    }

    /*
    Page hiển thị chi tiết 1 node phần summary
    Nạp ajax cpuload 7 days
    */
    public function nodesummarycpuload($nodeid){
        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query= SELECT avg(MinLoad) as MinCPU, avg(MaxLoad) as MaxCPU, avg(AvgLoad) as AvgCPU, round(avg(MinMemoryUsed/TotalMemory*100),0) as MinMEM, round(avg(MaxMemoryUsed/TotalMemory*100),0) as MaxMEM, round(avg(AvgPercentMemoryUsed),0) as AvgMEM FROM Orion.CPULoad where NodeID = ".$nodeid." and daydiff(datetime,getdate())<7 group by NodeID";
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $cpuloaddata = json_decode($response, TRUE);
        if (isset(array_values( $cpuloaddata )[0][0])){
            $cpuload = array_values( $cpuloaddata )[0][0];
        }else{
            $cpuload = null;
        }
        return $cpuload;
    }

    /*
    Page hiển thị chi tiết 1 node phần summary
    Nạp ajax all ips on node
    */
    public function nodesummaryallip($nodeid){
        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query= SELECT IPAddress, IPAddressType, SubnetMask FROM Orion.NodeIPAddresses where NodeID=" . $nodeid;
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $ipsdata = json_decode($response, TRUE);
        if (isset(array_values( $ipsdata )[0])){
            $ips = array_values( $ipsdata )[0];
        }else{
            $ips = null;
        }
        return $ips;
    }

    /*
    Page: Node summary
    Purpose: Display node detail interface
    Nạp ajax last 24 hours event sum
    */
    public function nodesummaryeventsum($nodeid){
        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT COUNT(E.EventID) AS Total, T.Name FROM Orion.Events AS E LEFT JOIN Orion.EventTypes AS T ON E.EventType=T.EventType WHERE (YEAR(ToLocal(EVENTTIME)) = YEAR(TOLOCAL(GETUTCDATE())) AND MONTH(ToLocal(EVENTTIME))= MONTH(TOLOCAL(GETUTCDATE())) AND DAY(ToLocal(EVENTTIME))= DAY(TOLOCAL(GETUTCDATE())) ) AND(((NetObjectID= ".$nodeid.")AND(NetObjectType<>'I')) OR ((NetObjectID IN (select InterfaceID FROM Orion.NPM.Interfaces WHERE NodeID = ".$nodeid.")) AND (NetObjectType='I')))  GROUP BY T.Name";
        //$query = "query=SELECT COUNT(E.EventID) AS Total, T.Name FROM Orion.Events AS E LEFT JOIN Orion.EventTypes AS T ON E.EventType=T.EventType WHERE (YEAR(ToLocal(EVENTTIME)) = ".date("Y")." AND MONTH(ToLocal(EVENTTIME))=".date("m")." AND DAY(ToLocal(EVENTTIME))=".date("d"). ") AND (E.NetObjectType = 'I') AND (E.NetObjectID = ".$interfaceid.") GROUP BY T.Name";
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $eventsumsdata = json_decode($response, TRUE);
        if(isset(array_values( $eventsumsdata )[0])){
            $eventsums = array_values( $eventsumsdata )[0];
        }else{
            $eventsums = null;
        }
        return $eventsums;
    }

    /*
    Page hiển thị chi tiết 1 node phần summary
    Nạp ajax all alerts
    */
    public function nodesummaryalerts($nodeid){
        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT D.Name, ObjectName, isnull(TriggerTimeStamp,'') AS TriggerTimeStamp, C.Severity, S.AlertMessage FROM Orion.AlertDefinitions D LEFT JOIN Orion.AlertStatus S ON D.AlertDefID = S.AlertDefID LEFT JOIN Orion.AlertConfigurations C ON C.AlertRefID = S.AlertDefID LEFT JOIN Orion.AlertObjects O ON S.AlertObjectID = O.AlertObjectID WHERE O.RelatedNodeID = ".$nodeid." ORDER BY TriggerTimeStamp DESC";
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $alertsdata = json_decode($response, TRUE);
        if (isset(array_values( $alertsdata )[0])){
            $alerts = array_values( $alertsdata )[0];
        }else{
            $alerts = null;
        }
        return $alerts;
    }


    /*
    Page hiển thị chi tiết 1 interface
    Nạp view chính của interface
    */
    public function interfacedetail($interfaceid){
        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT I.NodeID, InterfaceID, I.ObjectSubType, I.Name, I.Index, I.Icon, I.Type, I.TypeName, I.TypeDescription, I.Speed, I.MTU, I.LastChange, I.PhysicalAddress, I.AdminStatus, I.OperStatus, I.StatusIcon, I.InBandwidth, I.OutBandwidth, I.Caption, I.FullName, Round(I.Outbps,0) AS Outbps, Round(I.Inbps,0) AS Inbps, I.Bps, I.OutPercentUtil, I.InPercentUtil, I.PercentUtil, round(I.OutPps,1) AS OutPps, round(I.InPps,1) AS InPps, I.InPktSize, I.OutPktSize, I.OutUcastPps, I.OutMcastPps, I.InUcastPps, I.InMcastPps, I.InDiscardsThisHour, I.InDiscardsToday, I.InErrorsThisHour, I.InErrorsToday, I.OutDiscardsThisHour, I.OutDiscardsToday, I.OutErrorsThisHour, I.OutErrorsToday,I.Counter64, I.LastSync, I.Alias, I.IfName, I.Severity, I.CustomBandwidth, I.CustomPollerLastStatisticsPoll, I.PollInterval, I.NextPoll, I.RediscoveryInterval, I.NextRediscovery, I.StatCollection, I.UnPluggable, I.InterfaceSpeed, I.InterfaceCaption, I.InterfaceType, I.InterfaceSubType, I.MAC, I.InterfaceName, I.InterfaceIcon, I.InterfaceTypeName, I.AdminStatusLED, I.OperStatusLED, I.InterfaceAlias, I.InterfaceIndex, I.InterfaceLastChange, I.InterfaceMTU, I.InterfaceTypeDescription, I.OrionIdPrefix, I.DuplexMode, I.SkippedPollingCycles, I.MinutesSinceLastSync, I.Status, I.InterfaceResponding, I.Description, A.IPAddress, E.DisplayName, E.IP AS EngineIP FROM Orion.NPM.Interfaces I LEFT JOIN Orion.NodeIPAddresses A ON I.InterfaceIndex = A.InterfaceIndex AND I.NodeID = A.NodeID LEFT JOIN Orion.Nodes N ON I.NodeID = N.NodeID LEFT JOIN Orion.Engines E ON N.EngineID = E.EngineID where I.InterfaceID = " . $interfaceid;
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $devicedata = json_decode($response, TRUE);
        if (isset(array_values( $devicedata )[0][0])){
            $device = array_values( $devicedata )[0][0];
        }else{
            $device = null;
        }

        return view('slwnpm.interfacedetail',compact('user','slwnpmserver','interfaceid','device'));
    }

    /*
    Page hiển thị chi tiết 1 interface
    Widget Event summary AJAX (today)
    */
    public function interfacedetaileventsum($interfaceid){
        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT COUNT(E.EventID) AS Total, T.Name FROM Orion.Events AS E LEFT JOIN Orion.EventTypes AS T ON E.EventType=T.EventType WHERE (YEAR(ToLocal(EVENTTIME)) = YEAR(TOLOCAL(GETUTCDATE())) AND MONTH(ToLocal(EVENTTIME))=MONTH(TOLOCAL(GETUTCDATE())) AND DAY(ToLocal(EVENTTIME))=DAY(TOLOCAL(GETUTCDATE()))) AND (E.NetObjectType = 'I') AND (E.NetObjectID = ".$interfaceid.") GROUP BY T.Name";
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $data = json_decode($response, TRUE);
        if (isset(array_values( $data )[0])){
            $eventsums = array_values( $data )[0];
        }else{
            $eventsums = null;
        }
        return $eventsums;
    }

    /*
    Page hiển thị chi tiết 1 interface
    Widget Interface Utilization AJAX (today)
    */
    public function interfacedetailpercentutil($interfaceid){
        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;

        $query = "query=SELECT HOUR(TOLOCAL(DATETIME)) AS STATTIME, AVG(OutPercentUtil) AS OutPercentUtil, AVG(InPercentUtil) AS InPercentUtil, AVG(PercentUtil) AS PercentUtil FROM Orion.NPM.InterfaceTraffic WHERE InterfaceID = ".$interfaceid." AND MONTH(TOLOCAL(DATETIME))=MONTH(TOLOCAL(GETUTCDATE())) AND YEAR(TOLOCAL(DATETIME))=YEAR(TOLOCAL(GETUTCDATE())) AND DAY(TOLOCAL(DATETIME))=DAY(TOLOCAL(GETUTCDATE())) GROUP BY HOUR(TOLOCAL(DATETIME))";
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $data = json_decode($response, TRUE);
        if (isset(array_values( $data )[0])){
            $errordiscards = array_values( $data )[0];
        }else{
            $errordiscards = null;
        }
        return $errordiscards;
    }

    /*
    Page hiển thị chi tiết 1 interface
    Widget Error / Discards AJAX (today)
    */
    public function interfacedetailerrordiscards($interfaceid){
        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;

        //$query = 'query=SELECT HOUR(TOLOCAL(DATETIME)) AS STATTIME, AVG(OutPercentUtil) AS OutPercentUtil, AVG(InPercentUtil) AS InPercentUtil, AVG(PercentUtil) AS PercentUtil FROM Orion.NPM.InterfaceTraffic WHERE InterfaceID = 4817 AND MONTH(TOLOCAL(DATETIME))=9 AND YEAR(TOLOCAL(DATETIME))=2020 AND DAY(TOLOCAL(DATETIME))=23 GROUP BY HOUR(TOLOCAL(DATETIME))';
        $query = 'query=SELECT HOUR(TOLOCAL(DATETIME)) AS STATTIME, AVG(InDiscards) AS InDiscards, AVG(OutDiscards) AS OutDiscards, AVG(InErrors) AS InErrors, AVG(OutErrors) AS OutErrors FROM Orion.NPM.InterfaceErrors  WHERE InterfaceID = '.$interfaceid.' AND MONTH(TOLOCAL(DATETIME))=MONTH(TOLOCAL(GETUTCDATE())) AND YEAR(TOLOCAL(DATETIME))=YEAR(TOLOCAL(GETUTCDATE())) AND DAY(TOLOCAL(DATETIME))=DAY(TOLOCAL(GETUTCDATE())) GROUP BY HOUR(TOLOCAL(DATETIME))';

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $data = json_decode($response, TRUE);
        if (isset(array_values( $data )[0])){
            $percentutil = array_values( $data )[0];
        }else{
            $percentutil = null;
        }
        return $percentutil;
    }

    /*
    Page hiển thị chi tiết 1 interface
    Widget Downtime AJAX (last 24h)
    */
    public function interfacedetaildowntime($interfaceid){
        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;

        $query = 'query=SELECT State, TotalDurationMin, DateTimeFrom, DateTimeUntil FROM Orion.NPM.InterfaceNetObjectDowntime WHERE HOURDIFF(TOLOCAL(DateTimeFrom),TOLOCAL(GETUTCDATE())) <24 AND DateTimeUntil is not NULL AND EntityID = ' . $interfaceid;

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $data = json_decode($response, TRUE);
        if (isset(array_values( $data )[0])){
            $percentutil = array_values( $data )[0];
        }else{
            $percentutil = null;
        }
        return $percentutil;
    }

    /*
    Page hiển thị chi danh sách events
    Nạp view chính của events
    */
    public function events(){
        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query= select DisplayName, NodeID from orion.nodes order by DisplayName";
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $nodesdata = json_decode($response, TRUE);
        if (isset(array_values( $nodesdata )[0])){
            $nodes = array_values( $nodesdata )[0];
        }else{
            $nodes = null;
        }

        $query = "query= SELECT EventType, Name FROM Orion.EventTypes";
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $eventtypesdata = json_decode($response, TRUE);
        if (isset(array_values( $eventtypesdata )[0])){
            $eventtypes = array_values( $eventtypesdata )[0];
        }else{
            $eventtypes = null;
        }


        return view('slwnpm.events',compact('user','slwnpmserver','nodes','eventtypes'));
    }

    /*
    Page hiển thị chi danh sách events sau khi submit
    Nạp view chính của events sau khi submit
    */
    public function eventssubmit(){
        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query= select DisplayName, NodeID from orion.nodes order by DisplayName";
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $nodesdata = json_decode($response, TRUE);
        if (isset(array_values( $nodesdata )[0])){
            $nodes = array_values( $nodesdata )[0];
        }else{
            $nodes = null;
        }

        $query = "query= SELECT EventType, Name FROM Orion.EventTypes";
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $eventtypesdata = json_decode($response, TRUE);
        if (isset(array_values( $eventtypesdata )[0])){
            $eventtypes = array_values( $eventtypesdata )[0];
        }else{
            $eventtypes = null;
        }

        $selectednode = Request('selectednode');
        $selectedeventtype  = Request('selectedeventtype');
        $selectedtime = Request('selectedtime');

        $fromdate = date_format(date_create(Request('fromdate')),"d F, Y");
        $fromtime = Request('fromtime');
        $todate = date_format(date_create(Request('todate')),"d F, Y");
        $totime = Request('totime');

        $limit = Request('limit');

        $wherenode = '';
        if($selectednode==''){
            //Không chọn node
            $wherenode = ' (1=1)';
        }else{
            //Chọn node
            $wherenode = " ((NetObjectID = " . $selectednode . " AND NetObjectType<>'I'" . ") OR (NetObjectID IN (select InterfaceID from orion.npm.interfaces where NodeID = " .$selectednode. " ) AND NetObjectType='I'))";
        }

        $whereventtype = '';
        if($selectedeventtype=='All events'){
            //Không chọn event type
            $whereventtype = ' AND (1=1)';
        }else{
            //Chọn event type
            //Thì phải lấy cả node or interface của node
            //NetObjectID IN (select InterfaceID from orion.npm.interfaces where NodeID = selectednode)
            //$whereventtype = "( (E.EventType = " . $selectedeventtype . $wherenode.  ") OR (E.EventType = " . $selectedeventtype . " AND NetObjectID IN (select InterfaceID from orion.npm.interfaces where NodeID = ".$selectednode . ") ) )";
            $whereventtype = ' AND (E.EventType = ' . $selectedeventtype . ')';
        }

        $wheretime = '';
        switch ($selectedtime) {
            case 1:
                $wheretime =' AND ( HOURDIFF(TOLOCAL(EventTime),TOLOCAL(GETUTCDATE())) <1 )';
                break;
            case 2:
                $wheretime =' AND ( HOURDIFF(TOLOCAL(EventTime),TOLOCAL(GETUTCDATE())) <2 )';
                break;
            case 3:
                $wheretime =' AND ( HOURDIFF(TOLOCAL(EventTime),TOLOCAL(GETUTCDATE())) <24 )';
                break;
            case 4:
                $wheretime =' AND ( MONTH(TOLOCAL(EventTime))=MONTH(TOLOCAL(GETUTCDATE())) AND YEAR(TOLOCAL(EventTime))=YEAR(TOLOCAL(GETUTCDATE())) AND DAY(TOLOCAL(EventTime))=DAY(TOLOCAL(GETUTCDATE())) )';
                break;
            case 5:
                $wheretime =' AND ( YEAR(TOLOCAL(EventTime))=YEAR(TOLOCAL(GETUTCDATE())) AND MONTH(TOLOCAL(EventTime))=MONTH(TOLOCAL(GETUTCDATE())) AND DAY(TOLOCAL(EventTime))=DAY(TOLOCAL(GETUTCDATE()))-1 )';
                break;
            case 6:
                $wheretime =' AND ( DAYDIFF(TOLOCAL(EventTime),TOLOCAL(GETUTCDATE())) <7 )';
                break;
            case 7:
                $wheretime =' AND ( MONTH(TOLOCAL(EventTime))=MONTH(TOLOCAL(GETUTCDATE())) )';
                break;
            case 8:
                $wheretime =' AND ( YEAR(TOLOCAL(EventTime))=YEAR(TOLOCAL(GETUTCDATE())) AND MONTH(TOLOCAL(EventTime))=MONTH(TOLOCAL(GETUTCDATE()))-1 )';
                break;
            case 9:
                $wheretime =' AND ( DAYDIFF(TOLOCAL(EventTime),TOLOCAL(GETUTCDATE())) <30 )';
                break;
            case 10:
                $wheretime =' AND ( MONTH(TOLOCAL(GETUTCDATE())) - MONTH(TOLOCAL(EventTime)) >=3 )';
                break;
            case 11:
                $wheretime =' AND ( YEAR(TOLOCAL(EventTime))=YEAR(TOLOCAL(GETUTCDATE())) )';
                break;
            case 12:
                $wheretime =' AND ( MONTHDIFF(TOLOCAL(EventTime),TOLOCAL(GETUTCDATE())) <12 )';
                break;
            case 13:
                //where TOLOCAL(EventTime) > '09/24/2020 03:05 AM' AND EventTime < '07/05/2025 23:00 PM'
                //where EventTime > '09/24/2020 00:28:49.259' AND EventTime < '07/05/2025 15:28:50'
                //$wheretime = "AND (TOLOCAL(EventTime)>='". $fromdate. " " . $fromtime . "' AND TOLOCAL(EventTime)<='". $fromdate. " " . $fromtime . "')";
                $from = date_format(date_create(Request('fromdate')),"m/d/Y ") . ' ' . Request('fromtime');
                $to = date_format(date_create(Request('todate')),"m/d/Y ") . ' '. Request('totime');
                $wheretime = " AND (TOLOCAL(EventTime) > '" . $from . "' AND TOLOCAL(EventTime) < '" . $to ."')";
                break;
            default:
                $wheretime = ' AND (1=1)';
                break;
        }

        //All condition
        $where = $wherenode . $whereventtype . $wheretime;

        //Get events
        $query = "query= SELECT TOP " . $limit . " E.EventTime,E.Message,T.Name,E.EventType,E.NetObjectType,E.NetObjectID FROM Orion.Events AS E LEFT JOIN Orion.EventTypes AS T ON+E.EventType=T.EventType where " . $where ." ORDER BY E.EventTime DESC";
        //dd($query);

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $eventsdata = json_decode($response, TRUE);
        if (isset(array_values( $eventsdata )[0])){
            $events = array_values( $eventsdata )[0];
        }else{
            $events = null;
        }

        return view('slwnpm.events',compact('user','slwnpmserver','nodes','selectednode','selectedeventtype','eventtypes','selectedtime','fromdate','todate','fromtime','totime','limit', 'events'));
    }

    /*
    Page hiển thị chi danh sách alerts
    Nạp view chính của alerts
    */
    public function alerts(){
        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query= SELECT AlertID,o.AlertConfigurations.Name AS AlertName, EntityCaption AS AlertObject, RelatedNodeCaption AS RelatedNodeCaption, o.AlertActive.TriggeredDateTime AS TriggerTime, o.AlertActive.TriggeredMessage AS AlertMessage, RelatedNodeId, n.StatusIcon FROM Orion.AlertObjects o left join Orion.Nodes n ON n.NodeID = o.RelatedNodeId WHERE o.AlertActive.TriggeredMessage <> '' ORDER by o.AlertActive.TriggeredDateTime DESC";

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $data = json_decode($response, TRUE);
        if (isset(array_values( $data )[0])){
            $alerts = array_values( $data )[0];
        }else{
            $alerts = null;
        }
        //dd($alerts);

        return view('slwnpm.alerts',compact('user','slwnpmserver','alerts'));
    }

    /*
    Page: Dashboard
    Widget Chat
    Get chat
    */
    public function getchat(){

        $messages = DB::table('tbl_chat')
        ->leftJoin('tbl_accounts', 'tbl_accounts.userid', '=', 'tbl_chat.userid')
        ->where([
            ['tbl_chat.product', '=', 'slwnpm'],
            ['tbl_chat.domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])
        ->orderby('timestamp','DESC')
        ->take(1000)->get();

        $tmpstr = '';

        foreach ($messages as $message){
            $utc = '@'.$message->timestamp;
            $dt = new DateTime($utc);
            $tz = new DateTimeZone('Asia/Ho_Chi_Minh');
            $dt->setTimezone($tz);
            $tmpstr = $tmpstr . '<div style="margin: 10px;">(' . $dt->format('M d, yy H:i:s') . ') ';
            $tmpstr = $tmpstr . '<b>' . $message->username . '</b>: ' . $message->message;
            $tmpstr = $tmpstr . '</div>';
        }

        echo($tmpstr);
    }

    /*
    Page: Dashboard
    Widget Chat
    Insert chat into DB
    */
    public function storechat(Request $request){
        $data = $request->all();

        if($data['message']==''){
            return response()->json(['failure'=>'Message is empty']);
            //return response()->json(['success'=>time()]);
        }
        else
        {
            #create or update your data here
            DB::table('tbl_chat')
                ->insert(
                [
                    'domainid' => Crypt::decryptString(session('mymonitor_md')),
                    'message' => $data['message'],
                    'userid' => $data['userid'],
                    'product' => 'slwnpm',
                    'timestamp' => time()
                ]
            );

            return response()->json(['success'=>'Your message has been insert successfully']);
            //return response()->json(['success'=>time()]);
        }
    }

    /*
    Page: Search Result
    Hiển thị view Search result
    */
    public function search(){
        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }

        $searchtext = Request('searchtext');
        $searchtype = Request('searchtype');

        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $condition = '';
        switch (Request('searchtype')) {
            case 'Node Name':
                $condition = " WHERE (Caption like '%" . Request('searchtext') . "%')";
                break;
            case 'IP Address':
                $condition = " WHERE (IPAddress like '".$searchtext."%') ";
                break;
            case 'Machine Type':
                $condition = " WHERE (MachineType like '%" . Request('searchtext') . "%')";
                break;
            case 'Vendor':
                $condition = " WHERE (Vendor like '%" . Request('searchtext') . "%')";
                break;
            case 'Description':
                $condition = " WHERE (Description like '%" . Request('searchtext') . "%')";
                break;
            default:
                $condition = '';
                break;
        }

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query= SELECT TOP 1000 NodeID, NodeName, MachineType, StatusDescription, IOSImage, IOSVersion, IPAddress from Orion.Nodes ".$condition." ORDER BY NodeName";

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $data = json_decode($response, TRUE);
        if (isset(array_values( $data )[0])){
            $nodes = array_values( $data )[0];
        }else{
            $nodes = null;
        }

        return view('slwnpm.search',compact('user','slwnpmserver','nodes','searchtext','searchtype'));
    }

    /*
    Page: Report
    Hiển thị view Report
    */
    public function report(){
        $slwnpmserver = DB::table('tbl_slwnpmservers')
            ->where([
                ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
            ])->first();
        $totalNodeUp = 0;
        $totalNodeDown = 0;
        $totalIntUp = 0;
        $totalIntDown = 0;
        //region total node up

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT+COUNT(NodeId) AS NodesCount+FROM+ORION.Nodes+WHERE+Status=1";

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $data = json_decode($response, TRUE);
        $totalNodeUp = array_values( $data )[0][0]['NodesCount'];
        //endregion

        //region total node down

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT+COUNT(NodeId) AS NodesCount+FROM+ORION.Nodes+WHERE+Status=2";

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $data = json_decode($response, TRUE);
        $totalNodeDown = array_values( $data )[0][0]['NodesCount'];
        //end region

        //region int up
        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT+COUNT(InterfaceID) AS IntCount+FROM+Orion.NPM.Interfaces+WHERE+OperStatus=1";

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $data = json_decode($response, TRUE);
        $totalIntUp =  array_values( $data )[0][0]['IntCount'];
        //endregion

        //region int down
        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT+COUNT(InterfaceID) AS IntCount+FROM+Orion.NPM.Interfaces+WHERE+OperStatus=0+OR+OperStatus=2";
        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);

        $data = json_decode($response, TRUE);
        $totalIntDown =  array_values( $data )[0][0]['IntCount'];
        //endregion

        $result1 = array(
            array("category"=>"Status","NodeUp"=>$totalNodeUp,"NodeDown"=>$totalNodeDown),
        );

        $result2 = array(
            array("category"=>"Status","IntUp"=>$totalIntUp,"IntDown"=>$totalIntDown),
        );

        return view('slwnpm.report',compact('result1','result2'));
    }

    /*
    Page: Threshold
    Hiển thị view Threshold
    */
    public function threshold(){
        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT AC.AlertID, AC.Name,AC.Enabled,AC.Description,AC.ObjectType,AC.CreatedBy,AC.Canned FROM Orion.AlertConfigurations AC ORDER BY Name ASC";

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $data = json_decode($response, TRUE);
        $data = $data["results"];

        return view('slwnpm.threshold',compact('user','slwnpmserver','data'));
    }

    public function thresholdenable($alertid){
        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }
        
        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port."/SolarWinds/InformationService/v3/Json/";
        $client = new \GuzzleHttp\Client([
            'auth' => [$slwnpmserver->user,$slwnpmserver->password]
        ]);
        
        //Enable
        $URI = $client->request('GET', $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring."query=SELECT Uri FROM Orion.AlertConfigurations WHERE AlertID='".$alertid."'");
        $URI = json_decode($URI->getBody()->getContents())->results;
        $URI = $URI[0]->Uri;
        
        $client->request('POST', $apihost . $URI, [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => ['Enabled' => 'True']
        ]);
        
        return redirect('/admin/slwnpm/threshold');
    }

    public function thresholddisable($alertid){
        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }
        
        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port."/SolarWinds/InformationService/v3/Json/";
        $client = new \GuzzleHttp\Client([
            'auth' => [$slwnpmserver->user,$slwnpmserver->password]
        ]);
        
        //Enable
        $URI = $client->request('GET', $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring."query=SELECT Uri FROM Orion.AlertConfigurations WHERE AlertID='".$alertid."'");
        $URI = json_decode($URI->getBody()->getContents())->results;
        $URI = $URI[0]->Uri;
        
        $client->request('POST', $apihost . $URI, [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => ['Enabled' => 'False']
        ]);
        
        return redirect('/admin/slwnpm/threshold');
    }

    /*
    Page: Threshold
    Hiển thị view Threshold
    */
    public function thresholddetail($alertid){
        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;

        //Properties
        $query = "query=SELECT AC.Name, AC.Description FROM Orion.AlertConfigurations AC WHERE AC.AlertID='".$alertid."'ORDER BY Name ASC";
        $properties = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $properties = json_decode($properties, TRUE);
        $properties = $properties["results"][0];

        //Trigger Condition
        $query = "query=SELECT E.Fullname, E.DisplayName FROM Metadata.Entity E WHERE E.Metadata.Name='alertingSource' AND E.Metadata.Value='true' ORDER BY DisplayName ASC";
        $trigger = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $trigger = json_decode($trigger, TRUE);
        $trigger = $trigger["results"];

        return view('slwnpm.thresholddetail',compact('user','slwnpmserver','properties','trigger'));
    }

    /*
    Page: Notify
    Hiển thị view Notify
    */
    public function notify(){
        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT A.ActionID, A.ActionTypeID, A.Title, A.Description, A.Enabled, A.SortOrder, AA.CategoryType, AA.EnvironmentType, AC.Name AS [Assigned Alerts], AC.AlertID, AC.Description AS [Assigned Alerts Pop] FROM Orion.Actions A JOIN Orion.ActionsAssignments AA ON AA.ActionID=A.ActionID JOIN Orion.AlertConfigurations AC ON AC.AlertID=AA.ParentID ORDER BY Title ASC, sortorder";

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $data = json_decode($response, TRUE);
        $data = $data["results"];

        return view('slwnpm.notify',compact('user','slwnpmserver','data'));
    }

    public function notifyenable($actionid){
        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }
        
        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port."/SolarWinds/InformationService/v3/Json/";
        $client = new \GuzzleHttp\Client([
            'auth' => [$slwnpmserver->user,$slwnpmserver->password]
        ]);
        
        //Enable
        $URI = $client->request('GET', $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring."query=SELECT Uri FROM Orion.Actions WHERE ActionID='".$actionid."'");
        $URI = json_decode($URI->getBody()->getContents())->results;
        $URI = $URI[0]->Uri;
        
        $client->request('POST', $apihost . $URI, [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => ['Enabled' => 'True']
        ]);
        
        return redirect('/admin/slwnpm/notify');
    }

    public function notifydisable($actionid){
        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }
        
        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port."/SolarWinds/InformationService/v3/Json/";
        $client = new \GuzzleHttp\Client([
            'auth' => [$slwnpmserver->user,$slwnpmserver->password]
        ]);
        
        //Enable
        $URI = $client->request('GET', $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring."query=SELECT Uri FROM Orion.Actions WHERE ActionID='".$actionid."'");
        $URI = json_decode($URI->getBody()->getContents())->results;
        $URI = $URI[0]->Uri;
        
        $client->request('POST', $apihost . $URI, [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => ['Enabled' => 'False']
        ]);
        
        return redirect('/admin/slwnpm/notify');
    }

    /*
    Page: Notify
    Hiển thị view Notify
    */
    public function notifydetail($actionid){
        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }

        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring;
        $query = "query=SELECT A.ActionID, A.Title, P.PropertyValue, P.Uri AS [P URI], A.Uri AS [A URI] FROM Orion.Actions A JOIN Orion.ActionsProperties P ON P.ActionID = A.ActionID WHERE A.ActionID='".$actionid."' AND P.PropertyName='Message'";

        $response = Http::withBasicAuth($slwnpmserver->user,$slwnpmserver->password)->Get($apihost . $query);
        $data = json_decode($response, TRUE);
        $data = $data["results"][0];
        
        return view('slwnpm.notifydetail',compact('user','slwnpmserver','data'));
    }

    public function notifydetailsubmit($actionid){
        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }
        
        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port."/SolarWinds/InformationService/v3/Json/";
        $client = new \GuzzleHttp\Client([
            'auth' => [$slwnpmserver->user,$slwnpmserver->password]
        ]);
        
        //Update Name
        $nURI = Request("nURI");
        $client->request('POST', $apihost . $nURI, [
            'headers' => ['Content-Type' => 'application/json'],
             'json' => ['Title' => Request('message')]
        ]);

        //Update Message
        $mURI = Request("mURI");
        $client->request('POST', $apihost . $mURI, [
            'headers' => ['Content-Type' => 'application/json'],
             'json' => ['PropertyValue' => Request('message')]
        ]);
        
        return $this->notifydetail($actionid);
    }

    public function unmanage($NodeID){
        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }
        
        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port."/SolarWinds/InformationService/v3/Json/Invoke/Orion.Nodes/Unmanage";
        $client = new \GuzzleHttp\Client([
            'auth' => [$slwnpmserver->user,$slwnpmserver->password]
        ]);
        
        //Enable
        $res = $client->request('GET', $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring."query=SELECT Uri, DetailsUrl FROM Orion.Nodes WHERE NodeID='".$NodeID."'");
        $res = json_decode($res->getBody()->getContents())->results;
        $URI = $res[0]->Uri;
        $netObjectID = $res[0]->DetailsUrl;
        $temparr = explode("=",$netObjectID);
        $netObjectID = $temparr[1];
        
        $client->request('POST', $apihost, [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [$netObjectID,"01-01-1899","01-01-9999","false"]
        ]);
        
        return redirect('/admin/slwnpm/nodes');
    }

    public function manage($NodeID){
        if (!Session::has('Monitor')){
            $url = url('/');
            return redirect($url);
        }
        
        $user = DB::table('tbl_accounts')
        ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
        ->where([
            ['tbl_accounts.username', '=', session('mymonitor_userid')]
        ])->first();

        $slwnpmserver = DB::table('tbl_slwnpmservers')
        ->where([
            ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
        ])->first();

        $apihost = $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port."/SolarWinds/InformationService/v3/Json/Invoke/Orion.Nodes/Remanage";
        $client = new \GuzzleHttp\Client([
            'auth' => [$slwnpmserver->user,$slwnpmserver->password]
        ]);
        
        //Enable
        $res = $client->request('GET', $slwnpmserver->secures."://". $slwnpmserver->hostname.":". $slwnpmserver->port. $slwnpmserver->basestring."query=SELECT Uri, DetailsUrl FROM Orion.Nodes WHERE NodeID='".$NodeID."'");
        $res = json_decode($res->getBody()->getContents())->results;
        $URI = $res[0]->Uri;
        $netObjectID = $res[0]->DetailsUrl;
        $temparr = explode("=",$netObjectID);
        $netObjectID = $temparr[1];
        
        $client->request('POST', $apihost, [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [$netObjectID]
        ]);
        
        return redirect('/admin/slwnpm/nodes');
    }
}
