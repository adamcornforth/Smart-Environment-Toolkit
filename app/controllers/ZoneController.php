<?php

class ZoneController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('zones.index', array('roaming_spots' => Spot::getRoamingSpots()));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	/**
	 * Update object with given width, height, top and left values
	 * @param  int  $id
	 * @return Response
	 */
	public function postUpdateObject($id)
	{
		if($zone_object = ZoneObject::find($id)) {
			if(Input::has('width'))
				$zone_object->width = Input::get('width'); 

			if(Input::has('height'))
				$zone_object->height = Input::get('height'); 

			if(Input::has('left'))
				$zone_object->left = Input::get('left'); 

			if(Input::has('top'))
				$zone_object->top = Input::get('top'); 

			$zone_object->save();
			return Response::json(array('status' => 'success', 'message' => 'ZoneObject '.$id.' updated!', 'attr' => array('width' => $zone_object->width, 'height' => $zone_object->height, 'top' => $zone_object->top, 'left' => $zone_object->left)));
		} else {
			return Response::json(array('status' => 'error', 'error' => 'ZoneObject '.$id.' not found'));
		}
	}

	/**
	 * Update zone with given width, height, top and left values
	 * @param  int  $id
	 * @return Response
	 */
	public function postUpdateZone($id)
	{
		if($zone = Zone::find($id)) {
			if(Input::has('width'))
				$zone->width = Input::get('width'); 

			if(Input::has('height'))
				$zone->height = Input::get('height'); 

			if(Input::has('left'))
				$zone->left = Input::get('left'); 

			if(Input::has('top'))
				$zone->top = Input::get('top'); 

			$zone->save();
			return Response::json(array('status' => 'success', 'message' => 'Zone '.$id.' updated!', 'attr' => array('width' => $zone->width, 'height' => $zone->height, 'top' => $zone->top, 'left' => $zone->left)));
		} else {
			return Response::json(array('status' => 'error', 'error' => 'Zone '.$id.' not found'));
		}
	}

	/**
	 * Displays zone changes for the given spot id
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getUser($id)
	{
		return View::make('zones.user', array('spot' => Spot::find($id)));
	}

	public function getChanges()
	{
		echo ZoneSpot::orderBy('id', 'DESC')->whereNotNull('job_id')->take(10)->get();
	}

	public function getZonechange() {
		return View::make('zones.zonechange', array('zoneSpotDayHistory' => ZoneSpot::orderBy('id', 'DESC')->whereNotNull('job_id')->take(10)->get()));
	}
}
