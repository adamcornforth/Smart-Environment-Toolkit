<?php 
class Spot extends Eloquent {
    protected $table = 'Spot';

    /**
     * Override all() method to return spots with basestation user id of logged in user.
     * If logged in user is admin, return all spots 
     * @return [type] [description]
     */
    public static function all($columns = array()) {
    	if(Auth::user()->isAdmin())
    		return parent::all(); 
    	else
    		return Basestation::whereUserId(Auth::user()->id)->first()->spots; 
    }

    /**
     * Override find() method to return spots with basestation user id of logged in user.
     * If logged in user is admin, return spot by id
     * @return [type] [description]
     */
    public static function find($id, $columns = array()) {
    	if(Auth::user()->isAdmin())
    		return parent::find($id); 

    	$spot = Spot::whereId($id)->first(); 


    	if(isset($spot->id) && $spot->basestation->user_id == Auth::user()->id)
    		return $spot; 
    	else
    		return false; 
    }

    /**
	 * Get this spot's user 
	 */
	public function user()
	{
		return $this->belongsTo('User');
	}

	 /**
	 * Get this spot's basestation 
	 */
	public function basestation()
	{
		return $this->belongsTo('Basestation');
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
	 * Returns this spot's battery level. Maps 75-85 to 0-100
	 */
	public function getBatteryPercentAttribute($value)
	{
		$start = 71;
		$end = 85; 

		if(!$value) return 0;
		if($value > $end) return 100;
		
		$percent = ($value-$start)*($end-$start); 

		if($percent > 100) return 100; 
		if($percent < 0) return 0; 
		return $percent;
	}

	/**
	 * Return this spot's online status
	 */
	public function getOnlineAttribute() 
	{
		return ((Carbon::now()->subSeconds(30)->lt(Carbon::parse($this->updated_at))) ? "<span class='spot-status spot-status-online'>Online</span>" : "<span class='spot-status spot-status-offline'>Offline</span>");
	}

	/**
	 * Returns spots that have roaming sensors in its jobs
	 */
	public static function getRoamingSpots() 
	{
		$spots = DB::table('Spot')
			->join('Basestation', 'Spot.basestation_id', '=', 'Basestation.id')
			->join('Users', 'Users.id', '=', 'Basestation.user_id')
            ->join('Object', 'Spot.id', '=', 'Object.spot_id')
            ->join('Job', 'Object.id', '=', 'Job.object_id')
            ->join('Sensor', 'Job.sensor_id', '=', 'Sensor.id')
            ->where('Sensor.title', '=', 'Roaming Spot')
            ->where('Users.id', '=', Auth::user()->id)
            ->groupBy('Spot.id')
            ->get();
        $collection = new \Illuminate\Database\Eloquent\Collection;
        foreach ($spots as $spot) {
        	$collection->add(Spot::find($spot->spot_id));
        }

        return $collection->reverse();
	}

	/**
	 * Returns spots that have cell towers in its jobs
	 */
	public static function getTowerSpots() 
	{
		$spots = DB::table('Spot')
			->join('Basestation', 'Spot.basestation_id', '=', 'Basestation.id')
			->join('Users', 'Users.id', '=', 'Basestation.user_id')
            ->join('Object', 'Spot.id', '=', 'Object.spot_id')
            ->join('Job', 'Object.id', '=', 'Job.object_id')
            ->join('Sensor', 'Job.sensor_id', '=', 'Sensor.id')
            ->where('Sensor.title', '=', 'Cell Tower')
            ->where('Users.id', '=', Auth::user()->id)
            ->groupBy('Spot.id')
            ->get();
        $collection = new \Illuminate\Database\Eloquent\Collection;
        foreach ($spots as $spot) {
        	$collection->add(Spot::find($spot->spot_id));
        }
        
        return $collection->reverse();
	}

	/**
	 * Returns spots that don't have an object assigned
	 */
	public static function getNonObjectSpots() 
	{
		$collection = new \Illuminate\Database\Eloquent\Collection;
        foreach (Spot::all() as $spot) {
        	if(!isset($spot->object->id))
        		$collection->add($spot);
        }
        
        return $collection;
	}

	/**
	 * Returns spots that have cell towers in its jobs, and that do not have a zone assigned
	 */
	public static function getTowerSpotsNoZoneNoObjectZone() 
	{
		$spots = DB::table('Spot')
			->join('Basestation', 'Spot.basestation_id', '=', 'Basestation.id')
			->join('Users', 'Users.id', '=', 'Basestation.user_id')
            ->join('Object', 'Spot.id', '=', 'Object.spot_id')
            ->join('Job', 'Object.id', '=', 'Job.object_id')
            ->join('Sensor', 'Job.sensor_id', '=', 'Sensor.id')
            ->where('Sensor.title', '=', 'Cell Tower')
            ->where('Users.id', '=', Auth::user()->id)
            ->groupBy('Spot.id')
            ->get();
        $collection = new \Illuminate\Database\Eloquent\Collection;
        foreach ($spots as $spot) {
        	if(!isset(Spot::find($spot->spot_id)->object->zone->id) && !isset(Spot::find($spot->spot_id)->object->zoneobject->id))
        		$collection->add(Spot::find($spot->spot_id));
        }
        
        return $collection->reverse();
	}

}