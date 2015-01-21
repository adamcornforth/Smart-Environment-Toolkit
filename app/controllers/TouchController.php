<?php

class TouchController extends \BaseController {

	private function zone_spots() {
		$zone_spots = new \Illuminate\Database\Eloquent\Collection;
		$zone_objects = array(	'North Zone' => 1,
								'Center Zone' => 2,
								'South Zone' => 3);	
		$spots = new \Illuminate\Database\Eloquent\Collection;
		foreach (Spot::all() as $spot) {
			if(count($spot->object)) {
				if(count($spot->object->jobs->first())) {
					if(count($spot->object->jobs->first()->sensor)) {
						if($spot->object->jobs->first()->sensor->title == "Cell Tower")
							$zone_spots->add($spot); 
						else
							($spot->object->jobs->first()->sensor->title == "Roaming Spot" || $spot->object->jobs->first()->sensor->title == "Smart Cup") ? null : $spots->add($spot);

					}
				}
			}
		}
		$zone_spots = $zone_spots->sortBy(function($spot) use (&$zone_objects)
		{
		    return $zone_objects[$spot->object->title];
		});
		return array('zone_spots' => $zone_spots, 'spots' => $spots);
	}

	public function getIndex() {
		$spots = $this->zone_spots();
		return View::make('touch.index', array('zone_spots' => $spots['zone_spots'], 'spots' => $spots['spots']));
	}

	private function getZonejob_time($spot, $job) {
		$job = Job::find($job);
		$reading = $job->getReadings($job->threshold, $job->sensor->table, $job->sensor->field)->take(1); 
		if(!isset($reading->first()->created_at)) return null;
		return Carbon::parse($reading->first()->created_at)->toDateTimeString();
	}

	public function getZonejob($spot, $job) {
		$seconds = 0;
		while(true) {
			$last_ajax_call = Input::get('timestamp'); 

			$last_reading_update_time = $this->getZonejob_time($spot, $job);

			if($last_reading_update_time !== null && (!Input::has('timestamp') || $last_reading_update_time > $last_ajax_call || $seconds > 20)) {
				$response = Response::json(
					array(
						'timestamp' => $last_reading_update_time, 
						'data' => View::make('touch.tables.zonejob', array('count' => 0, 'spot' => Spot::find($spot), 'job' => Job::find($job)))->render()
					));

				return $response;
			} else {
				sleep(1);
				$seconds++;
				continue;
			}
		}
	}

	private function getZonechange_time($spot) {
		$spot = Spot::find($spot); 
		foreach (Spot::getRoamingSpots() as $roaming_spot) {
			if(count($roaming_spot->zonechanges) && count($roaming_spot->zonechanges()->orderBy('id', 'DESC')->first()->job) && $roaming_spot->zonechanges()->orderBy('id', 'DESC')->first()->job->object->title == $spot->object->title) {
				return Carbon::parse($roaming_spot->zonechanges()->orderBy('id', 'DESC')->first()->created_at)->toDateTimeString();
			}
		}
	}

	public function getZonechange($spot) {
		$seconds = 0;
		while(true) {
			$last_ajax_call = Input::get('timestamp'); 

			$last_reading_update_time = $this->getZonechange_time($spot);

			if($last_reading_update_time !== null && (!Input::has('timestamp') || $last_reading_update_time > $last_ajax_call || $seconds > 20)) {
				$response = Response::json(
					array(
						'timestamp' => $last_reading_update_time, 
						'data' => View::make('touch.tables.zonechange', array('spot' => Spot::find($spot)))->render()
					));

				return $response;
			} else {
				sleep(1);
				$seconds++;
				continue;
			}
		}
	}

	private function getZonelatest_time($spot) {
		$spot = Spot::find($spot); 
		return Carbon::parse($spot->object->getLatestReadingTime())->toDateTimeString();
	}

	public function getZonelatest($spot) {
		$seconds = 0;
		while(true) {
			$last_ajax_call = Input::get('timestamp'); 

			$last_reading_update_time = $this->getZonelatest_time($spot);
			if($last_reading_update_time !== null && (!Input::has('timestamp') || $last_reading_update_time > $last_ajax_call || $seconds > 20)) {
				$response = Response::json(
					array(
						'timestamp' => $last_reading_update_time, 
						'data' => View::make('touch.panels.zonelatest', array('spot' => Spot::find($spot)))->render()
					));

				return $response;
			} else {
				sleep(1);
				$seconds++;
				continue;
			}
		}
	}

	public function getZonelatestmin($spot) {
		$seconds = 0;
		while(true) {
			$last_ajax_call = Input::get('timestamp'); 

			$last_reading_update_time = $this->getZonelatest_time($spot);

			if($last_reading_update_time !== null && (!Input::has('timestamp') || $last_reading_update_time > $last_ajax_call || $seconds > 20)) {
				$response = Response::json(
					array(
						'timestamp' => $last_reading_update_time, 
						'data' => View::make('touch.panels.zonelatest-min', array('spot' => Spot::find($spot)))->render()
					));

				return $response;
			} else {
				sleep(1);
				$seconds++;
				continue;
			}
		}
	}
}
?>