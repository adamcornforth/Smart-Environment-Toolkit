<?php

class TouchController extends \BaseController {

	private function zone_spots() {
		$zone_spots = new \Illuminate\Database\Eloquent\Collection;
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
		return array('zone_spots' => $zone_spots, 'spots' => $spots);
	}

	public function getIndex() {
		$spots = $this->zone_spots();
		return View::make('touch.index', array('zone_spots' => $spots['zone_spots'], 'spots' => $spots['spots']));
	}

		/**
	 * Long polling: get ajax data for this spot
	 * @return  JSON response
	 */
	public function getAjaxspot($spot_id) 
	{
		$seconds = 0; 
		$response = array(); 
		$spot = Spot::find($spot_id); 
		$timestamp = 0;

		while($seconds < 10) {
			$data = array(); 
			
			// Job data
			if($spot->jobs->count()) {
				foreach($spot->jobs as $job) {
					$data["#table_".$spot->id."_".$job->id] = $this->getZonejob($spot->id, $job->id); 
				}
			}

			/**
			 * Loop all data and check for returns
			 */
			foreach ($data as $key => $json) {
				if($json['data'] != false) {
					$response[$key] = $json['data']; 
					$timestamp = ($timestamp < $json['timestamp']) ? $json['timestamp'] : $timestamp; 
				} 
			}

			if(count($response)) {
				return Response::json(
					array(	'timestamp' => $timestamp, 
							'html' => $response));
			}
			
			sleep(1); 
			$seconds++; 

		}

		return Response::json(array('nodata' => true, 'timestamp' => Input::get('timestamp')));
	}

	public function getAjax() {
		$seconds = 0; 
		$zone_spots = $this->zone_spots(); 
		$timestamp = 0;
		while($seconds < 10) {
			$response = array(); 
			$data = array(); 
			$zonechange = null; 

			// Cups stuff
			$data["#cup_no"] = $this->getCupsNo(); 
			$data["#cup_percent"] = $this->getCupPercent(); 

			foreach($zone_spots['zone_spots'] as $spot) {
				$data["#table_".$spot->id."_zonechange"] = $this->getZonechange($spot->id); 
				$data["#panel_".$spot->id."_zonelatest"] = $this->getZonelatest($spot->id); 
				$data["#panel_".$spot->id."_zonelatest_min"] = $this->getZonelatestmin($spot->id); 
				$data["#panel_".$spot->id."_battery"] = $this->getBatteryLatest($spot->id); 

				// zonechange detected
				if($data["#table_".$spot->id."_zonechange"]['data'] != false) $zonechange = true;
			}

			/**
			 * Loop all spots checking for job data
			 */
			foreach ($zone_spots['spots'] as $spot) {
				foreach($spot->jobs as $job) {
					$data["#table_".$spot->id."_".$job->id] = $this->getZonejob($spot->id, $job->id); 
				}
				$data["#panel_".$spot->id."_battery"] = $this->getBatteryLatest($spot->id); 
			}

			/**
			 * Loop all data and check for returns
			 */
			foreach ($data as $key => $json) {
				if($json['data'] != false) {
					$response[$key] = $json['data']; 
					$timestamp = ($timestamp < $json['timestamp']) ? $json['timestamp'] : $timestamp; 
				} 
			}

			// If we have detect a zonechange, we need to update all zones 
			if($zonechange) {
				foreach($zone_spots['zone_spots'] as $spot) {
					$data = $this->getZonechange($spot->id, TRUE);
					$response["#table_".$spot->id."_zonechange"] = $data['data']; 
				}
			}

			if(count($response)) {
				return Response::json(
					array(	'timestamp' => $timestamp, 
							'html' => $response));
			}
			
			sleep(1); 
			$seconds++; 
		}


		return Response::json(array('nodata' => true, 'timestamp' => Input::get('timestamp')));
	}

	private function getZonechange_time($spot) {
		foreach(ZoneSpot::orderBy('created_at', 'ASC')->get()->reverse() as $zone_spot) {
			return Carbon::parse($zone_spot->created_at)->toDateTimeString();
		}
	}

	private function getZonechange($spot, $force=null) {
		$seconds = 0;
		$last_ajax_call = Input::get('timestamp'); 

		$last_reading_update_time = $this->getZonechange_time($spot);

		if($force == true || $last_reading_update_time !== null && (!Input::has('timestamp') || $last_reading_update_time > $last_ajax_call)) {
			return array('data' => View::make('touch.tables.zonechange', array('spot' => Spot::find($spot)))->render(), 'timestamp' => $last_reading_update_time);
		} else {
			return false; 
		}
	}

	private function cup_time() {
		if(Water::orderBy('id', 'DESC')->get()->count() && Water::orderBy('id', 'DESC')->first()->exists())
			return Carbon::parse(Water::orderBy('id', 'DESC')->first()->created_at)->toDateTimeString();
		else
			return null;
	}

	private function getCupPercent() {
		$seconds = 0;
		$last_ajax_call = Input::get('timestamp'); 

		$last_reading_update_time = $this->cup_time();

		if($last_reading_update_time !== null && (!Input::has('timestamp') || $last_reading_update_time > $last_ajax_call)) {
			return array('data' => Water::orderBy('id', 'DESC')->first()->water_percent, 'timestamp' => $last_reading_update_time);
		} else {
			return false; 
		}
	}

	private function getCupsno() {
		$seconds = 0;
		$last_ajax_call = Input::get('timestamp'); 

		$last_reading_update_time = $this->cup_time();

		if($last_reading_update_time !== null && (!Input::has('timestamp') || $last_reading_update_time > $last_ajax_call)) {
			return array('data' => "<span id='cup_no'>".Water::whereWaterPercent('0')->whereBetween('created_at', array(Carbon::now()->startOfDay()->toDateTimeString(), Carbon::now()->endOfDay()->toDateTimeString()))->get()->count()."</span>", 'timestamp' => $last_reading_update_time);
		} else {
			return false; 
		}
	}

	private function getBatteryLatest_time($spot) {
		$spot = Spot::find($spot); 
		if(!$spot->exists()) return null;
		if(Carbon::now()->between(Carbon::parse($spot->updated_at)->addMinutes(1), Carbon::parse($spot->updated_at)->addMinutes(1)->addSeconds(30)))
			return Carbon::parse($spot->updated_at)->toDateTimeString();
		else
			return Carbon::parse($spot->updated_at)->toDateTimeString();
	}

	private function getBatteryLatest($spot) {
		$seconds = 0;
		$last_ajax_call = Input::get('timestamp'); 

		$last_reading_update_time = $this->getBatteryLatest_time($spot);

		if($last_reading_update_time !== null && (!Input::has('timestamp') || $last_reading_update_time > $last_ajax_call)) {
			return array('data' => View::make('touch.panels.battery', array('percent' => Spot::find($spot)->battery_percent, 'spot' => Spot::find($spot)))->render(), 'timestamp' => $last_reading_update_time);
		} else {
			return false; 
		}
	}

	private function getZonejob_time($spot, $job) {
		$job = Job::find($job);
		if(!isset($job->id)) return null; 
		$reading = $job->getReadings($job->threshold, $job->sensor->table, $job->sensor->field)->take(1); 
		if(!isset($reading->first()->created_at)) return null;
		return Carbon::parse($reading->first()->created_at)->toDateTimeString();
	}

	private function getZonejob($spot, $job) {
		$seconds = 0;
		$last_ajax_call = Input::get('timestamp'); 

		$last_reading_update_time = $this->getZonejob_time($spot, $job);

		if($last_reading_update_time !== null && (!Input::has('timestamp') || $last_reading_update_time > $last_ajax_call)) {
			return array('data' => View::make('touch.tables.zonejob', array('count' => 0, 'spot' => Spot::find($spot), 'job' => Job::find($job)))->render(), 'timestamp' => $last_reading_update_time);
		} else {
			return false; 
		}
	}

	private function getZonelatest_time($spot) {
		$spot = Spot::find($spot); 
		$time = $spot->object->getLatestReadingTime(1); 
		if(isset($time))
			return Carbon::parse($spot->object->getLatestReadingTime(1))->toDateTimeString();
		else
			return null;
	}

	private function getZonelatest($spot) {
		$seconds = 0;
		$last_ajax_call = Input::get('timestamp'); 

		$last_reading_update_time = $this->getZonelatest_time($spot);
		if($last_reading_update_time !== null && (!Input::has('timestamp') || $last_reading_update_time > $last_ajax_call)) {
			return array('data' => View::make('touch.panels.zonelatest', array('limit' => 1, 'spot' => Spot::find($spot)))->render(), 'timestamp' => $last_reading_update_time);
		} else {
			return false; 
		}
	}

	private function getZonelatestmin($spot) {
		$seconds = 0;
		$last_ajax_call = Input::get('timestamp'); 

		$last_reading_update_time = $this->getZonelatest_time($spot);

		if($last_reading_update_time !== null && (!Input::has('timestamp') || $last_reading_update_time > $last_ajax_call)) {
			return array('data' => View::make('touch.panels.zonelatest-min', array('limit' => 1, 'spot' => Spot::find($spot)))->render(), 'timestamp' => $last_reading_update_time);
		} else {
			return false; 
		}
	}
}
?>