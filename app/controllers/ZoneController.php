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
	 * Show zone configure page
	 */
	public function getZoneConfigure() 
	{
		return View::make('zones.configure');
	}

	/**
	 * Add zone
	 */
	public function postAddZone() 
	{
		/**
		 * If new zone...
		 * 	Create new object + link object to selected spot. Add cell tower to zone
		 * else 
		 * 	do nothing
		 *
		 * Then link object to new Zone 
		 * set default Zone width/height/left/top 
		 */
		// New zone
		if(Input::has('zone_title') && Input::has('spot_id')) {
			$object = new Object;
			$object->title = Input::get('zone_title'); 
			$object->spot_id = Input::get('spot_id');
			$object->save(); 

			$job = new Job; 
			$job->title = "User Entered ".Input::get('zone_title');
			$job->object_id = $object->id; 
			$job->sensor_id = Sensor::whereTitle('Cell Tower')->first()->id;
			$job->save(); 

			if(Input::has('track_heat')) {
				$job = new Job; 
				$job->title = "Zone Heat";
				$job->object_id = $object->id; 
				$job->sensor_id = Sensor::whereTitle('Thermometer')->first()->id;
				$job->sample_rate = 1;
				$job->save(); 
			}

			if(Input::has('track_light')) {
				$job = new Job; 
				$job->title = "Zone Light";
				$job->object_id = $object->id; 
				$job->sensor_id = Sensor::whereTitle('Photosensor')->first()->id;
				$job->sample_rate = 1; 
				$job->save(); 
			}

		// Add existing zone
		} elseif (Input::has('object_id')) {
			$object = Object::find(Input::get('object_id'));
		}

		if(isset($object->id)) {
			$zone = new Zone;
			$zone->object_id = $object->id; 
			$zone->width = 10; 
			$zone->height = 300;
			$zone->top = 20;
			$zone->left = 20; 

			$zone->save(); 
			return Redirect::to('/zones/configure')->with('message', 'Zone successfully added!');
		} else {
			return Redirect::to('/zones/configure')->with('error', 'Sorry, we could not create your zone. Did you fill in all the fields?');
		}
	}
	
	/**
	 * Add object
	 */
	public function postAddObject() 
	{
		/**
		 * If new object...
		 * 	Create new object + link object to selected spot. 
		 * 	Assign object to zone
		 * else 
		 * 	do nothing
		 *
		 * Then link object to new ZoneObject
		 * set default ZoneObject width/height/left/top 
		 */
		// New zone
		if(Input::has('object_title') && Input::has('spot_id')) {
			$object = new Object;
			$object->title = Input::get('object_title'); 
			$object->spot_id = Input::get('spot_id');
			$object->save(); 

		// Add existing zone
		} elseif (Input::has('object_id')) {
			$object = Object::find(Input::get('object_id'));
		}

		if(Input::has('zone_id')) {
			$zone = Zone::find(Input::get('zone_id'));
		}

		if(isset($object->id) && isset($zone->id)) {
			$zone_object = new ZoneObject;
			$zone_object->object_id = $object->id; 
			$zone_object->zone_id = $zone->id;
			$zone_object->width = 30; 
			$zone_object->height = 100;
			$zone_object->top = 20;
			$zone_object->left = 20; 

			$zone_object->save(); 
			return Redirect::to('/zones/configure')->with('message', 'Object successfully added!');
		} else {
			return Redirect::to('/zones/configure')->with('error', 'Sorry, we could not create your object. Did you fill in all the fields?');
		}
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
