<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(array('before' => 'api', 'after' => 'api-out'), function() {
	Route::get('api', 'APIController@api');
	Route::get('api/spots', 'APIController@spots');
	Route::get('api/actuators', 'APIController@actuators');
	Route::get('api/nonzone_spots', 'APIController@nonzone_spots');
}); 

Route::group(array('before' => 'guest'), function() {
	Route::controller('login', 'LoginController');
});

Route::group(array('before' => 'auth|admin'), function() {
	Route::get('admin/logout', 'LoginController@getLogout');

	Route::get('admin/basestations/{id}/unassign', 'BasestationController@getUnassignBasestation');
	Route::get('admin/basestations/{id}/edit', 'BasestationController@getEditBasestation');
	Route::get('admin/basestations/{id}', 'BasestationController@getBasestation');
	Route::post('admin/basestations/{id}', 'BasestationController@postEditBasestation');
	Route::controller('admin/basestations/', 'BasestationController');
 
 	Route::get('admin/spots', 'AdminController@getSpots');
	
	Route::get('admin/users/create', 'UserController@getCreateUser');
	Route::post('admin/users/create', 'UserController@postCreateUser');
	Route::get('admin/users/{id}/edit', 'UserController@getEditUser');
	Route::get('admin/users/{id}', 'UserController@getUser');
	Route::post('admin/users/{id}', 'UserController@postEditUser');
	Route::controller('admin/users/', 'UserController');

	Route::controller('admin', 'AdminController');
});

Route::group(array('before' => 'auth|basestation|spot|user'), function() {
	Route::get('spots/{id}/stop_tracking', 'SpotController@getStopTracking');
	Route::resource('spots', 'SpotController');
	Route::controller('spots', 'SpotController');

	Route::get('objects/{id}/unlink', 'ObjectController@getUnlink');
	Route::resource('objects', 'ObjectController');

	Route::post('jobs/clear/{id}', 'JobController@clear');
	Route::post('jobs/toggle_tracking/{id}', 'JobController@toggle_tracking');
	Route::resource('jobs', 'JobController');

	Route::get('actuators/{id}/time', 'ActuatorController@getActuatorTimes');
	Route::post('actuators/{id}/settime', 'ActuatorController@postActuatorTime');
	Route::get('actuators/{id}/ajax', 'ActuatorController@getAjax');
	Route::get('actuators/conditions/{id}', 'ActuatorController@getConditions');
	Route::post('actuators/boolean_condition/{id}', 'ActuatorController@setBoolean');
	Route::post('actuators/add_condition/{id}', 'ActuatorController@addCondition');
	Route::post('actuators/delete_condition/{id}', 'ActuatorController@deleteCondition');
	Route::post('actuators/delete_job/{id}', 'ActuatorController@deleteJob');
	Route::post('actuators/set_status', 'ActuatorController@postSetStatus');
	Route::resource('actuators', 'ActuatorController');

	Route::post('zones/configure/addZone', array('as' =>'zones.configure.add_zone' , 'uses' => 'ZoneController@postAddZone'));
	Route::post('zones/configure/addObject', array('as' =>'zones.configure.add_object' , 'uses' => 'ZoneController@postAddObject'));
	Route::get('zones/configure', array('as' =>'zones.configure' , 'uses' => 'ZoneController@getZoneConfigure'));
	Route::post('zones/{id}/updateObject', array('as' =>'zones.update_object' , 'uses' => 'ZoneController@postUpdateObject'));
	Route::post('zones/{id}/updateZone', array('as' =>'zones.update_zone' , 'uses' => 'ZoneController@postUpdateZone'));
	Route::controller('zones', 'ZoneController');
	Route::resource('zones', 'ZoneController');

	Route::controller('reports', 'ReportController');
	Route::resource('reports', 'ReportController');

	Route::get('logout', 'LoginController@getLogout');
	Route::controller('/', 'TouchController');
});