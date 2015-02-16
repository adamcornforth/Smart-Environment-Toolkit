<?php

class APIController extends \BaseController {

	public function getSensorLatestReading($id, $sensor_name, $limit=null) {
		$jobs = Job::whereObjectId($id)->get();
		foreach ($jobs as $job) {
			if($job->sensor->title == $sensor_name) {
				foreach($job->getReadings($job->threshold, $job->sensor->table, $job->sensor->field, $limit)->take(1) as $reading) {
					return number_format($reading[$job->sensor->field]);
				}
			}
		}
	}

	private function convertObjectIdToZoneId($object_id) {
		switch ($object_id)
		{
			case 5:
				return 1; // North Zone
				break;
			case 6:
				return 3; // Centre Zone
				break;
			case 7:
				return 2; // South Zone
				break;
		}
	}

	public function spots() {
		$zone_spots = new \Illuminate\Database\Eloquent\Collection;
		$roaming_spots = new \Illuminate\Database\Eloquent\Collection;
		foreach (Spot::all() as $spot) {

			if(count($spot->object)) {
				if(count($spot->object->jobs->first())) {
					if(count($spot->object->jobs->first()->sensor)) {
						if($spot->object->jobs->first()->sensor->title == "Cell Tower")
						{
							$spot_modified = new stdClass();
							$spot_modified->id = $spot->id;
							$spot_modified->address = $spot->spot_address;
							$spot_modified->title = $spot->object->title;
							// $spot_modified->is_online = ;
							$spot_modified->heat_level = $this->getSensorLatestReading($spot->object->id, "Thermometer", 1);
							$spot_modified->light_level = $this->getSensorLatestReading($spot->object->id, "Photosensor", 1);
							$spot_modified->battery_level = $spot->battery_percent;
							$spot_modified->zone_id = $this->convertObjectIdToZoneId($spot->object->id);

							$zone_spots->add($spot_modified);
						}
						else if ($spot->object->jobs->first()->sensor->title == "Roaming Spot")
						{
							$spot_modified = new stdClass();
							$spot_modified->id = $spot->id;
							$spot_modified->address = $spot->spot_address;
							$spot_modified->title = $spot->object->title;
							$spot_modified->user = $spot->user->first_name . " " . $spot->user->last_name;
							// $spot_modified->is_online = ;
							$spot_modified->heat_level = $this->getSensorLatestReading($spot->object->id, "Thermometer", 1);
							$spot_modified->light_level = $this->getSensorLatestReading($spot->object->id, "Photosensor", 1);
							$spot_modified->battery_level = $spot->battery_percent;
							$zone_spot = ZoneSpot::orderBy('created_at', 'DESC')->where('job_id', '!=', 'NULL')->where('spot_id', '=', $spot->id)->first();
							$spot_modified->zone_id = $zone_spot->zone_id;
							$spot_modified->date_of_entering_zone = Carbon::parse($spot->zonechanges()->orderBy('id', 'DESC')->first()->created_at)->format('G:ia jS M');

							$roaming_spots->add($spot_modified);
						}
					}
				}
			}
		}
		return array('zone_spots' => $zone_spots, 'roaming_spots' => $roaming_spots);
	}

		public function nonzone_spots() {
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

		return array('spots' => $spots);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
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
		//
	}


}
