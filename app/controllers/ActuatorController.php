<?php

class ActuatorController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('actuators.index', array('actuators' => Actuator::all()));
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
		return View::make('actuators.view', array('actuator' => Actuator::find($id)));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return View::make('actuators.edit', array('actuator' => Actuator::find($id)));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$actuator = Actuator::find($id); 

		/**
		 * Assign object to this actuator if object is selected
		 */
		if(Input::has('object_id')) {
			$actuator->object_id = Input::get('object_id');
			$actuator->save(); 
		}

		/**
		 * For creating jobs for the actuator
		 */
		if(Input::has('job_title') && Input::has('job_id') && Input::has('threshold') && Input::has('direction') && $actuator->object->count()) {

			$job = new ActuatorJob(); 
			$job->title = Input::get('job_title');
			$job->threshold = Input::get('threshold');
			$job->direction = Input::get('direction');
			$job->actuator_id = $actuator->id;
			$job->job_id = Input::get('job_id');
			$job->save();
		} 

		Session::forget('notice');
		return Redirect::to('actuators/'.$actuator->id);
	}

	/**
	 * Allows changing of actuator status
	 */
	public function postSetStatus()
	{
		if(Input::has('status') && Input::has('id')) {
			$actuator = Actuator::find(Input::get('id')); 
			$actuator->is_on = Input::get('status'); 
			$actuator->save();
			return json_encode(array('actuator_id' => $actuator->id, 'status' => $actuator->is_on));
		}
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
