<?php 
class Basestation extends Eloquent {
    protected $table = 'Basestation';

    /**
	 * Get this spot's user 
	 */
	public function user()
	{
		return $this->belongsTo('User');
	}

	/**
	 * Get this spot's objects
	 */
	public function spots()
	{
		return $this->hasMany('Spot');
	}

}