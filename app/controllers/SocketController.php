<?php

class SocketController extends \BaseController {

	public function getZonechange() {
		Event::fire('app.zonechange', array('event' => 'zonechange'));
		return View::make('sockets.sendsocket', array('socket' => 'zonechange'));
	}

	public function getHeat() {
		Event::fire('app.heat', array('event' => 'heat'));
		return View::make('sockets.sendsocket', array('socket' => 'heat'));
	}

	public function getLight() {
		Event::fire('app.light', array('event' => 'light'));
		return View::make('sockets.sendsocket', array('socket' => 'light'));
	}
}
?>