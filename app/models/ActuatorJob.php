<?php 
class ActuatorJob extends Eloquent {
    protected $table = 'actuator_job';

    public function actuator() {
    	return $this->belongsTo('Actuator');
    }

    public function job() {
    	return $this->belongsTo('Job');
    }

}