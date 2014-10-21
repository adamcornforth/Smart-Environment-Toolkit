<?php 
class Spot extends Eloquent {
    protected $table = 'Spot';

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
	public function object()
	{
		return $this->hasOne('Object');
	}

	/**
	 * Get this spot's jobs
	 */
	public function jobs()
	{
		return $this->hasManyThrough('Job', 'Object');
	}
}