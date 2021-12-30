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

//Cisco SDWAN
route::get('/ciscosdwan/report','ciscosdwanController@reporttotal');