<?php

class AdminController extends BaseController {

	/**
	 * Login form
	 */
	public function getIndex() {
		return View::make('admin.index'); 
	}

}
