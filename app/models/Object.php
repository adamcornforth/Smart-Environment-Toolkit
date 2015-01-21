<?php 
class Object extends Eloquent {
    protected $table = 'Object';

    /**
	 * Get this object's spots 
	 */
	public function spot()
	{
		return $this->belongsTo('Spot');
	}

	/**
	 * Get this object's jobs 
	 */
	public function jobs()
	{
		return $this->hasMany('Job');
	}

	/**
	 * Get the latest reading for this object, given a sensor name
	 */
	private function getSensorLatestReading($sensor_name, $created_at=null) {
		$jobs = Job::whereObjectId($this->id)->get(); 
		foreach ($jobs as $job) {
			if($job->sensor->title == $sensor_name) {
				foreach($job->getReadings($job->threshold, $job->sensor->table, $job->sensor->field)->take(1) as $reading) {
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

	public function getLatestReadingTime() {
		$latestHeatTime = $this->getSensorLatestReading("Thermometer", TRUE); 
		$latestLightTime = $this->getSensorLatestReading("Photosensor", TRUE);
		return ($latestHeatTime > $latestLightTime) ? $latestHeatTime : $latestLightTime;  
	}

	/**
	 * Get this object's latest heat reading
	 */
	public function getLatestHeat() 
	{
		return ($this->getSensorLatestReading("Thermometer")) ? $this->getSensorLatestReading("Thermometer") : "--";
	}

	/**
	 * Get this object's latest light reading
	 */
	public function getLatestLight() 
	{
		return ($this->getSensorLatestReading("Photosensor")) ? $this->getSensorLatestReading("Photosensor") : "--";
	}

}