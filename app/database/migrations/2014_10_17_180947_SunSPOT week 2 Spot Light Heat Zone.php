<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SunSPOTWeek2SpotLightHeatZone extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cron_manager', function($table) {
                    $table->increments('id');
                    $table->dateTime('rundate');
                    $table->float('runtime');
                });

		Schema::create('cron_job', function($table) {
                    $table->increments('id');
                    $table->string('name');
                    $table->text('return');
                    $table->float('runtime');
                    $table->integer('cron_manager_id');
                });

		Schema::create('Users', function($table)
		{
			$table->increments('id');
		    $table->string('first_name'); 
		    $table->string('last_name'); 
		    $table->string('email');
		    $table->string('password');
		    $table->boolean('admin')->default(false); 
		    $table->rememberToken();
		    $table->timestamps();
		});

		Schema::create('Basestation', function($table)
		{
			$table->increments('id');
		    $table->string('basestation_address')->unique();
		    $table->integer('user_id')->unsigned()->nullable();
		    $table->timestamps();

		    $table->foreign('user_id')->references('id')->on('Users');
		});

		Schema::create('Spot', function($table)
		{
			$table->increments('id');
		    $table->string('spot_address')->unique();
		    $table->integer('user_id')->unsigned()->nullable();
		    $table->integer('basestation_id')->unsigned()->nullable();
		    $table->integer('battery_percent')->nullable();
		    $table->timestamps();

		    $table->foreign('user_id')->references('id')->on('Users');
		    $table->foreign('basestation_id')->references('id')->on('Basestation');
		});

		Schema::create('Sensor', function($table)
		{
		    $table->increments('id');
		    $table->string('title');
		    $table->string('table')->nullable();
		    $table->string('field')->nullable();
		    $table->string('unit')->nullable();
		    $table->string('measures')->nullable();
		    $table->integer('decimal_points')->nullable();
		    $table->string('description');
		    $table->integer('port_number');
		    $table->timestamps();
		});

		Schema::create('Object', function($table)
		{
		    $table->increments('id');
		    $table->string('title');
		    $table->string('description'); 
		    $table->integer('basestation_id')->unsigned();
		    $table->integer('spot_id')->unsigned()->nullable();

			$table->foreign('basestation_id')->references('id')->on('Basestation');
			$table->foreign('spot_id')->references('id')->on('Spot');
		    $table->timestamps();
		});

		Schema::create('Actuator', function($table)
		{
			$table->increments('id');
		    $table->string('actuator_address')->unique();
		    $table->integer('basestation_id')->unsigned()->nullable();
		    $table->string('triggers')->nullable();
		    $table->string('triggered_by')->nullable();
		    $table->time('auto_start_time')->nullable();
		    $table->time('auto_end_time')->nullable();
		    $table->integer('is_on')->nullable();
		    
			$table->foreign('basestation_id')->references('id')->on('Basestation');
		    $table->timestamps();
		});

		Schema::create('Job', function($table)
		{
		    $table->increments('id');
		    $table->string('title');
		    $table->string('description');
		    $table->enum('direction', array('ABOVE', 'BELOW'))->nullable();
		    $table->float('threshold')->nullable();
		    $table->integer('sample_rate')->nullable();

		    $table->boolean('tracking')->default(true)->nullable();
		    
		    $table->integer('object_id')->unsigned();
		    $table->integer('sensor_id')->unsigned();

		    $table->foreign('object_id')->references('id')->on('Object');
		    $table->foreign('sensor_id')->references('id')->on('Sensor');
		    $table->timestamps();
		});

		Schema::create('actuator_job', function($table)
		{
			$table->increments('id');
			$table->string('title');
		    $table->integer('actuator_id')->unsigned();
		    $table->integer('job_id')->unsigned();
		    $table->enum('direction', array('ABOVE', 'BELOW', 'EQUALS'));
		    $table->float('threshold'); 
		    $table->boolean('status')->default(false); 
		    $table->integer('seconds')->default(0);

		    $table->foreign('actuator_id')->references('id')->on('Actuator');
		    $table->foreign('job_id')->references('id')->on('Job');
		    $table->timestamps();
		});

		Schema::create('Zone', function($table)
		{
		    $table->increments('id');
		    $table->integer('object_id')->unsigned();
		    $table->integer('width')->default(40); 
		    $table->integer('height')->default(100); 
		    $table->integer('top')->default(0); 
		    $table->integer('left')->default(0); 
		    
		    $table->foreign('object_id')->references('id')->on('Object');
		    $table->timestamps();
		});

		Schema::create('ZoneSpot', function($table)
		{
		    $table->increments('id');
		    $table->integer('zone_id')->unsigned();
		    $table->integer('spot_id')->unsigned();
		    $table->integer('job_id')->unsigned()->nullable();

		    $table->foreign('spot_id')->references('id')->on('Spot');
		    $table->foreign('zone_id')->references('id')->on('Zone');
		    $table->foreign('job_id')->references('id')->on('Job');
		    $table->timestamps();
		});

		Schema::create('ZoneObject', function($table)
		{
		    $table->increments('id');
		    $table->integer('zone_id')->unsigned();
		    $table->integer('object_id')->unsigned();
		    $table->integer('width')->default(40); 
		    $table->integer('height')->default(100); 
		    $table->integer('top')->default(0); 
		    $table->integer('left')->default(0); 

		    $table->foreign('object_id')->references('id')->on('Object');
		    $table->foreign('zone_id')->references('id')->on('Zone');
		    $table->timestamps();
		});

		Schema::create('Switch', function($table)
		{
		    $table->increments('id');
		    $table->string('switch_id');
		    $table->string('spot_address');
		    $table->integer('job_id')->unsigned()->nullable();
		    $table->integer('zone_id')->unsigned()->default(0);
		    $table->timestamp('created_at');

		    $table->foreign('job_id')->references('id')->on('Job');
		    $table->foreign('zone_id')->references('id')->on('Zone');
		});

		Schema::create('Light', function($table)
		{
		    $table->increments('id');
		    $table->integer('light_intensity');
		    $table->string('spot_address');
		    $table->integer('job_id')->unsigned()->nullable();
		    $table->integer('zone_id')->unsigned()->default(0);
		    $table->timestamp('created_at');

		    $table->foreign('job_id')->references('id')->on('Job');
		    $table->foreign('zone_id')->references('id')->on('Zone');
		});

		Schema::create('Heat', function($table)
		{
		    $table->increments('id');
		    $table->float('heat_temperature');
		    $table->string('spot_address');
		    $table->integer('job_id')->unsigned()->nullable();
		    $table->integer('zone_id')->unsigned()->default(0);
		    $table->timestamp('created_at'); 

		    $table->foreign('job_id')->references('id')->on('Job');
		    $table->foreign('zone_id')->references('id')->on('Zone');
		});

		Schema::create('Acceleration', function($table)
		{
		    $table->increments('id');
		    $table->float('acceleration');
		    $table->string('spot_address');
		    $table->integer('job_id')->unsigned()->nullable();
		    $table->integer('zone_id')->unsigned()->default(0);
		    $table->timestamp('created_at'); 

		    $table->foreign('job_id')->references('id')->on('Job');
		    $table->foreign('zone_id')->references('id')->on('Zone');
		});

		Schema::create('Motion', function($table)
		{
		    $table->increments('id');
		    $table->integer('motion');
		    $table->string('spot_address');
		    $table->integer('job_id')->unsigned()->nullable();
		    $table->integer('zone_id')->unsigned()->default(0);
		    $table->timestamp('created_at'); 

		    $table->foreign('job_id')->references('id')->on('Job');
		    $table->foreign('zone_id')->references('id')->on('Zone');
		});

		Schema::create('Water', function($table)
		{
		    $table->increments('id');
		    $table->integer('water_percent');
		    $table->string('spot_address');
		    $table->integer('job_id')->unsigned()->nullable();
		    $table->integer('zone_id')->unsigned()->default(0);
		    $table->timestamp('created_at'); 

		    $table->foreign('job_id')->references('id')->on('Job');
		    $table->foreign('zone_id')->references('id')->on('Zone');
		});

		Schema::create('Bearing', function($table)
		{
		    $table->increments('id');
		    $table->float('bearing');
		    $table->string('spot_address');
		    $table->integer('job_id')->unsigned()->nullable();
		    $table->integer('zone_id')->unsigned()->default(0);
		    $table->timestamp('created_at'); 

		    $table->foreign('job_id')->references('id')->on('Job');
		    $table->foreign('zone_id')->references('id')->on('Zone');
		});

		Schema::create('Impact', function($table)
		{
		    $table->increments('id');
		    $table->integer('impact');
		    $table->string('spot_address');
		    $table->integer('job_id')->unsigned()->nullable();
		    $table->integer('zone_id')->unsigned()->default(0);
		    $table->timestamp('created_at'); 

		    $table->foreign('job_id')->references('id')->on('Job');
		    $table->foreign('zone_id')->references('id')->on('Zone');
		});

		Schema::create('Tweet', function($table)
		{
		    $table->increments('id');
		    $table->bigInteger('tweet_id');
		    $table->string('tweet'); 
		    $table->string('from');
		    $table->string('from_url')->nullable();

		    $table->integer('seen')->default(0); 
		    $table->integer('replied')->default(0); 
		});

		Schema::create('Condition', function($table)
		{
		    $table->increments('id');

		    $table->integer('actuator_id')->unsigned()->nullable(); 

		    $table->integer('actuator_job')->unsigned()->nullable();
		    $table->string('boolean_operator')->nullable();
		    $table->integer('second_actuator_job')->unsigned()->nullable();

		    $table->integer('next_condition')->unsigned()->nullable();
		    $table->string('next_operator')->nullable();

		    $table->foreign('actuator_job')->references('id')->on('actuator_job');
		    $table->foreign('second_actuator_job')->references('id')->on('actuator_job');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Condition');  
		Schema::drop('Tweet'); 
		Schema::drop('Impact'); 
		Schema::drop('Bearing'); 
		Schema::drop('Water'); 
		Schema::drop('Motion'); 
		Schema::drop('Acceleration'); 
		Schema::drop('Heat'); 
		Schema::drop('Light'); 
		Schema::drop('Switch'); 
		Schema::drop('ZoneObject');
		Schema::drop('ZoneSpot'); 
		Schema::drop('Zone');
		Schema::drop('actuator_job');
		Schema::drop('Job'); 
		Schema::drop('Actuator'); 
		Schema::drop('Sensor');
		Schema::drop('Object');
		Schema::drop('Spot');    
		Schema::drop('Basestation');  
		Schema::drop('Users');  

		Schema::drop('cron_manager');  
		Schema::drop('cron_job');   
	}

}
