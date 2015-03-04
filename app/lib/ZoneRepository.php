<?php namespace Sunspot\Storage;

interface ZoneRepository {
	public function createZone($input);
	public function createZoneObject($input);

	public function updateZone($id, $input);
	public function updateZoneObject($id, $input);
}
