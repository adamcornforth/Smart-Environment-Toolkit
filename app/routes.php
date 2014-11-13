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
	Route::get('/', function()
	{
		return View::make('hello');
	});

	Route::get('touch', function() {
		$spots = new \Illuminate\Database\Eloquent\Collection;
		$zone_objects = array(	'North Zone' => 1,
								'Center Zone' => 2,
								'South Zone' => 3);	
		foreach (Spot::all() as $spot) {
			if(count($spot->object)) {
				if(count($spot->object->jobs->first())) {
					if(count($spot->object->jobs->first()->sensor)) {
						if($spot->object->jobs->first()->sensor->title == "Cell Tower")
							$spots->add($spot); 
					}
				}
			}
		}
		$spots = $spots->sortBy(function($spot) use (&$zone_objects)
		{
		    return $zone_objects[$spot->object->title];
		});
		return View::make('touch', array('zone_spots' => $spots));
	});

	Route::resource('spots', 'SpotController');
	Route::resource('objects', 'ObjectController');
	Route::resource('jobs', 'JobController');

	Route::get('zones/history', 'ZoneController@history');
	Route::controller('zones', 'ZoneController');
	Route::resource('zones', 'ZoneController');
});