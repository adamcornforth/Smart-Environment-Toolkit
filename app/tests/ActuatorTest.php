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

	public function tearDown() 
	{
		Mockery::close();
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

}
