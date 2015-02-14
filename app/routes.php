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

Route::filter('spot', function() {
	$spot = Spot::whereUserId(null)->first();
	if($spot != null) {
		Session::put('notice', 'A new Sun SPOT with the address of <strong>'.$spot->spot_address.'</strong> has been detected. Please <a href="'.url("spots/$spot->id/edit").'"><span class="glyphicon glyphicon-cog"></span> click here</a> to configure it.');
	}
});

Route::group(array('before' => 'spot'), function() {
	Route::get('twitter', function() {
		echo Heat::lab();
		echo "<br />";
		echo Light::lab();
	});

	Route::controller('cup', 'CupController');

	Route::resource('spots', 'SpotController');
	Route::controller('spots', 'SpotController');
	Route::resource('objects', 'ObjectController');

	Route::post('jobs/clear/{id}', 'JobController@clear');
	Route::post('jobs/toggle_tracking/{id}', 'JobController@toggle_tracking');
	Route::resource('jobs', 'JobController');

	Route::get('actuators/{id}/ajax', 'ActuatorController@getAjax');
	Route::get('actuators/conditions/{id}', 'ActuatorController@getConditions');
	Route::post('actuators/boolean_condition/{id}', 'ActuatorController@setBoolean');
	Route::post('actuators/add_condition/{id}', 'ActuatorController@addCondition');
	Route::post('actuators/delete_condition/{id}', 'ActuatorController@deleteCondition');
	Route::post('actuators/delete_job/{id}', 'ActuatorController@deleteJob');
	Route::post('actuators/set_status', 'ActuatorController@postSetStatus');
	Route::resource('actuators', 'ActuatorController');

	Route::controller('zones', 'ZoneController');
	Route::resource('zones', 'ZoneController');

	Route::controller('reports', 'ReportController');
	Route::resource('reports', 'ReportController');

	Route::get('api/spots', 'APIController@spots');
	Route::get('api/nonzone_spots', 'APIController@nonzone_spots');

	Route::controller('/', 'TouchController');
});