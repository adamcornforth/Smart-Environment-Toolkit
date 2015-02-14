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

		// $this->call('Initialise');
        // $this->call('MinimalDataSeeder');
        $this->call('DataSeeder');
	}

}

class Initialise extends Seeder {

    public function run()
    {
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
        $spots['dom'][] = Spot::create(array('spot_address' => '0014.4F01.0000.7E4D', 'user_id' => 2));
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
        $spot_zone['north'] = ZoneSpot::create(array('spot_id'  => $spots['adam'][1]->id, 'zone_id' => $zones['north']->id));
        $spot_zone['center'] = ZoneSpot::create(array('spot_id'=> $spots['dom'][1]->id, 'zone_id'   => $zones['center']->id));
        $spot_zone['south'] = ZoneSpot::create(array('spot_id'  => $spots['adam'][0]->id, 'zone_id' => $zones['south']->id));

        ZoneSpot::create(array('spot_id' => $spots['dom'][0]->id, 'zone_id' => $zones['north']->id));
        ZoneSpot::create(array('spot_id' => $spots['vitali'][0]->id, 'zone_id' => $zones['north']->id));
        ZoneSpot::create(array('spot_id' => $spots['vitali'][1]->id, 'zone_id' => $zones['north']->id));

        /**
         * Create some sensors
         */
        $sensors['thermometer'] = Sensor::create(array('title' => 'Thermometer', 'table' => 'Heat', 'field' => 'heat_temperature', 
                                                        'unit' => '&deg;C', 'measures' => 'Temperature', 'decimal_points' => 2, 'port_number' => 110));

        $sensors['photosensor'] = Sensor::create(array('title' => 'Photosensor', 'table' => 'Light', 'field' => 'light_intensity', 
                                                        'unit' => '<em>&Iota;</em><sub>v</sub>','measures' => 'Light', 'decimal_points' => 0, 'port_number' => 120));

        $sensors['accelerometer'] = Sensor::create(array('title' => 'Accelerometer', 'table' => 'Acceleration', 'field' => 'acceleration', 
                                                        'unit' => 'g', 'measures' => 'Acceleration', 'decimal_points' => 2, 'port_number' => 130));

        $sensors['motion_sensor'] = Sensor::create(array('title' => 'Motion Sensor', 'table' => 'Motion', 'field' => 'motion', 
                                                        'unit' => '', 'measures' => 'Motion', 'decimal_points' => 0, 'port_number' => 140));

        $sensors['cell_tower'] = Sensor::create(array('title' => 'Cell Tower', 'table' => 'ZoneSpot', 'field' => 'zone_id', 
                                                        'unit' => '', 'measures' => 'Zone Entries', 'port_number' => 150));

        $sensors['roaming_spot'] = Sensor::create(array('title' => 'Roaming Spot', 'port_number' => 160));

        $sensors['smart_cup'] = Sensor::create(array('title' => 'Smart Cup', 'table' => 'Water', 'field' => 'water_percent',
                                                        'unit' => '%', 'measures' => 'Water Level', 'port_number' => 180));

    }

}

class MinimalDataSeeder extends Seeder {

    public function run() 
    {
        /**
         * Create some test objects
         */
        $objects['kettle'] = Object::create(array('title' => 'Kettle'));
    }
}

class DataSeeder extends Seeder {

    public function run()
    {

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

        $spots['adam'][] = Spot::create(array('spot_address' => '0014.4F01.0000.7201', 'user_id' => 1)); // Fake SPOT for kettle
        $spots['adam'][] = Spot::create(array('spot_address' => '0014.4F01.0000.7202', 'user_id' => 1)); // Fake SPOT for chair
        $spots['adam'][] = Spot::create(array('spot_address' => '0014.4F01.0000.7203', 'user_id' => 1)); // Fake SPOT for door

        $spots['dom'][] = Spot::create(array('spot_address' => '0014.4F01.0000.77A7', 'user_id' => 2)); // Dom
        $spots['dom'][] = Spot::create(array('spot_address' => '0014.4F01.0000.77C0', 'user_id' => 2));
        $spots['dom'][] = Spot::create(array('spot_address' => '0014.4F01.0000.7E4D', 'user_id' => 2));
        $spots['vitali'][] = Spot::create(array('spot_address' => '0014.4F01.0000.7A12', 'user_id' => 3)); // Vitali
        $spots['vitali'][] = Spot::create(array('spot_address' => '0014.4F01.0000.7AD7', 'user_id' => 1));

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
        $spot_zone['north'] = ZoneSpot::create(array('spot_id'  => $spots['adam'][1]->id, 'zone_id' => $zones['north']->id));
        $spot_zone['center'] = ZoneSpot::create(array('spot_id'=> $spots['dom'][1]->id, 'zone_id'   => $zones['center']->id));
        $spot_zone['south'] = ZoneSpot::create(array('spot_id'  => $spots['adam'][0]->id, 'zone_id' => $zones['south']->id));

        ZoneSpot::create(array('spot_id' => $spots['dom'][0]->id, 'zone_id' => $zones['north']->id));
        ZoneSpot::create(array('spot_id' => $spots['dom'][1]->id, 'zone_id' => $zones['north']->id));
        ZoneSpot::create(array('spot_id' => $spots['dom'][2]->id, 'zone_id' => $zones['north']->id));
        ZoneSpot::create(array('spot_id' => $spots['vitali'][0]->id, 'zone_id' => $zones['north']->id));
        ZoneSpot::create(array('spot_id' => $spots['vitali'][1]->id, 'zone_id' => $zones['north']->id));

        ZoneSpot::create(array('spot_id' => $spots['adam'][2]->id, 'zone_id' => $zones['north']->id));
        ZoneSpot::create(array('spot_id' => $spots['adam'][3]->id, 'zone_id' => $zones['north']->id));
        ZoneSpot::create(array('spot_id' => $spots['adam'][4]->id, 'zone_id' => $zones['north']->id));


        /**
         * Create some sensors
         */
        $sensors['thermometer'] = Sensor::create(array('title' => 'Thermometer', 'table' => 'Heat', 'field' => 'heat_temperature', 
                                                        'unit' => '&deg;C', 'measures' => 'Temperature', 'decimal_points' => 2, 'port_number' => 110));

        $sensors['photosensor'] = Sensor::create(array('title' => 'Photosensor', 'table' => 'Light', 'field' => 'light_intensity', 
                                                        'unit' => '<em>&Iota;</em><sub>v</sub>','measures' => 'Light', 'decimal_points' => 0, 'port_number' => 120));

        $sensors['accelerometer'] = Sensor::create(array('title' => 'Accelerometer', 'table' => 'Acceleration', 'field' => 'acceleration', 
                                                        'unit' => 'g', 'measures' => 'Acceleration', 'decimal_points' => 2, 'port_number' => 130));

        $sensors['motion_sensor'] = Sensor::create(array('title' => 'Motion Sensor', 'table' => 'Motion', 'field' => 'motion', 
                                                        'unit' => '', 'measures' => 'Motion', 'decimal_points' => 0, 'port_number' => 140));

        $sensors['compass'] = Sensor::create(array('title' => 'Compass', 'table' => 'Bearing', 'field' => 'bearing', 
                                                        'unit' => '°', 'measures' => 'Angle', 'decimal_points' => 0, 'port_number' => 200));
        
        $sensors['cell_tower'] = Sensor::create(array('title' => 'Cell Tower', 'table' => 'ZoneSpot', 'field' => 'zone_id', 
                                                        'unit' => '', 'measures' => 'Zone Entries', 'port_number' => 150));

        $sensors['roaming_spot'] = Sensor::create(array('title' => 'Roaming Spot', 'table' => 'ZoneSpot', 'field' => 'zone_id',
                                                        'unit' => '', 'measures' => 'Zone', 'port_number' => 160));

        $sensors['smart_cup'] = Sensor::create(array('title' => 'Smart Cup', 'table' => 'Water', 'field' => 'water_percent',
                                                        'unit' => '%', 'measures' => 'Water Level', 'port_number' => 180));


    	DB::table('Acceleration')->delete();
        DB::table('Light')->delete();
        DB::table('Heat')->delete();

        /**
         * Create some lab Objects
         */
        $objects['smart_cup'] = Object::create(array('title' => 'Smart Cup', 'spot_id' => $spots['vitali'][0]->id));

        $objects['kettle'] = Object::create(array('title' => 'Kettle', 'spot_id' => $spots['adam'][2]->id));        
        $objects['chair'] = Object::create(array('title' => 'Computer Chair', 'spot_id' => $spots['adam'][3]->id));
        $objects['fridge'] = Object::create(array('title' => 'Fridge', 'spot_id' => $spots['adam'][4]->id));

        $objects['north_zone'] = Object::create(array('title' => 'North Zone', 'spot_id' => $spots['adam'][1]->id));
        $objects['south_zone'] = Object::create(array('title' => 'South Zone', 'spot_id' => $spots['adam'][0]->id));
        $objects['center_zone'] = Object::create(array('title' => 'Center Zone', 'spot_id' => $spots['dom'][0]->id));
        $objects['roaming_user_1'] = Object::create(array('title' => 'Roaming User (Dom)', 'spot_id' => $spots['dom'][1]->id));
        $objects['roaming_user_2'] = Object::create(array('title' => 'Roaming User (Vitali)', 'spot_id' => $spots['vitali'][1]->id));
        $objects['roaming_user_3'] = Object::create(array('title' => 'Roaming User (Dom)', 'spot_id' => $spots['dom'][2]->id));

        /**
         * Attach some actuators
         */
        // $actuator['light'] = Actuator::create(array('actuator_address' => '10F20', 'title' => 'LED Light', 'object_id' => $objects['north_zone']->id));

        /**
         * Create some Jobs
         */
        $jobs['door_open'] = Job::create(array('title' => 'Door open', 'object_id' => $objects['fridge']->id, 'sensor_id' => $sensors['accelerometer']->id, 'threshold' => null));
        $jobs['fridge_light_on'] = Job::create(array('title' => 'Light on', 'object_id' => $objects['fridge']->id, 'sensor_id' => $sensors['photosensor']->id, 'threshold' => 10));
        $jobs['chair_moved'] = Job::create(array('title' => 'Chair moved', 'object_id' => $objects['chair']->id, 'sensor_id' => $sensors['accelerometer']->id, 'threshold' => null));
        $jobs['kettle_moved'] = Job::create(array('title' => 'Kettle moved', 'object_id' => $objects['kettle']->id, 'sensor_id' => $sensors['accelerometer']->id, 'threshold' => null));
        $jobs['kettle_boiled'] = Job::create(array('title' => 'Kettle boiled', 'object_id' => $objects['kettle']->id, 'sensor_id' => $sensors['thermometer']->id, 'threshold' => 40));

        $jobs['cup_drank_from'] = Job::create(array('title' => 'Cup drank to', 'object_id' => $objects['smart_cup']->id, 'sensor_id' => $sensors['smart_cup']->id, 'threshold' => null));
        
        $jobs['center_table_range'] = Job::create(array('title' => 'User Entered Center Zone', 'object_id' => $objects['center_zone']->id, 'sensor_id' => $sensors['cell_tower']->id, 'threshold' => null));
        $jobs['center_table_temperature'] = Job::create(array('title' => 'Zone Temperature', 'object_id' => $objects['center_zone']->id, 'sensor_id' => $sensors['thermometer']->id, 'threshold' => null, 'sample_rate' => 10));
        $jobs['center_table_light'] = Job::create(array('title' => 'Zone Light', 'object_id' => $objects['center_zone']->id, 'sensor_id' => $sensors['photosensor']->id, 'threshold' => null, 'sample_rate' => 2));

        $jobs['north_zone_range'] = Job::create(array('title' => 'User Entered North Zone', 'object_id' => $objects['north_zone']->id, 'sensor_id' => $sensors['cell_tower']->id, 'threshold' => null));
        $jobs['north_zone_temperature'] = Job::create(array('title' => 'Zone Temperature', 'object_id' => $objects['north_zone']->id, 'sensor_id' => $sensors['thermometer']->id, 'threshold' => null, 'sample_rate' => 10));
        $jobs['north_zone_light'] = Job::create(array('title' => 'Zone Light', 'object_id' => $objects['north_zone']->id, 'sensor_id' => $sensors['photosensor']->id, 'threshold' => null, 'sample_rate' => 2));

        $jobs['south_zone_range'] = Job::create(array('title' => 'User Entered South Zone', 'object_id' => $objects['south_zone']->id, 'sensor_id' => $sensors['cell_tower']->id, 'threshold' => null));
        $jobs['south_zone_temperature'] = Job::create(array('title' => 'Zone Temperature', 'object_id' => $objects['south_zone']->id, 'sensor_id' => $sensors['thermometer']->id, 'threshold' => null, 'sample_rate' => 10));
        $jobs['south_zone_light'] = Job::create(array('title' => 'Zone Light', 'object_id' => $objects['south_zone']->id, 'sensor_id' => $sensors['photosensor']->id, 'threshold' => null, 'sample_rate' => 2));

        $jobs['roaming_user_1'] = Job::create(array('title' => 'Roaming User 1 (Dom)', 'object_id' => $objects['roaming_user_1']->id, 'sensor_id' => $sensors['roaming_spot']->id, 'threshold' => null));
        $jobs['roaming_user_2'] = Job::create(array('title' => 'Roaming User 2 (Vitali)', 'object_id' => $objects['roaming_user_2']->id, 'sensor_id' => $sensors['roaming_spot']->id, 'threshold' => null));
        $jobs['roaming_user_3'] = Job::create(array('title' => 'Roaming User 3 (Dom)', 'object_id' => $objects['roaming_user_3']->id, 'sensor_id' => $sensors['roaming_spot']->id, 'threshold' => null));

        /**
         * Assign actuator jobs
         */
        // ActuatorJob::create(array('title' => 'Turn LED Light On', 'actuator_id' => $actuator['light']->id, 'job_id' => $jobs['north_zone_light']->id, 'direction' => 'BELOW', 'threshold' => 40));

        /**
         * Assign zones to pillars
         */
        ZoneObject::create(array('object_id' => $objects['north_zone']->id, 'zone_id' => $zones['north']->id, 'job_id' => $jobs['north_zone_range']->id));
        ZoneObject::create(array('object_id' => $objects['north_zone']->id, 'zone_id' => $zones['center']->id, 'job_id' => $jobs['north_zone_range']->id));
        ZoneObject::create(array('object_id' => $objects['south_zone']->id, 'zone_id' => $zones['center']->id, 'job_id' => $jobs['south_zone_range']->id));
        ZoneObject::create(array('object_id' => $objects['south_zone']->id, 'zone_id' => $zones['south']->id, 'job_id' => $jobs['south_zone_range']->id));

        /**
         * Create Actuator 
         */
        Actuator::create(array('actuator_address' => 'RELAYLO1-10FBC.relay1', 'auto_start_time' => Carbon::now()->startOfDay()->addHours(9)->toTimeString(), 'auto_end_time' => Carbon::now()->startOfDay()->addHours(17)->toTimeString()));

        ActuatorJob::create(array('title' => "Light Off", 'actuator_id' => 1, 'job_id' => $jobs['north_zone_light']->id, 'direction' => 'BELOW', 'threshold' => 30)); 
        ActuatorJob::create(array('title' => "Light Off", 'actuator_id' => 1, 'job_id' => $jobs['south_zone_light']->id, 'direction' => 'BELOW', 'threshold' => 30)); 
        // ActuatorJob::create(array('title' => "Light Off", 'actuator_id' => 1, 'job_id' => $jobs['center_table_light']->id, 'direction' => 'BELOW', 'threshold' => 30)); 
        // ActuatorJob::create(array('title' => "Room Warm", 'actuator_id' => 1, 'job_id' => $jobs['center_table_temperature']->id, 'direction' => 'ABOVE', 'threshold' => 20)); 
        // ActuatorJob::create(array('title' => "Light On", 'actuator_id' => 1, 'job_id' => $jobs['south_zone_light']->id, 'direction' => 'ABOVE', 'threshold' => 30)); 
        ActuatorJob::create(array('title' => "Entered Zone", 'actuator_id' => 1, 'job_id' => $jobs['roaming_user_3']->id, 'direction' => 'EQUALS', 'threshold' => 1)); 

        /**
         * Create some boolean conditions
         */
        Condition::create(array('actuator_id' => 1, 'actuator_job' => 1, 'boolean_operator' => 'or', 'second_actuator_job' => 2, 'next_condition' => 2, 'next_operator' => 'and'));
        Condition::create(array('actuator_id' => 1, 'actuator_job' => 3, 'boolean_operator' => null, 'second_actuator_job' => null, 'next_condition' => null, 'next_operator' => null));
        // Condition::create(array('actuator_id' => 1, 'actuator_job' => 5, 'boolean_operator' => null, 'second_actuator_job' => null, 'next_condition' => null, 'next_operator' => null));

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
        $user_zone[2]['zone'] = 1; 
        $user_zone[2]['spot'] = $objects['roaming_user_3']->spot->id;

        $cup_percent = 100;

        while($carbon->lt($now)) {
    		$random_float = rand(0, 10) / 10;

            $water_drank = (mt_rand(0,80) == 1) ? 1 : 0;
            $kettle_boiled = (mt_rand(0,300) == 1) ? 50-mt_rand(1,5)+$random_float : 28-mt_rand(1,5)+$random_float;
            $zone_temp = (mt_rand(0,300) == 1) ? 35-mt_rand(1,5)+$random_float : 28-mt_rand(1,5)+$random_float;
            $fridge_light_on = (mt_rand(0,300) == 1) ? 50-mt_rand(1,5)+$random_float : 0;
            $zone_light = (mt_rand(0,300) == 1) ? 120-mt_rand(1,5)+$random_float : 70-mt_rand(1,15)+$random_float;
            $chair_moved = (mt_rand(0,300) == 1) ? 1.2+$random_float : $random_float-0.7;
            $kettle_moved = (mt_rand(0,300) == 1) ? 1.2+$random_float : $random_float-0.7;
            $door_open = (mt_rand(0,300) == 1) ? 1.2+$random_float : $random_float-0.7;

            foreach($user_zone as $key => $user) {
                $north_pillar = (mt_rand(0,300) == 1) ? -7+mt_rand(1,5)+$random_float : -30-mt_rand(1,5)+$random_float;
                $south_pillar = (mt_rand(0,300) == 1) ? -7+mt_rand(1,5)+$random_float : -30-mt_rand(1,5)+$random_float;

                /**
                 * Moving past north pillar
                 */
                if($north_pillar > -7 && $user_zone[$key]['zone'] != 1 && $user_zone[$key]['zone'] == 2) {
                    $user_zone[$key]['zone'] = 1; 
                    $zonechange = ZoneSpot::create(array('spot_id' => $user_zone[$key]['spot'], 'zone_id' => $user_zone[$key]['zone'], 'job_id' => $jobs['north_zone_range']->id, 'created_at' => $carbon->toDateTimeString()));
                    echo "Zonechange\n";
                } elseif($north_pillar > -7 && $user_zone[$key]['zone'] != 2 && ($user_zone[$key]['zone'] == 1 || $user_zone[$key]['zone'] == 3)) {
                    $user_zone[$key]['zone'] = 2; 
                    $zonechange = ZoneSpot::create(array('spot_id' => $user_zone[$key]['spot'], 'zone_id' => $user_zone[$key]['zone'], 'job_id' => $jobs['center_table_range']->id, 'created_at' => $carbon->toDateTimeString()));
                    echo "Zonechange\n";
                }

                /**
                 * Moving past south pillar
                 */
                if($south_pillar > -7 && $user_zone[$key]['zone'] != 2 && ($user_zone[$key]['zone'] == 1 || $user_zone[$key]['zone'] == 3)) {
                    $user_zone[$key]['zone'] = 2; 
                    $zonechange = ZoneSpot::create(array('spot_id' => $user_zone[$key]['spot'], 'zone_id' => $user_zone[$key]['zone'], 'job_id' => $jobs['center_table_range']->id, 'created_at' => $carbon->toDateTimeString()));
                    echo "Zonechange\n";
                } elseif($south_pillar > -7 && $user_zone[$key]['zone'] != 3 && $user_zone[$key]['zone'] == 2) {
                    $user_zone[$key]['zone'] = 3;
                    $zonechange = ZoneSpot::create(array('spot_id' => $user_zone[$key]['spot'], 'zone_id' => $user_zone[$key]['zone'], 'job_id' => $jobs['south_zone_range']->id, 'created_at' => $carbon->toDateTimeString()));
                    echo "Zonechange\n";
                }
            }

            /**
             * Insert water level data
             */
            if($water_drank > 0) {
                echo "Water drank to $cup_percent%\n";
                Water::create(array('water_percent' => $cup_percent, 'zone_id' => 1, 'job_id' => $jobs['cup_drank_from']->id, 'spot_address'  => $spots['dom'][1]->spot_address, 'created_at'    => $carbon->toDateTimeString()));
                $cup_percent -= 10;
                if($cup_percent < 0) $cup_percent = 100;
            }

         if($kettle_boiled > 40) Heat::create(array('heat_temperature' => $kettle_boiled, 'zone_id' => 1, 'job_id' => $jobs['kettle_boiled']->id,'spot_address' => $spots['adam'][2]->spot_address, 'created_at'    => $carbon->toDateTimeString()));
         if($fridge_light_on > 40) Light::create(array('light_intensity' => $fridge_light_on, 'zone_id' => 1, 'job_id' => $jobs['fridge_light_on']->id,'spot_address'  => $spots['adam'][1]->spot_address, 'created_at'    => $carbon->toDateTimeString()));
         if($chair_moved > 1.2) Acceleration::create(array('acceleration' => $chair_moved, 'zone_id' => 1, 'job_id' => $jobs['chair_moved']->id,'spot_address' => $spots['adam'][3]->spot_address, 'created_at'    => $carbon->toDateTimeString()));
         if($door_open > 1.2) Acceleration::create(array('acceleration' => $door_open, 'zone_id' => 1, 'job_id' => $jobs['door_open']->id,'spot_address' => $spots['adam'][4]->spot_address, 'created_at'    => $carbon->toDateTimeString()));
         if($kettle_moved > 1.2) Acceleration::create(array('acceleration' => $kettle_moved, 'zone_id' => 1, 'job_id' => $jobs['kettle_moved']->id,'spot_address' => $spots['adam'][2]->spot_address, 'created_at'    => $carbon->toDateTimeString()));

            Heat::create(array('heat_temperature' => $zone_temp, 'zone_id' => 1, 'job_id' => $jobs['north_zone_temperature']->id, 'spot_address'  => $spots['adam'][1]->spot_address, 'created_at'    => $carbon->toDateTimeString()));
            Heat::create(array('heat_temperature' => $zone_temp, 'zone_id' => 1, 'job_id' => $jobs['center_table_temperature']->id, 'spot_address'  => $spots['dom'][0]->spot_address, 'created_at'    => $carbon->toDateTimeString()));
            Heat::create(array('heat_temperature' => $zone_temp, 'zone_id' => 1, 'job_id' => $jobs['south_zone_temperature']->id, 'spot_address'  => $spots['adam'][0]->spot_address, 'created_at'    => $carbon->toDateTimeString()));


            Light::create(array('light_intensity' => $zone_light, 'zone_id' => 1, 'job_id' => $jobs['north_zone_light']->id, 'spot_address'  => $spots['adam'][1]->spot_address, 'created_at'    => $carbon->toDateTimeString()));
            Light::create(array('light_intensity' => $zone_light, 'zone_id' => 1, 'job_id' => $jobs['center_table_light']->id, 'spot_address'  => $spots['dom'][0]->spot_address, 'created_at'    => $carbon->toDateTimeString()));
            Light::create(array('light_intensity' => $zone_light, 'zone_id' => 1, 'job_id' => $jobs['south_zone_light']->id, 'spot_address'  => $spots['adam'][0]->spot_address, 'created_at'    => $carbon->toDateTimeString()));

            $carbon->addMinutes(5);
        }
    }

}