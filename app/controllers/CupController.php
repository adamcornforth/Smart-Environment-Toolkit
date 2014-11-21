<?php

class CupController extends \BaseController {

	public function getPercent() {
		$percent = Water::orderBy('id', 'DESC')->first()->water_percent;
		$percent = $percent - 10;
		Water::create(array('water_percent' => (int)$percent, 'zone_id' => 1, 'job_id' => 1, 'spot_address'  => '0014.4F01.0000.77A7', 'created_at'    => Carbon::now()->toDateTimeString()));
		if($percent < 10)
			Water::create(array('water_percent' => 100, 'zone_id' => 1, 'job_id' => 1, 'spot_address'  => '0014.4F01.0000.77A7', 'created_at'    => Carbon::now()->toDateTimeString()));

		return Response::json(array('percent' => Water::orderBy('id', 'DESC')->first()->water_percent));
	}
}
?>