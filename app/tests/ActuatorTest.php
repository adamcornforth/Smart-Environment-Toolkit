<?php

require_once 'vendor/phpunit/phpunit/PHPUnit/Framework/Assert/Functions.php';
use Way\Tests\Assert;
use Way\Tests\Should;

class ActuatorTest extends TestCase {

	public function __construct() 
	{
	}

	public function setUp() 
	{
		parent::setUp();
	}

	public function testEditActuatorWithEmptyFields()
	{
		// With
		$input = ['triggers' => '', 'triggered_by' => ''];

		// When
		$this->call("PUT", "/actuators/1", $input);

		$this->assertRedirectedToRoute('actuators.edit', 1);
		$this->assertSessionHas('error', 'Sorry, the <strong>Actuator Name</strong> and <strong>Triggered By</strong> fields are required.'); 
	}

	public function testEditActuatorWithEmptyTriggersField()
	{
		// With
		$input = ['triggers' => '', 'triggered_by' => 'Security Alarm'];

		// When
		$this->call("PUT", "/actuators/1", $input);

		$this->assertRedirectedToRoute('actuators.edit', 1);
		$this->assertSessionHas('error', 'Sorry, the <strong>Actuator Name</strong> field is required.'); 
	}

	public function testEditActuatorWithEmptyTriggeredByField()
	{
		// With
		$input = ['triggers' => 'Sounder', 'triggered_by' => ''];

		// When
		$this->call("PUT", "/actuators/1", $input);

		$this->assertRedirectedToRoute('actuators.edit', 1);
		$this->assertSessionHas('error', 'Sorry, the <strong>Triggered By</strong> field is required.'); 
	}

	public function testEditActuatorWithValidFields()
	{
		// With
		$input = ['triggers' => 'Sounder', 'triggered_by' => 'Security Alarm'];

		// When
		$this->call("PUT", "/actuators/1", $input);

		$this->assertRedirectedToRoute('actuators.show', 1);
		$this->assertSessionHas('message', 'Your actuator, <strong>Sounder</strong>, has been successfully configured!'); 
	}

	public function testEditNonExistentActuatorWithValidFields()
	{
		// With
		$input = ['triggers' => 'Sounder', 'triggered_by' => 'Security Alarm'];

		// When
		$this->call("PUT", "/actuators/2", $input);

		$this->assertRedirectedToRoute('actuators.index');
		$this->assertSessionHas('error', 'Sorry, the requested actuator could not be found.'); 
	}

	public function testAddEventWithEmptyFields()
	{
		// With
		$input = ['condition-id' => 1, 'job-field' => 1];

		// When
		$this->call("PUT", "/actuators/1", $input);

		$this->assertRedirectedToRoute('actuators.show', 1);
		$this->assertSessionHas('error', 'Sorry, you must fill in all the <strong>Add Event</strong> fields to create an event.'); 
	}

	public function testAddEventWithAllFields()
	{
		// With
		$input = ['condition-id' => 2, 'job-field' => 'second_actuator_job', 'job_title' => 'Light On', 'job_id' => 9, 'direction' => 'ABOVE', 'threshold' => 30];

		// When
		$this->call("PUT", "/actuators/1", $input);

		$this->assertRedirectedToRoute('actuators.show', 1);
		$this->assertSessionHas('message', 'Your event, <strong>Light On</strong>, has been successfully created!'); 
	}

	public function testDeleteActuatorJob()
	{
		// When
		$this->call("POST", "/actuators/delete_job/4", array());

		$this->assertEquals(false, ActuatorJob::find(4)); 
		$this->assertEquals(null, Condition::find(2)->second_actuator_job);
	}

	public function testDeleteActuatorJobNotExists()
	{
		// When
		$response = $this->call("POST", "/actuators/delete_job/5", array());

		$this->assertEquals(false, ActuatorJob::find(5)); 

		// Get the response and decode it
		$jsonResponse = $response->getContent();
		$responseData = json_decode($jsonResponse);

		$this->assertEquals("error", $responseData->status);
		$this->assertEquals("Sorry, the requested actuator job could not be found.", $responseData->error);
	}

	public function testDeleteCondition()
	{
		// When
		$this->call("POST", "/actuators/delete_condition/2", array());

		$this->assertEquals(false, Condition::find(2)); 
		$this->assertEquals(null, Condition::find(1)->next_condition);
		$this->assertEquals(null, Condition::find(1)->next_operand);
	}

	public function testDeleteConditionNotExists()
	{
		// When
		$response = $this->call("POST", "/actuators/delete_condition/3", array());

		$this->assertEquals(false, Condition::find(3));

		// Get the response and decode it
		$jsonResponse = $response->getContent();
		$responseData = json_decode($jsonResponse);

		$this->assertEquals("error", $responseData->status);
		$this->assertEquals("Sorry, the requested condition could not be found.", $responseData->error);
	}

	public function testAddCondition()
	{
		// When
		$response = $this->call("POST", "/actuators/add_condition/1", array());

		// Get the response and decode it
		$jsonResponse = $response->getContent();
		$responseData = json_decode($jsonResponse);

		$this->assertEquals(3, Condition::find(3)->id); 
		$this->assertEquals(3, Condition::find(1)->next_condition);
		$this->assertEquals("success", $responseData->status);
		$this->assertEquals("Condition successfully added!", $responseData->message);

		// When
		$response = $this->call("POST", "/actuators/add_condition/1", array());

		// Get the response and decode it
		$jsonResponse = $response->getContent();
		$responseData = json_decode($jsonResponse);

		$this->assertEquals(4, Condition::find(4)->id); 
		$this->assertEquals(4, Condition::find(3)->next_condition);
		$this->assertEquals("Condition successfully added!", $responseData->message);
	}

	public function testAddConditionActuatorNotExists()
	{
		// When
		$response = $this->call("POST", "/actuators/add_condition/2", array());

		// Get the response and decode it
		$jsonResponse = $response->getContent();
		$responseData = json_decode($jsonResponse);

		$this->assertEquals("error", $responseData->status);
		$this->assertEquals("Sorry, the condition could not be added.", $responseData->error);
	}

	public function testDeleteMiddleConditionCheckLinkedList()
	{
		// When
		$this->call("POST", "/actuators/delete_condition/3", array());

		$this->assertEquals(false, Condition::find(3)); 
		$this->assertEquals(4, Condition::find(1)->next_condition);
	}

	public function testSetTimeNoFields()
	{
		// When
		$response = $this->call("POST", "/actuators/1/settime", array());

		$this->assertTrue($response->isOk());

		// Get the response and decode it
		$jsonResponse = $response->getContent();
		$responseData = json_decode($jsonResponse);

		$this->assertEquals("error", $responseData->status);
		$this->assertEquals("Sorry, you must fill in all the time fields.", $responseData->error);
	}

	public function testSetTimeWithAllFields()
	{
		// When
		$response = $this->call("POST", "/actuators/1/settime", array('start_hour' => 9, 'start_minute' => 00, 'start_meridiem' => 'AM', 'end_hour' => 7, 'end_minute' => 00, 'end_meridiem' => 'PM'));

		$this->assertTrue($response->isOk());

		// Get the response and decode it
		$jsonResponse = $response->getContent();
		$responseData = json_decode($jsonResponse);

		$this->assertEquals("success", $responseData->status);
		$this->assertEquals("The actuator will now only activate between 9:00am and 7:00pm!", $responseData->message);
	}

	public function testSetTimeWithStartTimeAfterEnd()
	{
		// When
		$response = $this->call("POST", "/actuators/1/settime", array('start_hour' => 9, 'start_minute' => 00, 'start_meridiem' => 'PM', 'end_hour' => 7, 'end_minute' => 00, 'end_meridiem' => 'PM'));

		$this->assertTrue($response->isOk());

		// Get the response and decode it
		$jsonResponse = $response->getContent();
		$responseData = json_decode($jsonResponse);

		$this->assertEquals("error", $responseData->status);
		$this->assertEquals("The start time (9:00pm) must be before the end time (7:00pm).", $responseData->error);
	}

	
}
