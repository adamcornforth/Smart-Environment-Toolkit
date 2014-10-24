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

}