<?php

class JobController extends BaseController {

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
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if($job = Job::find($id)) {
			// Delete associated readings
			$this->clear($id);

			// If we have actuator_jobs (actuator condition events) using this job, we need to delete
			$actuator_jobs = ActuatorJob::whereJobId($id)->get();
			if($actuator_jobs->count()) {
				foreach($actuator_jobs as $actuator_job) {
					$actuatorController = new ActuatorController;
					$actuatorController->deleteJob($actuator_job->id);
				}
			}
			
			$job->delete(); 
			return Response::json(array('job_id' => $id, 'success' => 'Job '.$job->id.' deleted!')); 
		} else {
			return Response::json(array('job_id' => $id, 'error' => 'Job '.$id.' could not be found')); 
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
			if($job->sensor->title=="Roaming Spot") { // Have to delete Roaming Job job data differently 
				$readings = DB::table($job->sensor->table)->where('spot_id', '=', $job->object->spot->id)->delete();
			} else {
				$readings = DB::table($job->sensor->table)->where('job_id', '=', $job->id)->delete();
			}
			return Response::json(array('job_id' => $id, 'success' => 'Job '.$job->id.' readings deleted!')); 
		} else {
			return Response::json(array('job_id' => $id, 'error' => 'Job '.$id.' could not be found')); 
		}
	}

	/**
	 * Toggles the specified job's tracking status
	 * @param  int $id job id
	 * @return Response
	 */
	public function toggle_tracking($id) 
	{
		if($job = Job::find($id)) {
			$status = $job->tracking; 
			
			if($status) {
				$job->tracking = false;
			} else {
				$job->tracking = true; 
			}

			$job->save(); 

			return Response::json(array('job_id' => $id, 'success' => 'Job '.$job->id.' tracking status: '.$job->tracking, 'status' => $job->tracking)); 
		} else {
			return Response::json(array('job_id' => $id, 'error' => 'Job '.$id.' could not be found')); 
		}
	}


}
