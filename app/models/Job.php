<?php 
class Job extends Eloquent {
    protected $table = 'Job';

    protected $fillable = array('title', 'object_id', 'sensor_id', 'sample_rate');

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
		return $this->belongsTo('Object');
	}

	public function getReadings($threshold, $table, $field, $limit=null)
	{
		if($threshold && !is_null($threshold) && $table && !is_null($table)) {
			return $this->hasMany($table)->orderBy('id', 'DESC')->get();
		} elseif($table && !is_null($table) && $limit != null) {
			return $this->hasMany($table)->orderBy('id', 'DESC')->take(1)->get();
		} elseif($table && !is_null($table)) {
			return $this->hasMany($table)->orderBy('id', 'DESC')->get();
		} else {
			return new \Illuminate\Database\Eloquent\Collection;
		}
	}

}