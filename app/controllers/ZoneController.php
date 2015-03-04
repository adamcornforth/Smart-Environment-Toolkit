<?php

use Sunspot\Storage\ZoneRepository as ZoneRepository;

class ZoneController extends BaseController {

	public function __construct(ZoneRepository $zone_repository)
    {
        $this->zone_repository = $zone_repository;
    }

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
		if($this->zone_repository->createZone(Input::instance())) {
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
		if($this->zone_repository->createZoneObject(Input::instance())) {
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
		if(($zone_object = $this->zone_repository->updateZoneObject($id, Input::instance()))) {
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
		if(($zone = $this->zone_repository->updateZone($id, Input::instance()))) {
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
