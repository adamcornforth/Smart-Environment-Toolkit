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
		$spot->user_id = (Input::has('user_id')) ? Input::get('user_id') : null;
		$spot->save(); 

		if(Input::has('object_title')) {
			$object = new Object(); 
			$object->title = Input::get('object_title');
			$object->spot_id = $spot->id;
			$object->save();
		}

		if(Input::has('job_title') && Input::has('object_title') && Input::has('sensor_id')) {
			$job = new Job(); 
			$job->title = Input::get('job_title');
			$job->threshold = NULL;
			$job->object_id = $object->id;
			$job->sensor_id = Input::get('sensor_id');
			$job->save();
		}

		Session::forget('notice');

		return View::make('spots.view', array('spot' => $spot));
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
