<?php

class CupController extends \BaseController {
	public function getPercent() {
		return Response::json(array('percent' => Water::orderBy('id', 'DESC')->first()->water_percent));
	}
	public function getCupsno() {
		return Response::json(array('cups' => Water::whereWaterPercent('0')->whereBetween('created_at', array(Carbon::now()->startOfDay()->toDateTimeString(), Carbon::now()->endOfDay()->toDateTimeString()))->get()->count()));
	}
}
?>