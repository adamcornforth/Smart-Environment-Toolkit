<?php

require_once 'vendor/phpunit/phpunit/PHPUnit/Framework/Assert/Functions.php';
use Way\Tests\Assert;
use Way\Tests\Should;

class ZoneControllerTest extends TestCase {

	public function __construct() 
	{
	}

	public function setUp() 
	{
		parent::setUp();
		Auth::loginUsingId(1); 
	}

	public function testAddZoneNoFields()
	{
		// With
		$input = ['zone_title' => ''];

		// When
		$this->call("POST", "/zones/configure/addZone", $input);

		$this->assertRedirectedToRoute('zones.configure');
		$this->assertSessionHas('error', 'Sorry, we could not create your zone. Did you fill in all the fields?'); 
	}

	public function testAddZoneTitleNoSpotSelected()
	{
		// With
		$input = ['zone_title' => 'Kitchen'];

		// When
		$this->call("POST", "/zones/configure/addZone", $input);

		$this->assertRedirectedToRoute('zones.configure');
		$this->assertSessionHas('error', 'Sorry, we could not create your zone. Did you fill in all the fields?'); 
	}

	public function testAddZoneValidFields()
	{
		// With
		$input = ['zone_title' => 'Kitchen', 'spot_id' => 6, 'track_heat' => 'Yes', 'track_light' => 'Yes'];

		// When
		$this->call("POST", "/zones/configure/addZone", $input);

		$this->assertRedirectedToRoute('zones.configure');
		$this->assertSessionHas('message', 'Zone successfully added!'); 
	}

	public function testDeleteZone()
	{
		// When
		$this->call("GET", "/objects/11/unlink");

		$this->assertRedirectedToRoute('zones.configure');
		$this->assertSessionHas('message', 'Zone successfully unlinked.'); 
	}

	public function testDeleteZoneNotExists()
	{
		// When
		$this->call("GET", "/objects/11/unlink");

		$this->assertRedirectedToRoute('zones.configure');
		$this->assertSessionHas('error', 'Sorry, the zone you tried to delete does not exist.'); 
	}

	public function testAddObjectValidFields()
	{
		// With
		$input = ['object_title' => 'Test', 'spot_id' => 6, 'zone_id' => 2];

		// When
		$this->call("POST", "/zones/configure/addObject", $input);

		$this->assertRedirectedToRoute('zones.configure');
		$this->assertSessionHas('message', 'Object successfully added!'); 
	}

	public function testAddObjectNoFields()
	{
		// With
		$input = ['object_title' => ''];

		// When
		$this->call("POST", "/zones/configure/addObject", $input);

		$this->assertRedirectedToRoute('zones.configure');
		$this->assertSessionHas('error', 'Sorry, we could not create your object. Did you fill in all the fields?'); 
	}

	public function testUpdateZoneValidFields()
	{
		// When
		$response = $this->call("POST", "/zones/1/updateZone", array('left' => 1.8604651162790697, 'top' => 20, 'height' => 377, 'width' => 28.68217054263566));

		// Get the response and decode it
		$jsonResponse = $response->getContent();
		$responseData = json_decode($jsonResponse);

		$this->assertEquals("success", $responseData->status);
		$this->assertEquals("Zone 1 updated!", $responseData->message);
	}

	public function testUpdateZoneInvalidZone()
	{
		// When
		$response = $this->call("POST", "/zones/4/updateZone", array('left' => 1.8604651162790697, 'top' => 20, 'height' => 377, 'width' => 28.68217054263566));

		// Get the response and decode it
		$jsonResponse = $response->getContent();
		$responseData = json_decode($jsonResponse);

		$this->assertEquals("error", $responseData->status);
		$this->assertEquals("Zone 4 not found", $responseData->error);
	}

	public function testUpdateObjectValidFields()
	{
		// When
		$response = $this->call("POST", "/zones/4/updateObject", array('left' => 0, 'top' => 231, 'height' => 58, 'width' => 40));

		// Get the response and decode it
		$jsonResponse = $response->getContent();
		$responseData = json_decode($jsonResponse);

		$this->assertEquals("success", $responseData->status);
		$this->assertEquals("ZoneObject 4 updated!", $responseData->message);
	}

	public function testUpdateObjectInvalidZone()
	{
		// When
		$response = $this->call("POST", "/zones/6/updateObject", array('left' => 0, 'top' => 231, 'height' => 58, 'width' => 84.42460317460318));

		// Get the response and decode it
		$jsonResponse = $response->getContent();
		$responseData = json_decode($jsonResponse);

		$this->assertEquals("error", $responseData->status);
		$this->assertEquals("ZoneObject 6 not found", $responseData->error);
	}
	
}
