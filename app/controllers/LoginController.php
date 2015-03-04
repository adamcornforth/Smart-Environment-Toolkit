<?php

class LoginController extends BaseController {

	/**
	 * Login form
	 */
	public function getIndex() {
		return View::make('login.index'); 
	}

	public function postIndex() {
		Auth::attempt(array('first_name' => Input::get('first_name'), 'password' => 'password'));
		return Redirect::to('/');
	}

	public function getLogout() {
		Auth::logout();
		return Redirect::to('/');
	}

}
