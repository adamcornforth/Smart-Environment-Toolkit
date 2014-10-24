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
		Schema::create('Users', function($table)
		{
			$table->increments('id');
		    $table->string('first_name'); 
		    $table->string('last_name'); 
		    $table->string('email');
		    $table->string('password');
		    $table->rememberToken();
		    $table->timestamps();
		});

		Schema::create('Spot', function($table)
		{
			$table->increments('id');
		    $table->string('spot_address')->unique();
		    $table->integer('user_id')->unsigned()->nullable();
		    $table->timestamps();

		    $table->foreign('user_id')->references('id')->on('Users');
		});

		Schema::create('Sensor', function($table)
		{
		    $table->increments('id');
		    $table->string('title');
		    $table->string('table');
		    $table->string('field');
		    $table->string('description');
		    $table->integer('port_number');
		    $table->timestamps();
		});

		Schema::create('Object', function($table)
		{
		    $table->increments('id');
		    $table->string('title');
		    $table->string('description');
		    $table->integer('spot_id')->unsigned();

			$table->foreign('spot_id')->references('id')->on('Spot');
		    $table->timestamps();
		});

		Schema::create('Job', function($table)
		{
		    $table->increments('id');
		    $table->string('title');
		    $table->string('description');
		    $table->float('threshold')->nullable();
		    $table->integer('object_id')->unsigned();
		    $table->integer('sensor_id')->unsigned();

		    $table->foreign('object_id')->references('id')->on('Object');
		    $table->foreign('sensor_id')->references('id')->on('Sensor');
		    $table->timestamps();
		});

		Schema::create('Zone', function($table)
		{
		    $table->increments('id');
		    $table->string('title');
		    $table->timestamps();
		});

		Schema::create('zone_spot', function($table)
		{
		    $table->increments('id');
		    $table->string('spot_address');
		    $table->integer('zone_id')->unsigned();

		    $table->foreign('zone_id')->references('id')->on('Zone');
		    $table->timestamps();
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
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Acceleration'); 
		Schema::drop('Heat'); 
		Schema::drop('Light'); 
		Schema::drop('zone_spot'); 
		Schema::drop('Zone');
		Schema::drop('Job'); 
		Schema::drop('Sensor');
		Schema::drop('Object');
		Schema::drop('Spot');    
		Schema::drop('Users');   
	}

}
