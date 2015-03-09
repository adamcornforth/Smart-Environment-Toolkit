<?php

class AdminController extends BaseController {

	/**
	 * Admin dashboard
	 */
	public function getIndex() {
		return View::make('admin.index'); 
	}

	public function getSpots() {
		return View::make('admin.spot.index', array('spots' => Spot::all()));
	}

}
