<?php 
class Job extends Eloquent {
    protected $table = 'Job';

    /**
	 * Get this object's sensor
	 */
	public function sensor()
	{
		return $this->belongsTo('Sensor');
	}

	/**
	 * Get this jobs's object
	 */
	public function object()
	{
		return $this->hasOne('Object');
	}

	public function getReadings($threshold, $table, $field)
	{
		return $this->hasMany($table)->where($field, '>', $threshold)->get();
	}

}