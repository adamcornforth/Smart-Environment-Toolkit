<?php

class SocketController extends \BaseController {

	public function getZonechange() {
		return View::make('sockets.sendsocket', array('socket' => 'zonechange'));
	}

	public function getHeat() {
		return View::make('sockets.sendsocket', array('socket' => 'heat'));
	}

	public function getLight() {
		return View::make('sockets.sendsocket', array('socket' => 'light'));
	}
}
?>