<?php

class CupController extends \BaseController {

	public function getPercent() {
		return Response::json(array('percent' => Water::orderBy('id', 'DESC')->first()->water_percent));
	}
}
?>