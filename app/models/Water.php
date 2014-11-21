<?php 
class Water extends Eloquent {
	public $timestamps = false;

    protected $table = 'Water';
    protected $fillable = array('water_percent', 'spot_address', 'job_id', 'zone_id');
    public function spot()
    {
        return $this->belongsTo('Spot', 'spot_address', 'spot_address');
    }

    public function zone()
    {
        return $this->belongsTo('Zone');
    }
}