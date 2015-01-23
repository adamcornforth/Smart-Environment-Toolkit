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
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return View::make('spots.view', array('spot' => Spot::find($id)));
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
			$job->threshold = (Input::has('threshold')) ? Input::get('threshold') : NULL;
			$job->object_id = $spot->object->id;
			$job->sensor_id = Input::get('sensor_id');
			$job->save();
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
