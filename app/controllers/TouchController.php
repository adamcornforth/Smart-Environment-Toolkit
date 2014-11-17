<?php

class TouchController extends \BaseController {

	private function zone_spots() {
		$spots = new \Illuminate\Database\Eloquent\Collection;
		$zone_objects = array(	'North Zone' => 1,
								'Center Zone' => 2,
								'South Zone' => 3);	
		foreach (Spot::all() as $spot) {
			if(count($spot->object)) {
				if(count($spot->object->jobs->first())) {
					if(count($spot->object->jobs->first()->sensor)) {
						if($spot->object->jobs->first()->sensor->title == "Cell Tower")
							$spots->add($spot); 
					}
				}
			}
		}
		$spots = $spots->sortBy(function($spot) use (&$zone_objects)
		{
		    return $zone_objects[$spot->object->title];
		});
		return $spots;
	}

	public function getIndex() {
		$spots = $this->zone_spots();
		return View::make('touch.index', array('zone_spots' => $spots));
	}

	public function getZonejob($spot, $job) {
		return View::make('touch.tables.zonejob', array('count' => 0, 'spot' => Spot::find($spot), 'job' => Job::find($job)));
	}

	public function getZonechange($spot) {
		return View::make('touch.tables.zonechange', array('spot' => Spot::find($spot)));
	}

	public function getZonelatest($spot) {
		return View::make('touch.panels.zonelatest', array('spot' => Spot::find($spot)));
	}
}
?>