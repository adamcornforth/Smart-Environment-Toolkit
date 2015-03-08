<?php

class SpotController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('spots.index', array('spots' => Spot::all()));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('spots.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if(Input::has('spot_address')) {
			if($spot = Spot::whereBasestationId(null)->where('spot_address', 'LIKE', '%'.Input::get('spot_address').'%')->first()) {
				$spot->basestation_id = Auth::user()->basestation->id;
				$spot->save(); 
				return Redirect::to('spots')->with('success', "SPOT ".$spot->spot_address." successfully added!");
			} else {
				return Redirect::to('spots/create')->with('error', "Sorry, a SPOT with the address <strong>0014.4F01.0000.".Input::get('spot_address')."</strong> could not be found.");	
			}
		} else {
			return Redirect::to('spots/create')->with('error', "Sorry, you must specify a SPOT address.");
		}
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if($spot = Spot::find($id))
			return View::make('spots.view', array('spot' => $spot));
		else
			return App::abort(404);
	}

	/**
	 * Display a job on this spot
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getJob($spot_id, $job_id)
	{
		if(($object = Object::whereSpotId($spot_id)->first())) {
			$object_id = $object->id;
			if(Job::whereId($job_id)->whereObjectId($object_id)->exists())
				return View::make('spots.job', array('spot' => Spot::find($spot_id), 'job' => Job::find($job_id)));
			else 
				return App::abort(404);
		}
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return View::make('spots.edit', array('spot' => Spot::find($id)));
	}

	/**
	 * Makes this spot stop tracking
	 */
	public function getStopTracking($id) 
	{
		$object = Object::whereSpotId($id)->first(); 
		$object->spot_id = null;
		$object->save();

		if(isset($object->zone->id)) 
			$object->zone->delete(); 

		if(isset($object->zoneobject->id)) {
			$object->zoneobject->delete(); 
		}

		return Redirect::to('spots/'.$id.'/edit');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$spot = Spot::find($id); 

		/**
		 * Assign user to this SPOT if user is selected
		 */
		if(Input::has('user_id')) {
			$spot->user_id = Input::get('user_id');
			$spot->save(); 

			if(!ZoneSpot::whereSpotId($spot->id)->exists()) {
				$zonespot = new ZoneSpot(); 
				$zonespot->zone_id = 1; 
				$zonespot->spot_id = $spot->id; 
				$zonespot->save(); 
			}
		}

		/**
		 * Create new object and assign this new object to this SPOT if new
		 * object is created during SPOT configuration
		 */
		if(Input::has('object_title')) {
			$object = new Object(); 
			$object->title = Input::get('object_title');
			$object->basestation_id = Auth::user()->basestation->id; 
			$object->spot_id = $spot->id;
			$object->save();
		
		/**
		 * Assign object to this SPOT if object selected from dropdown
		 */
		} elseif (Input::has('object_id')) {
			$object = Object::find(Input::get('object_id'));
			$object->spot_id = $spot->id; 
			$object->save();
		}

		/**
		 * For creating jobs for the SPOT
		 */
		if(Input::has('job_title') && Input::has('sensor_id') && count($spot->object)) {
			$job = new Job(); 
			$job->title = Input::get('job_title');
			$job->direction = (Input::has('direction')) ? Input::get('direction') : NULL;
			$job->threshold = (Input::has('threshold')) ? Input::get('threshold') : NULL;
			$job->sample_rate = (Input::has('sample_rate')) ? Input::get('sample_rate') : NULL;
			$job->object_id = $spot->object->id;
			$job->sensor_id = Input::get('sensor_id');
			$job->save();

			
			// If job == Cell tower 
			if($sensor = Sensor::find(Input::get('sensor_id')) && isset($sensor->id) && $sensor->title == "Cell Tower") {
				// Get/create zone 
				if(isset($spot->object->zone)) {
					$zone = $spot->object->zone;
				} else {
					$zone = Zone::create(array('left' => 0, 'top' => 0, 'width' => 10, 'height' => 10, 'object_id' => $spot->object->id));
				}
				
				// Spot needs a ZoneSpot record 
				ZoneSpot::create(array("zone_id" => $zone->id, "spot_id" => $spot->object->spot->id));
			}

		}

		Session::forget('notice');
		return Redirect::to('spots/'.$spot->id);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
