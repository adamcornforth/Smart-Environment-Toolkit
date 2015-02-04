<?php 
class Condition extends Eloquent {
	public $timestamps = false;

    protected $table = 'Condition';

    public function actuator()
    {
        return $this->belongsTo('Actuator');
    }

    public function first()
    {
        return $this->belongsTo('ActuatorJob', 'actuator_job');
    }

    public function second()
    {
        return $this->belongsTo('ActuatorJob', 'second_actuator_job');
    }

    public function next_condition()
    {
        return $this->belongsTo('Condition', 'next_condition');
    }
}