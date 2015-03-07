<?php

class AdminController extends BaseController {

	/**
	 * Admin dashboard
	 */
	public function getIndex() {
		return View::make('admin.index'); 
	}

}
