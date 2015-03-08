<?php

class ActuatorController extends BaseController {

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
	 * @return Response
	 */
	public function create()
	{
		return View::make('actuators.create');
	}

	/**
	 * Store the specified resource.
	 *
	 * @return Response
	 */
	public function store()
	{
		if(Input::has('actuator_address')) {
			if($actuator = Actuator::whereBasestationId(null)->where('actuator_address', 'LIKE', '%'.Input::get('actuator_address').'%')->first()) {
				$actuator->basestation_id = Auth::user()->basestation->id;
				$actuator->save(); 
				return Redirect::to('actuators')->with('success', "Actuator ID '".$actuator->actuator_address."' successfully added!");
			} else {
				return Redirect::to('actuators/create')->with('error', "Sorry, an Actuator with the ID '<strong>".Input::get('actuator_address')."</strong>' could not be found.");	
			}
		} else {
			return Redirect::to('actuators/create')->with('error', "Sorry, you must specify an Actuator ID.");
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
		if(!$actuator = Actuator::find($id)) {
			return Redirect::to('actuators')->withInput()->with('error', 'Sorry, the requested actuator could not be found.');
		} 
		/**
		 * For creating jobs for the actuator
		 */
		if(Input::has('condition-id') && Input::has('job-field')) {
			if(Input::has('job_title') && Input::has('job_id') && Input::has('threshold') && Input::has('direction')) {
				$job = new ActuatorJob(); 
				$job->title = Input::get('job_title');
				$job->threshold = Input::get('threshold');
				$job->direction = Input::get('direction');
				$job->actuator_id = $actuator->id;
				$job->job_id = Input::get('job_id');
				$job->save();

				if(Input::has('condition-id') && Input::has('job-field')) {
					$condition = Condition::find(Input::get('condition-id'));
					if(!isset($condition->id)) {
						return Redirect::to('actuators/'.$actuator->id)->withInput()->with('error', 'Sorry, the condition '.Input::get('condition-id'). 'could not be found.');
					}
					$condition[Input::get('job-field')] = $job->id;
					$condition->save();
				}
				return Redirect::to('actuators/'.$actuator->id)->with('message', 'Your event, <strong>'.$job->title.'</strong>, has been successfully created!');
			} else {
				return Redirect::to('actuators/'.$actuator->id)->with('error', 'Sorry, you must fill in all the <strong>Add Event</strong> fields to create an event.');
			}
		/**
		 * For modifying the actuator itself
		 */
		} else {
			if(!Input::has('triggers') && !Input::has('triggered_by')) {
				return Redirect::to('actuators/'.$actuator->id.'/edit')->withInput()->with('error', 'Sorry, the <strong>Actuator Name</strong> and <strong>Triggered By</strong> fields are required.');
			} 

			if(!Input::has('triggers')) {
				return Redirect::to('actuators/'.$actuator->id.'/edit')->withInput()->with('error', 'Sorry, the <strong>Actuator Name</strong> field is required.');
			}

			if(!Input::has('triggered_by')) {
				return Redirect::to('actuators/'.$actuator->id.'/edit')->withInput()->with('error', 'Sorry, the <strong>Triggered By</strong> field is required.');
			}

			$actuator->triggers = Input::get('triggers'); 
			$actuator->triggered_by = Input::get('triggered_by'); 
			$actuator->save(); 

			return Redirect::to('actuators/'.$actuator->id)->with('message', 'Your actuator, <strong>'.$actuator->triggers.'</strong>, has been successfully configured!');
		}


		Session::forget('notice');
		return Redirect::to('actuators/'.$actuator->id);
	}

	public function postActuatorTime($id)
	{
		$error = false; 
		$actuator = Actuator::find($id); 
		if(!isset($actuator->id)) $error = "Actuator could not be found";

		if(Input::has('start_hour') && Input::has('start_minute') && Input::has('start_meridiem') && Input::has('end_hour') && Input::has('end_minute') && Input::has('end_meridiem')) {
			if(is_numeric(Input::get('start_hour')) && is_numeric(Input::get('start_minute')) && is_numeric(Input::get('start_hour')) && is_numeric(Input::get('start_minute'))) {
				// Add offsets if PM
				$start_offset = (Input::get('start_meridiem') == "PM") ? 12 : 0; 
				$end_offset = (Input::get('end_meridiem') == "PM") ? 12 : 0; 

				// Get start + end values 
				$start = Carbon::now()->startOfDay()->addHours($start_offset + Input::get('start_hour'))->addMinutes(Input::get('start_minute')); 
				$end = Carbon::now()->startOfDay()->addHours($end_offset + Input::get('end_hour'))->addMinutes(Input::get('end_minute')); 

				if($start->lt($end)) {
					// Save values
					$actuator->auto_start_time = $start->toTimeString(); 
					$actuator->auto_end_time = $end->toTimeString(); 
					$actuator->save(); 
				} else {
					$error = "The start time (".$start->format('g:ia').") must be before the end time (".$end->format('g:ia').").";
				}

			} else {
				$error = "Sorry the inputted time values must be numbers.";
			}
		} else {
			$error = "Sorry, you must fill in all the time fields.";
		}

		if($error) 
			return Response::json(array('status' => 'error', 'error' => $error));
		else
			return Response::json(array('status' => 'success', 'message' => "The actuator will now only activate between ".$start->format('g:ia')." and ".$end->format('g:ia')."!"));
	}

	public function getActuatorTimes($id) 
	{
		return View::make('actuators.time', array('actuator' => Actuator::find($id))); 
	}

	public function getAjax($id) 
	{
		$seconds = 0; 
		$timestamp = 0;
		while($seconds < 10) {
			
			$last_ajax_call = Input::get('timestamp'); 
			$last_reading_update_time = $this->getConditionsTime($id);

			if($last_reading_update_time !== null && (!Input::has('timestamp') || $last_reading_update_time > $last_ajax_call)) {
				return Response::json(
					array(	'timestamp' => $last_reading_update_time, 
							'html' => $this->getConditions($id)->render()));
			}
			
			sleep(1); 
			$seconds++; 
		}

		return Response::json(array('nodata' => true, 'timestamp' => Input::get('timestamp')));
	}

	private function getConditionsTime($id) {
		foreach(ActuatorJob::whereActuatorId($id)->orderBy('updated_at', 'ASC')->get()->reverse() as $actuator_job) {
			return Carbon::parse($actuator_job->updated_at)->toDateTimeString();
		}
	}

	public function getConditions($id) 
	{
		return View::make('actuators.conditions', array('actuator' => Actuator::find($id)));
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
		if($actuator = Actuator::find($actuator_id)) {
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
			return Response::json(array('status' => 'success', 'message' => 'Condition successfully added!'));
		} else {
			return Response::json(array('status' => 'error', 'error' => 'Sorry, the condition could not be added.'));
		}

	}

	/**
	 * Sets a specified boolean value 
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function setBoolean($id)
	{
		if($condition = Condition::find($id)) {
			if(Input::has('field') && Input::has('operator')) {
				$condition[Input::get('field')] = Input::get('operator'); 
				$condition->save();
			} elseif(Input::has('field')) {
				$condition[Input::get('field')] = null; 
				$condition->save();
			}
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
			return Response::json(array('status' => 'success', 'message' => "Condition successfully created!"));
		} else {
			return Response::json(array('status' => 'error', 'error' => "Sorry, the requested condition could not be found."));
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
			return Response::json(array('status' => 'error', 'error' => 'Sorry, the requested actuator job could not be found.'));  
		}
	}


}
