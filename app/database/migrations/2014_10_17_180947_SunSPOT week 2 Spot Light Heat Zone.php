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
		Schema::table('Spot', function($table)
		{
		    $table->string('spot_address');
		    $table->timestamps();
		});

		Schema::table('Zone', function($table)
		{
		    $table->increments('id');
		    $table->string('title');
		    $table->timestamps();
		});

		Schema::table('Spot_Zone', function($table)
		{
		    $table->increments('id');
		    $table->string('spot_address');
		    $table->integer('zone_id')->unsigned();

		    $table->foreign('spot_address')->references('spot_address')->on('Zone');
		    $table->foreign('zone_id')->references('id')->on('Zone');
		    $table->timestamps();
		});

		Schema::table('Light', function($table)
		{
		    $table->increments('id');
		    $table->integer('light_intensity');
		    $table->integer('zone_id')->unsigned();
		    $table->timestamp('created_at');

		    $table->foreign('zone_id')->references('id')->on('Zone');
		});

		Schema::table('Heat', function($table)
		{
		    $table->increments('id');
		    $table->float('heat_temperature');
		    $table->integer('zone_id')->unsigned();
		    $table->timestamp('created_at');

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
		//
	}

}
