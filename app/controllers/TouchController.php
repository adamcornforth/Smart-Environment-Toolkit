<?php

class TouchController extends \BaseController {

	public function getIndex() {
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
		return View::make('touch', array('zone_spots' => $spots));
	}
}
?>