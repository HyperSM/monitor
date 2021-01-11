<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

route::get('/','homeController@index');
route::post('doLogin','homeController@doLogin');
route::get('/admin/logout','homeController@doLogout');


route::get('/admin/dashboard','adminController@dashboard');
route::get('timequery','adminController@timequeryfunction')->name('timequery');
route::get('/admin/dashboard/users','adminController@users');
route::get('/admin/dashboard/users/adduser','adminController@adduser');
route::post('/admin/dashboard/users/adduser','adminController@addusersubmit');
route::post('/admin/dashboard/users/checkuserexist','adminController@checkuserexist');
route::get('/admin/dashboard/users/ena/{userid}','adminController@enableuser');
route::get('/admin/dashboard/users/disa/{userid}','adminController@disableuser');
route::get('/admin/dashboard/users/edit/{userid}','adminController@edituser');
route::post('/admin/dashboard/users/edit/{userid}','adminController@editusersubmit');
route::get('/admin/dashboard/users/delete/{userid}','adminController@deleteuser');
route::post('/admin/dashboard/users/delete/{userid}','adminController@deleteusersubmit');

//System Admin (Sysadmin)
route::get('/sysadmin','sysadminController@index');
route::post('doAdminLogin','sysadminController@doLogin');
route::get('/sysadmin/dashboard','sysadminController@dashboard');
route::get('/sysadmin/logout','sysadminController@doLogout');

//System Admin (Sysadmin) - User Management
route::get('/sysadmin/users','sysadminController@users');
route::get('/sysadmin/users/adduser','sysadminController@adduser');
route::post('/sysadmin/users/adduser','sysadminController@addusersubmit');
route::get('/sysadmin/users/edit/{userid}','sysadminController@edituser');
route::post('/sysadmin/users/edit/{userid}','sysadminController@editusersubmit');
route::get('/sysadmin/users/delete/{userid}','sysadminController@deleteuser');
route::post('/sysadmin/users/delete/{userid}','sysadminController@deleteusersubmit');

//System Admin (Sysadmin) - Domain Management
route::get('/sysadmin/domains','sysadminController@domains');
route::get('/sysadmin/domains/adddomain','sysadminController@adddomain');
route::post('/sysadmin/domains/adddomain','sysadminController@adddomainsubmit');
route::get('/sysadmin/domains/edit/{domainid}','sysadminController@editdomain');
route::post('/sysadmin/domains/edit/{domainid}','sysadminController@editdomainsubmit');
route::get('/sysadmin/domains/delete/{domainid}','sysadminController@deletedomain');
route::post('/sysadmin/domains/delete/{domainid}','sysadminController@deletedomainsubmit');
route::get('/sysadmin/domains/ena/{domainid}','sysadminController@enabledomain');
route::get('/sysadmin/domains/disa/{domainid}','sysadminController@disabledomain');
route::get('/sysadmin/domains/{domainid}/users','sysadminController@domainusers');
route::get('/sysadmin/domains/{domainid}/users/adduser','sysadminController@adddomainuser');
route::post('/sysadmin/domains/{domainid}/users/adduser','sysadminController@adddomainusersubmit');
route::get('/sysadmin/domains/{domainid}/users/edit/{userid}','sysadminController@editdomainuser');
route::post('/sysadmin/domains/{domainid}/users/edit/{userid}','sysadminController@editdomainusersubmit');
route::get('/sysadmin/domains/{domainid}/users/delete/{userid}','sysadminController@deletedomainuser');
route::post('/sysadmin/domains/{domainid}/users/delete/{userid}','sysadminController@deletedomainusersubmit');
route::get('/sysadmin/domains/{domainid}/users/ena/{userid}','sysadminController@enabledomainuser');
route::get('/sysadmin/domains/{domainid}/users/disa/{userid}','sysadminController@disabledomainuser');

//System Admin (Sysadmin) - Billing
route::get('/sysadmin/billing/prices','sysadminController@billingprices');
route::get('/sysadmin/billing/prices/addprice','sysadminController@addbillingprice');
route::post('/sysadmin/billing/prices/addprice','sysadminController@addbillingpricesubmit');
route::get('/sysadmin/billing/prices/edit/{product}','sysadminController@editbillingprice');
route::post('/sysadmin/billing/prices/edit/{product}','sysadminController@editbillingpricesubmit');
route::get('/sysadmin/billing/prices/delete/{product}','sysadminController@deletebillingprice');
route::post('/sysadmin/billing/prices/delete/{product}','sysadminController@deletebillingpricesubmit');
route::get('/sysadmin/billing/detail','sysadminController@billingdetail');
route::get('/sysadmin/billing/detail/{domainid}','sysadminController@billingdetaildomain');
route::post('/sysadmin/billing/detail/{domainid}','sysadminController@billingdetaildomainreload');
route::get('ajaxbillingcasvd/{domainid}/{start}/{end}','sysadminController@ajaxbillingcasvd')->name('ajaxbillingcasvd');
route::get('ajaxbillingcentreon/{domainid}','sysadminController@ajaxbillingcentreon')->name('ajaxbillingcentreon');
route::get('ajaxbillingslwnpm/{domainid}','sysadminController@ajaxbillingslwnpm')->name('ajaxbillingslwnpm');
route::get('ajaxbillingciscosdwan/{domainid}','sysadminController@ajaxbillingciscosdwan')->name('ajaxbillingciscosdwan');
// route::get('/test1/{domainid}','sysadminController@ajaxbillingciscosdwan');
// route::get('/sysadmin/ajaxbillingcasvdcount/{domainid}/{start}/{end}','sysadminController@ajaxbillingcasvdcount')->name('ajaxbillingcasvdcount');
// route::get('/sysadmin/ajaxbillingcentreoncount/{domainid}/{start}/{end}','sysadminController@ajaxbillingcentreoncount')->name('ajaxbillingcentreoncount');
// route::get('/sysadmin/ajaxbillingslwnpmcount/{domainid}/{start}/{end}','sysadminController@ajaxbillingslwnpmcount')->name('ajaxbillingslwnpmcount');
// route::get('/sysadmin/ajaxbillingsdwancount/{domainid}/{start}/{end}','sysadminController@ajaxbillingsdwancount')->name('ajaxbillingsdwancount');

//Solarwinds npm controller
route::get('totalnodesquery','slwnpmController@totalnodesqueryfunction')->name('totalnodesquery');
route::get('totalintquery','slwnpmController@totalintqueryfunction')->name('totalintquery');
route::get('nodeupquery','slwnpmController@nodeupqueryfunction')->name('nodeupquery');
route::get('nodedownquery','slwnpmController@nodedownqueryfunction')->name('nodedownquery');
route::get('intupquery','slwnpmController@intupqueryfunction')->name('intupquery');
route::get('intdownquery','slwnpmController@intdownqueryfunction')->name('intdownquery');
route::get('npmnodetree','slwnpmController@npmnodetreefunction')->name('npmnodetree');
route::get('ajaxnpmutilization','slwnpmController@ajaxnpmutilization')->name('ajaxnpmutilization');
route::get('ajaxhwhealthup','slwnpmController@ajaxhwhealthup')->name('ajaxhwhealthup');
route::get('ajaxhwhealthunknown','slwnpmController@ajaxhwhealthunknown')->name('ajaxhwhealthunknown');
route::get('ajaxhwhealthcritical','slwnpmController@ajaxhwhealthcritical')->name('ajaxhwhealthcritical');
route::get('ajaxhwhealthwarning','slwnpmController@ajaxhwhealthwarning')->name('ajaxhwhealthwarning');
route::get('ajaxnpmlast10event','slwnpmController@ajaxnpmlast10event')->name('ajaxnpmlast10event');
route::get('ajaxnpmeventsum','slwnpmController@ajaxnpmeventsum')->name('ajaxnpmeventsum');
route::get('ajaxnpmunack','slwnpmController@ajaxnpmunack')->name('ajaxnpmunack');
route::get('npmnodejstree','slwnpmController@npmnodejstree')->name('npmnodejstree');

route::get('/admin/slwnpm','slwnpmController@dashboard');
route::get('/admin/slwnpm/configserver','slwnpmController@configserver');
route::post('/admin/slwnpm/slwnpmserver','slwnpmController@slwnpmserversubmit');
route::get('/admin/slwnpm/configgroup','slwnpmController@configgroup');
route::post('/admin/slwnpm/configgroup','slwnpmController@configgroupsubmit');

//node detail
route::get('/admin/slwnpm/nodedetail/{nodeid}','slwnpmController@slwnpmnodedetail');
//route::get('slwnpmnodedetaildevice{nodeid}','slwnpmController@slwnpmnodedetaildevice')->name('slwnpmnodedetaildevice');

route::get('/admin/slwnpm/nodesummary/{nodeid}','slwnpmController@nodesummary');
route::get('nodesummarydevice{nodeid}','slwnpmController@nodesummarydevice')->name('nodesummarydevice');
route::get('nodesummaryinterfaces{nodeid}','slwnpmController@nodesummaryinterfaces')->name('nodesummaryinterfaces');
route::get('nodesummarycpuload{nodeid}','slwnpmController@nodesummarycpuload')->name('nodesummarycpuload');
route::get('nodesummaryallip{nodeid}','slwnpmController@nodesummaryallip')->name('nodesummaryallip');
route::get('nodesummaryeventsum{nodeid}','slwnpmController@nodesummaryeventsum')->name('nodesummaryeventsum');
route::get('nodesummaryalerts{nodeid}','slwnpmController@nodesummaryalerts')->name('nodesummaryalerts');

//node network
route::get('/admin/slwnpm/nodenetwork/{nodeid}','slwnpmController@slwnpmnodenetwork');

//interface detail
route::get('/admin/slwnpm/interfacedetail/{interfaceid}','slwnpmController@interfacedetail');
route::get('slwnpm.intdetail.eventsum{interfaceid}','slwnpmController@interfacedetaileventsum')->name('slwnpm.intdetail.eventsum');
route::get('slwnpm.intdetail.percentutil{interfaceid}','slwnpmController@interfacedetailpercentutil')->name('slwnpm.intdetail.percentutil');
route::get('slwnpm.intdetail.errordiscards{interfaceid}','slwnpmController@interfacedetailerrordiscards')->name('slwnpm.intdetail.errordiscards');
route::get('slwnpm.intdetail.downtime{interfaceid}','slwnpmController@interfacedetaildowntime')->name('slwnpm.intdetail.downtime');

//events
route::get('/admin/slwnpm/events','slwnpmController@events');
route::post('/admin/slwnpm/events','slwnpmController@eventssubmit');

//Alerts
route::get('/admin/slwnpm/alerts','slwnpmController@alerts');

//Chat
Route::get('slwnpm.chat', 'slwnpmController@getchat')->name('slwnpm.chat');
Route::post('slwnpm.chat', 'slwnpmController@storechat')->name('slwnpm.chat');

//Search
route::post('/admin/slwnpm/search','slwnpmController@search');

//Cisco SDWAN
//Dashboard
route::get('/admin/ciscosdwan','ciscosdwanController@dashboard');
route::get('/admin/ciscosdwan/configserver','ciscosdwanController@configserver');
route::post('/admin/ciscosdwan/ciscosdwanserver','ciscosdwanController@ciscosdwanserversubmit');
route::get('ciscosdwan.dashboard.alldevices','ciscosdwanController@dashboardalldevices')->name('ciscosdwan.dashboard.alldevices');
route::get('ciscosdwan.dashboard.rebootcount','ciscosdwanController@dashboardrebootcount')->name('ciscosdwan.dashboard.rebootcount');
route::get('ciscosdwan.dashboard.warningcount','ciscosdwanController@dashboardwarningcount')->name('ciscosdwan.dashboard.warningcount');
route::get('ciscosdwan.dashboard.invalidcount','ciscosdwanController@dashboardinvalidcount')->name('ciscosdwan.dashboard.invalidcount');
route::get('ciscosdwan.dashboard.wanedgehealth','ciscosdwanController@dashboardwanedgehealth')->name('ciscosdwan.dashboard.wanedgehealth');
route::get('ciscosdwan.dashboard.controlstatus','ciscosdwanController@dashboardcontrolstatus')->name('ciscosdwan.dashboard.controlstatus');
route::get('ciscosdwan.dashboard.wanedgeinventory','ciscosdwanController@dashboardwanedgeinventory')->name('ciscosdwan.dashboard.wanedgeinventory');
route::get('ciscosdwan.dashboard.sitehealth','ciscosdwanController@dashboardsitehealth')->name('ciscosdwan.dashboard.sitehealth');
route::get('ciscosdwan.dashboard.transportinterface','ciscosdwanController@dashboardstransportinterface')->name('ciscosdwan.dashboard.transportinterface');
route::get('ciscosdwan.dashboard.transporthealth','ciscosdwanController@dashboardtransporthealth')->name('ciscosdwan.dashboard.transporthealth');
route::get('ciscosdwan.dashboard.alarms','ciscosdwanController@dashboardalarms')->name('ciscosdwan.dashboard.alarms');
route::get('ciscosdwan.dashboard.serverdetail','ciscosdwanController@dashboardserverdetail')->name('ciscosdwan.dashboard.serverdetail');
route::get('ciscosdwan.dashboard.ajaxcontrol{type}','ciscosdwanController@dashboardajaxcontrol')->name('ciscosdwan.dashboard.ajaxcontrol');
route::get('ciscosdwan.dashboard.ajaxinventory','ciscosdwanController@dashboardajaxinventory')->name('ciscosdwan.dashboard.ajaxinventory');
route::get('ciscosdwan.dashboard.ajaxsitehealth','ciscosdwanController@dashboardajaxsitehealth')->name('ciscosdwan.dashboard.ajaxsitehealth');
route::get('ciscosdwan.dashboard.ajaxsiteinterface','ciscosdwanController@dashboardajaxsiteinterface')->name('ciscosdwan.dashboard.ajaxsiteinterface');

//Network
route::get('/admin/ciscosdwan/network','ciscosdwanController@network');
Route::get('/admin/ciscosdwan/network/detail/{deviceid}/systemstatus','ciscosdwanController@systemstatus');
route::get('ciscosdwan.network.ajaxreboot','ciscosdwanController@networkajaxreboot')->name('ciscosdwan.network.ajaxreboot');
route::get('ciscosdwan.network.ajaxcrash','ciscosdwanController@networkajaxcrash')->name('ciscosdwan.network.ajaxcrash');
route::get('ciscosdwan.network.ajaxsummary','ciscosdwanController@networkajaxsummary')->name('ciscosdwan.network.ajaxsummary');
route::post('ciscosdwan.network.loadcpumemory','ciscosdwanController@networkloadcpumemory')->name('ciscosdwan.network.loadcpumemory');
Route::get('/admin/ciscosdwan/network/detail/{deviceid}/applicationdpi','ciscosdwanController@applicationdpi');
route::post('ciscosdwan.network.loadapplicationdpi','ciscosdwanController@networkloadapplicationdpi')->name('ciscosdwan.network.loadapplicationdpi');
Route::get('/admin/ciscosdwan/network/detail/{deviceid}/events','ciscosdwanController@networkevents');
Route::get('/admin/ciscosdwan/network/detail/{deviceid}/connections','ciscosdwanController@networkconnections');

//Template
Route::get('/admin/ciscosdwan/templates','ciscosdwanController@templates');
Route::get('/admin/ciscosdwan/templates/{templateid}/attach','ciscosdwanController@templatesattach');
Route::get('/admin/ciscosdwan/templates/{templateid}/detach','ciscosdwanController@templatesdetach');
Route::post('/admin/ciscosdwan/templates/attachcheck','ciscosdwanController@templatesattachcheck');
Route::post('/admin/ciscosdwan/templates/detachcheck','ciscosdwanController@templatesdetachcheck');

//Schedule
Route::get('/admin/ciscosdwan/schedules','ciscosdwanController@schedules');
Route::get('/admin/ciscosdwan/schedules/getdeviceandtemplate','ciscosdwanController@getdeviceandtemplate');
Route::post('/admin/ciscosdwan/schedules/doaddnew','ciscosdwanController@scheduledoaddnew');
Route::post('/admin/ciscosdwan/schedules/dodelete','ciscosdwanController@scheduledodelete');

//Bandwidth forecasting
Route::get('/admin/ciscosdwan/bandwidth/forecast','ciscosdwanController@forecast');
Route::post('/admin/ciscosdwan/bandwidth/bandwidthvalue','ciscosdwanController@bandwidthvalue');
//Route::get('/admin/ciscosdwan/bandwidth/bandwidthvalue','ciscosdwanController@bandwidthvalue');

//CA Service Desks
//Dashboard
Route::get('/admin/casvd','casvdController@dashboard');
Route::get('/admin/casvd/crs','casvdController@crs');
Route::get('/admin/casvd/serverconfig','casvdController@serverconfig');
Route::post('/admin/casvd/casvdserver','casvdController@casvdserversubmit');
Route::get('ajaxcasvddashboardincidents','casvdController@ajaxcasvddashboardincidents')->name('ajaxcasvddashboardincidents');
Route::get('ajaxcasvddashboardrequests','casvdController@ajaxcasvddashboardrequests')->name('ajaxcasvddashboardrequests');
Route::get('ajaxcasvddashboardchanges','casvdController@ajaxcasvddashboardchanges')->name('ajaxcasvddashboardchanges');
Route::get('ajaxcasvddashboardtotalincidents/{start}/{end}','casvdController@ajaxcasvddashboardtotalincidents')->name('ajaxcasvddashboardtotalincidents');
Route::get('ajaxcasvddashboardtotalrequests/{start}/{end}','casvdController@ajaxcasvddashboardtotalrequests')->name('ajaxcasvddashboardtotalrequests');
Route::get('ajaxcasvddashboardtotalchanges/{start}/{end}','casvdController@ajaxcasvddashboardtotalchanges')->name('ajaxcasvddashboardtotalchanges');
Route::get('ajaxcasvddashboardticketchart','casvdController@ajaxcasvddashboardticketchart')->name('ajaxcasvddashboardticketchart');
Route::get('/admin/casvd/getrefreshrate','casvdController@getrefreshrate');
Route::post('/admin/casvd/setrefreshrate','casvdController@setrefreshrate');
//Incident
Route::get('/admin/casvd/allincidents','casvdController@allincidents');
Route::post('ajaxcasvdallincidents','casvdController@ajaxcasvdallincidents')->name('ajaxcasvdallincidents');
Route::get('/admin/casvd/addincident','casvdController@addincident');
Route::post('/admin/casvd/addincident','casvdController@addincidentsubmit');
//Request
Route::get('/admin/casvd/allrequests','casvdController@allrequests');
Route::post('ajaxcasvdallrequests','casvdController@ajaxcasvdallrequests')->name('ajaxcasvdallrequests');
Route::get('/admin/casvd/allrequests/edit/{refnum}','casvdController@editrequest');
Route::post('/admin/casvd/allrequests/edit/{refnum}','casvdController@editrequestsubmit');
Route::get('/admin/casvd/allrequests/create','casvdController@createrequest');
Route::post('/admin/casvd/allrequests/create','casvdController@createrequestsubmit');
//Change
Route::get('/admin/casvd/allchanges','casvdController@allchanges');
Route::post('ajaxcasvdallchanges','casvdController@ajaxcasvdallchanges')->name('ajaxcasvdallchanges');
//Popup
Route::get('/admin/casvd/popup/person','casvdController@popupperson');
Route::get('/admin/casvd/popup/person/requester','casvdController@requesterdialog');
Route::get('/admin/casvd/popup/person/customer','casvdController@popuppersonsearch');
Route::get('/admin/casvd/popup/group/{id}','casvdController@popupgroup');
Route::get('/admin/casvd/popup/ci/{id}','casvdController@popupcisearch');
//Search
Route::get('/admin/casvd/popup/search/requester','casvdController@getListRequester') ->name('ajaxgetListRequester');
//Route::post('/admin/casvd/popup/person/customer/{id}','casvdController@popupperson');
//Route::post('/admin/casvd/popup/ci/{id}','casvdController@popupci');
Route::get('/admin/casvd/popup/assignee/search','casvdController@openDialogAssignee');
Route::get('/admin/casvd/popup/requester/search','casvdController@test') ->name('test111');


//Centreon
Route::get('/admin/centreon','centreonController@dashboard');
Route::get('/admin/centreon/serverconfig','centreonController@serverconfig');
route::post('/admin/centreon/centreonserver','centreonController@centreonserversubmit');
Route::get('/admin/centreon/getrefreshrate','centreonController@getrefreshrate');
Route::post('/admin/centreon/setrefreshrate','centreonController@setrefreshrate');
Route::post('ajaxgetservicebyhost','centreonController@ajaxgetservicebyhost')->name('ajaxgetservicebyhost');
Route::post('ajaxgetinfohost','centreonController@ajaxgetinfohost')->name('ajaxgetinfohost');
// host
Route::get('/admin/centreon/hosts','centreonController@hosts');
route::get('/admin/centreon/hosts/addhost','centreonController@addhost');
route::post('/admin/centreon/hosts/addhostsubmit','centreonController@addhostsubmit');
route::get('/admin/centreon/hosts/delete/{name}','centreonController@deletehost');
route::post('/admin/centreon/hosts/delete/{name}','centreonController@deletehostsubmit');
route::get('/admin/centreon/hosts/edit/{id}','centreonController@edithost');
route::post('/admin/centreon/hosts/edit','centreonController@edithostsubmit');
// host group
Route::get('/admin/centreon/hostgroup','centreonController@hostgroup');
route::get('/admin/centreon/hostgroup/add','centreonController@addhostgroup');
route::post('/admin/centreon/hostgroup/add','centreonController@addhostgroupsubmit');
route::get('/admin/centreon/hostgroup/edit/{id}','centreonController@edithostgroup');
route::post('/admin/centreon/hostgroup/edit','centreonController@edithostgroupsubmit');
route::get('/admin/centreon/hostgroup/delete/{name}','centreonController@deletehostgroup');
route::post('/admin/centreon/hostgroup/delete/{name}','centreonController@deletehostgroupsubmit');
//service group
Route::get('/admin/centreon/srvgroup','centreonController@srvgroup');
Route::get('/admin/centreon/srvgroup/add','centreonController@addservicegroup');
Route::post('/admin/centreon/srvgroup/addsubmit','centreonController@addservicegroupsubmit');
Route::get('/admin/centreon/srvgroup/delete/{name}','centreonController@deleteservicegroup');
Route::post('/admin/centreon/srvgroup/delete/{name}','centreonController@deleteservicegroupsubmit');
Route::get('/admin/centreon/srvgroup/edit/{id}','centreonController@editservicegroup');
Route::post('/admin/centreon/srvgroup/edit','centreonController@editservicegroupsubmit');
// service by host
Route::get('/admin/centreon/service','centreonController@services');
Route::get('/admin/centreon/service/add','centreonController@addservice');
Route::post('/admin/centreon/service/add','centreonController@addservicesubmit');
route::get('/admin/centreon/service/delete/host/{host}/service/{service}','centreonController@deleteservice');
route::post('/admin/centreon/service/delete','centreonController@deleteservicesubmit');
Route::get('/admin/centreon/service/edit/{id}','centreonController@editservice');
Route::post('/admin/centreon/service/edit','centreonController@editservicesubmit');
// monitoring
Route::get('/admin/centreon/monitoring','centreonController@monitoring');
Route::get('ajaxmonitors','centreonController@ajaxmonitors')->name('ajaxmonitors');
Route::post('ajaxgetdetailhost','centreonController@ajaxgetdetailhost')->name('ajaxgetdetailhost');

//Test route
Route::get('/test','adminController@test');

//Report
Route::get('/admin/centreon/report','centreonController@report');
Route::get('/admin/centreon/report/{name}','centreonController@getreportbyname');
