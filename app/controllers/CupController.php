<?php

class CupController extends \BaseController {

	public function getPercent() {
		return Response::json(array('percent' => 10));
	}
}
?>