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

        $this->call('UserSeeder');
        $this->call('DataSeeder');
	}

} 

class UserSeeder extends Seeder {
    public function run() 
    {
        Eloquent::unguard();
        DB::table('Spot')->delete();
        DB::table('Zone')->delete();
        DB::table('Object')->delete();
        DB::table('Basestation')->delete();
        DB::table('Users')->delete();
        DB::table('Sensor')->delete();

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

        $basestation = Basestation::create(array('basestation_address' => 'SET'));
        $set = Object::create(array('title' => "SET", 'basestation_id' => $basestation->id));
        Zone::create(array('object_id' => $set->id, 'width' => 28.91, 'height' => 430, 'top' => 20, 'left' => 2.19));

        /**
         * Initialise users. In order:
         * Adam, Dom, Vitali, Admin
         */
        User::seederCreate(array('first_name' => 'Adam', 'last_name' => 'Cornforth', 'password' => Hash::make('password'), 'email' => 'adam@sunspot.app'));
        User::seederCreate(array('first_name' => 'Dom', 'last_name' => 'Lindsay', 'password' => Hash::make('password'), 'email' => 'dominic@sunspot.app'));
        User::seederCreate(array('first_name' => 'Vitali', 'last_name' => 'Bokov', 'password' => Hash::make('password'), 'email' => 'vitali@sunspot.app'));
        $admin = User::seederCreate(array('first_name' => 'Admin', 'last_name' => '', 'password' => Hash::make('password'), 'email' => 'administrator@sunspot.app'));
        $admin->setAdmin();
    }
}

class DataSeeder extends Seeder {

    public function run()
    {
        /**
         * Initialise Basestation
         */
        Basestation::create(array('basestation_address' => '0014.4F01.0000.7E54', 'user_id' => 1));
        Basestation::create(array('basestation_address' => '0014.4F01.0000.7E99', 'user_id' => 2));
        Basestation::create(array('basestation_address' => '0014.4F01.0000.UNSG'));

        /**
         * Initialise SPOTs. In order: 
         * 2x Adam's SPOTs
         * 2x Dom's SPOTs
         * 2x Vitali's SPOTs
         */
        $spots['adam'][0] = Spot::create(array('spot_address' => '0014.4F01.0000.7827', 'user_id' => 1, 'basestation_id' => 2, 'battery_percent' => 0)); // South
        $spots['adam'][1] = Spot::create(array('spot_address' => '0014.4F01.0000.76FF', 'user_id' => 1, 'basestation_id' => 2, 'battery_percent' => 0)); // North

        $spots['adam'][2] = Spot::create(array('spot_address' => '0014.4F01.0000.7836', 'user_id' => 1, 'basestation_id' => 2, 'battery_percent' => 0)); // Roaming
        $spots['adam'][3] = Spot::create(array('spot_address' => '0014.4F01.0000.7FBF', 'user_id' => 1, 'basestation_id' => 2, 'battery_percent' => 0)); // Lab Door

        // $spots['adam'][4] = Spot::create(array('spot_address' => '0014.4F01.0000.7A06', 'user_id' => 1, 'basestation_id' => null, 'battery_percent' => 0)); // Blank
        // $spots['adam'][5] = Spot::create(array('spot_address' => '0014.4F01.0000.78E0', 'user_id' => 1, 'basestation_id' => null, 'battery_percent' => 0)); // Blank

        $spots['dom'][0] = Spot::create(array('spot_address' => '0014.4F01.0000.77A7', 'user_id' => 2, 'basestation_id' => 2, 'battery_percent' => 0)); // Smart Cup
        $spots['dom'][1] = Spot::create(array('spot_address' => '0014.4F01.0000.77C0', 'user_id' => 2, 'basestation_id' => 3, 'battery_percent' => 0)); // Fridge Door
        $spots['dom'][2] = Spot::create(array('spot_address' => '0014.4F01.0000.7E4D', 'user_id' => 2, 'basestation_id' => 2, 'battery_percent' => 0));

        $spots['vitali'][0] = Spot::create(array('spot_address' => '0014.4F01.0000.7A12', 'user_id' => 3, 'basestation_id' => 2, 'battery_percent' => 0)); // Center
        $spots['vitali'][1] = Spot::create(array('spot_address' => '0014.4F01.0000.7AD7', 'user_id' => 1, 'basestation_id' => 2, 'battery_percent' => 0));

        /**
         * Create some sensors
         */
        $sensors['thermometer'] = Sensor::find(1);
        $sensors['photosensor'] = Sensor::find(2);
        $sensors['accelerometer'] = Sensor::find(3);
        $sensors['motion_sensor'] = Sensor::find(4);
        $sensors['compass'] = Sensor::find(5);
        $sensors['cell_tower'] = Sensor::find(6);
        $sensors['roaming_spot'] = Sensor::find(7);
        $sensors['smart_cup'] = Sensor::find(8);


    	DB::table('Acceleration')->delete();
        DB::table('Light')->delete();
        DB::table('Heat')->delete();

        /**
         * Create some lab Objects
         */
        $objects['north_zone'] = Object::create(array('title' => 'North Zone', 'spot_id' => $spots['adam'][1]->id, 'basestation_id' => 2));
        $objects['center_zone'] = Object::create(array('title' => 'Center Zone', 'spot_id' => $spots['vitali'][0]->id, 'basestation_id' => 2));
        $objects['south_zone'] = Object::create(array('title' => 'South Zone', 'spot_id' => $spots['adam'][0]->id, 'basestation_id' => 2));

        $objects['roaming_user_1'] = Object::create(array('title' => 'Roaming User', 'spot_id' => $spots['adam'][2]->id, 'basestation_id' => 2));
        $objects['lab_door'] = Object::create(array('title' => 'Lab Door', 'spot_id' => $spots['adam'][3]->id, 'basestation_id' => 2));
        $objects['smart_cup'] = Object::create(array('title' => 'Smart Cup', 'spot_id' => $spots['dom'][0]->id, 'basestation_id' => 2));
        $objects['fridge_door'] = Object::create(array('title' => 'Fridge Door', 'spot_id' => $spots['dom'][1]->id, 'basestation_id' => 2));


        /**
         * Attach some actuators
         */
        // $actuator['light'] = Actuator::create(array('actuator_address' => '10F20', 'title' => 'LED Light', 'object_id' => $objects['north_zone']->id));

        /**
         * Create some Jobs
         */
        $jobs['lab_door_open'] = Job::create(array('title' => 'Lab Door Open', 'object_id' => $objects['lab_door']->id, 'sensor_id' => $sensors['compass']->id, 'threshold' => 1));
        $jobs['fridge_light_on'] = Job::create(array('title' => 'Fridge Door Open', 'object_id' => $objects['fridge_door']->id, 'sensor_id' => $sensors['photosensor']->id, 'threshold' => 10, 'direction' => 'ABOVE'));

        $jobs['cup_drank_from'] = Job::create(array('title' => 'Cup drank to', 'object_id' => $objects['smart_cup']->id, 'sensor_id' => $sensors['smart_cup']->id, 'threshold' => null));
        

        $jobs['center_table_range'] = Job::create(array('title' => 'User Entered Center Zone', 'object_id' => $objects['center_zone']->id, 'sensor_id' => $sensors['cell_tower']->id, 'threshold' => null));
        $jobs['center_table_temperature'] = Job::create(array('title' => 'Zone Temperature', 'object_id' => $objects['center_zone']->id, 'sensor_id' => $sensors['thermometer']->id, 'threshold' => null, 'sample_rate' => 10, 'tracking' => 0));
        $jobs['center_table_light'] = Job::create(array('title' => 'Zone Light', 'object_id' => $objects['center_zone']->id, 'sensor_id' => $sensors['photosensor']->id, 'threshold' => null, 'sample_rate' => 2, 'tracking' => 0));

        $jobs['north_zone_range'] = Job::create(array('title' => 'User Entered North Zone', 'object_id' => $objects['north_zone']->id, 'sensor_id' => $sensors['cell_tower']->id, 'threshold' => null));
        $jobs['north_zone_temperature'] = Job::create(array('title' => 'Zone Temperature', 'object_id' => $objects['north_zone']->id, 'sensor_id' => $sensors['thermometer']->id, 'threshold' => null, 'sample_rate' => 10, 'tracking' => 0));
        $jobs['north_zone_light'] = Job::create(array('title' => 'Zone Light', 'object_id' => $objects['north_zone']->id, 'sensor_id' => $sensors['photosensor']->id, 'threshold' => null, 'sample_rate' => 2, 'tracking' => 0));
        $jobs['north_zone_light_off'] = Job::create(array('title' => 'North Light Off', 'object_id' => $objects['north_zone']->id, 'sensor_id' => $sensors['photosensor']->id, 'threshold' => 30, 'direction' => "BELOW", 'tracking' => 1));

        $jobs['south_zone_range'] = Job::create(array('title' => 'User Entered South Zone', 'object_id' => $objects['south_zone']->id, 'sensor_id' => $sensors['cell_tower']->id, 'threshold' => null));
        $jobs['south_zone_temperature'] = Job::create(array('title' => 'Zone Temperature', 'object_id' => $objects['south_zone']->id, 'sensor_id' => $sensors['thermometer']->id, 'threshold' => null, 'sample_rate' => 10, 'tracking' => 0));
        $jobs['south_zone_light'] = Job::create(array('title' => 'Zone Light', 'object_id' => $objects['south_zone']->id, 'sensor_id' => $sensors['photosensor']->id, 'threshold' => null, 'sample_rate' => 2, 'tracking' => 0));

        $jobs['roaming_user_1'] = Job::create(array('title' => 'Roaming User', 'object_id' => $objects['roaming_user_1']->id, 'sensor_id' => $sensors['roaming_spot']->id, 'threshold' => null));

        /**
         * Initialise Zones
         */
        $zones['north'] = Zone::create(array('object_id' => $objects['north_zone']->id, 'width' => 28.91, 'height' => 430, 'top' => 20, 'left' => 2.19));
        $zones['center'] = Zone::create(array('object_id' => $objects['center_zone']->id, 'width' => 40.42, 'height' => 320, 'top' => 20, 'left' => 31.10));
        $zones['south'] = Zone::create(array('object_id' => $objects['south_zone']->id, 'width' => 26.52, 'height' => 430, 'top' => 20, 'left' => 71.44));

        /**
         * Assign objects to Zones
         */
        $zone_object['lab_door'] = ZoneObject::create(array('object_id' => $objects['lab_door']->id, 'zone_id' => $zones['north']->id, 'width'=>25, 'height'=>60, 'top' => 30, 'left' => 5));
        $zone_object['fridge_door'] = ZoneObject::create(array('object_id' => $objects['fridge_door']->id, 'zone_id' => $zones['north']->id, 'width'=>20, 'height'=>50, 'top' => 30, 'left' => 35));
        $zone_object['smart_cup'] = ZoneObject::create(array('object_id' => $objects['smart_cup']->id, 'zone_id' => $zones['center']->id, 'width'=>20, 'height'=>60, 'top' => 30, 'left' => 5));

        /**
         * Assign spots to Zones
         */
        $spot_zone['north'] = ZoneSpot::create(array('spot_id'  => $spots['adam'][1]->id, 'zone_id' => $zones['north']->id));
        $spot_zone['center'] = ZoneSpot::create(array('spot_id'=> $spots['dom'][1]->id, 'zone_id'   => $zones['center']->id));
        $spot_zone['south'] = ZoneSpot::create(array('spot_id'  => $spots['adam'][0]->id, 'zone_id' => $zones['south']->id));

        foreach($spots as $owner) {
            foreach($owner as $spot) {
                ZoneSPot::create(array('spot_id' => $spot->id, 'zone_id' => $zones['north']->id));
            }
        }

        /**
         * Assign actuator jobs
         */
        // ActuatorJob::create(array('title' => 'Turn LED Light On', 'actuator_id' => $actuator['light']->id, 'job_id' => $jobs['north_zone_light']->id, 'direction' => 'BELOW', 'threshold' => 40));

        /**
         * Create Alarm 
         */
        Actuator::create(array('actuator_address' => 'RELAYLO1-10FBC.relay1', 'auto_start_time' => Carbon::now()->startOfDay()->addHours(9)->toTimeString(), 'auto_end_time' => Carbon::now()->startOfDay()->addHours(17)->toTimeString(), 'triggers' => 'Alarm', 'triggered_by' => 'High Energy Use or Security Alarm', 'basestation_id' => 2));

        ActuatorJob::create(array('title' => "Fridge Door Open", 'actuator_id' => 1, 'job_id' => $jobs['fridge_light_on']->id, 'direction' => 'ABOVE', 'threshold' => 20, 'seconds' => 10));  
        ActuatorJob::create(array('title' => "Lab Door Open", 'actuator_id' => 1, 'job_id' => $jobs['lab_door_open']->id, 'direction' => 'BELOW', 'threshold' => 300, 'seconds' => 0));  
        Condition::create(array('actuator_id' => 1, 'actuator_job' => 1, 'boolean_operator' => 'or', 'second_actuator_job' => 2, 'next_condition' => null, 'next_operator' => null));


        /**
         * Create Light
         */
        Actuator::create(array('actuator_address' => 'RELAYLO1-10F70.relay1', 'auto_start_time' => Carbon::now()->startOfDay()->addHours(9)->toTimeString(), 'auto_end_time' => Carbon::now()->startOfDay()->addHours(17)->toTimeString(), 'triggers' => 'LED Light', 'triggered_by' => 'Low Light', 'basestation_id' => 2));
        
         ActuatorJob::create(array('title' => "Light Off", 'actuator_id' => 2, 'job_id' => $jobs['north_zone_light_off']->id, 'direction' => 'BELOW', 'threshold' => 20, 'seconds' => 0)); 
         Condition::create(array('actuator_id' => 2, 'actuator_job' => 3, 'boolean_operator' => null, 'second_actuator_job' => null, 'next_condition' => null, 'next_operator' => null));
 
        /**
         * Add a week's worth of data
         */
        $carbon = Carbon::now()->subDays(1);
        $now = Carbon::now();

        $light_on = false;
        $user_zone[0]['zone'] = 2;
        $user_zone[0]['spot'] = $objects['roaming_user_1']->spot->id;

        $cup_percent = 100;
        echo "Seeding data";
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
                $north_pillar = (mt_rand(0,50) == 1) ? -7+mt_rand(1,5)+$random_float : -30-mt_rand(1,5)+$random_float;
                $south_pillar = (mt_rand(0,50) == 1) ? -7+mt_rand(1,5)+$random_float : -30-mt_rand(1,5)+$random_float;

                /**
                 * Moving past north pillar
                 */
                if($north_pillar > -7 && $user_zone[$key]['zone'] != 2 && $user_zone[$key]['zone'] == 3) {
                    $user_zone[$key]['zone'] = 2; 
                    $zonechange = ZoneSpot::create(array('spot_id' => $user_zone[$key]['spot'], 'zone_id' => $user_zone[$key]['zone'], 'job_id' => $jobs['north_zone_range']->id, 'created_at' => $carbon->toDateTimeString()));
                } elseif($north_pillar > -7 && $user_zone[$key]['zone'] != 2 && ($user_zone[$key]['zone'] == 2 || $user_zone[$key]['zone'] == 4)) {
                    $user_zone[$key]['zone'] = 3; 
                    $zonechange = ZoneSpot::create(array('spot_id' => $user_zone[$key]['spot'], 'zone_id' => $user_zone[$key]['zone'], 'job_id' => $jobs['center_table_range']->id, 'created_at' => $carbon->toDateTimeString()));
                }

                /**
                 * Moving past south pillar
                 */
                if($south_pillar > -7 && $user_zone[$key]['zone'] != 3 && ($user_zone[$key]['zone'] == 2 || $user_zone[$key]['zone'] == 4)) {
                    $user_zone[$key]['zone'] = 3; 
                    $zonechange = ZoneSpot::create(array('spot_id' => $user_zone[$key]['spot'], 'zone_id' => $user_zone[$key]['zone'], 'job_id' => $jobs['center_table_range']->id, 'created_at' => $carbon->toDateTimeString()));
                } elseif($south_pillar > -7 && $user_zone[$key]['zone'] != 3 && $user_zone[$key]['zone'] == 2) {
                    $user_zone[$key]['zone'] = 4;
                    $zonechange = ZoneSpot::create(array('spot_id' => $user_zone[$key]['spot'], 'zone_id' => $user_zone[$key]['zone'], 'job_id' => $jobs['south_zone_range']->id, 'created_at' => $carbon->toDateTimeString()));
                }
            }

            /**
             * Insert water level data
             */
            if($water_drank > 0) {
                Water::create(array('water_percent' => $cup_percent, 'zone_id' => 1, 'job_id' => $jobs['cup_drank_from']->id, 'spot_address'  => $spots['dom'][1]->spot_address, 'created_at'    => $carbon->toDateTimeString()));
                $cup_percent -= 10;
                if($cup_percent < 0) $cup_percent = 100;
            }

         if($fridge_light_on > 40) Light::create(array('light_intensity' => $fridge_light_on, 'zone_id' => 1, 'job_id' => $jobs['fridge_light_on']->id,'spot_address'  => $spots['adam'][1]->spot_address, 'created_at'    => $carbon->toDateTimeString()));

            Heat::create(array('heat_temperature' => $zone_temp, 'zone_id' => 1, 'job_id' => $jobs['north_zone_temperature']->id, 'spot_address'  => $spots['adam'][1]->spot_address, 'created_at'    => $carbon->toDateTimeString()));
            Heat::create(array('heat_temperature' => $zone_temp, 'zone_id' => 1, 'job_id' => $jobs['center_table_temperature']->id, 'spot_address'  => $spots['dom'][0]->spot_address, 'created_at'    => $carbon->toDateTimeString()));
            Heat::create(array('heat_temperature' => $zone_temp, 'zone_id' => 1, 'job_id' => $jobs['south_zone_temperature']->id, 'spot_address'  => $spots['adam'][0]->spot_address, 'created_at'    => $carbon->toDateTimeString()));


            Light::create(array('light_intensity' => $zone_light, 'zone_id' => 1, 'job_id' => $jobs['north_zone_light']->id, 'spot_address'  => $spots['adam'][1]->spot_address, 'created_at'    => $carbon->toDateTimeString()));
            Light::create(array('light_intensity' => $zone_light, 'zone_id' => 1, 'job_id' => $jobs['center_table_light']->id, 'spot_address'  => $spots['dom'][0]->spot_address, 'created_at'    => $carbon->toDateTimeString()));
            Light::create(array('light_intensity' => $zone_light, 'zone_id' => 1, 'job_id' => $jobs['south_zone_light']->id, 'spot_address'  => $spots['adam'][0]->spot_address, 'created_at'    => $carbon->toDateTimeString()));

            $carbon->addMinutes(5);
            echo ((mt_rand(0,10) == 1)) ? "." : "";
        }
        echo " Done!\n";
    }

}