<?php

class AdminController extends BaseController {

	/**
	 * Admin dashboard
	 */
	public function getIndex() {
		return View::make('admin.index'); 
	}

	public function getBasestations() {
		return View::make('admin.basestation.index');
	}

	public function getEditBasestation($id) {
		return View::make('admin.basestation.edit', array('basestation' => Basestation::find($id)));	
	}

	public function getBasestation($id) {
		return View::make('admin.basestation.view', array('basestation' => Basestation::find($id)));	
	}

	public function getUnassignBasestation($id) {
		$basestation = Basestation::find($id);
		if($basestation->update(array('user_id' => null))) {
			return Redirect::to('admin/basestations/'.$id.'/edit')->with('message', 'Basestation successfully unassigned!');
		} else {
			die(Input::all());
		}
	}

	public function postEditBasestation($id) {
		$basestation = Basestation::find($id);
		if($basestation->update(Input::all())) {
			return Redirect::to('admin/basestations')->with('message', 'Basestation successfully assigned to <strong>'.$basestation->user->name.'</strong>!');
		} else {
			die(Input::all());
		}
	}

}
