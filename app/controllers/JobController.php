<?php

class JobController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('jobs.index', array('jobs' => Job::all()));
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
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if($job = Job::find($id)) {
			// Delete associated readings
			$readings = DB::table($job->sensor->table)->where('job_id', '=', $job->id)->delete();
			$job->delete(); 
			return Response::json(array('success' => 'Job '.$job->id.' deleted!')); 
		} else {
			return Response::json(array('error' => 'Job '.$id.' could not be found')); 
		}
	}

	/**
	 * Removes the specified job's readings from storage
	 * @param  int $id job id
	 * @return Response
	 */
	public function clear($id) 
	{
		if($job = Job::find($id)) {
			// Delete associated readings
			$readings = DB::table($job->sensor->table)->where('job_id', '=', $job->id)->delete();
			return Response::json(array('success' => 'Job '.$job->id.' readings deleted!')); 
		} else {
			return Response::json(array('error' => 'Job '.$id.' could not be found')); 
		}
	}


}
