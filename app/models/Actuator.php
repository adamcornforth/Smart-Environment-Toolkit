<?php 
class Actuator extends Eloquent {
    protected $table = 'Actuator';

     /**
     * Override all() method to return spots with basestation user id of logged in user.
     * If logged in user is admin, return all spots 
     * @return [type] [description]
     */
    public static function all($columns = array()) {
    	if(Auth::user()->isAdmin())
    		return parent::all(); 
    	else
    		return Basestation::whereUserId(Auth::user()->id)->first()->actuators; 
    }

    /**
     * Override find() method to return spots with basestation user id of logged in user.
     * If logged in user is admin, return spot by id
     * @return [type] [description]
     */
    public static function find($id, $columns = array()) {
    	if(Auth::user()->isAdmin())
    		return parent::find($id); 

    	$actuator = Actuator::whereId($id)->first(); 


    	if(isset($actuator->id) && $actuator->basestation->user_id == Auth::user()->id)
    		return $actuator; 
    	else
    		return false; 
    }

    /**
	 * Get this actuator's object 
	 */
	public function object()
	{
		return $this->belongsTo('Object');
	}

	 /**
	 * Get this actuator's object 
	 */
	public function basestation()
	{
		return $this->hasOne('Basestation');
	}

	/**
	 * Get this actuator's jobs
	 */
	public function jobs()
	{
		return $this->hasMany('ActuatorJob');
	}

	/**
	 * Get this actuator's conditions
	 */
	public function conditions()
	{
		return $this->hasMany('Condition');
	}

}