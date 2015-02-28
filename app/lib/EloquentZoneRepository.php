<?php namespace Sunspot\Storage; 

use Zone;
use ZoneObject;

use Object; 
use Job; 
use Sensor;

class EloquentZoneRepository implements ZoneRepository {

	public function createZone($input) 
	{
		// New object
		if($input->has('zone_title') && $input->has('spot_id')) {
			$object = Object::create(array('title' => $input->get('zone_title'), 'spot_id' => $input->get('spot_id')));

			// Set up jobs
			$job = Job::create(array('title' => "User Entered ".$input->get('zone_title'), 'object_id' => $object->id, 'sensor_id' => Sensor::whereTitle('Cell Tower')->first()->id, 'sample_rate' => 1));

			if($input->has('track_heat')) 
				$job = Job::create(array('title' => "Zone Heat", 'object_id' => $object->id, 'sensor_id' => Sensor::whereTitle('Thermometer')->first()->id, 'sample_rate' => 1));

			if($input->has('track_light')) 
				$job = Job::create(array('title' => "Zone Light", 'object_id' => $object->id, 'sensor_id' => Sensor::whereTitle('Photosensor')->first()->id, 'sample_rate' => 1));

		// Use existing object
		} elseif ($input->has('object_id')) {
			$object = Object::find($input->get('object_id'));
		}

		// Create zone, linked to object
		if(isset($object->id)) 
			return Zone::create(array_merge(array('object_id' => $object->id), $input->all()));

		return false; 
	}


	public function createZoneObject($input) 
	{
		// New object
		if($input->has('object_title') && $input->has('spot_id'))
			$object = Object::create(array('title' => $input->get('object_title'), 'spot_id' => $input->get('spot_id')));
		
		// Use existing object
		elseif ($input->has('object_id')) 
			$object = Object::find($input->get('object_id'));

		// Get selected zone
		if($input->has('zone_id')) 
			$zone = Zone::find($input->get('zone_id'));

		// Create zone object, linked to zone and object
		if(isset($object->id) && isset($zone->id)) 
			return ZoneObject::create(array_merge(array('object_id' => $object->id, 'zone_id' => $zone->id), $input->all()));

		return false; 
	}
	
	public function updateZone($id, $input) 
	{
		if($zone = Zone::find($id)) $zone->update($input->all());
		return $zone;
	}

	public function updateZoneObject($id, $input) 
	{
		if($zone_object = ZoneObject::find($id)) $zone_object->update($input->all());
		return $zone_object;
	}

}
