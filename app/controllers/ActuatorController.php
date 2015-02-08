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
		if(Input::has('job_title') && Input::has('job_id') && Input::has('threshold') && Input::has('direction')) {

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
			if(Input::get('status') == "on") $actuator->is_on = 1;
			if(Input::get('status') == "off") $actuator->is_on = 0;
			if(Input::get('status') == "auto") $actuator->is_on = null;
			$actuator->save();
			return json_encode(array('actuator_id' => $actuator->id, 'status' => $actuator->is_on, 'posted_status' => Input::get('status')));
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

	}

	/**
	 * Adds a condition, given an actuator id
	 */
	public function addCondition($actuator_id) 
	{
		$new_condition = new Condition();
		$new_condition->actuator_id = $actuator_id;

		// Check if condition for this actuator exists. If so we need to link new condition
		$condition = Condition::whereActuatorId($actuator_id)->orderBy('id', 'DESC')->first(); 
		if(isset($condition->id)) {
			$new_condition->save(); 
			$condition->next_condition = $new_condition->id; 
			$condition->save(); 
		} else {
			$new_condition->save(); 
		}

	}

	/**
	 * Remove the specific condition from storage 
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function deleteCondition($id)
	{
		if($condition = Condition::find($id)) {
			if($condition->actuator_job) {
				$this->deleteJob($condition->actuator_job); 
			}
			if($condition->second_actuator_job) {
				$this->deleteJob($condition->second_actuator_job); 
			}

			// If we have a next condition, we need to link this to the previous (if there's a previous)
			if($next_condition = Condition::find($condition->next_condition)) {
				$prev_condition = Condition::whereNextCondition($id)->first();
				if(isset($prev_condition->id)) { // Link previous condition to next
					$prev_condition->next_condition = $next_condition->id; 
					$prev_condition->save(); 
				}
			// No next condition? get previous condition and set it's next pointer and operator to null
			} elseif($prev_condition = Condition::whereNextCondition($id)->first()) {
				if(isset($prev_condition->id)) {
					$prev_condition->next_condition = null; 
					$prev_condition->next_operator = null; 
					$prev_condition->save(); 
				}
			}

			$condition->delete(); 
		}
	}

	/**
	 * Remove the specified actuator job from storage
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function deleteJob($id) 
	{
		if($actuatorJob = ActuatorJob::find($id)) {

			// Delete any Condition references 
			$condition = Condition::whereActuatorJob($id)->first(); 
			if(isset($condition->id)) {
				$condition->actuator_job = null;
				$condition->save();
			}

			$condition_second_actuator_job = Condition::whereSecondActuatorJob($id)->first(); 
			if(isset($condition_second_actuator_job->id)) {
				$condition_second_actuator_job->second_actuator_job = null;
				$condition_second_actuator_job->save();
				$condition = $condition_second_actuator_job; 
			}

			// If we've deleted actuator jobs on both condition's operands, we should remove operand
			if($condition->actuator_job == null && $condition->second_actuator_job == null) {
				$condition->boolean_operator = null; 
				$condition->save(); 
			}

			$actuatorJob->delete(); 
			return Response::json(array('success' => 'Actuator Job '.$actuatorJob->id.' deleted!')); 
		} else {
			return Response::json(array('error' => 'Actuator Job '.$id.' could not be found')); 
		}
	}


}
