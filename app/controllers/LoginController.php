<?php

class LoginController extends BaseController {

	/**
	 * Login form
	 */
	public function getIndex() {
		return View::make('login.index'); 
	}

	public function postIndex() {
		if(Auth::attempt(array('first_name' => Input::get('first_name'), 'password' => Input::get('password')))) {
			if(Auth::check() && Auth::user()->isAdmin())
				return Redirect::to('/admin');	
			return Redirect::to('/');
		} else {
			return Redirect::to('/login')->with('error', "Sorry, we could not log you in with the details you supplied.");
		}
	}

	public function getLogout() {
		Auth::logout();
		return Redirect::to('/');
	}

}
