<?php 
class Object extends Eloquent {
    protected $table = 'Object';

    protected $fillable = array('title', 'spot_id');

    /**
     * Override all() method to return objects with basestation user id of logged in user.
     * If logged in user is admin, return all objects 
     */
    public static function all($columns = array()) {
    	if(Auth::user()->isAdmin())
    		return parent::all(); 
    	else
    		return Basestation::whereUserId(Auth::user()->id)->first()->objects; 
    }

    /**
     * Override find() method to return spots with basestation user id of logged in user.
     * If logged in user is admin, return spot by id
     * @return [type] [description]
     */
    public static function find($id, $columns = array()) {
    	if(Auth::user()->isAdmin())
    		return parent::find($id); 

    	$object = Object::whereId($id)->first(); 

    	if(isset($object->id) && isset($object->basestation->id) && $object->basestation->user_id == Auth::user()->id)
    		return $object; 
    	else
    		return false; 
    }

    /**
	 * Get this object's spot 
	 */
	public function spot()
	{
		return $this->belongsTo('Spot');
	}

	/**
	 * Get this object's basestation 
	 */
	public function basestation()
	{
		return $this->belongsTo('Basestation');
	}

	/**
	 * Get this object's jobs 
	 */
	public function jobs()
	{
		return $this->hasMany('Job');
	}

	/**
	 * Get this object's zone
	 */
	public function zone() 
	{
		return $this->hasOne('Zone');
	}

	/**
	 * Get this zone's ZoneObject
	 */
	public function zoneobject()
	{
		return $this->hasOne("ZoneObject");
	}

	/**
	 * Returns spots that have cell towers in its jobs
	 */
	public static function getNoZoneObjectObjects() 
	{
		$collection = new \Illuminate\Database\Eloquent\Collection;
        foreach (Object::all() as $object) {
        	if(!isset($object->zoneobject->id) && !isset($object->zone->id))
        		$collection->add($object);
        }
        
        return $collection;
	}

	/**
	 * Get the latest reading for this object, given a sensor name
	 */
	private function getSensorLatestReading($sensor_name, $limit=null, $created_at=null) {
		$jobs = Job::whereObjectId($this->id)->get(); 
		foreach ($jobs as $job) {
			if($job->sensor->title == $sensor_name) {
				foreach($job->getReadings($job->threshold, $job->sensor->table, $job->sensor->field, $limit)->take(1) as $reading) {
					// return created at
					if($created_at)
						return $reading["created_at"];
					// return actual sensor reading
					else
						return number_format($reading[$job->sensor->field], $job->sensor->decimal_points).$job->sensor->unit;
				}
			}
		}
	}

	public function getLatestReadingTime($limit=null) {
		$latestHeatTime = $this->getSensorLatestReading("Thermometer", $limit, TRUE); 
		$latestLightTime = $this->getSensorLatestReading("Photosensor", $limit, TRUE);
		
		if($latestHeatTime || $latestLightTime)
			return ($latestHeatTime > $latestLightTime) ? $latestHeatTime : $latestLightTime;  
		else 
			return null;
	}

	/**
	 * Get this object's latest heat reading
	 */
	public function getLatestHeat($limit=null) 
	{
		$latest = $this->getSensorLatestReading("Thermometer", $limit, null); 
		return ($latest) ? $latest : "--";
	}

	/**
	 * Get this object's latest light reading
	 */
	public function getLatestLight($limit=null) 
	{
		$latest = $this->getSensorLatestReading("Photosensor", $limit, null); 
		return ($latest) ? $latest : "--";
	}

}