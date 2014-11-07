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
		$spot_zone['north'] = ZoneSpot::create(array('spot_id'	=> $spots['adam'][0]->id, 'zone_id'	=> $zones['north']->id));
		$spot_zone['center'] = ZoneSpot::create(array('spot_id'=> $spots['adam'][1]->id, 'zone_id'	=> $zones['center']->id));
		$spot_zone['south'] = ZoneSpot::create(array('spot_id'	=> $spots['dom'][0]->id, 'zone_id'	=> $zones['south']->id));
        ZoneSpot::create(array('spot_id' => $spots['dom'][1]->id, 'zone_id' => $zones['north']->id));
        ZoneSpot::create(array('spot_id' => $spots['vitali'][0]->id, 'zone_id' => $zones['north']->id));
        ZoneSpot::create(array('spot_id' => $spots['vitali'][1]->id, 'zone_id' => $zones['north']->id));

        /**
         * Create some lab Objects
         */
        $objects['kettle'] = Object::create(array('title' => 'Kettle', 'spot_id' => $spots['vitali'][1]->id));
        
        // $objects['chair'] = Object::create(array('title' => 'Computer Chair', 'spot_id' => $spots['dom'][0]->id));
        // $objects['fridge_light'] = Object::create(array('title' => 'Fridge Light', 'spot_id' => $spots['dom'][1]->id));

        $objects['north_pillar'] = Object::create(array('title' => 'North Pillar', 'spot_id' => $spots['adam'][1]->id));
        $objects['south_pillar'] = Object::create(array('title' => 'South Pillar', 'spot_id' => $spots['adam'][0]->id));
        $objects['center_table'] = Object::create(array('title' => 'Center Table', 'spot_id' => $spots['dom'][1]->id));
        $objects['roaming_user_1'] = Object::create(array('title' => 'Roaming User 1', 'spot_id' => $spots['dom'][0]->id));
        $objects['roaming_user_2'] = Object::create(array('title' => 'Roaming User 2', 'spot_id' => $spots['vitali'][0]->id));

        /**
         * Create some sensors
         */
        $sensors['thermometer'] = Sensor::create(array('title' => 'Thermometer', 'table' => 'Heat', 'field' => 'heat_temperature', 'port_number' => 110));
        $sensors['photosensor'] = Sensor::create(array('title' => 'Photosensor', 'table' => 'Light', 'field' => 'light_intensity', 'port_number' => 120));
        $sensors['accelerometer'] = Sensor::create(array('title' => 'Accelerometer', 'table' => 'Acceleration', 'field' => 'acceleration', 'port_number' => 130));
        $sensors['motion_sensor'] = Sensor::create(array('title' => 'Motion Sensor', 'table' => 'Motion', 'field' => 'motion', 'port_number' => 140));

        $sensors['cell_tower'] = Sensor::create(array('title' => 'Cell Tower', 'table' => 'ZoneSpot', 'field' => 'zone_id', 'port_number' => 150));
        $sensors['roaming_spot'] = Sensor::create(array('title' => 'Roaming Spot', 'port_number' => 160));

        /**
         * Create some Jobs
         */
        // $jobs['door_open'] = Job::create(array('title' => 'Door open', 'object_id' => $objects['fridge']->id, 'sensor_id' => $sensors['accelerometer']->id, 'threshold' => null));
        // $jobs['chair_moved'] = Job::create(array('title' => 'Chair moved', 'object_id' => $objects['chair']->id, 'sensor_id' => $sensors['accelerometer']->id, 'threshold' => null));
        // 
        $jobs['kettle_boiled'] = Job::create(array('title' => 'Kettle boiled', 'object_id' => $objects['kettle']->id, 'sensor_id' => $sensors['thermometer']->id, 'threshold' => 40));
        
        $jobs['center_table_range'] = Job::create(array('title' => 'User Approached Center Table', 'object_id' => $objects['center_table']->id, 'sensor_id' => $sensors['cell_tower']->id, 'threshold' => -1));

        $jobs['north_pillar_range'] = Job::create(array('title' => 'User Passed North Pillar', 'object_id' => $objects['north_pillar']->id, 'sensor_id' => $sensors['cell_tower']->id, 'threshold' => 1));
        $jobs['south_pillar_range'] = Job::create(array('title' => 'User Passed South Pillar', 'object_id' => $objects['south_pillar']->id, 'sensor_id' => $sensors['cell_tower']->id, 'threshold' => 1));

        $jobs['roaming_user_1'] = Job::create(array('title' => 'Roaming User 1', 'object_id' => $objects['roaming_user_1']->id, 'sensor_id' => $sensors['roaming_spot']->id, 'threshold' => null));
        $jobs['roaming_user_2'] = Job::create(array('title' => 'Roaming User 2', 'object_id' => $objects['roaming_user_2']->id, 'sensor_id' => $sensors['roaming_spot']->id, 'threshold' => null));

        /**
         * Assign zones to pillars
         */
        ZoneObject::create(array('object_id' => $objects['north_pillar']->id, 'zone_id' => $zones['north']->id, 'job_id' => $jobs['north_pillar_range']->id));
        ZoneObject::create(array('object_id' => $objects['north_pillar']->id, 'zone_id' => $zones['center']->id, 'job_id' => $jobs['north_pillar_range']->id));
        ZoneObject::create(array('object_id' => $objects['south_pillar']->id, 'zone_id' => $zones['center']->id, 'job_id' => $jobs['south_pillar_range']->id));
        ZoneObject::create(array('object_id' => $objects['south_pillar']->id, 'zone_id' => $zones['south']->id, 'job_id' => $jobs['south_pillar_range']->id));

        /**
         * Add a week's worth of data
         */
        $carbon = Carbon::now()->subDays(7);
        $now = Carbon::now();

        $light_on = false;
        $user_zone[0]['zone'] = 1;
        $user_zone[0]['spot'] = $objects['roaming_user_1']->spot->id;
        $user_zone[1]['zone'] = 1; 
        $user_zone[1]['spot'] = $objects['roaming_user_2']->spot->id;

        while($carbon->lt($now)) {
    		$random_float = rand(0, 10) / 10;

            $door_open = (mt_rand(0,300) == 1) ? 1.2+$random_float : $random_float-0.7;
            $kettle_boiled = (mt_rand(0,300) == 1) ? 50-mt_rand(1,5)+$random_float : 28-mt_rand(1,5)+$random_float;
            $fridge_light_on = (mt_rand(0,300) == 1) ? 120-mt_rand(1,5)+$random_float : 0;
            $chair_moved = (mt_rand(0,300) == 1) ? 1.2+$random_float : $random_float-0.7;

            foreach($user_zone as $key => $user) {
                $north_pillar = (mt_rand(0,300) == 1) ? -7+mt_rand(1,5)+$random_float : -30-mt_rand(1,5)+$random_float;
                $south_pillar = (mt_rand(0,300) == 1) ? -7+mt_rand(1,5)+$random_float : -30-mt_rand(1,5)+$random_float;

                /**
                 * Moving past north pillar
                 */
                if($north_pillar > -7 && $user_zone[$key]['zone'] != 1 && $user_zone[$key]['zone'] == 2) {
                    $user_zone[$key]['zone'] = 1; 
                    ZoneSpot::create(array('spot_id' => $user_zone[$key]['spot'], 'zone_id' => $user_zone[$key]['zone'], 'job_id' => $jobs['north_pillar_range']->id, 'created_at' => $carbon->toDateTimeString()));
                } elseif($north_pillar > -7 && $user_zone[$key]['zone'] != 2 && ($user_zone[$key]['zone'] == 1 || $user_zone[$key]['zone'] == 3)) {
                    $user_zone[$key]['zone'] = 2; 
                    ZoneSpot::create(array('spot_id' => $user_zone[$key]['spot'], 'zone_id' => $user_zone[$key]['zone'], 'job_id' => $jobs['north_pillar_range']->id, 'created_at' => $carbon->toDateTimeString()));
                }

                /**
                 * Moving past south pillar
                 */
                if($south_pillar > -7 && $user_zone[$key]['zone'] != 2 && ($user_zone[$key]['zone'] == 1 || $user_zone[$key]['zone'] == 3)) {
                    $user_zone[$key]['zone'] = 2; 
                    ZoneSpot::create(array('spot_id' => $user_zone[$key]['spot'], 'zone_id' => $user_zone[$key]['zone'], 'job_id' => $jobs['south_pillar_range']->id, 'created_at' => $carbon->toDateTimeString()));
                } elseif($south_pillar > -7 && $user_zone[$key]['zone'] != 3 && $user_zone[$key]['zone'] == 2) {
                    $user_zone[$key]['zone'] = 3;
                    ZoneSpot::create(array('spot_id' => $user_zone[$key]['spot'], 'zone_id' => $user_zone[$key]['zone'], 'job_id' => $jobs['south_pillar_range']->id, 'created_at' => $carbon->toDateTimeString()));
                }
            }

            // if($door_open > 1.2) Acceleration::create(array('acceleration' => $door_open, 'zone_id' => 1, 'job_id' => $jobs['door_open']->id,'spot_address' => $spots['adam'][0]->spot_address, 'created_at'    => $carbon->toDateTimeString()));
        	// if($kettle_boiled > 40) Heat::create(array('heat_temperature' => $kettle_boiled, 'zone_id' => 1, 'job_id' => $jobs['kettle_boiled']->id,'spot_address'	=> $spots['adam'][1]->spot_address, 'created_at'	=> $carbon->toDateTimeString()));
            // if($fridge_light_on > 40) Light::create(array('light_intensity' => $fridge_light_on, 'zone_id' => 1, 'job_id' => $jobs['fridge_light_on']->id,'spot_address'  => $spots['adam'][1]->spot_address, 'created_at'    => $carbon->toDateTimeString()));
            // if($chair_moved > 1.2) Acceleration::create(array('acceleration' => $chair_moved, 'zone_id' => 1, 'job_id' => $jobs['chair_moved']->id,'spot_address' => $spots['dom'][0]->spot_address, 'created_at'    => $carbon->toDateTimeString()));


            $carbon->addMinutes(1);
        }
    }

}