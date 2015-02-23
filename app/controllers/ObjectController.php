<?php

class ObjectController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('objects.index', array('objects' => Object::all()));
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
		return Redirect::to('objects');
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return View::make('objects.edit', array('object' => Object::find($id)));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$object = Object::find($id); 

		/**
		 * Update object title if title supplied
		 */
		if(Input::has('title')) {
			$object->title = Input::get('title');
			$object->save(); 
		}

		return Redirect::to('objects/'.$object->id);
	}

	public function getUnlink($id) 
	{
		$object = Object::find($id); 

		// Unlink zoneobject from zone
		if(isset($object->zoneobject->id)) {
			$object->zoneobject->delete(); 
			return Redirect::to('/zones/configure')->with('message', 'Object successfully unlinked.');
		}

		// Unlink object from zone 
		if(isset($object->zone->id)) {
			if(Zone::all()->count() == 1)
				return Redirect::to('/zones/configure')->with('error', 'Sorry, you cannot have no zones.');

			// delete objects from zone 
			foreach($object->zone->zoneobjects as $zone_objects)
				ObjectController::getUnlink($zone_objects->object->id);

			// delete things referencing zone
			foreach(ZoneSpot::whereZoneId($object->zone->id)->get() as $zone_spot)
				$zone_spot->delete();
			$object->zone->delete(); 
			return Redirect::to('/zones/configure')->with('message', 'Zone successfully unlinked.');
		}
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


}
