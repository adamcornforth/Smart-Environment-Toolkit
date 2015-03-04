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
		return $this->hasManyThrough('Object', 'Spot');
	}

}