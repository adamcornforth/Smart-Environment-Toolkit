<?php namespace Sunspot\Storage; 

use Zone;
use ZoneObject;

use Object; 
use Job; 
use Sensor;

class EloquentZoneRepository implements ZoneRepository {

	public function createZone($input) 
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
		if($input->has('zone_title') && $input->has('spot_id')) {
			$object = new Object;
			$object->title = $input->get('zone_title'); 
			$object->spot_id = $input->get('spot_id');
			$object->save(); 

			$job = new Job; 
			$job->title = "User Entered ".$input->get('zone_title');
			$job->object_id = $object->id; 
			$job->sensor_id = Sensor::whereTitle('Cell Tower')->first()->id;
			$job->save(); 

			if($input->has('track_heat')) {
				$job = new Job; 
				$job->title = "Zone Heat";
				$job->object_id = $object->id; 
				$job->sensor_id = Sensor::whereTitle('Thermometer')->first()->id;
				$job->sample_rate = 1;
				$job->save(); 
			}

			if($input->has('track_light')) {
				$job = new Job; 
				$job->title = "Zone Light";
				$job->object_id = $object->id; 
				$job->sensor_id = Sensor::whereTitle('Photosensor')->first()->id;
				$job->sample_rate = 1; 
				$job->save(); 
			}

		// Add existing zone
		} elseif ($input->has('object_id')) {
			$object = Object::find($input->get('object_id'));
		}

		if(isset($object->id)) {
			$zone = new Zone;
			$zone->object_id = $object->id; 
			$zone->width = 10; 
			$zone->height = 300;
			$zone->top = 20;
			$zone->left = 20; 

			$zone->save(); 
			return true;
		} else {
			return false;
		}
	}

	public function createZoneObject($input) 
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
		if($input->has('object_title') && $input->has('spot_id')) {
			$object = new Object;
			$object->title = $input->get('object_title'); 
			$object->spot_id = $input->get('spot_id');
			$object->save(); 

		// Add existing zone
		} elseif ($input->has('object_id')) {
			$object = Object::find($input->get('object_id'));
		}

		if($input->has('zone_id')) {
			$zone = Zone::find($input->get('zone_id'));
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
			return true;
		} else {
			return false;
		}
	}
	
	public function updateZone($id, $input) 
	{
		if($zone = Zone::find($id)) {
			if($input->has('width'))
				$zone->width = $input->get('width'); 

			if($input->has('height'))
				$zone->height = $input->get('height'); 

			if($input->has('left'))
				$zone->left = $input->get('left'); 

			if($input->has('top'))
				$zone->top = $input->get('top'); 

			$zone->save();
			return $zone;
		} else {
			return false;
		}
	}

	public function updateZoneObject($id, $input) 
	{
		if($zone_object = ZoneObject::find($id)) {
			if($input->has('width'))
				$zone_object->width = $input->get('width'); 

			if($input->has('height'))
				$zone_object->height = $input->get('height'); 

			if($input->has('left'))
				$zone_object->left = $input->get('left'); 

			if($input->has('top'))
				$zone_object->top = $input->get('top'); 

			$zone_object->save();
			return $zone_object;
		} else {
			return false;
		}
	}

}
