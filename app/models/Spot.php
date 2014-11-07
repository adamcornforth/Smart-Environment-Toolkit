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

	/**
	 * Get this spot's zone changes
	 */
	public function zonechanges()
	{
		return $this->hasMany('ZoneSpot');
	}

	/**
	 * Returns this spot's switch events
	 */
	public function switches() 
	{
		return $this->hasMany('Switches', 'spot_address', 'spot_address');
	}

	/**
	 * Returns spots that have roaming sensors in its jobs
	 */
	public static function getRoamingSpots() 
	{
		$spots = DB::table('Spot')
            ->join('Object', 'Spot.id', '=', 'Object.spot_id')
            ->join('Job', 'Object.id', '=', 'Job.object_id')
            ->join('Sensor', 'Job.sensor_id', '=', 'Sensor.id')
            ->where('Sensor.title', '=', 'Roaming Spot')
            ->groupBy('Spot.id')
            ->get();
        $collection = new \Illuminate\Database\Eloquent\Collection;
        foreach ($spots as $spot) {
        	$collection->add(Spot::find($spot->spot_id));
        }
        
        return $collection->reverse();
	}

}