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
	Route::controller('cup', 'CupController');
	Route::controller('/', 'TouchController');

	Route::resource('spots', 'SpotController');
	Route::resource('objects', 'ObjectController');
	Route::resource('jobs', 'JobController');

	Route::controller('zones', 'ZoneController');
	Route::resource('zones', 'ZoneController');

	Route::controller('reports', 'ReportController');
	Route::resource('reports', 'ReportController');
});