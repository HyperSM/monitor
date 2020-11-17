<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Crypt;
use Session;
use SoapClient;

date_default_timezone_set('Asia/Ho_Chi_Minh');

// use CodeDredd\Soap\Facades\Soap;
//
// use GuzzleHttp\Client;
// use GuzzleHttp\Psr7;


class casvdController extends Controller
{
  /*
  Page: Dashboard
  */
  public function dashboard() {
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

    if ($user->casvduse == '1') {
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
      return view('casvd.dashboard', compact('domain', 'user', 'refreshrate'));
    } else {
      $url = url('/') . '/admin/dashboard';
      return redirect($url);
    }
  }

  /*
  Page: Dashboard
  Section: Function Return top 10 open incidents to ajax call back
  */
  public function ajaxcasvddashboardincidents() {
    // Response template
    $tmpstr =
      '<table class="table table-hover table-condensed" width="100%"">
        <thead>
            <tr>
                <th>Incident #</th>
                <th>Summary</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>';

    $casvdserver = DB::table('tbl_casvdservers')
      ->where([
        ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
      ])->first();

    if ($casvdserver->hostname == '') {
      return 'N/A';
    } else {
      $client = new SoapClient($casvdserver->secures . "://" . $casvdserver->hostname . ":" . $casvdserver->port . $casvdserver->basestring, array('trace' => 1));
      // Login to CASVD
      $ap_param = array(
        'username' => 'sd_test',
        'password' => 'msc@A2020'
      );
      $info = $client->__call("login", array($ap_param));

      // Get top10 incidents
      $ap_param = array(
        'sid' => $info->loginReturn,
        'objectType' => 'cr',
        'whereClause' => "type='I' AND status.sym='Open'", // type I, R, P : Incident, Request, Problem
        'maxRows' => 10,
        'attributes' => ['ref_num', 'summary', 'status.sym']
      );
      $response = $client->__call("doSelect", array($ap_param))->doSelectReturn;

      // Convert XML to object
      $xmlresponse = simplexml_load_string($response);
      // Convert SimpleXMLElement object to Array $responseArray
      $responseArray = array();
      foreach ($xmlresponse->UDSObject as $node) {
        $responseArray[] = $node;
      }

      // Sorting SimpleXMLElement object array
      function comparator($a, $b)
      {
        // sort by ID
        return (intval($a->Attributes->Attribute[0]->AttrValue) > intval($b->Attributes->Attribute[0]->AttrValue)) ? -1 : 1;
      }
      usort($responseArray, __NAMESPACE__ . '\comparator');

      // Print xml response to response template
      foreach ($responseArray as $item) {
        $tmpstr = $tmpstr .
          '<tr>
                  <td style="vertical-align: middle;">' . $item->Attributes->Attribute[0]->AttrValue . '</style=></td>
                  <td style="vertical-align: middle;">' . $item->Attributes->Attribute[1]->AttrValue . '</style=></td>
                  <td style="vertical-align: middle;">' . $item->Attributes->Attribute[2]->AttrValue . '</style=></td>
          </tr>';
      }
      $tmpstr = $tmpstr . '
            </tbody>
        </table>';
      echo $tmpstr;
    }
  }

  /*
  Page: Dashboard
  Section: Function Return top 10 open requests to ajax call back
  */
  public function ajaxcasvddashboardrequests() {
    // Response template
    $tmpstr =
      '<table class="table table-hover table-condensed" width="100%"">
        <thead>
            <tr>
                <th>Incident #</th>
                <th>Summary</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>';

    $casvdserver = DB::table('tbl_casvdservers')
      ->where([
        ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
      ])->first();

    if ($casvdserver->hostname == '') {
      return 'N/A';
    } else {
      $client = new SoapClient($casvdserver->secures . "://" . $casvdserver->hostname . ":" . $casvdserver->port . $casvdserver->basestring, array('trace' => 1));
      // Login to CASVD
      $ap_param = array(
        'username' => 'sd_test',
        'password' => 'msc@A2020'
      );
      $info = $client->__call("login", array($ap_param));

      // Get top10 tickets
      $ap_param = array(
        'sid' => $info->loginReturn,
        'objectType' => 'cr',
        'whereClause' => "type='R' AND status.sym='Open'", // type I, R, P : Incident, Request, Problem
        'maxRows' => 10,
        'attributes' => ['ref_num', 'summary', 'status.sym']
      );
      $response = $client->__call("doSelect", array($ap_param))->doSelectReturn;

      // Convert XML to object
      $xmlresponse = simplexml_load_string($response);
      // Convert SimpleXMLElement object to Array $responseArray
      $responseArray = array();
      foreach ($xmlresponse->UDSObject as $node) {
        $responseArray[] = $node;
      }

      // Sorting SimpleXMLElement object array
      function comparator($a, $b)
      {
        // sort by ID
        return (intval($a->Attributes->Attribute[0]->AttrValue) > intval($b->Attributes->Attribute[0]->AttrValue)) ? -1 : 1;
      }
      usort($responseArray, __NAMESPACE__ . '\comparator');

      // Print xml response to response template
      foreach ($responseArray as $item) {
        $tmpstr = $tmpstr .
          '<tr>
                  <td style="vertical-align: middle;">' . $item->Attributes->Attribute[0]->AttrValue . '</a></td>
                  <td style="vertical-align: middle;">' . $item->Attributes->Attribute[1]->AttrValue . '</a></td>
                  <td style="vertical-align: middle;">' . $item->Attributes->Attribute[2]->AttrValue . '</a></td>
          </tr>';
      }
      $tmpstr = $tmpstr . '
            </tbody>
        </table>';
      echo $tmpstr;
    }
  }

  /*
  Page: Dashboard
  Section: Function Return top 10 open changes to ajax call back
  */
  public function ajaxcasvddashboardchanges() {
    // Response template
    $tmpstr =
      '<table class="table table-hover table-condensed" width="100%"">
        <thead>
            <tr>
                <th>Incident #</th>
                <th>Summary</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>';

    $casvdserver = DB::table('tbl_casvdservers')
      ->where([
        ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
      ])->first();

    if ($casvdserver->hostname == '') {
      return 'N/A';
    } else {
      $client = new SoapClient($casvdserver->secures . "://" . $casvdserver->hostname . ":" . $casvdserver->port . $casvdserver->basestring, array('trace' => 1));
      // Login to CASVD
      $ap_param = array(
        'username' => 'sd_test',
        'password' => 'msc@A2020'
      );
      $info = $client->__call("login", array($ap_param));

      // Get top10 changes
      $ap_param = array(
        'sid' => $info->loginReturn,
        'objectType' => 'chg',
        'whereClause' => "status.sym='Open'",
        'maxRows' => 10,
        'attributes' => ['chg_ref_num', 'summary', 'status.sym']
      );
      $response = $client->__call("doSelect", array($ap_param))->doSelectReturn;

      // Convert XML to object
      $xmlresponse = simplexml_load_string($response);
      // Convert SimpleXMLElement object to Array $responseArray
      $responseArray = array();
      foreach ($xmlresponse->UDSObject as $node) {
        $responseArray[] = $node;
      }

      // Sorting SimpleXMLElement object array
      function comparator($a, $b)
      {
        // sort by ID
        return (intval($a->Attributes->Attribute[0]->AttrValue) > intval($b->Attributes->Attribute[0]->AttrValue)) ? -1 : 1;
      }
      usort($responseArray, __NAMESPACE__ . '\comparator');

      // Print xml response to response template
      foreach ($responseArray as $item) {
        $tmpstr = $tmpstr .
          '<tr>
                  <td style="vertical-align: middle;">' . $item->Attributes->Attribute[0]->AttrValue . '</a></td>
                  <td style="vertical-align: middle;">' . $item->Attributes->Attribute[1]->AttrValue . '</a></td>
                  <td style="vertical-align: middle;">' . $item->Attributes->Attribute[2]->AttrValue . '</a></td>
          </tr>';
      }
      $tmpstr = $tmpstr . '
            </tbody>
        </table>';
      echo $tmpstr;
    }
  }

  /*
  Page: Dashboard
  Section: Get Refresh rate
  */
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
        ['product', '=', 'casvd']
      ])->first();

    if ($refreshrate == NULL) {
      return 0;
    } else {
      return $refreshrate->refreshrate;
    }
  }

  /*
  Page: Dashboard
  Section: Set Refresh rate
  */
  public function setrefreshrate(Request $request) {
    $user = DB::table('tbl_accounts')
      ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
      ->where([
        ['tbl_accounts.username', '=', session('mymonitor_userid')]
      ])->first();
    $userid = $user->userid;

    $refreshrate = DB::table('tbl_refreshrate')
      ->where([
        ['userid', '=', $userid],
        ['product', '=', 'casvd']
      ])->first();



    if (empty($refreshrate)) {
      //Create
      DB::table('tbl_refreshrate')
        ->insert(
          [
            'userid' => $userid,
            'product' => 'casvd',
            'refreshrate' => $request->refreshrate
          ]
        );
    } else {
      //Update
      DB::table('tbl_refreshrate')
        ->where([
          ['userid', '=', $userid],
          ['product', '=', 'casvd']
        ])
        ->update([
          'refreshrate' => $request->refreshrate
        ]);
    }
  }

  /*
  Page: Dashboard -> Config server
  Section: Main view
  */
  public function serverconfig() {
    if (!Session::has('Monitor')) {
      $url = url('/');
      return redirect($url);
    }

    $dm = Crypt::decryptString(session('mymonitor_md'));

    $casvdserver = DB::table('tbl_casvdservers')
      ->where([
        ['domainid', '=', $dm]
      ])->first();

    $user = DB::table('tbl_accounts')
      ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
      ->where([
        ['tbl_accounts.username', '=', session('mymonitor_userid')]
      ])->first();


    return view('casvd.serverconfig', compact('casvdserver', 'user'));
  }

  /*
  Page: Dashboard -> Submit Config server
  Section: Submit form
  */
  public function casvdserversubmit() {

    $server = DB::table('tbl_casvdservers')
      ->where('domainid', '=', Crypt::decryptString(session('mymonitor_md')))
      ->first();
    if (empty($server)) {
      //Create
      DB::table('tbl_casvdservers')
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
      DB::table('tbl_casvdservers')
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
    $url = url('/') . '/admin/casvd';
    return redirect($url);
  }

  /*
  Page: Dashboard -> AllIncidents
  */
  public function allincidents() {
    if (!Session::has('Monitor')) {
      $url = url('/');
      return redirect($url);
    }

    $casvdserver = DB::table('tbl_casvdservers')
      ->where([
        ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
      ])->first();

    $user = DB::table('tbl_accounts')
      ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
      ->where([
        ['tbl_accounts.username', '=', session('mymonitor_userid')]
      ])->first();

    // Get refreshrate
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

    return view('casvd.allincidents', compact('casvdserver', 'user', 'refreshrate'));
  }

  /*
  Page: Dashboard -> All Incidents
  Section: Function Return all incidents to ajax call back
  */
  public function ajaxcasvdallincidents() {
    // Response template
    $tmpstr =
      '<table class="table table-hover table-condensed datatable" id="ajaxcasvdallincidentstable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Incident#</th>
                <th>Summary</th>
                <th>Priority</th>
                <th>Category</th>
                <th>CI</th>
                <th>Status</th>
                <th>Group</th>
                <th>Assigned To</th>
                <th>Main Assignee</th>
                <th>Open Date</th>
                <th>Last Modified Date</th>
                <th>SLA Violation</th>
            </tr>
        </thead>
        <tbody>';

    $casvdserver = DB::table('tbl_casvdservers')
      ->where([
        ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
      ])->first();

    if ($casvdserver->hostname == '') {
      return 'N/A';
    } else {
      $client = new SoapClient($casvdserver->secures . "://" . $casvdserver->hostname . ":" . $casvdserver->port . $casvdserver->basestring, array('trace' => 1));
      // Login to CASVD
      $ap_param = array(
        'username' => 'sd_test',
        'password' => 'msc@A2020'
      );
      $info = $client->__call("login", array($ap_param));

      // Get all tickets
      $ap_param = array(
        'sid' => $info->loginReturn,
        'objectType' => 'cr',
        'whereClause' => "type='I'", // type I, R, P : Incident, Request, Problem
        'maxRows' => 50,
        'attributes' => ['id', 'ref_num', 'summary', 'priority.sym', 'category.sym', 'affected_resource.name', 'status.sym', 'group.last_name', 'assignee.last_name', 'assignee.first_name', 'assignee.middle_name', 'zmain_tech.last_name', 'zmain_tech.first_name', 'zmain_tech.middle_name', 'open_date', 'last_mod_dt', 'sla_violation']
      );
      $response = $client->__call("doSelect", array($ap_param))->doSelectReturn;

      // Convert XML to object
      $xmlresponse = simplexml_load_string($response);
      // Convert SimpleXMLElement object to Array $responseArray
      $responseArray = array();
      foreach ($xmlresponse->UDSObject as $node) {
        $responseArray[] = $node;
      }

      // Sorting SimpleXMLElement object array
      function comparator($a, $b)
      {
        // sort by ID
        return (intval($a->Attributes->Attribute[0]->AttrValue) > intval($b->Attributes->Attribute[0]->AttrValue)) ? -1 : 1;
      }
      usort($responseArray, __NAMESPACE__ . '\comparator');

      // Print xml response to response template
      foreach ($responseArray as $item) {
        // Init attribute's variable
        $assignee_lastname = $item->Attributes->Attribute[8]->AttrValue;
        $assignee_firstname = $item->Attributes->Attribute[9]->AttrValue;
        $assignee_middlename = $item->Attributes->Attribute[10]->AttrValue;
        $zmaintech_lastname = $item->Attributes->Attribute[11]->AttrValue;
        $zmaintech_firstname = $item->Attributes->Attribute[12]->AttrValue;
        $zmaintech_middlename = $item->Attributes->Attribute[13]->AttrValue;

        // Generate assignee name
        if ($assignee_firstname != '' and $assignee_middlename != '') {
          $assignee_name = $assignee_lastname . ', ' . $assignee_firstname . ' ' . $assignee_middlename;
        } elseif ($assignee_middlename = '' and $assignee_firstname != '') {
          $assignee_name = $assignee_lastname . ', ' . $assignee_firstname;
        } elseif ($assignee_middlename != '' and $assignee_firstname = '') {
          $assignee_name = $assignee_lastname . ', ' . $assignee_middlename;
        } else {
          $assignee_name = $assignee_lastname;
        }

        // Generate zmaintech name
        if ($zmaintech_firstname != '' and $zmaintech_middlename != '') {
          $zmaintech_name = $zmaintech_lastname . ', ' . $zmaintech_firstname . ' ' . $zmaintech_middlename;
        } elseif ($zmaintech_middlename = '' and $zmaintech_firstname != '') {
          $zmaintech_name = $zmaintech_lastname . ', ' . $zmaintech_firstname;
        } elseif ($zmaintech_middlename != '' and $zmaintech_firstname = '') {
          $zmaintech_name = $zmaintech_lastname . ', ' . $zmaintech_middlename;
        } else {
          $zmaintech_name = $zmaintech_lastname;
        }

        $tmpstr = $tmpstr .
          '<tr>
                  <td style="vertical-align: middle;">' . $item->Attributes->Attribute[0]->AttrValue . '</a></td>' . // id
          '<td style="vertical-align: middle;">' . $item->Attributes->Attribute[1]->AttrValue . '</a></td>' . // ref_num
          '<td style="vertical-align: middle;">' . $item->Attributes->Attribute[2]->AttrValue . '</a></td>' . // summary
          '<td style="vertical-align: middle;">' . $item->Attributes->Attribute[3]->AttrValue . '</a></td>' . // priority
          '<td style="vertical-align: middle;">' . $item->Attributes->Attribute[4]->AttrValue . '</a></td>' . // category
          '<td style="vertical-align: middle;">' . $item->Attributes->Attribute[5]->AttrValue . '</a></td>' . // affected_resource
          '<td style="vertical-align: middle;">' . $item->Attributes->Attribute[6]->AttrValue . '</a></td>' . // status
          '<td style="vertical-align: middle;">' . $item->Attributes->Attribute[7]->AttrValue . '</a></td>' . // group
          '<td style="vertical-align: middle;">' . $assignee_name . '</a></td>' . // assignee
          '<td style="vertical-align: middle;">' . $zmaintech_name . '</a></td>' . // zmain_tech
          '<td style="vertical-align: middle;">' . date("d-m-Y g:i a", intval($item->Attributes->Attribute[14]->AttrValue)) . '</a></td>' . // open_date
          '<td style="vertical-align: middle;">' . date("d-m-Y g:i a", intval($item->Attributes->Attribute[15]->AttrValue)) . '</a></td>' . // last_mod_dt
          '<td style="vertical-align: middle;">' . $item->Attributes->Attribute[16]->AttrValue . '</a></td>' . // sla_violation
          '</tr>';
      }
      $tmpstr = $tmpstr . '
            </tbody>
        </table>';
      echo $tmpstr;
    }
  }

  /*
  Page: Dashboard -> Add Incident
  */
  public function addincident() {
    if (!Session::has('Monitor')) {
      $url = url('/');
      return redirect($url);
    }

    $dm = Crypt::decryptString(session('mymonitor_md'));

    $user = DB::table('tbl_accounts')
      ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
      ->where([
        ['tbl_accounts.username', '=', session('mymonitor_userid')]
      ])->first();

    $err_msg = '';

    //Get Droplist
    $arr = $this->SoapLogin();
    $client = $arr[0];
    $sid = $arr[1];
    $dl_priority = $this->droplist_priority($client, $sid);
    $dl_category = $this->droplist_category($client, $sid);
    $dl_ci = $this->droplist_ci($client, $sid);
    $dl_group = $this->droplist_group($client, $sid);

    return view('casvd.addincident', compact('user', 'err_msg', 'dl_priority', 'dl_category', 'dl_ci', 'dl_group'));
  }

  /*
  Page: Dashboard -> AllRequests
  */
  public function allrequests() {
    if (!Session::has('Monitor')) {
      $url = url('/');
      return redirect($url);
    }

    $casvdserver = DB::table('tbl_casvdservers')
      ->where([
        ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
      ])->first();

    $user = DB::table('tbl_accounts')
      ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
      ->where([
        ['tbl_accounts.username', '=', session('mymonitor_userid')]
      ])->first();

    // Get refreshrate
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

    $tmpstr = '[';

    if ($casvdserver->hostname == '') {
      return 'N/A';
    } else {
      $client = new SoapClient($casvdserver->secures . "://" . $casvdserver->hostname . ":" . $casvdserver->port . $casvdserver->basestring, array('trace' => 1));
      // Login to CASVD
      $ap_param = array(
        'username' => 'sd_test',
        'password' => 'msc@A2020'
      );
      $info = $client->__call("login", array($ap_param));

      // Get all Requests
      $ap_param = array(
        'sid' => $info->loginReturn,
        'objectType' => 'cr',
        'whereClause' => "type='R'", // type I, R, P : Incident, Request, Problem
        'maxRows' => 50,
        'attributes' => ['id', 'ref_num', 'summary', 'priority.sym', 'category.sym', 'status.sym', 'group.combo_name', 'assignee.combo_name', 'zmain_tech.combo_name', 'open_date', 'last_mod_dt', 'sla_violation']
      );
      $response = $client->__call("doSelect", array($ap_param))->doSelectReturn;

      // Convert XML to object
      $xmlresponse = simplexml_load_string($response);

      // Convert SimpleXMLElement object to Array $responseArray
      $responseArray = array();
      foreach ($xmlresponse->UDSObject as $node) {
        $responseArray[] = $node;
      }

      // Sorting SimpleXMLElement object array
      function comparator($a, $b)
      {
        // sort by ID
        return (intval($a->Attributes->Attribute[0]->AttrValue) > intval($b->Attributes->Attribute[0]->AttrValue)) ? -1 : 1;
      }
      usort($responseArray, __NAMESPACE__ . '\comparator');
      $jsondata = json_encode($responseArray);
      // Print xml response to response template
      foreach ($responseArray as $item) {
        $tmpstr = $tmpstr .
          '{' .
          '"ID":"' . $item->Attributes->Attribute[0]->AttrValue . '", ' . // id
          '"Request#":"' . $item->Attributes->Attribute[1]->AttrValue . '", ' . // ref_num
          '"Summary":"' . $item->Attributes->Attribute[2]->AttrValue . '", ' . // summary
          '"Priority":"' . $item->Attributes->Attribute[3]->AttrValue . '", ' . // priority
          '"Category":"' . $item->Attributes->Attribute[4]->AttrValue . '", ' . // category
          '"Status":"' . $item->Attributes->Attribute[5]->AttrValue . '", ' . // status
          '"Group":"' . $item->Attributes->Attribute[6]->AttrValue . '", ' . // group
          '"Assigned To":"' . $item->Attributes->Attribute[7]->AttrValue . '", ' . // assignee
          '"Main Assignee":"' . $item->Attributes->Attribute[8]->AttrValue . '", ' . // zmain_tech
          '"Open Date":"' . date("d-m-Y g:i a", intval($item->Attributes->Attribute[9]->AttrValue)) . '", ' . // open_date
          '"Last Modified Date":"' . date("d-m-Y g:i a", intval($item->Attributes->Attribute[10]->AttrValue)) . '", ' . // last_mod_dt
          '"SLA Violation":"' . $item->Attributes->Attribute[11]->AttrValue . '"' . // sla_violation
          '},';
      }
      $tmpstr = substr($tmpstr, 0, -1);
      $tmpstr = $tmpstr . ']';
      $tmpstr = json_decode($tmpstr);
    }

    return view('casvd.allrequests', compact('casvdserver', 'user', 'refreshrate', 'tmpstr'));
  }

  /*
  Page: Dashboard -> AllRequests -> Edit Request
  */
  public function editrequest($refnum) {
    if (!Session::has('Monitor')) {
      $url = url('/');
      return redirect($url);
    }

    $dm = Crypt::decryptString(session('mymonitor_md'));

    $casvdserver = DB::table('tbl_casvdservers')
      ->where([
        ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
      ])->first();

    $user = DB::table('tbl_accounts')
      ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
      ->where([
        ['tbl_accounts.username', '=', session('mymonitor_userid')]
      ])->first();

    $err_msg = '';
    $tmpstr = '';

    if ($casvdserver->hostname == '') {
      return 'N/A';
    } else {
      $client = new SoapClient($casvdserver->secures . "://" . $casvdserver->hostname . ":" . $casvdserver->port . $casvdserver->basestring, array('trace' => 1));
      // Login to CASVD
      $ap_param = array(
        'username' => 'sd_test',
        'password' => 'msc@A2020'
      );
      $info = $client->__call("login", array($ap_param));

      // Get Request Info
      $ap_param = array(
        'sid' => $info->loginReturn,
        'objectType' => 'cr',
        'whereClause' => "type='R' AND ref_num='{$refnum}'", // type I, R, P : Incident, Request, Problem
        'maxRows' => 1,
        // 'attributes' => []
        'attributes' => [
          'requested_by.combo_name',    // 0. Requester
          'customer.combo_name',        // 1. Affected End User
          'category.sym',               // 2. Request Area
          'status.sym',                 // 3. Status
          'priority.sym',               // 4. Priority
          'log_agent.combo_name',       // 5. Reported By
          'group.combo_name',           // 6. Group
          'assignee.combo_name',        // 7. Assignee
          'affected_resource.name',     // 8. Configuration Item
          'zccaddr',                    // 9. Mail CC
          'severity.sym',               // 10. Severity
          'urgency.sym',                // 11. Urgency
          'impact.sym',                 // 12. Impact
          'active.sym',                 // 13. Active?
          'charge_back_id',             // 14. Charge Back ID
          'call_back_date',             // 15. Call Back Date
          'resolution_code.sym',        // 16. Resolution Code
          'requested_by.phone_number',  // 17. Requester Phone
          'resolution_method.sym',      // 18. Resolution Method
          'zmain_tech.combo_name',      // 19. ZmainTech
          'change.chg_ref_num',         // 20. Change
          'caused_by_chg.chg_ref_num',  // 21. Caused by Change Order
          'external_system_ticket'      // 22. External System Ticket
        ]
      );
      $response = $client->__call("doSelect", array($ap_param))->doSelectReturn;

      // Convert XML to object
      $xmlresponse = simplexml_load_string($response);

      // Convert SimpleXMLElement object to Array $responseArray
      $responseArray = array();
      foreach ($xmlresponse->UDSObject as $node) {
        $responseArray[] = $node;
      }

      // Print xml response to response template
      foreach ($responseArray as $item) {
        $tmpstr = $tmpstr .
          '{' .
          '"Requester":"' . $item->Attributes->Attribute[0]->AttrValue . '", ' .
          '"Affected End User":"' . $item->Attributes->Attribute[1]->AttrValue . '", ' .
          '"Request Area":"' . $item->Attributes->Attribute[2]->AttrValue . '", ' .
          '"Status":"' . $item->Attributes->Attribute[3]->AttrValue . '", ' .
          '"Priority":"' . $item->Attributes->Attribute[4]->AttrValue . '", ' .
          '"Reported By":"' . $item->Attributes->Attribute[5]->AttrValue . '", ' .
          '"Group":"' . $item->Attributes->Attribute[6]->AttrValue . '", ' .
          '"Assignee":"' . $item->Attributes->Attribute[7]->AttrValue . '", ' .
          '"Configuration Item":"' . $item->Attributes->Attribute[8]->AttrValue . '", ' .
          '"Mail CC":"' . $item->Attributes->Attribute[9]->AttrValue . '", ' .
          '"Severity":"' . $item->Attributes->Attribute[10]->AttrValue . '", ' .
          '"Urgency":"' . $item->Attributes->Attribute[11]->AttrValue . '", ' .
          '"Impact":"' . $item->Attributes->Attribute[12]->AttrValue . '", ' .
          '"Active?":"' . $item->Attributes->Attribute[13]->AttrValue . '", ' .
          '"Charge Back ID":"' . $item->Attributes->Attribute[14]->AttrValue . '", ' .
          '"Call Back Date/Time":"' . $item->Attributes->Attribute[15]->AttrValue . '", ' .
          '"Resolution Code":"' . $item->Attributes->Attribute[16]->AttrValue . '", ' .
          '"Requester Phone":"' . $item->Attributes->Attribute[17]->AttrValue . '", ' .
          '"Resolution Method By":"' . $item->Attributes->Attribute[18]->AttrValue . '", ' .
          '"ZmainTech":"' . $item->Attributes->Attribute[19]->AttrValue . '", ' .
          '"Change":"' . $item->Attributes->Attribute[20]->AttrValue . '", ' .
          '"Caused by Change Order":"' . $item->Attributes->Attribute[21]->AttrValue . '", ' .
          '"External System Ticket":"' . $item->Attributes->Attribute[22]->AttrValue . '"' .
          '},';
      }

      $tmpstr = substr($tmpstr, 0, -1);
      // $tmpstr = $tmpstr . ']';
      $tmpstr = json_decode($tmpstr, true);
    };

    return view('casvd.editrequest', compact('user', 'err_msg', 'refnum', 'tmpstr'));
  }

  /*
  Page: Dashboard -> AllRequests -> Edit Request (Submit)
  */
  public function editrequestsubmit($refnum) {
    if (!Session::has('Monitor')) {
      $url = url('/');
      return redirect($url);
    }

    $dm = Crypt::decryptString(session('mymonitor_md'));

    $casvdserver = DB::table('tbl_casvdservers')
      ->where([
        ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
      ])->first();

    $user = DB::table('tbl_accounts')
      ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
      ->where([
        ['tbl_accounts.username', '=', session('mymonitor_userid')]
      ])->first();

    $err_msg = '';

    if ($casvdserver->hostname == '') {
      return 'N/A';
    } else {
      $client = new SoapClient($casvdserver->secures . "://" . $casvdserver->hostname . ":" . $casvdserver->port . $casvdserver->basestring, array('trace' => 1));
      // Login to CASVD
      $ap_param = array(
        'username' => 'sd_test',
        'password' => 'msc@A2020'
      );
      $info = $client->__call("login", array($ap_param));

      // Get Request Info
      $ap_param = array(
        'sid' => $info->loginReturn,
        'objectType' => 'cr',
        'whereClause' => "type='R' AND ref_num='{$refnum}'", // type I, R, P : Incident, Request, Problem
        'maxRows' => 1,
        'attributes' => []
      );
      $response = $client->__call("doSelect", array($ap_param))->doSelectReturn;
      // Convert XML to object
      $xmlresponse = simplexml_load_string($response);
      $handle = $xmlresponse->UDSObject->Handle;

      // Post
      $ap_param = array(
        'sid' => $info->loginReturn,
        'objectHandle' => $handle,
        'attrVals' => ["zccaddr",Request('zccaddr')],
        'attributes' => []
      );
      $client->__call("updateObject", array($ap_param));
    }
    return $this->allrequests();
  }

  /*
  Page: Dashboard -> All Incidents
  Section: Function Return all incidents to ajax call back
  */
  public function ajaxcasvdallrequests_backup() {
    // Response template
    $tmpstr =
      '<table class="table table-hover table-condensed datatable" id="ajaxcasvdallrequeststable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Request#</th>
                <th>Summary</th>
                <th>Priority</th>
                <th>Category</th>
                <th>Status</th>
                <th>Group</th>
                <th>Assigned To</th>
                <th>Main Assignee</th>
                <th>Open Date</th>
                <th>Last Modified Date</th>
                <th>SLA Violation</th>
            </tr>
        </thead>
        <tbody>';

    $casvdserver = DB::table('tbl_casvdservers')
      ->where([
        ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
      ])->first();

    if ($casvdserver->hostname == '') {
      return 'N/A';
    } else {
      $client = new SoapClient($casvdserver->secures . "://" . $casvdserver->hostname . ":" . $casvdserver->port . $casvdserver->basestring, array('trace' => 1));
      // Login to CASVD
      $ap_param = array(
        'username' => 'sd_test',
        'password' => 'msc@A2020'
      );
      $info = $client->__call("login", array($ap_param));

      // Get all tickets
      $ap_param = array(
        'sid' => $info->loginReturn,
        'objectType' => 'cr',
        'whereClause' => "type='R'", // type I, R, P : Incident, Request, Problem
        'maxRows' => 50,
        'attributes' => ['id', 'ref_num', 'summary', 'priority.sym', 'category.sym', 'status.sym', 'group.last_name', 'assignee.last_name', 'assignee.first_name', 'assignee.middle_name', 'zmain_tech.last_name', 'zmain_tech.first_name', 'zmain_tech.middle_name', 'open_date', 'last_mod_dt', 'sla_violation']
      );
      $response = $client->__call("doSelect", array($ap_param))->doSelectReturn;

      // Convert XML to object
      $xmlresponse = simplexml_load_string($response);
      // Convert SimpleXMLElement object to Array $responseArray
      $responseArray = array();
      foreach ($xmlresponse->UDSObject as $node) {
        $responseArray[] = $node;
      }

      // Sorting SimpleXMLElement object array
      function comparator($a, $b)
      {
        // sort by ID
        return (intval($a->Attributes->Attribute[0]->AttrValue) > intval($b->Attributes->Attribute[0]->AttrValue)) ? -1 : 1;
      }
      usort($responseArray, __NAMESPACE__ . '\comparator');

      // Print xml response to response template
      foreach ($responseArray as $item) {
        // Init attribute's variable
        $assignee_lastname = $item->Attributes->Attribute[7]->AttrValue;
        $assignee_firstname = $item->Attributes->Attribute[8]->AttrValue;
        $assignee_middlename = $item->Attributes->Attribute[9]->AttrValue;
        $zmaintech_lastname = $item->Attributes->Attribute[10]->AttrValue;
        $zmaintech_firstname = $item->Attributes->Attribute[11]->AttrValue;
        $zmaintech_middlename = $item->Attributes->Attribute[12]->AttrValue;

        // Generate assignee name
        if ($assignee_firstname != '' and $assignee_middlename != '') {
          $assignee_name = $assignee_lastname . ', ' . $assignee_firstname . ' ' . $assignee_middlename;
        } elseif ($assignee_middlename = '' and $assignee_firstname != '') {
          $assignee_name = $assignee_lastname . ', ' . $assignee_firstname;
        } elseif ($assignee_middlename != '' and $assignee_firstname = '') {
          $assignee_name = $assignee_lastname . ', ' . $assignee_middlename;
        } else {
          $assignee_name = $assignee_lastname;
        }

        // Generate zmaintech name
        if ($zmaintech_firstname != '' and $zmaintech_middlename != '') {
          $zmaintech_name = $zmaintech_lastname . ', ' . $zmaintech_firstname . ' ' . $zmaintech_middlename;
        } elseif ($zmaintech_middlename = '' and $zmaintech_firstname != '') {
          $zmaintech_name = $zmaintech_lastname . ', ' . $zmaintech_firstname;
        } elseif ($zmaintech_middlename != '' and $zmaintech_firstname = '') {
          $zmaintech_name = $zmaintech_lastname . ', ' . $zmaintech_middlename;
        } else {
          $zmaintech_name = $zmaintech_lastname;
        }

        $tmpstr = $tmpstr .
          '<tr>
                  <td style="vertical-align: middle;">' . $item->Attributes->Attribute[0]->AttrValue . '</a></td>' . // id
          '<td style="vertical-align: middle;">' . $item->Attributes->Attribute[1]->AttrValue . '</a></td>' . // ref_num
          '<td style="vertical-align: middle;">' . $item->Attributes->Attribute[2]->AttrValue . '</a></td>' . // summary
          '<td style="vertical-align: middle;">' . $item->Attributes->Attribute[3]->AttrValue . '</a></td>' . // priority
          '<td style="vertical-align: middle;">' . $item->Attributes->Attribute[4]->AttrValue . '</a></td>' . // category
          '<td style="vertical-align: middle;">' . $item->Attributes->Attribute[5]->AttrValue . '</a></td>' . // status
          '<td style="vertical-align: middle;">' . $item->Attributes->Attribute[6]->AttrValue . '</a></td>' . // group
          '<td style="vertical-align: middle;">' . $assignee_name . '</a></td>' . // assignee
          '<td style="vertical-align: middle;">' . $zmaintech_name . '</a></td>' . // zmain_tech
          '<td style="vertical-align: middle;">' . date("d-m-Y g:i a", intval($item->Attributes->Attribute[13]->AttrValue)) . '</a></td>' . // open_date
          '<td style="vertical-align: middle;">' . date("d-m-Y g:i a", intval($item->Attributes->Attribute[14]->AttrValue)) . '</a></td>' . // last_mod_dt
          '<td style="vertical-align: middle;">' . $item->Attributes->Attribute[15]->AttrValue . '</a></td>' . // sla_violation
          '</tr>';
      }
      $tmpstr = $tmpstr . '
            </tbody>
        </table>';
      echo $tmpstr;
    }
  }

  /*
  Page: Dashboard -> AllChanges
  */
  public function allchanges() {
    if (!Session::has('Monitor')) {
      $url = url('/');
      return redirect($url);
    }

    $casvdserver = DB::table('tbl_casvdservers')
      ->where([
        ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
      ])->first();

    $user = DB::table('tbl_accounts')
      ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
      ->where([
        ['tbl_accounts.username', '=', session('mymonitor_userid')]
      ])->first();

    // Get refreshrate
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

    //Login
    $this->SoapLogin();

    return view('casvd.allchanges', compact('casvdserver', 'user', 'refreshrate'));
  }

  /*
  Page: Dashboard -> All Changes
  Section: Function Return all changes to ajax call back
  */
  public function ajaxcasvdallchanges() {
    // Response template
    $tmpstr =
      '<table class="table table-hover table-condensed datatable" id="ajaxcasvdallchangestable">
        <thead>
            <tr>
                <th>Change Order #</th>
                <th>Summary</th>
                <th>Priority</th>
                <th>Category</th>
                <th>Status</th>
                <th>Assigned To</th>
                <th>Change Type</th>
            </tr>
        </thead>
        <tbody>';

    $casvdserver = DB::table('tbl_casvdservers')
      ->where([
        ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
      ])->first();

    if ($casvdserver->hostname == '') {
      return 'N/A';
    } else {
      $client = new SoapClient($casvdserver->secures . "://" . $casvdserver->hostname . ":" . $casvdserver->port . $casvdserver->basestring, array('trace' => 1));
      // Login to CASVD
      $ap_param = array(
        'username' => 'sd_test',
        'password' => 'msc@A2020'
      );
      $info = $client->__call("login", array($ap_param));

      // Get all tickets
      $ap_param = array(
        'sid' => $info->loginReturn,
        'sortBy' => 'id',
        'objectType' => 'chg',
        'whereClause' => "",
        'maxRows' => 20,
        'attributes' => ['chg_ref_num', 'summary', 'priority.sym', 'category.sym', 'status.sym', 'assignee.last_name', 'assignee.first_name', 'assignee.middle_name', 'chgtype']
      );
      $response = $client->__call("doSelect", array($ap_param))->doSelectReturn;

      // Convert XML to object
      $xmlresponse = simplexml_load_string($response);
      // Convert SimpleXMLElement object to Array $responseArray
      $responseArray = array();
      foreach ($xmlresponse->UDSObject as $node) {
        $responseArray[] = $node;
      }

      // Sorting SimpleXMLElement object array
      function comparator($a, $b)
      {
        // sort by ID
        return (intval($a->Attributes->Attribute[0]->AttrValue) > intval($b->Attributes->Attribute[0]->AttrValue)) ? -1 : 1;
      }
      usort($responseArray, __NAMESPACE__ . '\comparator');

      // Print xml response to response template
      foreach ($responseArray as $item) {
        // Init attribute's variable
        $assignee_lastname = $item->Attributes->Attribute[5]->AttrValue;
        $assignee_firstname = $item->Attributes->Attribute[6]->AttrValue;
        $assignee_middlename = $item->Attributes->Attribute[7]->AttrValue;

        // Generate assignee name
        if ($assignee_firstname != '' and $assignee_middlename != '') {
          $assignee_name = $assignee_lastname . ', ' . $assignee_firstname . ' ' . $assignee_middlename;
        } elseif ($assignee_middlename = '' and $assignee_firstname != '') {
          $assignee_name = $assignee_lastname . ', ' . $assignee_firstname;
        } elseif ($assignee_middlename != '' and $assignee_firstname = '') {
          $assignee_name = $assignee_lastname . ', ' . $assignee_middlename;
        } else {
          $assignee_name = $assignee_lastname;
        }

        $tmpstr = $tmpstr .
          '<tr>
                  <td style="vertical-align: middle;">' . $item->Attributes->Attribute[0]->AttrValue . '</a></td>' . // chg_ref_num
          '<td style="vertical-align: middle;">' . $item->Attributes->Attribute[1]->AttrValue . '</a></td>' . // summary
          '<td style="vertical-align: middle;">' . $item->Attributes->Attribute[2]->AttrValue . '</a></td>' . // priority
          '<td style="vertical-align: middle;">' . $item->Attributes->Attribute[3]->AttrValue . '</a></td>' . // category.sym
          '<td style="vertical-align: middle;">' . $item->Attributes->Attribute[4]->AttrValue . '</a></td>' . // status.sym
          '<td style="vertical-align: middle;">' . $assignee_name . '</a></td>' . // assignee
          '<td style="vertical-align: middle;">' . $item->Attributes->Attribute[8]->AttrValue . '</a></td>' . // chgtype
          '</tr>';
      }
      $tmpstr = $tmpstr . '
            </tbody>
        </table>';
      echo $tmpstr;
    }
  }

  /*
  Section: Function Soap Login
  */
  public function SoapLogin() {
    $casvdserver = DB::table('tbl_casvdservers')
      ->where([
        ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
      ])->first();

    if ($casvdserver->hostname == '') {
      return 'N/A';
    } else {
      $client = new SoapClient($casvdserver->secures . "://" . $casvdserver->hostname . ":" . $casvdserver->port . $casvdserver->basestring, array('trace' => 1));
      // Login to CASVD
      $ap_param = array(
        'username' => 'sd_test',
        'password' => 'msc@A2020'
      );
      $sid = $client->__call("login", array($ap_param))->loginReturn;
    }

    // Sorting SimpleXMLElement object array
    function comparator($a, $b)
    {
      // sort by ID
      return (intval($a->Attributes->Attribute[0]->AttrValue) < intval($b->Attributes->Attribute[0]->AttrValue)) ? -1 : 1;
    }

    return [$client, $sid];
  }

  /*
  Section: Function Return Priority drop down list
  */
  public function droplist_priority($client, $sid) {
    $tmpstr = '
      <select name="priority" class="select2 full-width-fix required">
      <option></option>
      <option value="">None</option>';

    // Get priority dropdown list
    $ap_param = array(
      'sid' => $sid,
      'objectType' => 'pri',
      'whereClause' => "",
      'maxRows' => 50,
      'attributes' => ['sym']
    );

    $response = $client->__call("doSelect", array($ap_param))->doSelectReturn;

    // Convert XML to object
    $xmlresponse = simplexml_load_string($response);
    // Convert SimpleXMLElement object to Array $responseArray
    $responseArray = array();
    foreach ($xmlresponse->UDSObject as $node) {
      $responseArray[] = $node;
    }

    usort($responseArray, __NAMESPACE__ . '\comparator');

    // Print xml response to response template
    foreach ($responseArray as $item) {
      $val = $item->Attributes->Attribute[0]->AttrValue;
      $tmpstr = $tmpstr .
        '<option value="' . $val . '">' . $val . '</option>'; // id
    }

    $tmpstr = $tmpstr . '
      </select>';

    return $tmpstr;
  }

  /*
  Section: Function Return Category drop down list
  */
  public function droplist_category($client, $sid) {
    $tmpstr = '
      <select name="category" class="select2 full-width-fix">
      <option></option>
      <option value="">None</option>';

    // Get category dropdown list
    $ap_param = array(
      'sid' => $sid,
      'objectType' => 'pcat',
      'whereClause' => "",
      'maxRows' => 50,
      'attributes' => ['sym']
    );

    $response = $client->__call("doSelect", array($ap_param))->doSelectReturn;

    // Convert XML to object
    $xmlresponse = simplexml_load_string($response);
    // Convert SimpleXMLElement object to Array $responseArray
    $responseArray = array();
    foreach ($xmlresponse->UDSObject as $node) {
      $responseArray[] = $node;
    }

    usort($responseArray, __NAMESPACE__ . '\comparator');

    // Print xml response to response template
    foreach ($responseArray as $item) {
      $val = $item->Attributes->Attribute[0]->AttrValue;
      $tmpstr = $tmpstr .
        '<option value="' . $val . '">' . $val . '</option>'; // id
    }

    $tmpstr = $tmpstr . '
      </select>';

    return $tmpstr;
  }

  /*
  Section: Function Return CI drop down list
  */
  public function droplist_ci($client, $sid) {
    $tmpstr = '
      <select name="ci" class="select2 full-width-fix">
      <option></option>
      <option value="">None</option>';

    // Get ci dropdown list
    $ap_param = array(
      'sid' => $sid,
      'objectType' => 'nr',
      'whereClause' => "",
      'maxRows' => 50,
      'attributes' => ['name']
    );

    $response = $client->__call("doSelect", array($ap_param))->doSelectReturn;

    // Convert XML to object
    $xmlresponse = simplexml_load_string($response);
    // Convert SimpleXMLElement object to Array $responseArray
    $responseArray = array();
    foreach ($xmlresponse->UDSObject as $node) {
      $responseArray[] = $node;
    }

    usort($responseArray, __NAMESPACE__ . '\comparator');

    // Print xml response to response template
    foreach ($responseArray as $item) {
      $val = $item->Attributes->Attribute[0]->AttrValue;
      $tmpstr = $tmpstr .
        '<option value="' . $val . '">' . $val . '</option>'; // id
    }

    $tmpstr = $tmpstr . '
      </select>';

    return $tmpstr;
  }

  /*
  Section: Function Return Group drop down list
  */
  public function droplist_group_backup($client, $sid) {
    $tmpstr = '
      <select name="group" class="select2 full-width-fix required">
      <option></option>
      <option value="">None</option>';

    // Get group dropdown list
    $ap_param = array(
      'sid' => $sid,
      'objectType' => 'cnt',
      'whereClause' => "",
      'maxRows' => 50,
      'attributes' => ['last_name']
    );

    $response = $client->__call("doSelect", array($ap_param))->doSelectReturn;

    // Convert XML to object
    $xmlresponse = simplexml_load_string($response);
    // Convert SimpleXMLElement object to Array $responseArray
    $responseArray = array();
    foreach ($xmlresponse->UDSObject as $node) {
      $responseArray[] = $node;
    }

    usort($responseArray, __NAMESPACE__ . '\comparator');

    // Print xml response to response template
    foreach ($responseArray as $item) {
      $val = $item->Attributes->Attribute[0]->AttrValue;
      $tmpstr = $tmpstr .
        '<option value="' . $val . '">' . $val . '</option>'; // id
    }

    $tmpstr = $tmpstr . '
      </select>';

    return $tmpstr;
  }

  /*
  Section: Function Return Group drop down list
  */
  public function droplist_group($client, $sid, $default) {
    $tmpstr = '<option value="">None</option>';

    // Get group dropdown list
    $ap_param = array(
      'sid' => $sid,
      'objectType' => 'cnt',
      'whereClause' => "type.sym='Group'",
      'maxRows' => 50,
      'attributes' => ['last_name']
    );

    $response = $client->__call("doSelect", array($ap_param))->doSelectReturn;

    // Convert XML to object
    $xmlresponse = simplexml_load_string($response);
    // Convert SimpleXMLElement object to Array $responseArray
    $responseArray = array();
    foreach ($xmlresponse->UDSObject as $node) {
      $responseArray[] = $node;
    }

    usort($responseArray, __NAMESPACE__ . '\comparator');

    // Print xml response to response template
    foreach ($responseArray as $item) {
      $val = $item->Attributes->Attribute[0]->AttrValue;
      if (strcmp($val, $default) == 0) {
        $tmpstr = $tmpstr . '<option value="' . $val . '" selected="selected">' . $val . '</option>';
      } else {
        $tmpstr = $tmpstr . '<option value="' . $val . '">' . $val . '</option>';
      }
    }
    // dd($tmpstr);
    // $tmpstr = substr($tmpstr, 0, -1);

    return $tmpstr;
  }

  /*
  Section: Function Return Group drop down list
  */
  public function droplist_contact($client, $sid, $type, $default) {
    $tmpstr = '<option value="">None</option>';

    // Get group dropdown list
    if ($type == "") {
      $ap_param = array(
        'sid' => $sid,
        'objectType' => 'cnt',
        'whereClause' => "",
        'attributes' => ['last_name', 'first_name', 'middle_name']
      );
    } else {
      $ap_param = array(
        'sid' => $sid,
        'objectType' => 'cnt',
        'whereClause' => "type.sym='$type'",
        'attributes' => ['last_name', 'first_name', 'middle_name']
      );
    };
    $listHandleID = $client->__call("doQuery", array($ap_param))->doQueryReturn->listHandle;

    // Get List value
    $ap_param = array(
      'sid' => $sid,
      'listHandle' => $listHandleID,
      'startIndex' => 0,
      'endIndex' => -1,
      'attributeNames' => ['last_name', 'first_name', 'middle_name']
    );
    $response = $client->__call("getListValues", array($ap_param))->getListValuesReturn;

    // Free List hanlde
    $ap_param = array(
      'sid' => $sid,
      'handles' => $listHandleID,
    );
    $client->__call("freeListHandles", array($ap_param));

    // dd($response);
    // Convert XML to object
    $xmlresponse = simplexml_load_string($response);
    // Convert SimpleXMLElement object to Array $responseArray
    $responseArray = array();
    foreach ($xmlresponse->UDSObject as $node) {
      $responseArray[] = $node;
    }

    usort($responseArray, __NAMESPACE__ . '\comparator');

    // Print xml response to response template
    foreach ($responseArray as $item) {
      if ($item->Attributes->Attribute[1]->AttrValue != '' and $item->Attributes->Attribute[2]->AttrValue != '') {
        $val = $item->Attributes->Attribute[0]->AttrValue . ', ' . $item->Attributes->Attribute[1]->AttrValue . ' ' . $item->Attributes->Attribute[2]->AttrValue;
      } elseif ($item->Attributes->Attribute[2]->AttrValue = '' and $item->Attributes->Attribute[1]->AttrValue != '') {
        $val = $item->Attributes->Attribute[0]->AttrValue . ', ' . $item->Attributes->Attribute[1]->AttrValue;
      } elseif ($item->Attributes->Attribute[2]->AttrValue != '' and $item->Attributes->Attribute[1]->AttrValue = '') {
        $val = $item->Attributes->Attribute[0]->AttrValue . ', ' . $item->Attributes->Attribute[2]->AttrValue;
      } else {
        $val = $item->Attributes->Attribute[0]->AttrValue;
      }

      if (strcmp($val, $default) == 0) {
        $tmpstr = $tmpstr . '<option value="' . $val . '" selected="selected">' . $val . '</option>';
      } else {
        $tmpstr = $tmpstr . '<option value="' . $val . '">' . $val . '</option>';
      }
    }
    // dd($tmpstr);
    // $tmpstr = substr($tmpstr, 0, -1);

    return $tmpstr;
  }

  /*
  Section: Popup window - Person
  */
  public function popupperson() {
    if (!Session::has('Monitor')) {
      $url = url('/');
      return redirect($url);
    }

    $dm = Crypt::decryptString(session('mymonitor_md'));

    $casvdserver = DB::table('tbl_casvdservers')
      ->where([
        ['domainid', '=', Crypt::decryptString(session('mymonitor_md'))]
      ])->first();

    $user = DB::table('tbl_accounts')
      ->leftJoin('tbl_rights', 'tbl_accounts.userid', '=', 'tbl_rights.userid')
      ->where([
        ['tbl_accounts.username', '=', session('mymonitor_userid')]
      ])->first();

    $tmpstr = $this->allpersons($casvdserver);

    return view('casvd.popupperson', compact('casvdserver', 'user', 'tmpstr'));
  }

  /*
  Section: List all person contact
  */
  public function allpersons($casvdserver) {
    $tmpstr = '[';

    if ($casvdserver->hostname == '') {
      return 'N/A';
    } else {
      $client = new SoapClient($casvdserver->secures . "://" . $casvdserver->hostname . ":" . $casvdserver->port . $casvdserver->basestring, array('trace' => 1));
      // Login to CASVD
      $ap_param = array(
        'username' => 'sd_test',
        'password' => 'msc@A2020'
      );
      $info = $client->__call("login", array($ap_param));

      // Get all Requests
      $ap_param = array(
        'sid' => $info->loginReturn,
        'objectType' => 'cnt',
        'whereClause' => "last_name='SD Test'",
        'maxRows' => 50,
        'attributes' => ['id','combo_name','userid','zcnt_area','email_address','type.sym','access_type.sym','schedule.sym','delete_flag.sym']
      );
      $response = $client->__call("doSelect", array($ap_param))->doSelectReturn;

      // Convert XML to object
      $xmlresponse = simplexml_load_string($response);

      // Convert SimpleXMLElement object to Array $responseArray
      $responseArray = array();
      foreach ($xmlresponse->UDSObject as $node) {
        $responseArray[] = $node;
      }

      // Sorting SimpleXMLElement object array
      function comparator($a, $b)
      {
        // sort by ID
        return (intval($a->Attributes->Attribute[0]->AttrValue) > intval($b->Attributes->Attribute[0]->AttrValue)) ? -1 : 1;
      }
      usort($responseArray, __NAMESPACE__ . '\comparator');
      $jsondata = json_encode($responseArray);

      // Print xml response to response template
      foreach ($responseArray as $item) {
        $tmpstr = $tmpstr .
          '{' .
          '"ID":"' . $item->Attributes->Attribute[0]->AttrValue . '", ' . // id
          '"Name":"' . $item->Attributes->Attribute[1]->AttrValue . '", ' . // combo_name
          '"User ID":"' . $item->Attributes->Attribute[2]->AttrValue . '", ' . // userid
          '"Area":"' . $item->Attributes->Attribute[3]->AttrValue . '", ' . // zcnt_area
          '"Email Address":"' . $item->Attributes->Attribute[4]->AttrValue . '", ' . // email_address
          '"Contact Type":"' . $item->Attributes->Attribute[5]->AttrValue . '", ' . // type.sym
          '"Access Type":"' . $item->Attributes->Attribute[6]->AttrValue . '", ' . // access_type.sym
          '"Workshift":"' . $item->Attributes->Attribute[7]->AttrValue . '", ' . // schedule.sym
          '"Status":"' . $item->Attributes->Attribute[8]->AttrValue . '"' . // delete_flag.sym
          '},';
      }

      $tmpstr = substr($tmpstr, 0, -1);
      $tmpstr = $tmpstr . ']';
      $tmpstr = json_decode($tmpstr);

    }

    return $tmpstr;
  }

  /*
  Section: Function Return Contact drop down list
  */
  // public function droplist_group($client, $sid) {
  //   $tmpstr='
  //     <select name="group" class="select2 full-width-fix required">
  //     <option></option>
  //     <option value="">None</option>';

  //   // Get group dropdown list
  //   $ap_param = array(
  //     'sid' => $sid,
  //     'objectType' => 'cnt',
  //     'whereClause' => "",
  //     'maxRows' => 50,
  //     'attributes' => ['last_name']
  //   );

  //   $response = $client->__call("doSelect",array($ap_param))->doSelectReturn;

  //   // Convert XML to object
  //   $xmlresponse = simplexml_load_string($response);
  //   // Convert SimpleXMLElement object to Array $responseArray
  //   $responseArray = array();
  //   foreach($xmlresponse->UDSObject as $node) {
  //     $responseArray[] = $node;
  //   }

  //   usort($responseArray, __NAMESPACE__ . '\comparator');

  //   // Print xml response to response template
  //   foreach ($responseArray as $item) {
  //     $val = $item->Attributes->Attribute[0]->AttrValue;
  //     $tmpstr = $tmpstr .
  //             '<option value="'.$val.'">'.$val.'</option>'; // id
  //   }

  //   $tmpstr = $tmpstr . '
  //     </select>';

  //   return $tmpstr;
  // }
}
