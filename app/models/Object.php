<?php 
class Object extends Eloquent {
    protected $table = 'Object';

    /**
	 * Get this object's spots 
	 */
	public function spots()
	{
		return $this->hasOne('Spot');
	}

	/**
	 * Get this object's jobs 
	 */
	public function jobs()
	{
		return $this->hasMany('Job');
	}

}