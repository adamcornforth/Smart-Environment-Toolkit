<?php

use Carbon\Carbon;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('LightHeatSeeder');
	}

}

class LightHeatSeeder extends Seeder {

    public function run()
    {
    	DB::table('Acceleration')->delete();
        DB::table('Light')->delete();
        DB::table('Heat')->delete();

        DB::table('zone_spot')->delete();

        DB::table('Spot')->delete();
        DB::table('Zone')->delete();
        DB::table('Users')->delete();

        /**
         * Initialise users. In order:
         * Adam, Dom, Vitali
         */
        User::create(array('first_name' => 'Adam', 'last_name' => 'Cornforth', 'password' => Hash::make('password'), 'email' => 'adam@sunspot.app'));
        User::create(array('first_name' => 'Dominic', 'last_name' => 'Lindsay', 'password' => Hash::make('password'), 'email' => 'dominic@sunspot.app'));
        User::create(array('first_name' => 'Vitali', 'last_name' => 'Bokov', 'password' => Hash::make('password'), 'email' => 'vitali@sunspot.app'));

        /**
         * Initialise SPOTs. In order: 
         * 2x Adam's SPOTs
         * 2x Dom's SPOTs
         * 2x Vitali's SPOTs
         */
        $spots['adam'][] = Spot::create(array('spot_address' => '0014.4F01.0000.7827', 'user_id' => 1)); // Adam
        $spots['adam'][] = Spot::create(array('spot_address' => '0014.4F01.0000.76FF', 'user_id' => 1));
        $spots['dom'][] = Spot::create(array('spot_address' => '0014.4F01.0000.77A7', 'user_id' => 2)); // Dom
        $spots['dom'][] = Spot::create(array('spot_address' => '0014.4F01.0000.77C0', 'user_id' => 2));
        $spots['vitali'][] = Spot::create(array('spot_address' => '0014.4F01.0000.7A12', 'user_id' => 3)); // Vitali
        $spots['vitali'][] = Spot::create(array('spot_address' => '0014.4F01.0000.7AD7', 'user_id' => 3));

        /**
         * Initialise Zones
         */
        $zones['north'] = Zone::create(array('title' => 'North End of Lab'));
        $zones['center'] = Zone::create(array('title' => 'Presentation and Touch Table Area'));
		$zones['south'] = Zone::create(array('title' => 'South End of Lab'));
        $zones['lab'] = Zone::create(array('title' => 'Lab'));

		/**
		 * Assign spots to Zones
		 */
		$spot_zone['north'] = ZoneSpot::create(array('spot_address'	=> '0014.4F01.0000.7827', 'zone_id'	=> $zones['north']->id));
		$spot_zone['center'] = ZoneSpot::create(array('spot_address'=> '0014.4F01.0000.76FF', 'zone_id'	=> $zones['center']->id));
		$spot_zone['south'] = ZoneSpot::create(array('spot_address'	=> '0014.4F01.0000.77A7', 'zone_id'	=> $zones['south']->id));
        ZoneSpot::create(array('spot_address' => $spots['dom'][1]->spot_address, 'zone_id' => $zones['lab']->id));
        ZoneSpot::create(array('spot_address' => $spots['vitali'][0]->spot_address, 'zone_id' => $zones['lab']->id));
        ZoneSpot::create(array('spot_address' => $spots['vitali'][1]->spot_address, 'zone_id' => $zones['lab']->id));

        /**
         * Create some lab Objects
         */
        $objects['fridge'] = Object::create(array('title' => 'Fridge Door', 'spot_id' => $spots['adam'][0]->id));
        $objects['kettle'] = Object::create(array('title' => 'Kettle', 'spot_id' => $spots['adam'][1]->id));
        $objects['chair'] = Object::create(array('title' => 'Computer Chair', 'spot_id' => $spots['dom'][0]->id));

        /**
         * Create some sensors
         */
        $sensors['thermometer'] = Sensor::create(array('title' => 'Thermometer', 'table' => 'Heat', 'field' => 'heat_temperature', 'port_number' => 110));
        $sensors['photosensor'] = Sensor::create(array('title' => 'Photosensor', 'table' => 'Light', 'field' => 'light_intensity', 'port_number' => 120));
        $sensors['accelerometer'] = Sensor::create(array('title' => 'Accelerometer', 'table' => 'Acceleration', 'field' => 'acceleration', 'port_number' => 130));

        /**
         * Create some Jobs
         */
        $jobs['door_open'] = Job::create(array('title' => 'Door open', 'object_id' => $objects['fridge']->id, 'sensor_id' => $sensors['accelerometer']->id, 'threshold' => null));
        $jobs['kettle_boiled'] = Job::create(array('title' => 'Kettle boiled', 'object_id' => $objects['kettle']->id, 'sensor_id' => $sensors['thermometer']->id, 'threshold' => 30));
        $jobs['chair_moved'] = Job::create(array('title' => 'Chair moved', 'object_id' => $objects['chair']->id, 'sensor_id' => $sensors['accelerometer']->id, 'threshold' => null));
        
        /**
         * Add a week's worth of data
         */
        $carbon = Carbon::now()->startOfWeek();
        $now = Carbon::now();
        $light_on = false;
        while($carbon->lt($now)) {
                if(mt_rand(0,100) == 1) 
                    $light_on = true;
                elseif(mt_rand(0,100) == 1 && $light_on) 
                    $light_on = false; 

        		$random_float = rand(0, 10) / 10;
                $door_open = (mt_rand(0,300) == 1) ? (100 * mt_rand(1,5))+mt_rand(1,100) : 0;
                $kettle_boiled = (mt_rand(0,300) == 1) ? 40-mt_rand(1,5)+$random_float : 30-mt_rand(1,5)+$random_float;
                $chair_moved = (mt_rand(0,300) == 1) ? (100 * mt_rand(1,5))+mt_rand(1,100) : 0;
        		Acceleration::create(array('acceleration' => $door_open, 'zone_id' => 1, 'job_id' => $jobs['door_open']->id,'spot_address' => $spots['adam'][0]->spot_address, 'created_at'    => $carbon->toDateTimeString()));
            	Heat::create(array('heat_temperature' => $kettle_boiled, 'zone_id' => 1, 'job_id' => $jobs['kettle_boiled']->id,'spot_address'	=> $spots['adam'][1]->spot_address, 'created_at'	=> $carbon->toDateTimeString()));
                Acceleration::create(array('acceleration' => $chair_moved, 'zone_id' => 1, 'job_id' => $jobs['chair_moved']->id,'spot_address' => $spots['dom'][0]->spot_address, 'created_at'    => $carbon->toDateTimeString()));
            $carbon->addMinutes(1);
        }
    }

}