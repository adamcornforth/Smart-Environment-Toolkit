<?php 
class Basestation extends Eloquent {
    protected $table = 'Basestation';

    protected $fillable = array('user_id', 'basestation_address');

    /**
	 * Get this spot's user 
	 */
	public function user()
	{
		return $this->belongsTo('User');
	}

	/**
	 * Get this basestation's spots
	 */
	public function actuators()
	{
		return $this->hasMany('Actuator');
	}

	/**
	 * Get this basestation's spots
	 */
	public function spots()
	{
		return $this->hasMany('Spot');
	}

	/**
	 * Get this basestations's objects
	 */
	public function objects()
	{
		return $this->hasMany('Object');
	}

}