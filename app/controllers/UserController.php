<?php

class UserController extends BaseController {

	public function getIndex() {
		return View::make('admin.user.index');
	}

	public function postCreateUser() {
		if($user = User::create(Input::all())) {
			return Redirect::to('admin/users')->with('message', 'User <strong>'.$user->name.'</strong> successfully created!');
		} else {
			return Redirect::to('admin/users/create')->with('error', 'Sorry, the user could not be created. Did you fill in all the fields?');
		}
	}

	public function getCreateUser() {
		return View::make('admin.user.create');	
	}

	public function getEditUser($id) {
		return View::make('admin.user.edit', array('user' => User::find($id)));	
	}

	public function getUser($id) {
		return View::make('admin.user.view', array('user' => User::find($id)));	
	} 

	public function postEditUser($id) {
		$user = User::find($id);
		if($user->update(Input::all())) {
			return Redirect::to('admin/users')->with('message', 'User <strong>'.$user->name.'</strong> successfully modified!');
		} else {
			die(var_dump(Input::all()));
		}
	}

}
