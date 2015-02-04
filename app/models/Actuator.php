<?php 
class Actuator extends Eloquent {
    protected $table = 'Actuator';

    /**
	 * Get this actuator's object 
	 */
	public function object()
	{
		return $this->belongsTo('Object');
	}

	/**
	 * Get this actuator's jobs
	 */
	public function jobs()
	{
		return $this->hasMany('ActuatorJob');
	}

	/**
	 * Get this actuator's conditions
	 */
	public function conditions()
	{
		return $this->hasMany('Condition');
	}

}